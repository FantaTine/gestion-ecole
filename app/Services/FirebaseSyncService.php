<?php

namespace App\Services;

use App\Models\User;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\Log;

class FirebaseSyncService
{
    public function syncUser(User $user)
    {
        $firebaseAuth = Firebase::auth();
        $firebaseDatabase = Firebase::database();

        try {
            if (!$user->firebase_uid) {
                $firebaseUser = $firebaseAuth->createUser([
                    'email' => $user->email,
                    'password' => $user->password,
                    'displayName' => $user->nom . ' ' . $user->prenom,
                ]);
                $user->firebase_uid = $firebaseUser->uid;
                $user->save();
            } else {
                $firebaseAuth->updateUser($user->firebase_uid, [
                    'email' => $user->email,
                    'displayName' => $user->nom . ' ' . $user->prenom,
                ]);
            }

            $firebaseDatabase->getReference('users/' . $user->firebase_uid)->set([
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'adresse' => $user->adresse,
                'telephone' => $user->telephone,
                'fonction' => $user->fonction,
                'email' => $user->email,
                'photo' => $user->photo,
                'statut' => $user->statut,
                'role' => $user->role,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur de synchronisation Firebase: ' . $e->getMessage());
        }
    }

    public function deleteUser(User $user)
    {
        if ($user->firebase_uid) {
            try {
                Firebase::auth()->deleteUser($user->firebase_uid);
                Firebase::database()->getReference('users/' . $user->firebase_uid)->remove();
            } catch (\Exception $e) {
                Log::error('Erreur de suppression Firebase: ' . $e->getMessage());
            }
        }
    }
}