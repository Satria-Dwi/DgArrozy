<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class dgarrozyseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\DgarrozyRole::insert([
            ['code' => 'admin', 'name' => 'Administrator'],
            ['code' => 'user', 'name' => 'User'],
            ['code' => 'manajemen', 'name' => 'manajemen'],
        ]);
    }
}
