<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class user_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::create([
        //     'name' => 'user1',
        //     'email' => 'user1@userdomain.com',
        //     'password' => Hash::make('User1@1234'),
        // ]);

        // User::create([
        //     'name' => 'user2',
        //     'email' => 'user2@userdomain.com',
        //     'password' => Hash::make('User2@1234'),
        // ]);

        User::create([
            'name' => 'user3',
            'email' => 'user3@userdomain.com',
            'password' => Hash::make('User3@1234'),
        ]);

        User::create([
            'name' => 'user4',
            'email' => 'user4@userdomain.com',
            'password' => Hash::make('User4@1234'),
        ]);

        User::create([
            'name' => 'user5',
            'email' => 'user5@userdomain.com',
            'password' => Hash::make('User5@1234'),
        ]);

        // Add more dummy users as needed
    }
}
