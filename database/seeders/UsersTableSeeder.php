<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        

        $users=[

            ['id'=>Str::uuid(),'name'=>'田中太郎','email'=>'arekux800@gmail.com',
            'password'=>Hash::make('password')],
            ['id'=>Str::uuid(),'name'=>'山田太郎','email'=>'test22@example.com',
            'password'=>Hash::make('password')],
            ['id'=>Str::uuid(),'name'=>'反町たかし','email'=>'test3@example.com',
            'password'=>Hash::make('password')],
            ['id'=>Str::uuid(),'name'=>'新垣結衣','email'=>'test4@example.com',
            'password'=>Hash::make('password')],
            ['id'=>Str::uuid(),'name'=>'雨宮天','email'=>'test5@example.com',
            'password'=>Hash::make('password')],
            ['id'=>Str::uuid(),'name'=>'山崎はるか','email'=>'test6@example.com',
            'password'=>Hash::make('password')],
            ['id'=>Str::uuid(),'name'=>'里見浩太朗','email'=>'test7@example.com',
            'password'=>Hash::make('password')],
            ['id'=>Str::uuid(),'name'=>'山崎隆','email'=>'test8@example.com',
            'password'=>Hash::make('password')],
            ['id'=>Str::uuid(),'name'=>'後藤真紀','email'=>'test9@example.com',
            'password'=>Hash::make('password')],
            ['id'=>Str::uuid(),'name'=>'阿部貞夫','email'=>'test10@example.com',
            'password'=>Hash::make('password')],


        ];
        
foreach($users as $user){


    DB::table('users')->insert($user);


}






    }
}
