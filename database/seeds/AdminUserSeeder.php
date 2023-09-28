<?php

use Illuminate\Database\Seeder;
use App\AdminUser;
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		AdminUser::insert([
    		'id' 				=> 1,
            'name' 				=> "Admin",
            'email' 			=> "admin@dems.com",
            'phone' 			=> "1234567890",
            'password' 			=> bcrypt("Dems@123"),
            'user_type'         => 1,
            'created_by' 		=> 1,
            'updated_by' 		=> 1,
            'active' 			=> 1,
    	]);
    }
}
