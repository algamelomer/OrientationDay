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
        // Create first viewer user
        $viewer1 = User::firstOrCreate(
            [ 'email' => 'viewer@example.com' ],
            [
                'name' => 'Viewer',
                'password' => Hash::make('viewer123'),
            ]
        );
        if ($viewer1 && method_exists($viewer1, 'assignRole')) {
            $viewer1->assignRole('viewer');
        }

        $viewer2 = User::firstOrCreate(
            [ 'email' => 'itclub@cbatu.com' ],
            [
                'name' => 'Viewer Two',
                'password' => Hash::make('itclub5040'),
            ]
        );
        if ($viewer2 && method_exists($viewer2, 'assignRole')) {
            $viewer2->assignRole('viewer');
        }
    }
}
