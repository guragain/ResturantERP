<?php

namespace App\Services\Implementation;

use App\Contracts\Interfaces\IAuthRepo;
use App\DTOs\UserLoginDTO;
use App\DTOs\UserRegistrationDTO;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Interfaces\IAuthService;
use LoginResultDTO;

class AuthService implements IAuthService
{
    protected IAuthRepo $authRepo;

    public function __construct(IAuthRepo $authRepo)
    {
        $this->authRepo = $authRepo;
    }
    public function register(UserRegistrationDTO $dto): User
    {
        return $this->authRepo->register([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => bcrypt($dto->password),
        ]);
    }

    /**
     * @param UserLoginDTO $dto
     * @return mixed
     */
    public function login(UserLoginDTO $dto): LoginResultDTO
    {
        // $credentials = [
        //     'username' => $dto->username,
        //     'password' => $dto->password,
        // ];
        return $this->authRepo->login([
            'username' => $dto->username,
            'password' => $dto->password,
        ]);
        // Implement login logic here
    }
}
