<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Container\Attributes\Auth;
//use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)

    {








        if (Cache::has('products'.$request->query('cid').$request->query('page'))) {



           $cached_products= Cache::get('products'.$request->query('cid').$request->query('page'));
            return response()->json([
            'products'=> $cached_products,
            'page_c'=>$request->query("page"),
            'cid_c'=>$request->query("cid"),


            ],200);

        }else{

            // $categories = Category::orderBy('created_at','desc')->paginate(10);


            if($request->query('cid')==0){
                $products = Product::orderBy('created_at','desc')->paginate(10);
            }else{
                $products = Product::where('category_id' ,$request->query('cid'))->orderBy('created_at','desc')->paginate(10);

            }

            Cache::put('products'.$request->query('cid').$request->query('page'), $products, 30);

            return response()->json([
                'products'=> $products,
                'page_'=>$request->query("page"),
                'cid'=>$request->query("cid"),
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
