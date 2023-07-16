<?php

namespace App\Http\Controllers;

use App\Models\{Farm,Farmer};
use App\Http\Requests\StoreFarmRequest;
use App\Http\Requests\UpdateFarmRequest;
use Illuminate\Http\Request;

class FarmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $values=$request->except('created_geolocation');
        $description= $request->farmer_id.'|'.Farmer::find($request->farmer_id)->farmer_name.' |0'.Farm::where('farmer_id',$request->farmer_id)->count()+1;
        $savedValues=array_merge($values,[
                                'description'=>$description,
                                'created_by_user_id'=>$request->user()->id,
                                'created_geolocation'=>json_encode($request->created_geolocation)
                                ]);

        Farm::create($savedValues);

        return redirect(route('farmer.show',$request->farmer_id));
    }

    /**
     * Display the specified resource.
     */
    public function show(Farm $farm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Farm $farm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFarmRequest $request, Farm $farm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Farm $farm)
    {
        //
    }
}
