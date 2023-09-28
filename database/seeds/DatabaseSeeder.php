<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	$this->call(AdminUserTypeSeeder::class);
        $this->call(UserTypeSeeder::class);
    	$this->call(DeviceTypeSeeder::class);
        $this->call(GenderSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(ModuleTypeSeeder::class);
    }
}
