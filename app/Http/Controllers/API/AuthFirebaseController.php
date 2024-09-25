<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AuthFirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthFirebaseController extends Controller
{
    protected $userFirebaseService;

    public function __construct(AuthFirebaseService $userFirebaseService)
    {
        $this->userFirebaseService = $userFirebaseService;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $userId = $this->userFirebaseService->login($request->email, $request->password);
            return response()->json(['user' => $userId], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Authentication error: ' . $e->getMessage()], 401);
        }
    }
}
