<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     */
    public function run()
    {
        $users = User::all();
        foreach($users as $user){
            Employee::create([
                'user_id' => $user->id,
                'name' =>  $user->name,
                'nip' => '0',
                'is_active' => true
             ]);
        }

    }
}
