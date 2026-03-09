<?php

namespace App\Http\Controllers;

use App\DTOs\UserLoginDTO;
use App\DTOs\UserRegistrationDTO;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\Auth\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $dto = UserRegistrationDTO::fromRequest($request);
        $result = $this->authService->register($dto);

        $cookie = cookie('jwt_token', $result->token, 1440, '/', null, env('SESSION_SECURE_COOKIE', true), true, false, 'Lax');

        return $this->sendResponse([
            'user'  => $result->user,
        ], 'User registered successfully', 201)->withCookie($cookie);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $dto = UserLoginDTO::fromRequest($request);
            $result = $this->authService->login($dto);

            $cookie = cookie('jwt_token', $result->token, 1440, '/', null, env('SESSION_SECURE_COOKIE', true), true, false, 'Lax');

            return $this->sendResponse([
                'user'  => $result->user,
                'token' => $result->token,
            ], 'User logged in successfully')->withCookie($cookie);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], $e->getCode() ?: 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        $cookie = cookie()->forget('jwt_token');

        return $this->sendResponse(null, 'User logged out successfully')->withCookie($cookie);
    }
}
