<?php

namespace App\Services;

use App\Services\UserFirebaseServiceInterface;
use App\Repositories\UserFirebaseRepositoryInterface;

class UserFirebaseService implements UserFirebaseServiceInterface
{
    protected $repository;

    public function __construct(UserFirebaseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllUsers(?string $role = null): array
    {
        $users = $this->repository->getAll();
        if ($role) {
            $users = array_filter($users, function($user) use ($role) {
                return $user['role'] === $role;
            });
        }
        return array_values($users);
    }

    public function getUserById(string $id): ?array
    {
        return $this->repository->findById($id);
    }

    public function getUserByEmail(string $email): ?array
    {
        return $this->repository->findByEmail($email);
    }

    public function getUserByPhone(string $phone): ?array
    {
        return $this->repository->findByPhone($phone);
    }

    public function createUser(array $data): string
    {
        return $this->repository->create($data);
    }

    public function updateUser(string $id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function deleteUser(string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function registerUser(string $email, string $password): string
    {
        $userId = $this->repository->createWithEmailAndPassword($email, $password);
        /* $this->repository->update($userId, $additionalData); */
        return $userId;
    }

    public function uploadUserImage(string $filePath, string $fileName): string
    {
        return $this->repository->uploadImage($filePath, $fileName);
    }
}