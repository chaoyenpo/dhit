<?php

namespace Database\Seeders;

use App\Models\Bot;
use App\Models\Domain;
use Illuminate\Database\Seeder;
use Database\Seeders\PermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Domain::factory(100000)->create();

        // $this->call([
        //     PermissionsSeeder::class
        // ]);
    }
}
