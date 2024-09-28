<?php

namespace App\Repositories;

interface UserFirebaseRepositoryInterface
{
    public function getAll(): array;
    public function findById(string $id): ?array;
    public function findByEmail(string $email): ?array;
    public function findByPhone(string $phone): ?array;
    public function create(array $data): string;
    public function update(string $id, array $data): bool;
    public function delete(string $id): bool;
    public function createWithEmailAndPassword(string $email, string $password): string;
    public function uploadImage(string $filePath, string $fileName): string;
}