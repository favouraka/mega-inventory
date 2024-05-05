<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ProductSeeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Stock;
use App\Models\Store;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProductSeeder::class,
        ]);

        Store::factory()->count(5)->create();

        User::factory(12)->for(Store::inRandomOrder()->first(), 'store')->create();
        // 
        User::factory()->for(
                            Store::factory()->has(
                                    Stock::factory()->for(
                                        Product::inRandomOrder()->first()
                                )
                        )->count(5)->create()->first(), 'store')
                ->create([
                    'username' => 'administrator',
                ]);

        Category::factory(12)->create();

    }
}
