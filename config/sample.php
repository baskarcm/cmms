<?php

return [

"adminType" =>[
                ["name" => "Admin", 	"code" => "admin", "admin_type" => 1],
				["name" => "Sub Admin", "code" => "subadmin", "admin_type" => 2]
			],
"userType" =>[
				["name" => "Technician"],
                ["name" => "Engineer"],
                ["name" => "Manager"]
			],

"moduleType" =>[
				["name" => "PM"],
                ["name" => "Breakdown"]
			],
"gender"	=> [
					["name" => "Male", "code" => "male"],
					["name" => "Female", "code" => "female"],
				],

"deviceType"	=> [
					["name" => "Android", "code" => "android", "mobile_device" => 1],
					["name" => "IOS", "code" => "ios", "mobile_device" => 1],
					["name" => "Web", "code" => "web", "mobile_device" => 0],
				],

"permission"   =>[
					[	"name" => "Can View Question Type",
				 		"code" => "questiontype.view"
				 	],
					[	"name" => "Can Edit Question Type",
						"code" => "questiontype.edit"
					],
					[	"name" => "Can Delete Question Type",
						"code" => "questiontype.delete"
					],
					[	"name" => "Can Create Question Type",
						"code" => "questiontype.create"
					],
					[	"name" => "Can Create Question Sub Type",
						"code" => "questionsubtype.create"
					],
					[	"name" => "Can Edit Question Sub Type",
						"code" => "questionsubtype.edit"
					],
					[	"name" => "Can Delete Question Sub Type",
						"code" => "questionsubtype.delete"
					],
					[	"name" => "Can View Question Sub Type",
						"code" => "questionsubtype.view"
					],
					[	"name" => "Can View Product Category",
						"code" => "productcategory.view"
					],
					[	"name" => "Can Delete Product Category",
						"code" => "productcategory.delete"
					],
					[	"name" => "Can Edit Product Category",
						"code" => "productcategory.edit"
					],
					[	"name" => "Can Create Product Category",
						"code" => "productcategory.create"
					],
					[	"name" => "Can View Product Sub Category",
						"code" => "productsubcategory.view"
					],
					[	"name" => "Can Delete Product Sub Category",
						"code" => "productsubcategory.delete"
					],
					[	"name" => "Can Edit Product Sub Category",
						"code" => "productsubcategory.edit"
					],
					[	"name" => "Can Create Product Sub Category",
						"code" => "productsubcategory.create"
					],
					[	"name" => "Can View Attribute Category",
						"code" => "attributecategory.view"
					],
					[	"name" => "Can Delete Attribute Category",
						"code" => "attributecategory.delete"
					],
					[	"name" => "Can Edit Attribute Category",
						"code" => "attributecategory.edit"
					],
					[	"name" => "Can Create Attribute Category",
						"code" => "attributecategory.create"
					],
					[	"name" => "Can View Attribute Sub Category",
						"code" => "attributesubcategory.view"
					],
					[	"name" => "Can Delete Attribute Sub Category",
						"code" => "attributesubcategory.delete"
					],
					[	"name" => "Can Edit Attribute Sub Category",
						"code" => "attributesubcategory.edit"
					],
					[	"name" => "Can Create Attribute Sub Category",
						"code" => "attributesubcategory.create"
					],
					[	"name" => "Can View User Attribute Category",
						"code" => "userAttributecategory.view"
					],
					[	"name" => "Can Delete User Attribute Category",
						"code" => "userAttributecategory.delete"
					],
					[	"name" => "Can Edit User Attribute Category",
						"code" => "userAttributecategory.edit"
					],
					[	"name" => "Can Create User Attribute Category",
						"code" => "userAttributecategory.create"
					],
					[	"name" => "Can View User Attribute Sub Category",
						"code" => "userAttributesubcategory.view"
					],
					[	"name" => "Can Delete User Attribute Sub Category",
						"code" => "userAttributesubcategory.delete"
					],
					[	"name" => "Can Edit User Attribute Sub Category",
						"code" => "userAttributesubcategory.edit"
					],
					[	"name" => "Can Create User Attribute Sub Category",
						"code" => "userAttributesubcategory.create"
					],

				],


];
