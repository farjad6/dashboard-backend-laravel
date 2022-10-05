<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@dashboard.com',
            'password' => Hash::make('password'),
            'role' => 1,
            'shipping_address' => '233, GVR-1, bahria town, Lahore',
            'mailing_address' => '233, GVR-1, bahria town, Lahore',
        ]);

        DB::table('users')->insert([
            'first_name' => 'Farjad',
            'last_name' => 'Ahmad',
            'email' => 'farjadahmad3@gmail.com',
            'password' => Hash::make('password'),
            'role' => 1,
            'shipping_address' => '233, GVR-1, bahria town, Lahore',
            'mailing_address' => '233, GVR-1, bahria town, Lahore',
        ]);

        DB::table('users')->insert([
            'first_name' => '',
            'last_name' => 'Support',
            'email' => 'support@dashboard.com',
            'password' => Hash::make('password'),
            'role' => 2,
            'shipping_address' => '233, GVR-1, bahria town, Lahore',
            'mailing_address' => '233, GVR-1, bahria town, Lahore',
        ]);

        DB::table('users')->insert([
            'first_name' => '',
            'last_name' => 'None',
            'email' => 'none@dashboard.com',
            'password' => Hash::make('password'),
            'role' => 0,
            'shipping_address' => '233, GVR-1, bahria town, Lahore',
            'mailing_address' => '233, GVR-1, bahria town, Lahore',
        ]);

    }
}
