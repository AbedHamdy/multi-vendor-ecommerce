<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductAttributeValue;

class ProductAttributeValuesSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product)
        {
            $attributes = Attribute::where('department_id', $product->department_id)->get();
            foreach ($attributes as $attr)
            {
                $value = AttributeValue::where('attribute_id', $attr->id)->inRandomOrder()->first();
                if ($value)
                {
                    ProductAttributeValue::create([
                        'product_id' => $product->id,
                        'attribute_id' => $attr->id,
                        'attribute_value_id' => $value->id
                    ]);
                }
            }
        }
    }
}
