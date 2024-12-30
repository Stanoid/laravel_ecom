<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('created_at','desc')->paginate(10);

        return response()->json([
            'data'=> $categories
                    ],200);

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
    public function store(StoreCategoryRequest $request)
    {


        $patho = Storage::disk('public')->put('imgs', $request->file('img'));

        $category = Category::create([
            'name' => $request->name,
            'img'=> $patho
        ]);

        return response()->json([
            'data'=> $category
                    ],201);


    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::find($id);
        return response()->json([
            'data'=> $category
                    ],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    public function list(Category $category)
    {
        $categories = Category::select(['id','name'])->orderBy('created_at','desc')->get();

        return response()->json([
            'data'=> $categories
                    ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $category = Category::find($id);
        $category->delete();

        return response()->json([
            'message'=> 'Category deleted successfully'

                    ],200);
    }
}
