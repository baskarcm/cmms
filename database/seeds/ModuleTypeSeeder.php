<?php

use Illuminate\Database\Seeder;
use App\ModuleType;

class ModuleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $moduleType = config("sample.moduleType");
        $i = 1;
    	foreach ($moduleType as $value) {
    		ModuleType::insert([
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
