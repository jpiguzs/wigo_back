<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            ['name' => 'administardor',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
            'carnet_id' => '0',
            'tlf' => '0',
            'type' => 1,],
            ['name' => 'user',
            'email' => 'user@user.com',
            'password' => Hash::make('123456'),
            'carnet_id' => '1',
            'tlf' => '1',
            'type' => 2,]
        ];
        foreach ($data as $key => $user) {
            # code...
            User::create($user);
        }

    }
}
