<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\DTOs\LoginResultDTO;

class EloquentAuthRepository implements AuthRepositoryInterface
{
    public function register(array $data): LoginResultDTO
    {
        $user = User::create($data);
        $token = $user->createToken('api-token')->plainTextToken;

        return new LoginResultDTO($user, $token);
    }

    // public function login(array $credentials): ?LoginResultDTO
    // {
    //     $user = User::where('email', $credentials['username'])->first();

    //     if (!$user || !Hash::check($credentials['password'], $user->password)) {
    //         throw new \Exception('Invalid credentials', 401);
    //     }

    //     // Establish session for stateful authentication
    //     Auth::login($user);

    //     $token = $user->createToken('api-token')->plainTextToken;

    //     return new LoginResultDTO($user, $token);
    // }

    public function login(array $credentials): ?LoginResultDTO
    {
        // Ensure 'username' vs 'email' consistency
        $user = User::where('email', $credentials['email'] ?? $credentials['username'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new \Exception('Invalid credentials', 401);
        }

        // This creates the session cookie that Next.js will use for CSRF
        Auth::login($user);

        $token = $user->createToken('api-token')->plainTextToken;

        return new LoginResultDTO($user, $token);
    }

    public function logout(User $user): bool
    {
        // 1. Revoke the API token (for localStorage/Mobile)
        $user->tokens()->delete();

        // 2. Invalidate the Session (for the CSRF cookie/SPA)
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return true;
    }
}
