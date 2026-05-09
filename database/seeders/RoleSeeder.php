<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@rda.gov.lk'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('RDA@dmin2026!'), // Change immediately after first login
                'role' => 'admin',
            ]
        );
    }
}
