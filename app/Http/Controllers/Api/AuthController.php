<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Register a new user.
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->register($request->validated());
            return ApiResponse::successResponse(
                new UserResource($user),
                'User registered successfully.',
                201
            );
        } catch (\Exception $e) {
            return ApiResponse::errorResponse(
                'Registration failed.',
                500,
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Log in a user.
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        try {
            $data = $this->userService->login($request->validated());

            // Check if login returned null or empty data
            if (!$data || empty($data['user']) || empty($data['token'])) {
                return ApiResponse::errorResponse(
                    'Invalid login credentials.',
                    401
                );
            }

            return ApiResponse::successResponse(
                [
                    'user' => new UserResource($data['user']),
                    'token' => $data['token']
                ],
                'Login successful.'
            );
        } catch (\Exception $e) {
            return ApiResponse::errorResponse(
                'Login failed.',
                500,
                ['error' => $e->getMessage()]
            );
        }
    }
}
