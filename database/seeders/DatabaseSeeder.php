<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ProductSeeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Store;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Store::factory(5)->create();
        //
        User::factory(12)->create([
            'store_id' => Store::inRandomOrder()->first(),
        ]);
        // 
        User::factory()->create([
            'username' => 'administrator',
            'store_id' => Store::inRandomOrder()->first(),
        ]);

        Category::factory(12)->create();

        $this->call([
            ProductSeeder::class,
        ]);
    }
}
