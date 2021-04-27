<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class ProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $profile = new Profile();
        $profile->type = 'super_admin';
        $profile->save();

        $profile = new Profile();
        $profile->type = 'admin';
        $profile->save();

        $profile = new Profile();
        $profile->type = 'e-commerce';
        $profile->save();

    }
}
