<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::factory([
            'is_admin' => 'staff',
            'email' => 'staff@dannalisglobal.com'
        ])->for(Store::factory(), 'store')->create();
    }
}
