<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ProductSeeder;
use App\Models\User;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //
        User::factory(12)->create();
        // 
        User::factory()->create([
            'username' => 'administrator',
        ]);

        Category::factory(12)->create();

        $this->call([
            ProductSeeder::class,
        ]);
    }
}
