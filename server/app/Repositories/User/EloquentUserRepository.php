<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Collection;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function getAllUsers(): Collection
    {
        return User::all();
    }

    public function createUser(array $data): User
    {
        return User::create($data);
    }

    public function updateUser(int $id, array $data): User
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function deleteUser(int $id): bool
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }

    public function getUserById(int $id): ?User
    {
        return User::find($id);
    }
}
