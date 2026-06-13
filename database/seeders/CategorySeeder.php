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
            ],
            [
                'name'        => 'Vegetables',
                'description' => 'Farm-fresh vegetables sourced directly from local farmers.',
            ],
            [
                'name'        => 'Fruits',
                'description' => 'Seasonal and exotic fruits available at wholesale prices.',
            ],
            [
                'name'        => 'Masala',
                'description' => 'Authentic Indian spices and masala blends.',
            ],
        ];

        foreach ($categories as $data) {
            Category::create([
                'name'        => $data['name'],
                'slug'        => Str::slug($data['name']),
                'description' => $data['description'],
                'status'      => true,
            ]);
        }
    }
}
