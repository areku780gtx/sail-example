<x-app-layout>
    <x-slot name="script">

<script src="/js/recipe/create.js"></script>

    </x-slot>
    
    <form  action ="{{route('recipe.store')}}" method="POST" class ="w-10/12 p-4 mx-auto bg-white rounded" enctype="multipart/form-data">
       @csrf
        {{Breadcrumbs::render('create')}}

        <div class="grid grid-cols-2 rounded border border-gray-500 mt-4">
            <div class="col-span-1">
                <img class ="object-cover rounded-t-lg  w-full aspect-video" src="/images/recipe-dummy.png" alt="recipe-image">
            




                
                <input type="file" name="image" class="border border-gray-300 p-2 mb-4 w-full rounded"> 

            </div>
            
            <div class="col-span-1 p-4">
                <input type="text" name="title" placeholder="レシピ名" class="border border-gray-300 p-2 mb-4 w-full rounded">
           
                <textarea name="description" placeholder="レシピの説明" class ="border border-gray-300 p-2 mb-4 w-full rounded"></textarea>

<select name="category" class ="border border-gray-300 p-2 mb-4 w-full rounded">


<option value="">カテゴリー</option>

@foreach($categories as $c)


<option value="{{$c['id']}}">{{$c['name']}}</option>



@endforeach

</select>


                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">レシピを投稿する</button>


            </div>
        </div>
    </div>


<hr class ="my-4">
<h4 class="text-center">手順を入力</h4>
<div id="steps">
    @for($i=1; $i<4; $i++)

    
    <div class ="step flex justify-between">

        @include ('components.bar-3')
      <p>手順{{$i}}</p>
            <input type="text" name="steps[]" placeholder="手順を入力" class="border border-gray-300 p-2 mb-4 w-full rounded">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
              </svg>
              

    
    </div>

    @endfor
</div>
</form>
</x-app-layout>


    
