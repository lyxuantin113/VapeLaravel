<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\ProductType;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Salt Nic', 'Freebase', 'Pod System', 'Disposable', 'Box Mod'];

        foreach ($types as $type) {
            ProductType::create(['name' => $type]);
        }
    }
}
