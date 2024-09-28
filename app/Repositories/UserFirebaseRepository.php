<?php

namespace App\Repositories;

use App\Repositories\UserFirebaseRepositoryInterface;
use App\Facades\UserFirebaseFacade;

class UserFirebaseRepository implements UserFirebaseRepositoryInterface
{
    protected $firebase;

    public function __construct()
    {
        $this->firebase = UserFirebaseFacade::getFacadeRoot();
    }

    public function getAll(): array
    {
        return UserFirebaseFacade::getAll('users') ?? [];
    }

    public function findById(string $id): ?array
    {
        $result = UserFirebaseFacade::find('users', $id);
        
        // Check if the result is a JsonResponse and extract the data if it is
        if ($result instanceof \Illuminate\Http\JsonResponse) {
            $data = $result->getData(true);
            return $data['user'] ?? null;
        }
        
        return $result;
    }

    public function findByEmail(string $email): ?array
    {
        return UserFirebaseFacade::findUserByEmail($email);
    }

    public function findByPhone(string $phone): ?array
    {
        return UserFirebaseFacade::findUserByPhone($phone);
    }

    public function create(array $data): string
    {
        return UserFirebaseFacade::create('users', $data);
    }

    public function update(string $id, array $data): bool
    {
        $result = UserFirebaseFacade::update('users', $id, $data);
        return $result->status() === 200;
    }

    public function delete(string $id): bool
    {
        $result = UserFirebaseFacade::delete('users', $id);
        return $result->status() === 200;
    }

    public function createWithEmailAndPassword(string $email, string $password): string
    {
        return UserFirebaseFacade::createUserWithEmailAndPassword($email, $password);
    }

    public function uploadImage(string $filePath, string $fileName): string
    {
        return UserFirebaseFacade::uploadImageToStorage($filePath, $fileName);
    }
}