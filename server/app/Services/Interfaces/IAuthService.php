<?php

namespace App\Services\Interfaces;

use App\DTOs\UserLoginDTO;
use App\DTOs\UserRegistrationDTO;
use App\Models\User;
use LoginResultDTO;

interface IAuthService
{
    public function register(UserRegistrationDTO $data):User;
    public function login(UserLoginDTO $dto):LoginResultDTO;
}
