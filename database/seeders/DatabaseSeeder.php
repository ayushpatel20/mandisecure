<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Guard: do not seed test data in production
        if (app()->isProduction()) {
            $this->command->warn('Seeder skipped: cannot run in production environment.');
            return;
        }

        // Users — pass plain-text passwords; the 'hashed' cast on User handles bcrypt
        $admin = User::create([
            'name'              => 'Admin',
            'email'             => 'admin@mandisecure.com',
            'mobile'            => '9000000001',
            'password'          => 'password',
            'role'              => 'admin',
            'status'            => 'active',
            'language'          => 'en',
            'email_verified_at' => now(),
        ]);

        $buyer = User::create([
            'name'              => 'Test Buyer',
            'email'             => 'buyer@mandisecure.com',
            'mobile'            => '9000000002',
            'password'          => 'password',
            'role'              => 'buyer',
            'status'            => 'active',
            'language'          => 'en',
            'email_verified_at' => now(),
        ]);

        $seller = User::create([
            'name'              => 'Test Seller',
            'email'             => 'seller@mandisecure.com',
            'mobile'            => '9000000003',
            'password'          => 'password',
            'role'              => 'seller',
            'status'            => 'active',
            'language'          => 'en',
            'email_verified_at' => now(),
        ]);

        // Categories
        $this->call(CategorySeeder::class);

        $coconut    = Category::where('slug', 'coconut')->first();
        $vegetables = Category::where('slug', 'vegetables')->first();
        $fruits     = Category::where('slug', 'fruits')->first();
        $masala     = Category::where('slug', 'masala')->first();

        // Sample products
        $sampleProducts = [
            [
                'category_id'            => $coconut->id,
                'seller_id'              => $seller->id,
                'product_name'           => 'Fresh Tender Coconut',
                'description'            => 'Juicy tender coconuts directly from Kerala farms.',
                'price'                  => 45.00,
                'wholesale_price'        => 35.00,
                'discount_price'         => 40.00,
                'stock_quantity'         => 500,
                'unit'                   => 'piece',
                'minimum_order_quantity' => 10,
                'delivery_charges'       => 50.00,
                'expected_delivery_time' => '1-2 days',
                'location'               => 'Kerala',
                'status'                 => 'approved',
            ],
            [
                'category_id'            => $vegetables->id,
                'seller_id'              => $seller->id,
                'product_name'           => 'Fresh Tomatoes',
                'description'            => 'Farm-fresh red tomatoes, pesticide-free.',
                'price'                  => 30.00,
                'wholesale_price'        => 22.00,
                'discount_price'         => null,
                'stock_quantity'         => 1000,
                'unit'                   => 'kg',
                'minimum_order_quantity' => 5,
                'delivery_charges'       => 30.00,
                'expected_delivery_time' => 'Same day',
                'location'               => 'Pune, Maharashtra',
                'status'                 => 'approved',
            ],
            [
                'category_id'            => $fruits->id,
                'seller_id'              => $seller->id,
                'product_name'           => 'Alphonso Mangoes',
                'description'            => 'Premium Alphonso mangoes from Ratnagiri.',
                'price'                  => 500.00,
                'wholesale_price'        => 420.00,
                'discount_price'         => 480.00,
                'stock_quantity'         => 200,
                'unit'                   => 'dozen',
                'minimum_order_quantity' => 2,
                'delivery_charges'       => 80.00,
                'expected_delivery_time' => '2-3 days',
                'location'               => 'Ratnagiri, Maharashtra',
                'status'                 => 'pending',
            ],
            [
                'category_id'            => $masala->id,
                'seller_id'              => $seller->id,
                'product_name'           => 'Turmeric Powder',
                'description'            => 'Pure Erode turmeric, high curcumin content.',
                'price'                  => 180.00,
                'wholesale_price'        => 150.00,
                'discount_price'         => null,
                'stock_quantity'         => 300,
                'unit'                   => 'kg',
                'minimum_order_quantity' => 1,
                'delivery_charges'       => 40.00,
                'expected_delivery_time' => '3-5 days',
                'location'               => 'Erode, Tamil Nadu',
                'status'                 => 'pending',
            ],
        ];

        foreach ($sampleProducts as $data) {
            Product::create(array_merge($data, [
                'slug' => Str::slug($data['product_name']),
            ]));
        }

        // Default payment settings
        $paymentSettings = [
            'payment_upi_id'               => 'mandisecure@ybl',
            'payment_bank_account_holder'  => 'MandiSecure Private Limited',
            'payment_bank_account_number'  => '1234567890',
            'payment_bank_ifsc'            => 'SBIN0001234',
            'payment_bank_name'            => 'State Bank of India',
        ];

        foreach ($paymentSettings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
