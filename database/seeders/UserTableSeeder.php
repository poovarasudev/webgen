<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Abc',
                'email' => 'abc@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Abc 2',
                'email' => 'abc2@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Abc 3',
                'email' => 'abc3@gmail.com',
                'password' => Hash::make('password'),
            ]
        ];

        foreach ($users as $user) {
            User::firstOrCreate([
                'email' => $user['email']
            ],
                $user
            );
        }
    }
}
