<?php

namespace App\Services\User;

use App\Repositories\User\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    protected UserRepositoryInterface $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function listUsers(): Collection
    {
        return $this->userRepo->getAllUsers();
    }

    public function storeUser(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        return $this->userRepo->createUser($data);
    }

    public function showUser(int $id): User
    {
        return $this->userRepo->getUserById($id);
    }

    public function updateUserInfo(int $id, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        return $this->userRepo->updateUser($id, $data);
    }

    public function removeUser(int $id): bool
    {
        return $this->userRepo->deleteUser($id);
    }
}
