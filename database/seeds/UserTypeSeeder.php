<?php

use Illuminate\Database\Seeder;
use App\UserType;
class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userTypes = config("sample.userType");
        $i = 1;
    	foreach ($userTypes as $value) {
    		UserType::insert([
	    		'id' 			=> $i,
	            'name' 			=> $value['name'],
	            'created_by' 	=> 1,
	            'updated_by' 	=> 1,
	            'active' 		=> 1,
        	]);
        	$i++;
    	}
    }
}
