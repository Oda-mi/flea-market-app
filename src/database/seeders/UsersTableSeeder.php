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
            'name' => 'テスト太郎',
            'email' => 'test@email.com',
            'password' => Hash::make('12345678'),
            'profile_image' => null,
            'postal_code' => '123-4567',
            'address' => 'テスト県テスト市テスト町',
            'building' => 'テストビル101',
            ]);
    }
}
