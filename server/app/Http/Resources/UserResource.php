<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // 'this' refers to the User Model instance
        return [
            'id'               => $this->id,
            'full_name'        => $this->name,
            'email'            => $this->email,
            'phone'            => $this->phone_number,
            'is_verified'      => $this->email_verified_at !== null,
            'member_since'     => $this->created_at->format('Y-m-d'),

            // You can even include relationships conditionally
            'roles'            => $this->whenLoaded('roles'),
            'permissions'      => $this->whenLoaded('permissions'),
        ];
    }
}
