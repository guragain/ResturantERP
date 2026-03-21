<?php

namespace App\DTOs;

class UserDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $first_name,
        public readonly ?string $middle_name,
        public readonly string $last_name,
        public readonly string $user_name,
        public readonly string $email,
        public readonly ?string $phone,
        public readonly bool $is_phone_verified,
        public readonly ?string $phone_verified_at,
        public readonly string $status,
        public readonly ?string $email_verified_at,
        public readonly string $created_at,
        public readonly string $updated_at
    ) {}

    public static function fromModel($user): self
    {
        return new self(
            id: $user->id,
            first_name: $user->first_name,
            middle_name: $user->middle_name,
            last_name: $user->last_name,
            user_name: $user->user_name,
            email: $user->email,
            phone: $user->phone,
            is_phone_verified: (bool) $user->is_phone_verified,
            phone_verified_at: $user->phone_verified_at ? $user->phone_verified_at->toISOString() : null,
            status: $user->status,
            email_verified_at: $user->email_verified_at ? $user->email_verified_at->toISOString() : null,
            created_at: $user->created_at->toISOString(),
            updated_at: $user->updated_at->toISOString()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'user_name' => $this->user_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_phone_verified' => $this->is_phone_verified,
            'phone_verified_at' => $this->phone_verified_at,
            'status' => $this->status,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
