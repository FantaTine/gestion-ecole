<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AuthFirebaseService;
use Exception;
use Kreait\Firebase\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\Auth\UserNotFound;

class AuthFirebaseController extends Controller
{
    protected $userFirebaseService;
    protected $firebaseService;

    public function __construct(AuthFirebaseService $userFirebaseService)
    {
       /*  dd(env('FIREBASE_CREDENTIALS')); */
       $factory = (new Factory)->withServiceAccount(env('FIREBASE_CREDENTIALS'));
        $this->firebaseService = $factory->createAuth();
        $this->userFirebaseService = $userFirebaseService;
    }
    public function login(Request $request)
    {
        $email = $request['email'];
        $password = $request['password'];
    
        Log::info('Tentative de connexion pour l\'email: ' . $email);
    
        try {
            Log::info('Appel à signInWithEmailAndPassword');
            $signInResult = $this->firebaseService->signInWithEmailAndPassword($email, $password);
            Log::info('Connexion réussie, récupération de l\'UID');
            $uid = $signInResult->firebaseUserId();
            Log::info('Création du token personnalisé');
            $customToken = $this->firebaseService->createCustomToken($uid);
        
            return [
                'message' => 'Authenticated successfully',
                'firebase_token' => $customToken->toString()
            ];
        } catch (UserNotFound $e) {
            Log::error('Utilisateur non trouvé: ' . $email);
            return response()->json(['error' => 'User not found'], 404);
        } catch (InvalidPassword $e) {
            Log::error('Mot de passe invalide pour l\'email: ' . $email);
            return response()->json(['error' => 'Invalid password'], 401);
        } catch (\Kreait\Firebase\Exception\Auth\AuthError $e) {
            Log::error('Erreur Firebase: ' . $e->getMessage());
            return response()->json(['error' => 'Firebase authentication error: ' . $e->getMessage()], 500);
        } catch (Exception $e) {
            Log::error('Erreur d\'authentification: ' . $e->getMessage());
            return response()->json(['error' => 'Authentication failed: ' . $e->getMessage()], 500);
        }
        
    }
    
}
