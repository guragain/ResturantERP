<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\DTOs\LoginResultDTO;

interface AuthRepositoryInterface
{
    public function register(array $data): LoginResultDTO;

    public function login(array $credentials): ?LoginResultDTO;

    public function logout(User $user): bool;
}
