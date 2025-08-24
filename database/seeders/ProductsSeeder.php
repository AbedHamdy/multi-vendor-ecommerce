<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Department;
use App\Models\Vendor; // لو عندك موديل Vendor
use Faker\Factory as Faker;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $departments = Department::all();
        $vendors = Vendor::all(); // لو عندك Vendors

        foreach ($departments as $dept)
        {
            for ($i=0; $i<5; $i++)
            {
                Product::create([
                    'vendor_id' => $vendors->random()->id ?? 1,
                    'department_id' => $dept->id,
                    'name' => $faker->words(3, true),
                    'description' => $faker->sentence(10),
                    'price' => $faker->randomFloat(2, 50, 5000),
                    'stock' => $faker->numberBetween(0, 100),
                    'is_active' => true,
                    'discount' => $faker->optional()->randomFloat(2, 5, 50)
                ]);
            }
        }
    }
}
