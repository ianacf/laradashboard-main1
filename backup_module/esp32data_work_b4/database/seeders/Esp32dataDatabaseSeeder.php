<?php

namespace Modules\Esp32data\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Esp32data\Models\Esp32;

class Esp32dataDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();

        $this->call([
            Esp32PermissionsSeeder::class,
        ]);

        Esp32::factory(20)->create();

        Model::reguard();
    }
}
