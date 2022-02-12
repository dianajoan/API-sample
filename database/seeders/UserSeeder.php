<?php

namespace Database\Seeders;
use App\Models\User;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        $user_super = new User();
        $user_super->name = 'Admin User';
        $user_super->email = 'admin@admin.com';
        $user_super->password = bcrypt('password');
        $user_super->is_admin = true;
        $user_super->save();

        $guest = new User();
        $guest->name = 'Guest User';
        $guest->email = 'guest@guest.com';
        $guest->password = bcrypt('password');
        $guest->is_admin = false;
        $guest->save();
    }
}
