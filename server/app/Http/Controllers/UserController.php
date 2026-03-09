<?php

namespace App\Http\Controllers;

use App\Services\User\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest; // Reusing the same validation for now

class UserController extends Controller
{
    protected UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        $users = $this->userService->listUsers();
        return $this->sendResponse($users, 'Users retrieved successfully');
    }

    public function store(RegisterRequest $request): JsonResponse
    {
        $user = $this->userService->storeUser($request->validated());
        return $this->sendResponse($user, 'User created successfully', 201);
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->userService->showUser($id);
        return $this->sendResponse($user, 'User retrieved successfully');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        // Add validation logic here as needed
        $user = $this->userService->updateUserInfo($id, $request->all());
        return $this->sendResponse($user, 'User updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->userService->removeUser($id);
        return $this->sendResponse(null, 'User deleted successfully');
    }
}
