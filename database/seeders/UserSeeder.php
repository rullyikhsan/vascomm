<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();
        $data = [
            'name' => 'Rully Ikhsan',
            'email' => 'rlly.ikhsn@gmail.com',
            'nomor_telfon' => '082298766855',
            'password' => Hash::make('123456')
        ];
        User::create($data);
        UserFactory::new()->count(50)->create();
    }
}
