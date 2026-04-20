<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Casual Leave', 'default_days' => 7, 'description' => 'Casual leave for personal matters'],
            ['name' => 'Medical Leave', 'default_days' => 21, 'description' => 'Sick leave with medical certificate'],
            ['name' => 'Annual Leave', 'default_days' => 14, 'description' => 'Annual vacation leave'],
        ];

        foreach ($types as $type) {
            LeaveType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}
