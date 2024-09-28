<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use App\Services\FirebaseSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected $authService;
    protected $firebaseSyncService;

    public function __construct(AuthService $authService, FirebaseSyncService $firebaseSyncService)
    {
        $this->authService = $authService;
        $this->firebaseSyncService = $firebaseSyncService;
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'fonction' => 'required|string|max:255',
            'role' => 'required|in:admin,coach,manager,cm,apprenant',
        ]);

        try {
            $user = $this->authService->registerUser($validatedData);
            $token = $user->createToken('authToken')->accessToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed: ' . $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $user = $this->authService->loginUser($loginData['email'], $loginData['password']);

        if (!$user) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        $token = $user->createToken('authToken')->accessToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function loginWithProvider($provider)
    {
        try {
            $user = $this->authService->authenticateFirebase($provider);
            $token = $user->createToken('authToken')->accessToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Authentication failed: ' . $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}