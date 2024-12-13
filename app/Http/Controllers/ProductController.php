<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Cache;
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




        if (Cache::has('products')) {

           $cached_products= Cache::get('products');
            return response()->json([
                'data'=>$cached_products,

                ],200);

        }else{

            $products = Product::with(['category'])->orderBy('created_at','desc')->paginate(30);

            Cache::put('products', $products, 60);

            return response()->json([
                'data'=>$products,

                ],200);


        }







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
    public function store(StoreProductRequest $request)
    {

         if($request->validated()){


           // $patho = Storage::disk('public')->put('imgs', $request->file('image'));





         $paths = [];

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $patho = Storage::disk('public')->put('imgs', $image);

                    array_push($paths,$patho);
                // Do something with the image path, like storing it in a database
            }
        }



                $product= Product::create(
            [
                'name' => $request->name,
                'stock' => $request->stock,
                'price' => $request->price,
                'category_id'=>$request->category,
                'description'=>$request->description,
                'img'=>json_encode($paths)


            ]
           );

           return response()->json([
             'data'=>$product,

              ],201);
        }else{

            //  return response()->json([
            // 'error'=>$request->validated()
            // ],201);////created

       }








    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $product = Product::with('category')->findOrFail($id);

        return response()->json([
            'data'=>$product,

             ],200);
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
