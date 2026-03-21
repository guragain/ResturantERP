<?php

namespace App\DTOs;

class AuthResponseDTO
{
    public function __construct(
        public readonly UserDTO $user,
        public  readonly string $message = 'Success'
    ) {}

    public function toArray():array{
        return[
            'user'=>$this->user->toArray(),
            'message'=>$this->message
        ];
    }
}
