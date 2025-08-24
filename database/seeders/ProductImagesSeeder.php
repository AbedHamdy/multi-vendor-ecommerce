<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;

class ProductImagesSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product)
        {
            for ($i=0; $i<3; $i++)
            {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => "https://picsum.photos/seed/{$product->id}{$i}/600/400",
                    'is_main' => $i === 0
                ]);
            }
        }
    }
}
