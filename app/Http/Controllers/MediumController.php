<?php

namespace App\Http\Controllers;

use App\Models\Medium;
use Illuminate\Http\Request;
class MediumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

      //Display all users

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
        //
        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Medium $medium)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medium $medium)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medium $medium)
    {
        //
    }
}
