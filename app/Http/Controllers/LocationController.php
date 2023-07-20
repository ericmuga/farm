<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Services\{SearchService,ExcelService};
use App\Http\Resources\LocationResource;
use App\Exports\LocationExport;
use App\Models\Visit;
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

        // dd($request->all());
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



    public function export()
    {
        $excelService = new ExcelService(new Location());

        return $excelService->download('exported_data.xlsx');
        // return $excelService->store('exported_data.xlsx', 'public');
    }

    public function import(Request $request)
    {
        $file = $request->file('import_file');

        // Add your logic to handle the imported file and save it to the respective model
        // You can use 'laravel-excel' to read the data from the file if needed

        // Example of reading data from the imported file
        $importedData = Excel::toArray([], $file);
        // Process the $importedData and save it to your model

        return redirect()->back()->with('success', 'Data imported successfully.');
    }
}
