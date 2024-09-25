<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $role = $request->query('role');
        if ($role) {
            $users = $this->userService->getUsersByRole($role);
        } else {
            $users = $this->userService->getAllUsers();
        }
        return UserResource::collection($users);
    }

    public function store(CreateUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        return new UserResource($user);
    }

    public function update(Request $request, $id)
    {
        $user = $this->userService->updateUser($id, $request->all());
        return new UserResource($user);
    }
}