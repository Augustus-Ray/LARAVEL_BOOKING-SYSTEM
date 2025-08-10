<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first super admin to set as creator
        $superAdmin = \App\Models\SuperAdmin::first();
        
        if ($superAdmin) {
            \App\Models\Admin::create([
                'name' => 'Paradise Admin',
                'email' => 'admin@paradiseisland.com',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ]);
        }
    }
}
