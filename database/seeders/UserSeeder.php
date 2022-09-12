<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory()->create([
        //     'fullname' => 'Nguyễn Hoài Chương',
        //     'phone' => '0918031587',
        //     'password' => Hash::make('HoaiChuongaA'),
        //     'gender' => 1,
        //     'address_name' => '12 Hồ Thành Biên, Phường 4, Quận 8, Tp. Hồ Chí Minh',
        //     'avatar' => null,
        //     'token' => null,
        //     'refresh_token' => null,
        // ]);
        DB::table('users')->insert([
            [
                'fullname' => 'Nguyễn Hoài Chương',
                'phone' => '0918031587',
                'password' => Hash::make('HoaiChuongaA'),
                'gender' => 1,
                'address_name' => '12 Hồ Thành Biên, Phường 4, Quận 8, Tp. Hồ Chí Minh',
                'avatar' => null,
                'token' => null,
                'refresh_token' => null,
            ],
        ]);
    }
}
