<?php

namespace App\Contracts\Implementation;

use App\Contracts\Interfaces\IAuthRepo;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Contracts\Implementation\LoginResultDTO;

class AuthRepo implements IAuthRepo
{
    public function register(array $data): User
    {
        return User::create($data);
    }

    public function login(array $request): ?LoginResultDTO
    {
        $user = User::where('email', $request['username'])->first();

        if (!$user || !Hash::check($request['password'], $user->password)) {
            // Throwing an exception is good—it stops the flow immediately
            throw new \Exception('Invalid credentials', 401);
        }

        // Generate the token
        $token = $user->createToken('api-token')->plainTextToken;

        // Load relationships if needed for the DTO/Resource
        $user->load('roles', 'permissions');

        // ONLY return the DTO. No JSON here!
        return new LoginResultDTO($user, $token);
    }
}
