<?php

namespace App\Http\Controllers;

use App\Models\userData;
use App\Http\Requests\StoreuserDataRequest;
use App\Http\Requests\UpdateuserDataRequest;

class UserDataController extends Controller
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
    public function store(StoreuserDataRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(userData $userData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(userData $userData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateuserDataRequest $request, userData $userData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(userData $userData)
    {
        //
    }
}
