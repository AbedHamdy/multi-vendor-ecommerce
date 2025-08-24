<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\Department;

class AttributesSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            'Electronics' => ['Brand', 'Color', 'Warranty'],
            'Fashion' => ['Size', 'Color', 'Material'],
            'Home & Kitchen' => ['Material', 'Brand', 'Color'],
            'Books' => ['Author', 'Genre', 'Language'],
            'Toys' => ['Age Group', 'Material', 'Brand'],
            'Sports' => ['Brand', 'Type', 'Size']
        ];

        foreach ($attributes as $deptName => $attrs)
        {
            $department = Department::where('name', $deptName)->first();
            foreach ($attrs as $attr)
            {
                Attribute::create([
                    'department_id' => $department->id,
                    'name' => $attr
                ]);
            }
        }
    }
}
