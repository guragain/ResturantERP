<?php

namespace App\Services\Auth;

use App\DTOs\UserLoginDTO;
use App\DTOs\UserRegistrationDTO;
use App\Models\User;
use App\DTOs\LoginResultDTO;

interface AuthServiceInterface
{
    public function register(UserRegistrationDTO $data): LoginResultDTO;

    public function login(UserLoginDTO $dto): LoginResultDTO;

    public function logout(User $user): bool;
}
