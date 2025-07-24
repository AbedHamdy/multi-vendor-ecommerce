<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::Create(
[
                'name' => env('ADMIN_NAME', 'Abed'),
                'email' => env('ADMIN_EMAIL' , "abed@abed.com"),
                'password' => Hash::make(env('ADMIN_PASSWORD' , "password123")),
            ]
        );
    }
}
