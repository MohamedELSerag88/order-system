<?php

namespace Database\Seeders;



use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

//        \DB::table('users')->truncate();
        User::create([
            'name' => "User",
            'email' => "user@order.com",
            'password' => \Hash::make("123456")
        ]);
    }
}
