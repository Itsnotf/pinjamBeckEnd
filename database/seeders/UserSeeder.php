<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'badge' => '0',
            'password' => Hash::make('password'),
            'id_role' => 1
        ]);

        User::factory()->create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'badge' => '072371292',
            'password' => Hash::make('password'),
            'id_role' => 2
        ]);
    }
}
