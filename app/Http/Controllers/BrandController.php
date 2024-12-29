<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::all();

        return response()->json([
            'data'=>$brands,

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
    public function store(Request $request)
    {

       // dd($request->input('name'));
        $Brand = new Brand();
        $Brand->name = $request->input('name');
        $Brand->save();

        return response()->json(['message' => 'Brand created successfully', 'Brand' => $Brand], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $Brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $Brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $Brand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $Brand = Brand::find($id);
        $Brand->delete();
        return response()->json(['message'=> 'Brand deleted','brand'=> $Brand],
        200);

    }
}
