<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()

    {

        $products = Product::with(['category'])->orderBy('created_at','desc')->paginate(18);

        return response()->json([
            'data'=>$products,

            ]);


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

      // $patho = Storage::disk('public')->put('imgs', $request->file('image'));
        //    Auth::user()-> posts()->create(
        //     [
        //         'title' => $data['title'],
        //         'body' => $data['body'],
        //         'img' => $patho,
        //     ]
        //    );



          // return back()->with('succ','Post created');

          return "aaaa";

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
