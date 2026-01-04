<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Repositories\userRepository;

class UserService
{
    protected $userRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(userRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        return $this->userRepository->getAll()->map(fn($user) => UserDTO::fromModel($user));
    }

    public function create($data)
    {

        $user = $this->userRepository->create($data);

        return UserDTO::fromModel($user);
    }
}
