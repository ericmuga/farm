<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Http\Requests\StoreFarmerRequest;
use App\Http\Requests\UpdateFarmerRequest;
use Illuminate\Http\Request;
use App\Http\Resources\FarmerResource;
use App\Models\Contact;
use App\Services\{SearchService,ExcelService};
use App\Exports\FarmerExport;
use App\Models\Location;
use Maatwebsite\Excel\Facades\Excel;
class FarmerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


         $farmers=FarmerResource::collection(
              (new SearchService(new Farmer()))
                ->with(['contacts','associates','media','farms'])
                ->counts(['contacts','associates','media'])
                // ->sums(['relatedModel4' => 'column'])
              ->search($request)

          );

        //   dd($farmers);

      return inertia('Farmer/List',compact('farmers'));

    }

    public   static function saveContact(Farmer $farmer,Request $request,$contact_type)
    {
        if($request->has($contact_type)&&$request->input($contact_type)!='')
        {
            //create an email contact and attach in the pivot table
         $contact= new Contact();
         $contact->contact=$request->$contact_type;
         $contact->isPrimary =true;
         $contact->contact_type=$contact_type;
         $farmer->contacts()->save($contact);
        }
    }

    private function saveOrUpdateLocation(Farmer $farmer,Request $request,$function)
    {
      if($function=='save'){
        if ($request->has('latitude')&& $request->has('longitude')){
            $farmer->created_geolocation=json_encode(['latitude'=>$request->latitude,'longitude'=>$request->longitude]);
        }

      }
      else{
        if ($request->has('latitude')&& $request->has('longitude')){
            $farmer->updated_geolocation=json_encode(['latitude'=>$request->latitude,'longitude'=>$request->longitude]);
        }
      }
      $farmer->save();

    }

    public function store(StoreFarmerRequest $request)
    {
        // dd($request->all()) $this->saveOrUpdateLocation($farmer,$request,'save');;
       $values=$request->except('created_geolocation');

        $savedValues=array_merge($values,[
                                'created_by_user_id'=>$request->user()->id,
                                'created_geolocation'=>json_encode($request->created_geolocation),
                                ]);



        $farmer=Farmer::create($savedValues);
        $this->saveContact($farmer,$request,'email');
        $this->saveContact($farmer,$request,'phone_no');
        $this->saveOrUpdateLocation($farmer,$request,'save');
        return redirect (route('farmer.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // This view will show farmer details
        $f=Farmer::firstWhere('id',$id)->load('contacts','associates','associates.contacts','media','farms','farms.visits','farms.visits.user');
        $farmer=FarmerResource::make($f);
        $locations= Location::select('id','location_name')->get();

    //    dd($farmer);
        return inertia('Farmer/Show',compact('farmer','locations'));
    }


     public function addMedia(Request $request)
     {
        //upload the media
        //generate media url
        //record the meta data in the medium model


     }


    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFarmerRequest $request)
    {

        $farmer=Farmer::find($request->id);
        $farmer->update($request->except(['id','email','phone_no','latitude','longitude']));
         $this->saveOrUpdateLocation($farmer,$request,'update');
        //update the phone number and email


        return redirect (route('farmer.index'));




    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Farmer $farmer)
    {
        $farmer->delete();
        return redirect(route('farmer.index'));
    }

    // public function export ()
    // {
    //     return Excel::download(new FarmerExport, 'farmers.xlsx');
    // }

    public function export()
    {
        $excelService = new ExcelService(new Farmer());

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
