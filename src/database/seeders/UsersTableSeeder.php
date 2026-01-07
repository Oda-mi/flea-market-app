<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'ユーザーA',
            'email' => 'usera@example.com',
            'password' => Hash::make('password123'),
            'profile_image' => null,
            'postal_code' => '111-1111',
            'address' => 'A県A市A町',
            'building' => 'Aマンション101',
            ]);

        User::create([
            'name' => 'ユーザーB',
            'email' => 'userb@example.com',
            'password' => Hash::make('password123'),
            'profile_image' => null,
            'postal_code' => '222-2222',
            'address' => 'B県B市B町',
            'building' => 'Bマンション201',
            ]);

        User::create([
            'name' => 'ユーザーC',
            'email' => 'userc@example.com',
            'password' => Hash::make('password123'),
            'profile_image' => null,
            'postal_code' => '333-3333',
            'address' => 'C県C市C町',
            'building' => 'Cマンション301',
            ]);
    }
}
