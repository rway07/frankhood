<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB as DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_types')
            ->insert(
                [
                    'id' => 1,
                    'description' => 'Contanti'
                ]
            );

        DB::table('payment_types')
            ->insert(
                [
                    'id' => 2,
                    'description' => 'Bonifico Bancario'
                ]
            );
    }
}
