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
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)

    {

        if ($request->query('cid') == 0) {


            $products = Cache::remember('products'.$request->query('cid').$request->query('page')
, 60, function () {
                return    Product::with(['brand', 'category'])->select(
                    [
                        'img',
                        'price',
                        'stock',
                        'category_id',
                        "name",
                        "brand_id",
                        "id"
                    ]
                )->simplePaginate(10);
            });
        } else {
            $products = Cache::remember('products'.$request->query('cid').$request->query('page')
, 60, function (Request $request) {
                return    Product::with(['brand', 'category'])->select(
                [
                    'img',
                    'price',
                    'stock',
                    'category_id',
                    "name",
                    "brand_id",
                    "id"
                ]
            )->where('category_id', $request->
            query('cid'))->simplePaginate(10);
            });
        }

        return response()->json([
                  'products' => $products,
                  ], 200);







        // if (Cache::has('products' . $request->query('cid') . $request->query('page'))) {
        //     $cached_products = Cache::get('products' . $request->query('cid') . $request->query('page'));
        //     return response()->json([
        //         'products' => $cached_products,
        //     ], 200);
        // } else {
        //     // $categories = Category::orderBy('created_at','desc')->paginate(10);
        //     if ($request->query('cid') == 0) {
        //         $products = Product::with(['brand', 'category'])->select(
        //             [
        //                 'img',
        //                 'price',
        //                 'stock',
        //                 'category_id',
        //                 "name",
        //                 "brand_id",
        //                 "id"
        //             ]
        //         )->get();

        //         $products = $products->orderBy('created_at', 'desc')->paginate(10);
        //     } else {
        //         $products = Product::with(['brand', 'category'])->select(
        //             [
        //                 'img',
        //                 'price',
        //                 'stock',
        //                 'category_id',
        //                 "name",
        //                 "brand_id",
        //                 "id"
        //             ]
        //         )->where('category_id', $request->
        //         query('cid'))->get();

        //         $products = $products->orderBy('created_at', 'desc')->paginate(10);
        //     }

        //     Cache::put('products' . $request->query('cid') . $request->
        //     query('page'), $products, 120);
        //     return response()->json([
        //         'products' => $products,
        //     ], 200);

        // }
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

        if ($request->validated()) {


            // $patho = Storage::disk('public')->put('imgs', $request->file('image'));





            $paths = [];

            // if ($request->hasFile('image')) {
            //     foreach ($request->file('image') as $image) {
            //         $patho = Storage::disk('public')->put('imgs', $image);

            //         array_push($paths, $patho);
            //         // Do something with the image path, like storing it in a database
            //     }
            // }



            $product = Product::create(
                [
                    'name' => $request->name,
                    'brand_id' => $request->brand_id,
                    'stock' => $request->stock,
                    'fact_id' => $request->fact_id,
                    'origin_country' => $request->origin_country,
                    'discount' => $request->discount,
                    'size' => $request->size,
                    'expiration_date' => $request->expiration_date,
                    'price' => $request->price,
                    'category_id' => $request->category,
                    'description' => $request->description,
                    //'img' => json_encode($paths)


                ]
            );

            return response()->json([
                'data' => $product,

            ], 201);
        } else {

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

        $product = Product::with(['brand', 'category', 'recipes','fact'])->findOrFail($id);

        return response()->json([
            'data' => $product,

        ], 200);
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
    public function update(UpdateProductRequest $request , $id)
    {
        try {
            $product = Product::findOrFail($id);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'error' =>  $e->getMessage(),
            ], 404);
        }





        if (json_decode($request->imgChanged)) {

            $paths = [];

            if ($request->hasFile('image')) {

                $patho = Storage::disk('public')->put('imgs', $request->file('image'));

                //  dd($request->file('image'));
                foreach ($request->file('image') as $image) {
                    dd($image);
                    $patho = Storage::disk('public')->put('imgs', $image);
                    array_push($paths, $patho);
                    // Do something with the image path, like storing it in a database
                }
            }



            $product->update([
            'img' => json_encode($patho)
            ]);
        }


        $product->update([
            'name' => $request->name,
            'brand_id' => $request->brand_id,
            'stock' => $request->stock,
            'fact_id' => $request->fact_id,
            'origin_country' => $request->origin_country,
            'discount' => $request->discount,
            'size' => $request->size,
            'expiration_date' => $request->expiration_date,
            'price' => $request->price,
            'category_id' => $request->category,
            'description' => $request->description,
        ]);


        return response()->json([
            'message' => "Product updated",
            'data' => $product,


        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }

    public function test(Request $request)
    {
        $products = Product::with(['brand', 'category'])->select(
            [
                'img',
                'price',
                'stock',
                'category_id',
                "name",
                "brand_id",
                "id"
            ]
        )->orderBy('created_at', 'desc')->
        simplePaginate(10);


        return $products;
    }
}
