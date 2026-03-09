<?php

namespace App\Services\Auth;

use App\Repositories\Auth\AuthRepositoryInterface;
use App\DTOs\UserLoginDTO;
use App\DTOs\UserRegistrationDTO;
use App\Models\User;
use App\DTOs\LoginResultDTO;

class AuthService implements AuthServiceInterface
{
    protected AuthRepositoryInterface $authRepo;

    public function __construct(AuthRepositoryInterface $authRepo)
    {
        $this->authRepo = $authRepo;
    }

    public function register(UserRegistrationDTO $dto): LoginResultDTO
    {
        return $this->authRepo->register([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => bcrypt($dto->password),
        ]);
    }

    public function login(UserLoginDTO $dto): LoginResultDTO
    {
        return $this->authRepo->login([
            'username' => $dto->username,
            'password' => $dto->password,
        ]);
    }

    public function logout(User $user): bool
    {
        return $this->authRepo->logout($user);
    }
}
