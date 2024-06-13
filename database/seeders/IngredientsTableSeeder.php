<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class IngredientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $recipes =DB::table('recipes')->pluck('id')->toArray();
        $ingredient_Names=['Salt','Sugar','Flour','Eggs','Milk','Butter','Oil','vanilla extract','Baking Powder','Cocoa powder'];
        foreach($recipes as $recipe){
            for($i=0;$i<rand(2,5);$i++){

            DB::table('ingredients')->insert([


                'recipe_id'=>$recipe,
                'name'=>$ingredient_Names[array_rand($ingredient_Names)],
                'quantity'=>rand(1,500) . 'g',

                'created_at'=>now(),
                'updated_at'=>now(),

 


            ]);





            }



        }




    }
}
