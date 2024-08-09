<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create users account
        User::create([
            "name" => "admin",
            "email" => "admin@gmail.com",
            "role" => "admin",
            "password" => bcrypt("password"),
        ]);

        User::create([
            "name" => "agent",
            "email" => "agent@gmail.com",
            "role" => "agent",
            "password" => bcrypt("password"),
        ]);

        User::create([
            "name" => "customer-service",
            "email" => "customer-service@gmail.com",
            "role" => "customer-service",
            "password" => bcrypt("password"),
        ]);
        User::create([
            "name" => "customer-service2",
            "email" => "customer-service2@gmail.com",
            "role" => "customer-service",
            "password" => bcrypt("password"),
        ]);
    }
}
