<?php

return [

	"redirectTo" => "/private/dashboard",

	"limit"	=> [
		"name" 				=> ["min" => 3, "max" => 75],
		"firstname" 		=> ["min" => 3, "max" => 75],
		"lastname" 			=> ["min" => 1, "max" => 75],
		"email" 			=> ["min" => 3, "max" => 75],
		"phone" 			=> ["min" => 10, "max" => 10],
		"password" 			=> ["min" => 8, "max" => 16],
		"profile_pic" 		=> ["min" => 10, "max" => 5048, "format" => "jpeg,png,jpg,gif,svg", "min_width" => 100, "min_height" => 100],
		"sub_image" 		=> ["min" => 10, "max" => 5048, "format" => "jpeg,png,jpg,gif,svg"],
		"fcm_code" 			=> ["min" => 3, "max" => 200],
		"social_token" 		=> ["min" => 3, "max" => 200],
		"bio" 				=> ["min" => 3, "max" => 60],
		"age" 				=> ["min" => 1, "max" => 75],
		"height" 			=> ["min" => 1, "max" => 75],
		"weight" 			=> ["min" => 1, "max" => 75],
		"event_detail" 		=> ["min" => 30, "max" => 200],
		"event_name" 		=> ["min" => 3, "max" => 200],
		"rule_name" 		=> ["min" => 3, "max" => 200],
		"event_location" 	=> ["min" => 3, "max" => 200],
		"event_organisation"=> ["min" => 3, "max" => 200],
		"post_text" 		=> ["min" => 2, "max" => 600],
		"post_comment" 		=> ["min" => 1, "max" => 100],
		"question_name" 	=> ["min" => 1, "max" => 4000],
		"questionans_name" 	=> ["min" => 1, "max" => 1000],
		"product_name" 		=> ["min" => 2, "max" => 225],
		"product_description" 		=> ["min" => 3, "max" => 1000],
		"product_code" 		=> ["min" => 3, "max" =>15 ],
		"product_price" 	=> ["min" => 1, "max" => 10000000],
		"product_image" 	=> ["format" => "jpeg,png,jpg,gif,svg", "max" => 2048, "count" => 10 ],
		"thumb_image" 	    => ["format" => "jpeg,png,jpg,gif,svg", "max" => 2048, "count" => 10 ],
		"file"              => ["format" => "mkv,mp4,avi,mpeg,jpeg,png,jpg,gif","count" => 10 ],
		"user_type"         => ["min" => 3, "max" => 40],
		"inspection"        => ["min" => 3, "max" => 225],
		"event_distance"    => 300,
		"cat_name" 			=> ["min" => 3, "max" => 75],
		"cat_sub_name" 		=> ["min" => 3, "max" => 75],
		"profile_text" 		=> ["min" => 3, "max" => 500],

	],

	"social_providers" => ["google", "twitter", "facebook"],

	"status" => ["Inactive", "Active"],

	"line" => ["1"=>'1' ,"2"=>"2","3"=>"3","4"=>"S/ASSY MECH","5"=>"RECTIFICATION","6"=>"FINISH/FINAL","0"=>"0"],


	"date_format" => ["front" => "DD/MM/YYYY", "back" => "d/m/Y"],
	"date_time_format" => ["front" => "DD/MM/YYYY hh:mm A", "back" => "d/m/Y H:i A"],

	"pagination" => [
		"notification" 		=> 20,
		"events" 			=> 10,
		"attenders" 		=> 10,
		"likes" 			=> 10,
		"comments" 			=> 10,
		"posts" 			=> 10,
		"stickers"			=> 10,
		"block"			    => 30,
		"chats"			    => 30,
	],
];
