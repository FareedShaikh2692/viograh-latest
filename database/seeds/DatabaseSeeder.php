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
        // $this->call(UsersTableSeeder::class);
        DB::table('manages')->insert([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => "super.admin@admin.com",
            'password' => bcrypt('admin123'),
            'admin_type'=>'super_admin',
            'created_by' => 1,
            'updated_by' => 1,
            'created_ip' => '111',
            'created_at'=>date('Y-m-d h:m:s'),
            'updated_at' => date('Y-m-d h:m:s'),
        ]);
        DB::table('settings')->insert([
            'name'=>'Admin Pagination Limit',
            'setting_key'=>'admin_pagination_limit',
            'value'=>'10',
            'comment'=>'Setting Pagination Limit per page',
            'setting_type'=>'general',
            'status'=>'enable',
            'created_ip'=>'111',
            'created_by'=>1,
            'created_at'=>date('Y-m-d h:m:s'),
            'updated_at' => date('Y-m-d h:m:s'),
        ]);

        DB::table('settings')->insert([
            'name'=>'Site Name',
            'setting_key'=>'site_name',
            'value'=>'Admin Demo',
            'comment'=>'This field is used for set site name.',
            'setting_type'=>'general',
            'status'=>'enable',
            'created_ip'=>'111',
            'created_by'=>1,
			'updated_by'=>1,
            'created_at'=>date('Y-m-d h:m:s'),
            'updated_at' => date('Y-m-d h:m:s'),
        ]);

        DB::table('settings')->insert([
            'name'=>'Site Email',
            'setting_key'=>'site_email',
            'value'=>'laravel.demo@pavansgroup.in',
            'comment'=>'This field is used for set site email.',
            'setting_type'=>'general',
            'status'=>'enable',
            'created_ip'=>'111',
            'created_by'=>1,
            'created_at'=>date('Y-m-d h:m:s'),
            'updated_at' => date('Y-m-d h:m:s'),
        ]);
		
    }
}
