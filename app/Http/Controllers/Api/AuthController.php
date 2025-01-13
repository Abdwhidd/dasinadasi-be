<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterUserRequest $request)
    {
        $user = $this->userService->register($request->validated());
        return ApiResponse::successResponse(new UserResource($user), 'User registered successfully.');
    }

    public function login(LoginUserRequest $request)
    {
        $data = $this->userService->login($request->validated());
        return ApiResponse::successResponse([
            'user' => new UserResource($data['user']),
            'token' => $data['token']
        ], 'Login successful.');
    }
}
