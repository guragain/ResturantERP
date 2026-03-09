<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function getAllUsers(): Collection;
    public function createUser(array $data): User;
    public function updateUser(int $id, array $data): User;
    public function deleteUser(int $id): bool;
    public function getUserById(int $id): ?User;
}
