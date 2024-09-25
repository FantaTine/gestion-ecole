<?php

namespace App\Repositories;

use App\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById($userId)
    {
        return User::findOrFail($userId);
    }

    public function deleteUser($userId)
    {
        User::destroy($userId);
    }

    public function createUser(array $userDetails)
    {
        $userDetails['password'] = Hash::make($userDetails['password']);
        return User::create($userDetails);
    }

    public function updateUser($userId, array $newDetails)
    {
        return User::whereId($userId)->update($newDetails);
    }

    public function getUsersByRole($role)
    {
        return User::where('role', $role)->get();
    }
}