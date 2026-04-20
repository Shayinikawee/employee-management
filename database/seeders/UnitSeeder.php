<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'Administration Unit', 'description' => 'Administrative and management functions'],
            ['name' => 'Technical Unit', 'description' => 'Engineering and technical operations'],
            ['name' => 'Maintenance Unit', 'description' => 'Road maintenance and field operations'],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(['name' => $unit['name']], $unit);
        }
    }
}
