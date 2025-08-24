<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\Department;
use App\Models\Package; // تأكد عندك جدول Packages
use Illuminate\Support\Facades\Hash;

class VendorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all();
        $packages = Package::all();

        if ($departments->isEmpty() || $packages->isEmpty())
        {
            $this->command->info('Please create at least 1 Department and 1 Package first.');
            return;
        }

        $vendorsData = [
            [
                'name' => 'Abdo Hamdy',
                'email' => 'ven@ven.com',
                'password' => Hash::make('password123'),
                'department_id' => $departments->random()->id,
                'package_id' => $packages->random()->id,
                'status' => 'active',
            ],
            [
                'name' => 'Fashion Hub',
                'email' => 'fashionhub@example.com',
                'password' => Hash::make('password123'),
                'department_id' => $departments->random()->id,
                'package_id' => $packages->random()->id,
                'status' => 'active',
            ],
            [
                'name' => 'Gadget Store',
                'email' => 'gadgetstore@example.com',
                'password' => Hash::make('password123'),
                'department_id' => $departments->random()->id,
                'package_id' => $packages->random()->id,
                'status' => 'inactive',
            ],
        ];

        foreach ($vendorsData as $vendor)
        {
            Vendor::create($vendor);
        }

        $this->command->info('Vendors seeded successfully!');
    }
}
