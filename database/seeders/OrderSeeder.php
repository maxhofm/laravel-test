<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->insert([
            [
                'title' => 'title1',
                'text' => 'text1',
                'client_id' => 1,
                'status_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'title2',
                'text' => 'text2',
                'client_id' => 1,
                'status_id' => 1,
                'created_at' => Carbon::now()->addSecond(10),
                'updated_at' => Carbon::now()->addSecond(10),
            ],
        ]);
    }
}
