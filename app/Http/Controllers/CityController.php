<?php

namespace App\Http\Controllers;

use App\Models\city;
use App\Http\Requests\StorecityRequest;
use App\Http\Requests\UpdatecityRequest;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = city::all();

        return response()->json([
            'data'=>$cities,

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
    public function store(StorecityRequest $request)
    {
        $City = new city();
        $City->name = $request->input('name');
        $City->price = $request->input('price');
        $City->save();

        return response()->json(['message' => 'Brand created successfully', 'City' => $City], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(city $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(city $city)
    {
        //
    }


    public function paid($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatecityRequest $request, city $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(city $city)
    {
        //
    }
}
