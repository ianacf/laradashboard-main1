<?php

declare(strict_types=1);

namespace Modules\DeviceManager\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\DeviceManager\Models\Device;

class DeviceManagerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();

        $this->call([
            DevicePermissionsSeeder::class,
        ]);

        Device::factory(100)->create();

        Model::reguard();
    }
}
