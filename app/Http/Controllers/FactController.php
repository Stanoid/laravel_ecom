<?php

namespace App\Http\Controllers;

use App\Models\Fact;
use App\Http\Requests\StoreFactRequest;
use App\Http\Requests\UpdateFactRequest;

class FactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreFactRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Fact $fact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fact $fact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFactRequest $request, Fact $fact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fact $fact)
    {
        //
    }
}
