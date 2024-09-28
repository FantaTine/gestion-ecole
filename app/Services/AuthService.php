<?php

namespace App\Services;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Services\FirebaseSyncService;

class AuthService
{
    protected $firebaseSyncService;

    public function __construct(FirebaseSyncService $firebaseSyncService)
    {
        $this->firebaseSyncService = $firebaseSyncService;
    }

    public function authenticateFirebase($provider)
    {
        $socialUser = Socialite::driver($provider)->user();
        
        $user = User::updateOrCreate([
            'email' => $socialUser->getEmail(),
        ], [
            'nom' => $socialUser->getName(),
            'prenom' => '', // You might want to split the name or leave it blank
            'email' => $socialUser->getEmail(),
            'password' => Hash::make(Str::random(16)),
            'role' => 'apprenant', // Default role, you might want to adjust this
            'statut' => 'Actif',
        ]);

        // Sync the user with Firebase
        $this->firebaseSyncService->syncUser($user);

        return $user;
    }

    public function registerUser(array $data)
    {
        $user = User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'adresse' => $data['adresse'],
            'telephone' => $data['telephone'],
            'fonction' => $data['fonction'],
            'role' => $data['role'],
            'statut' => 'Actif',
        ]);

        // Sync the user with Firebase
        $this->firebaseSyncService->syncUser($user);

        return $user;
    }

    public function loginUser($email, $password)
    {
        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }

        return null;
    }
}