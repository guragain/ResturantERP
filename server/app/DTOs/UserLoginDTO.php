<?php
namespace App\DTOs;

use App\Http\Requests\Auth\LoginRequest;

readonly class UserLoginDTO
{
    public function __construct(
        public string $username,
        public string $password,
    ) {}

    public static function fromRequest(LoginRequest $request): self
    {
        return new self(
        username: $request->validated('username'),
        password: $request->validated('password'),
        );
    }
}
 