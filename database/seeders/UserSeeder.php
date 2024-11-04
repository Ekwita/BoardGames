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
        User::create([
            'name' => 'Wojciech',
            'email' => 'www@www.pl',
            'password' => Hash::make('12341234'),
        ]);
        User::create([
            'name' => 'Monika',
            'email' => 'mmm@mmm.pl',
            'password' => Hash::make('12341234'),
        ]);
    }
}
