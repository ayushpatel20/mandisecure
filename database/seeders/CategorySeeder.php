<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Coconut',
                'description' => 'Fresh coconuts and coconut products from farms across India.',
                'image'       => 'categories/eaAQgNza9TRBfd2F5vRaNO1DnkZWTApyS5316lZC.png',
            ],
            [
                'name'        => 'Vegetables',
                'description' => 'Farm-fresh vegetables sourced directly from local farmers.',
                'image'       => 'categories/S5WQqWmgZQpPO8B5kepPmOG8aBDxVRS1GrQ9ZnHa.webp',
            ],
            [
                'name'        => 'Fruits',
                'description' => 'Seasonal and exotic fruits available at wholesale prices.',
                'image'       => 'categories/mCMlym9avJzTl87u3EAQx4iX3JBI9DFs42G7zotl.jpg',
            ],
            [
                'name'        => 'Masala',
                'description' => 'Authentic Indian spices and masala blends.',
                'image'       => 'categories/hB2uYDjXK3s75MY9M7KJIMeala89j7ByWv2veB2U.jpg',
            ],
        ];

        foreach ($categories as $data) {
            Category::create([
                'name'        => $data['name'],
                'slug'        => Str::slug($data['name']),
                'description' => $data['description'],
                'image'       => $data['image'],
                'status'      => true,
            ]);
        }
    }
}
