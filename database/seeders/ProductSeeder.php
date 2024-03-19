<?php

namespace Database\Seeders;

use App\Models\Product;
use Database\Factories\ProductFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'nama' => 'Product Test',
            'harga' => '10000',
            'gambar' => 'Dummy Image 1',
            'status' => 'Ready'
        ];
        Product::create($data);
        ProductFactory::new()->count(50)->create();
    }
}
