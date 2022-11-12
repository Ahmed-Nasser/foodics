<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('merchants')->insert(
            [
                'id'      => '5616c9e6-6233-11ed-8cb2-d4d252eedae0',
                'address' => fake()->address,
                'name'    => fake()->name,
            ]
        );
    }
}
