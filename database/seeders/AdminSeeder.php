<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Admin::create([
        //     'name' => 'Super-Admin',
        //     'email' => 'email@admin.com',
        //     'password' => Hash::make(123123),
        // ]);


        Admin::create([
            'name' => 'Sup-Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make(123123),
        ]);


    }
}
