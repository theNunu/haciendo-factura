<?php

namespace App\DTOs;

use App\Models\User;

class UserDTO
{
    public function __construct(
        public int $user_id,
        public string $first_name,
        public string $last_name,
        public string $email,

    ) {}

    /**
     * Convierte un Model user en un DTO
     */
    public static function fromModel(User $user): self
    {
        return new self(
            user_id: $user->user_id,
            first_name: $user->first_name,
            last_name: $user->last_name,
            email: $user->email,
            // stock: $user->stock,
            // is_active: $user->is_active,
        );
    }
}
