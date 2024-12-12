<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $posts = Order::with(['user','items','items.product','items.product.category'])->orderBy('created_at','desc')->paginate(18);



    //    Post::
    //    lazyById(200, column: 'id')
    //    ->each->update(['title' => "cunking"]);

    //$flight = Post::where('price', '>', 950)->paginate(10);
    //$max = Post::where('price', '>', 950)->count();





    //   $postsr= Post::orderBy('created_at','desc')->paginate(18);
       return $posts;




        //dd($posts);
        //return $posts;
        return view('posts.index',['posts'=>$posts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

       $data= $request->validate([
        'title'=>['required','max:255'],
        'body'=>['required'],
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);

       // Post::create(['user_id'=>Auth::id(), ...$data]);
     //  $imagePath = $request->file('image')->store('public/posts');
   //dd($request->file('image'));

   //$imageName = time().'.'.$request->image->extension();
   $patho = Storage::disk('public')->put('imgs', $request->file('image'));
//dd($patho);


       Auth::user()-> posts()->create(
        [
            'title' => $data['title'],
            'body' => $data['body'],
            'img' => $patho,
        ]
       );

      // dd($data);
       //Storage::disk('local')->put('example.txt', 'Contents');


       return back()->with('succ','Post created');


    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)

    {
        $fullPost = Post::with(['user','user.userData'])->find($post);
        //dd($fullPost);
       //return $fullPost[0];
       return view('posts.post',['post'=>$fullPost[0]]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {

$poste = Post::find($post->id);

$poste->title = 'edited';

$poste->save();

        return $poste;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //dd($post);
        $post->delete();
       return back()->with('del','Post deleted');


    }
}
