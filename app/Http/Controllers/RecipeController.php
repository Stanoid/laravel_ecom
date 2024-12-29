<?php

namespace App\Http\Controllers;

use App\Models\recipe;
use App\Http\Requests\StorerecipeRequest;
use App\Http\Requests\UpdaterecipeRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $recipes = recipe::select(
            [
                'img',
                'name',
                'id',
                'serving',
                'timeInMinutes',

            ]
        )->orderBy('created_at', 'desc')->simplePaginate(10);

        return response()->json([
            'recipes' => $recipes,
        ], 200);


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
    public function store(StorerecipeRequest $request)
    {


        //return response()->json(['message' => 'Recipe created successfully', 'recipe' => $request], 201);
    //dd($request->file('img'));
        $patho = Storage::disk('public')->put('imgs', $request->file('img'));

        $recipe = new recipe();
        $recipe->name = $request->name;
        $recipe->description = $request->input('description');
        $recipe->serving = $request->input('serving');
        $recipe->img = $patho;
        $recipe->product_id = $request->input('product_id');
        $recipe->timeInMinutes = $request->input('timeInMinutes');
        $recipe->insructions = $request->input('instructions');
        $recipe->save();

        return response()->json(['message' => 'Recipe created successfully', 'recipe' => $recipe], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $recipe = recipe::with('product')->findOrFail($id);

        return response()->json([
            'data' => $recipe,

        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(recipe $recipe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdaterecipeRequest $request, $id)

    {

        $recipe = recipe::findOrFail($id);


        if (json_decode($request->imgChanged)) {

            $paths = [];

            if ($request->hasFile('img')) {

                $patho = Storage::disk('public')->put('imgs', $request->file('img'));

                //  dd($request->file('image'));

            }



            $recipe->update([
            'img' => json_encode($patho)
            ]);
        }

        $recipe->update([
          'name'=> $recipe->name,
            'description'=> $recipe->description,
            'serving'=> $recipe->serving,
            'img'=> $recipe->img,
            'product_id'=> $recipe->product_id,
            'timeInMinutes'=> $recipe->timeInMinutes     ,
            'insructions'=> $recipe->instructions

        ]);

        return response()->json([
            'message' => "Recipe updated",
            'data' =>json_decode( $recipe),


        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $recipe = recipe::findOrFail($id);
        $recipe->delete();
        return response()->json([
            'message'=> 'Recipe deleted successfully',
            'data'=>json_decode( $recipe),
            ],200);
    }
}
