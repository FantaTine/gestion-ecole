<?php

namespace App\Services;

interface AuthFirebaseServiceInterface
{
    public function login(string $email, string $password): string;
}