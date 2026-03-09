<?php
namespace App\DTOs;

use App\Models\User;

readonly class LoginResultDTO
{
    public function __construct(public User $user, public string $token) {}
}
