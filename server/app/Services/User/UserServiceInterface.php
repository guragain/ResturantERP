<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserServiceInterface
{
    public function listUsers(): Collection;
    public function storeUser(array $data): User;
    public function showUser(int $id): User;
    public function updateUserInfo(int $id, array $data): User;
    public function removeUser(int $id): bool;
}
