<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	$rows = [
			[ 
				'permission_id' => 1,
				'name' => 'User Management Index',
				'page_id' => 'usermanagement-index',
			],
                        [ 
                                'permission_id' => 2,
                                'name' => 'User Management Add',
                                'page_id' => 'usermanagement-add',
                        ],
                        [ 
                                'permission_id' => 3,
                                'name' => 'User Management Edit',
                                'page_id' => 'usermanagement-edit',
                        ],
                        [ 
                                'permission_id' => 4,
                                'name' => 'Role Management Index',
                                'page_id' => 'role-index',
                        ],
                        [ 
                                'permission_id' => 5,
                                'name' => 'Role Permission Management Index',
                                'page_id' => 'rolepermission-index',
                        ],
                        [ 
                                'permission_id' => 6,
                                'name' => 'View Profile',
                                'page_id' => 'profile-index',
                        ],
	];

	foreach ($rows as $row)
	{			
	
        	DB::table('permissions')->insert($row);
	}
    }
}

