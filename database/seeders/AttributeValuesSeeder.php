<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeValuesSeeder extends Seeder
{
    public function run(): void
    {
        $values = [
            'Brand' => ['Samsung', 'Apple', 'Nike', 'Adidas', 'Ikea', 'Lego'],
            'Color' => ['Red', 'Blue', 'Black', 'White', 'Green', 'Yellow'],
            'Size' => ['S', 'M', 'L', 'XL'],
            'Material' => ['Cotton', 'Leather', 'Plastic', 'Wood', 'Metal'],
            'Warranty' => ['6 months', '1 year', '2 years'],
            'Author' => ['J.K. Rowling', 'George R.R. Martin', 'Dan Brown'],
            'Genre' => ['Fiction', 'Non-fiction', 'Fantasy', 'Mystery'],
            'Language' => ['English', 'Arabic', 'French', 'Spanish'],
            'Age Group' => ['0-3', '4-7', '8-12', '13+'],
            'Type' => ['Outdoor', 'Indoor']
        ];

        foreach ($values as $attrName => $vals)
        {
            $attribute = Attribute::where('name', $attrName)->first();
            if ($attribute)
            {
                foreach ($vals as $val)
                {
                    AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value' => $val
                    ]);
                }
            }
        }
    }
}
