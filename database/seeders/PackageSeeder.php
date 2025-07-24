<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\Feature;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Basic Package',
                'price' => 99.99,
                'duration' => 1, // 1 month
                'max_products' => 10,
                'status' => 'active',
                'features' => [
                    'Support up to 10 products',
                    'Email support',
                    'Basic sales reports',
                ],
            ],
            [
                'name' => 'Advanced Package',
                'price' => 249.99,
                'duration' => 3, // 3 months
                'max_products' => 50,
                'status' => 'active',
                'features' => [
                    'Support up to 50 products',
                    'Live chat support',
                    'Professional analytics and reports',
                    'In-app promotional tools',
                ],
            ],
            [
                'name' => 'Professional Package',
                'price' => 499.99,
                'duration' => 6, // 6 months
                'max_products' => 200,
                'status' => 'inactive',
                'features' => [
                    'Unlimited products',
                    '24/7 customer support',
                    'Advanced analytics dashboard',
                    'Integration with multiple payment gateways',
                    'Team management features',
                ],
            ],
        ];

        foreach ($packages as $data) {
            $package = Package::create([
                'name' => $data['name'],
                'price' => $data['price'],
                'duration' => $data['duration'],
                'max_products' => $data['max_products'],
                'status' => $data['status'],
            ]);

            foreach ($data['features'] as $featureTitle) {
                Feature::create([
                    'package_id' => $package->id,
                    'title' => $featureTitle,
                ]);
            }
        }
    }
}
