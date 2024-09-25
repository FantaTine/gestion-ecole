<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->getAllUsers();
    }

    public function createUser(array $data)
    {
        // Vérifier les permissions de l'utilisateur connecté
        $currentUser = Auth::user();
        if (!$this->canCreateUser($currentUser, $data['role'])) {
            throw new \Exception("Vous n'avez pas la permission de créer cet utilisateur.");
        }

        return $this->userRepository->createUser($data);
    }

    public function updateUser($userId, array $data)
    {
        return $this->userRepository->updateUser($userId, $data);
    }

    public function getUsersByRole($role)
    {
        return $this->userRepository->getUsersByRole($role);
    }

    private function canCreateUser($currentUser, $roleToCreate)
    {
        if ($currentUser->role === 'admin') {
            return true;
        }

        if ($currentUser->role === 'manager' && in_array($roleToCreate, ['coach', 'manager', 'cm', 'apprenant'])) {
            return true;
        }

        if ($currentUser->role === 'cm' && $roleToCreate === 'apprenant') {
            return true;
        }

        return false;
    }
}