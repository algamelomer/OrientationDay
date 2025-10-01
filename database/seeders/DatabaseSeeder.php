<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Setting;
use App\Models\Hall;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@it.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('it123456789'),
            ]
        );

        // Seed Default Settings
        Setting::updateOrCreate(
            ['key' => 'whatsapp_group_link'],
            ['value' => 'https://chat.whatsapp.com/KyyH4VBplqlEwHkeGbAanM']
        );

        // Seed Default Halls if none exist
        if (Hall::count() === 0) {
            Hall::create(['name' => 'مسرح', 'timing' => '10:00 AM - 11:30 AM', 'capacity' => 250]);
            Hall::create(['name' => 'Hall 101', 'timing' => '11:30 AM - 12:30 PM', 'capacity' => 300]);
            Hall::create(['name' => 'Hall 118', 'timing' => '12:30 PM - 1:30 PM', 'capacity' => 300]);
        }
    }
}

