<?php

namespace App\Services;

use Kreait\Firebase\Contract\Auth as FirebaseAuth;

class AuthFirebaseService implements AuthFirebaseServiceInterface
{
    protected $auth;

    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
    }

    public function login(string $email, string $password): string
    {
        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($email, $password);
            /* dd($signInResult); */
            return $signInResult->idToken();
        } catch (\Exception $e) {
            throw new \Exception('Échec de lauthentification: ' . $e->getMessage());
        }
    }
}