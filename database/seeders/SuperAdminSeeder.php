<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\SuperAdmin::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@paradiseisland.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'is_active' => true,
        ]);
    }
}
