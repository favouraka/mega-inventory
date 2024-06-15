<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::factory([
            'name' => 'Administrator',
            'username' => 'administrator',
            'email' => 'admin@dannalisglobal.com',
            'is_admin' => 'administrator',
        ])->for(
                Store::factory()->create([
                    'name' => 'Dannalis Global',
                    'address' => 'Dannalis Global Okota',
                    'phone' => '08012345678',
                    'country' => 'NG'
                ]), 'store'
        )->create();
    }
}
