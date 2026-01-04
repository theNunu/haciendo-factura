<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{

    public function getAll()
    {
        return User::all();
    }
    public function create(array $data)
    {
        // return Course::create($data);
        return User::create([
            'first_name'         => $data['first_name'],
            'last_name'         => $data['last_name'],
            'email'         => $data['email'],

        ]);
    }
}
