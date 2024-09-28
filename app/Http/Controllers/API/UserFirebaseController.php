<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\UserFirebaseServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserFirebaseController extends Controller
{
    protected $userFirebaseService;

    public function __construct(UserFirebaseServiceInterface $userFirebaseService)
    {
        $this->userFirebaseService = $userFirebaseService;
    }

    public function index(Request $request)
    {
        try {
            $role = $request->query('role');
            $users = $this->userFirebaseService->getAllUsers($role);
            return response()->json(['users' => $users], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des utilisateurs: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = $this->userFirebaseService->getUserById($id);
            if (!$user) {
                return response()->json(['message' => 'Utilisateur non trouvé'], 404);
            }
            return response()->json(['user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération de l\'utilisateur: ' . $e->getMessage()], 500);
        }
    }

    public function create(Request $request)
    {
        // Valider les données entrantes
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'fonction' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',  // 2MB Max
            'statut' => ['required', Rule::in(['Bloquer', 'Actif'])],
            'role' => ['required', Rule::in(['admin', 'coach', 'manager', 'cm', 'apprenant'])],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Préparer les données de l'utilisateur
            $userData = $request->only([
                'nom', 'prenom', 'email', 'adresse', 'telephone', 'fonction', 'statut', 'role'
            ]);
            $userData['password'] = bcrypt($request->password);
            $userData['created_at'] = now()->toDateTimeString();
              // Gérer l'upload de la photo si elle est fournie
              if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $fileName = 'photo_' . $userData['email'] . '.' . $file->getClientOriginalExtension();
                $filePath = $file->getRealPath();

                $photoUrl = $this->userFirebaseService->uploadUserImage($filePath, $fileName);
                $userData['photo'] = $photoUrl;
                // Mettre à jour l'utilisateur avec l'URL de la photo
            /*     $this->userFirebaseService->updateUser($userId, ['photo' => $photoUrl]); */
            }
            // Créer l'utilisateur
            $userId = $this->userFirebaseService->createUser($userData);
            $userId = $this->userFirebaseService->registerUser($userData['email'], $userData['password']);

          

            // Récupérer les données de l'utilisateur créé
         /*    $createdUser = $this->userFirebaseService->getUserById($userId); */

            return response()->json([
                'message' => 'Utilisateur créé avec succès',
                'user' => $userData,
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la création de l\'utilisateur: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'adresse' => 'sometimes|string|max:255',
            'telephone' => 'sometimes|string|max:20',
            'fonction' => 'sometimes|string|max:255',
            'photo' => 'nullable|image|max:2048',  // 2MB Max
            'statut' => ['sometimes', Rule::in(['Bloquer', 'Actif'])],
            'role' => ['sometimes', Rule::in(['admin', 'coach', 'manager', 'cm', 'apprenant'])],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $updateData = $request->only([
                'nom', 'prenom', 'email', 'adresse', 'telephone', 'fonction', 'statut', 'role'
            ]);

            if ($request->has('password')) {
                $updateData['password'] = bcrypt($request->password);
            }

            $success = $this->userFirebaseService->updateUser($id, $updateData);

            if (!$success) {
                return response()->json(['message' => 'Utilisateur non trouvé'], 404);
            }

            // Gérer l'upload de la photo si elle est fournie
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $fileName = 'photo_' . $id . '.' . $file->getClientOriginalExtension();
                $filePath = $file->getRealPath();

                $photoUrl = $this->userFirebaseService->uploadUserImage($filePath, $fileName);

                // Mettre à jour l'utilisateur avec l'URL de la photo
                $this->userFirebaseService->updateUser($id, ['photo' => $photoUrl]);
            }

            $updatedUser = $this->userFirebaseService->getUserById($id);

            return response()->json([
                'message' => 'Utilisateur mis à jour avec succès',
                'user' => $updatedUser
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour de l\'utilisateur: ' . $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $success = $this->userFirebaseService->deleteUser($id);

            if (!$success) {
                return response()->json(['message' => 'Utilisateur non trouvé'], 404);
            }

            return response()->json(['message' => 'Utilisateur supprimé avec succès'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression de l\'utilisateur: ' . $e->getMessage()], 500);
        }
    }

    public function getUserByEmail($email)
    {
        try {
            $user = $this->userFirebaseService->getUserByEmail($email);
            if (!$user) {
                return response()->json(['message' => 'Utilisateur non trouvé'], 404);
            }
            return response()->json(['user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération de l\'utilisateur: ' . $e->getMessage()], 500);
        }
    }

    public function getUserByPhone($telephone)
    {
        try {
            $user = $this->userFirebaseService->getUserByPhone($telephone);
            if (!$user) {
                return response()->json(['message' => 'Utilisateur non trouvé'], 404);
            }
            return response()->json(['user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération de l\'utilisateur: ' . $e->getMessage()], 500);
        }
    }
}