<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Services\SearchService;
use App\Http\Resources\LocationResource;
use App\Exports\LocationExport;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $locations=LocationResource::collection(
            (new SearchService(new Location()))
            //   ->with(['contacts','associates'])
            //   ->counts(['contacts'])
              // ->sums(['relatedModel4' => 'column'])
            ->search($request)

        );

      //   dd($farmers);

    return inertia('Location/List',compact('locations'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // dd($request);
        Location::forceCreate($request->all());

        return redirect(route('locations.index',$request));
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLocationRequest $request, Location $location)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();
    }

     public function export ()
    {
        return Excel::download(new LocationExport, 'locations.xlsx');
    }
}
