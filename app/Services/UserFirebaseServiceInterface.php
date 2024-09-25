<?php

namespace App\Services;

interface UserFirebaseServiceInterface
{
    public function getAllUsers(?string $role = null): array;
    //public function getAllUsers(): array;
    public function getUserById(string $id): ?array;
    public function getUserByEmail(string $email): ?array;
    public function getUserByPhone(string $phone): ?array;
    public function createUser(array $data): string;
    public function updateUser(string $id, array $data): bool;
    public function deleteUser(string $id): bool;
    public function registerUser(string $email, string $password): string;
    public function uploadUserImage(string $filePath, string $fileName): string;
}