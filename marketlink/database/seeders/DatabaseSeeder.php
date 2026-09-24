<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Market;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Account Create Karein
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@marketlink.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '03001234567',
        ]);

        // 2. Farmer Account Create Karein
        User::create([
            'name' => 'Green Farm Owner',
            'email' => 'farmer@marketlink.com',
            'password' => Hash::make('farmer123'),
            'role' => 'farmer',
            'stall_name' => 'Green Valley Produce',
            'phone' => '03111234567',
            'address' => 'Stall #12, Sunday Farmers Market',
            'latitude' => 24.8607,
            'longitude' => 67.0011,
        ]);

        // 3. Customer Account Create Karein
        User::create([
            'name' => 'John Doe',
            'email' => 'customer@marketlink.com',
            'password' => Hash::make('customer123'),
            'role' => 'customer',
            'phone' => '03221234567',
            'address' => 'Clifton, Block 5',
        ]);

        // 4. Sample Market Entry
        Market::create([
            'market_name' => 'Central Farmers Market',
            'address' => 'Main Boulevard, Sector G-6',
            'operating_days' => 'Saturday - Sunday',
            'timings' => '08:00 AM - 02:00 PM',
            'latitude' => 24.8607,
            'longitude' => 67.0011,
        ]);
    }
}