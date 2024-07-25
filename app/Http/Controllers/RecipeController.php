<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Category;
use App\Models\Ingredient;
use Illuminate\Support\Str;
use App\Http\Requests\RecipeCreateRequest;
use App\Http\Requests\RecipeUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



use App\Models\Step;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home (){



$recipes = Recipe::select('recipes.id','recipes.title','recipes.description',
'recipes.created_at','recipes.image','users.name')
->join('users','users.id','=','recipes.user_id')
->orderBy('created_at','desc')
->limit(3)
->get();




$popular= Recipe::select('recipes.id','recipes.title','recipes.description',
'recipes.created_at','recipes.image','recipes.views','users.name')
->join('users','users.id','=','recipes.user_id')
->orderBy('recipes.views','desc')
->limit(3)
->get();
//dd($popular);










        return view ('home',compact('recipes','popular'));


    }




    public function index(Request $request)
    {

        $filters=$request->all();
        




        $query = Recipe::query()->select('recipes.id','recipes.title','recipes.description',
'recipes.created_at','recipes.image','users.name',\DB::raw('AVG(reviews.rating)as rating'))
->join('users','users.id','=','recipes.user_id')
->leftJoin('reviews','reviews.recipe_id','=','recipes.id')
->groupBy('recipes.id')
->orderBy('created_at','desc');

if(!empty($filters))
{
//もしカテゴリーが選択されていたら。
    if(!empty($filters['categories'])){
//カテゴリーで絞込み選択したカテゴリーのレシピを取得。
$query->whereIn('recipes.categories_id',$filters['categories']);


    }
if(!empty($filters['rating'])){


$query->havingRaw('AVG(reviews.rating) >= ?',[$filters['rating']])->orderBy('rating','desc');





}





if(!empty($filters['title']))
{

$query->where('recipes.title','like','%'.$filters['title'].'%');


}



}
$recipes=$query->paginate(5);

$categories= Category::all();







//dd($recipes);
return view("recipes.index",compact("recipes","categories","filters"));



    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $categories=Category::all();


        return view('recipes.create',compact('categories'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RecipeCreateRequest $request)
    {
      

        $posts = $request->all();
        $uuid=Str::uuid()->toString();
        

        
     //  dd($posts);
        $image=$request->file('image');
        $path=Storage::disk('s3')->putFile('recipe',$image,'public');
        $url=Storage::disk('s3')->url($path);
       // dd($url);
try
{



        Recipe::insert([
            'id'=>$uuid,
            'title'=>$posts['title'],
            'description'=>$posts['description'],
            'categories_id'=>$posts['category'],
             'image'=>$url,
            'user_id'=>Auth::id(),

        ]);

        $ingredients=[];
        foreach($posts['ingredients']as $key=>$ingredient){

            $ingredients[$key]=[
                'recipe_id'=>$uuid,
                'name'=>$ingredient['name'],
                'quantity'=>$ingredient['quantity']

            ];





        }


 Ingredient::insert($ingredients);
 

        $steps=[];
        foreach($posts['steps-array']as $key=>$step){
            $steps[$key]=[

                'recipe_id'=>$uuid,
                'step_number'=>$key+1,
                'description'=>$step





            ];





        }


STEP::insert($steps);
DB::commit();


    }catch(\Throwable $th){


    DB::rollBack();
    \Log::debug(print_r($th->getMessage(),true));



    throw $th;

    }

flash()->success('レシピを投稿しました。');

return redirect()->route('recipe.show',['id'=>$uuid]);

        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $recipe=Recipe::with('ingredients','steps','reviews.user','user')
        ->where('recipes.id',$id)
        ->get()
        ->first();


        $recipe_recode=Recipe::find($id);
        // $ingredients= Ingredient::where('recipe_id',$recipe['id'])->get();
        // $steps=Step::where('recipe_id',$recipe['id'])->get();
     //上と等価
        $recipe_recode->increment('views');
        $is_my_recipe=false;
     
        if(Auth::check()&&Auth::id()==$recipe->user_id){

            $is_my_recipe=true;

        }
        $is_reviewed=false;
        if(Auth::check()){

            $is_reviewed=$recipe->reviews->contains('user_id',Auth::id());
        }



        return view('recipes.show',compact('recipe','is_my_recipe','is_reviewed'));


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $recipe=Recipe::with('ingredients','steps','reviews.user','user')
        ->where('recipes.id',$id)
        ->get()
        ->first()->toArray();
if(!Auth::check()||(Auth::id()!==$recipe['user_id'])){

    abort(403);
    
}


        $categories=Category::all();

      return view('recipes.edit',compact('recipe','categories'));

    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(RecipeUpdateRequest $request, string $id)
    {
        

        $posts=$request->all();

        $update_array=[
            
            'title'=>$posts['title'],
            'description'=>$posts['description'],
            'categories_id'=>$posts['category_id'],

        ];
if($request->hasFile('image')){

    $image=$request->file('image');
    $path=Storage::disk('s3')->putFile('recipe',$image,'public');
    $url=Storage::disk('s3')->url($path);
    Recipe::where('id',$id)->update(['image'=>$url]);

$update_array['image']=$url;




}




    try{

        DB::beginTransaction();

        Recipe::where('id',$id)->update($update_array); 
        Ingredient::where('recipe_id',$id)->delete();
        Step::where('recipe_id',$id)->delete();

$ingredients=[];
foreach($posts['ingredients']as $key=>$ingredient){

    $ingredients[$key]=[
        'recipe_id'=>$id,
        'name'=>$ingredient['name'],
        'quantity'=>$ingredient['quantity']

    ];





}


Ingredient::insert($ingredients);


$steps=[];
foreach($posts['steps-array']as $key=>$step){
    $steps[$key]=[

        'recipe_id'=>$id,
        'step_number'=>$key+1,
        'description'=>$step





    ];





}


STEP::insert($steps);

DB::commit();


}catch(\Throwable $th){

    DB::rollBack();
    \Log::debug(print_r($th->getMessage(),true));



    throw $th;

    }
flash()->success('レシピを更新しました。');
return redirect()->route('recipe.show',['id'=>$id]);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    
    Recipe::find($id)->delete();
    flash()->warning('レシピを削除しました。');
    return redirect()->route('home');
    
    }


}
