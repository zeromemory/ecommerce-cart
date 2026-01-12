<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Laptop Pro',
                'description' => 'High-performance laptop with 16GB RAM and 512GB SSD',
                'price' => 1299.99,
                'stock_quantity' => 15,
                'image' => 'laptop.jpg',
            ],
            [
                'name' => 'Wireless Mouse',
                'description' => 'Ergonomic wireless mouse with precision tracking',
                'price' => 29.99,
                'stock_quantity' => 3, // Low stock
                'image' => 'mouse.jpg',
            ],
            [
                'name' => 'Mechanical Keyboard',
                'description' => 'RGB mechanical keyboard with blue switches',
                'price' => 89.99,
                'stock_quantity' => 25,
                'image' => 'keyboard.jpg',
            ],
            [
                'name' => 'USB-C Hub',
                'description' => '7-in-1 USB-C hub with HDMI and SD card reader',
                'price' => 49.99,
                'stock_quantity' => 2, // Low stock
                'image' => 'usb-hub.jpg',
            ],
            [
                'name' => 'Monitor 27"',
                'description' => '4K UHD monitor with HDR support',
                'price' => 399.99,
                'stock_quantity' => 8,
                'image' => 'monitor.jpg',
            ],
            [
                'name' => 'Webcam HD',
                'description' => '1080p webcam with built-in microphone',
                'price' => 69.99,
                'stock_quantity' => 12,
                'image' => 'webcam.jpg',
            ],
            [
                'name' => 'Headphones',
                'description' => 'Noise-cancelling over-ear headphones',
                'price' => 199.99,
                'stock_quantity' => 1, // Very low stock
                'image' => 'headphones.jpg',
            ],
            [
                'name' => 'Desk Lamp',
                'description' => 'LED desk lamp with adjustable brightness',
                'price' => 39.99,
                'stock_quantity' => 20,
                'image' => 'lamp.jpg',
            ],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
