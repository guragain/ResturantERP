<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\DTOs\UserDTO;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function getAllUsers(): array
    {
        $users = $this->userRepository->all();
        return $users->map(fn($user) => UserDTO::fromModel($user)->toArray())->toArray();
    }

    public function getPaginatedUsers(int $perPage = 15): LengthAwarePaginator
    {
        $users = $this->userRepository->paginate($perPage);
        
        // Transform the items within the paginator
        $users->through(fn($user) => UserDTO::fromModel($user)->toArray());
        
        return $users;
    }

    public function getUserById(int $id): ?array
    {
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            return null;
        }
        
        return UserDTO::fromModel($user)->toArray();
    }

    public function updateUser(int $id, array $data): ?array
    {
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            return null;
        }
        
        // List of fields allowed to be updated by this service
        $allowedFields = ['first_name', 'middle_name', 'last_name', 'user_name', 'email', 'phone', 'status'];
        $updateData = array_intersect_key($data, array_flip($allowedFields));
        
        $updatedUser = $this->userRepository->update($user, $updateData);
        
        return UserDTO::fromModel($updatedUser)->toArray();
    }

    public function deleteUser(int $id): bool
    {
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            return false;
        }
        
        return $this->userRepository->delete($user);
    }
}
