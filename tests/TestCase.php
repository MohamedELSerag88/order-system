<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function addUser(): void{
        User::create([
            'name' => 'user test',
            'email' => 'email@order.com',
            'password' => Hash::make('123456')
        ]);
    }
}
