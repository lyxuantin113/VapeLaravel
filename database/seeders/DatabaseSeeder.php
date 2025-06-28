<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ProductType;
use App\Models\Voucher;
use App\Models\Product;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ProductTypeSeeder;
use Database\Seeders\ProductSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            ProductTypeSeeder::class,
            ProductSeeder::class,
        ]);

        User::factory()->count(5)->create();
        ProductType::factory()->count(3)->create();
        Voucher::factory()->count(3)->create();
        Product::factory()->count(20)->create();
    }
}
