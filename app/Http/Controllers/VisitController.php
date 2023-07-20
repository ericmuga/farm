<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Http\Requests\StoreVisitRequest;
use App\Http\Requests\UpdateVisitRequest;
use App\Services\{SearchService,ExcelService};
use Illuminate\Http\Request;
use App\Http\Resources\VisitResource;
class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        $visits=VisitResource::collection(
            (new SearchService(new Visit()))
              ->with(['farmer','location','farm'])
            //   ->counts(['contacts','associates','media'])
              // ->sums(['relatedModel4' => 'column'])
            ->search($request)

        );

      //   dd($farmers);

    return inertia('Visit/List',compact('visits'));
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
        // dd($request->all());
        $values=$request->except('created_geolocation','herd_inventory','ready_by_dates');

        $savedValues=array_merge($values,[
                                'created_by_user_id'=>$request->user()->id,
                                'created_geolocation'=>json_encode($request->created_geolocation),
                                'herd_inventory'=>json_encode($request->herd_inventory),
                                'ready_by_dates'=>$request->ready_by_date?json_encode($request->ready_by_dates):null
                                ]);


        $visit=Visit::create($savedValues);


        return redirect(route('farmer.show',$visit->farm->farmer->id));

    }

    /**
     * Display the specified resource.
     */
    public function show(Visit $visit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visit $visit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVisitRequest $request, Visit $visit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visit $visit)
    {
        //
    }

    public function export()
    {
        $excelService = new ExcelService(new Visit());

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

