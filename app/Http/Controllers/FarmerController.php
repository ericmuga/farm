<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Http\Requests\StoreFarmerRequest;
use App\Http\Requests\UpdateFarmerRequest;
use Illuminate\Http\Request;
use App\Http\Resources\FarmerResource;
use App\Services\SearchService;

class FarmerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //filter through the request return a list of farmers
        $model=new Farmer();


         $farmers=FarmerResource::collection(
              (new SearchService($model))->search($request)


              // Farmer::query()
              //       ->when($request->has('search'),fn($q)=>$q->where('farmer_name','like','%'.$request->search.'%')
              //                                                 ->orWhere('id_no','like','%'.$request->search.'%')
              //                                                 ->orWhere('pf_no','like','%'.$request->search.'%')
              //                                                 ->orWhere('registration_no','like','%'.$request->search.'%')

              //              )
              //       ->paginate(15)
              //       ->withQueryString()
              //       ->appends($request->all())
          );

      return inertia('Farmer/List',compact('farmers'));

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
    public function store(StoreFarmerRequest $request)
    {
        // dd($request->all());
        Farmer::forceCreate($request->all());
        return redirect (route('farmer.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Farmer $farmer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Farmer $farmer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
dd($request->all());

    

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Farmer $farmer)
    {
        $farmer->delete();
        return redirect(route('farmer.index'));
    }
}
