<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'Electronics',
            'Fashion',
            'Home & Kitchen',
            'Books',
            'Health & Beauty',
            'Sports & Outdoors',
            'Toys & Games',
            'Automotive',
            'Groceries',
            'Office Supplies',
        ];

        foreach ($departments as $name) {
            Department::create(['name' => $name]);
        }
    }
}
