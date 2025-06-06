<?php

namespace App\Services;

use App\Models\User;

class AuthService
{
    /**
     * Register a new user.
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function register(array $data)
    {
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        return $user;
    }
}