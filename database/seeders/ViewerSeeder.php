<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ViewerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a viewer user
        $user = User::firstOrCreate(
            [ 'email' => 'viewer@example.com' ],
            [
                'name' => 'Viewer',
                'password' => Hash::make('viewer123'),
            ]
        );

        // Assign the 'viewer' role if using spatie/laravel-permission
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('viewer');
        }
    }
}
