<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Product::truncate();
        User::truncate();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $products = [
            [
                'nama' => 'Ducky One 2 Mini',
                'harga' => 1200000,
                'kategori' => 'Keyboard',
                'qty' => 15
            ],
            [
                'nama' => 'Varminlo TKL',
                'harga' => 1300000,
                'kategori' => 'Keyboard',
                'qty' => 10
            ],
            [
                'nama' => 'Ducky One 2 TKL Midnight',
                'harga' => 1100000,
                'kategori' => 'Keyboard',
                'qty' => 15
            ],
            [
                'nama' => 'Cherry MX Switches speed silver',
                'harga' => 3500,
                'kategori' => 'Switch',
                'qty' => 1000
            ],
            [
                'nama' => 'Keycaps PBT Sunshine full set',
                'harga' => 250000,
                'kategori' => 'Keycaps',
                'qty' => 50
            ],
            [
                'nama' => 'Keycaps PBT Transparant full set',
                'harga' => 200000,
                'kategori' => 'Keycaps',
                'qty' => 0
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
