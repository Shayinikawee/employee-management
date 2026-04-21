<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'Indikatudeniya Unit', 'description' => 'Road maintenance and field operations'],
            ['name' => 'Beragama Unit', 'description' => 'Road maintenance and field operations'],
            ['name' => 'Porupitiya Unit', 'description' => 'Road maintenance and field operations'],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(['name' => $unit['name']], $unit);
        }
    }
}
