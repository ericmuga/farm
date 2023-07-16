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
              ->with(['parent'])
            //   ->counts(['contacts'])
              // ->sums(['relatedModel4' => 'column'])
            ->search($request)

        );
    return inertia('Location/List',compact('locations'));

    }


    public function store(Request $request)
    {
        //
        $values=$request->except('photo','created_geolocation');
        $savedValues=array_merge($values,[
                                'created_by_user_id'=>$request->user()->id,
                                'created_geolocation'=>json_encode($request->created_geolocation)
                                ]);
        Location::create($savedValues);

        return redirect(route('locations.index'));
    }

    public function update(Request $request)
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
