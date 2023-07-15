<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Http\Requests\StoreFarmerRequest;
use App\Http\Requests\UpdateFarmerRequest;
use Illuminate\Http\Request;
use App\Http\Resources\FarmerResource;
use App\Models\Contact;
use App\Services\SearchService;
use App\Exports\FarmerExport;
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
                ->with(['contacts','associates','media'])
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
        $farmer=Farmer::forceCreate($request->except(['id','email','phone_no','latitude','longitude']));
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
        $f=Farmer::firstWhere('id',$id)->load('contacts','associates','associates.contacts','media');
        // dd($farmer);
        $farmer=FarmerResource::make($f);
       return inertia('Farmer/Show',compact('farmer'));
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

    public function export ()
    {
        return Excel::download(new FarmerExport, 'farmers.xlsx');
    }
}
