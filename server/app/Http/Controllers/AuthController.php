<?php

namespace App\Http\Controllers;

use App\DTOs\UserLoginDTO;
use App\DTOs\UserRegistrationDTO;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use App\Services\Interfaces\IAuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    protected IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        // Now $request is an instance of RegisterRequest,
        // and the DTO will accept it without a TypeError.
        $dto = UserRegistrationDTO::fromRequest($request);

        $user = $this->authService->register($dto);

        return response()->json([
            'message' => 'User registered successfully',
            'user'    => $user
        ], 201);
    }

    public function  login(LoginRequest $request): JsonResponse
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        $dto = UserLoginDTO::fromRequest($request);
        $user = $this->authService->login($dto);
       return response()->json([
            'message' => 'User logged in successfully',
            'user'    => $user
        ], 200);
    }
}
