<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Http\Requests\StoreFarmerRequest;
use App\Http\Requests\UpdateFarmerRequest;
use Illuminate\Http\Request;
use App\Http\Resources\FarmerResource;
use App\Models\Contact;
use App\Services\SearchService;

class FarmerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

         $farmers=FarmerResource::collection(
              (new SearchService(new Farmer()))
                ->with(['contacts'])
                ->counts(['contacts'])
                // ->sums(['relatedModel4' => 'column'])
              ->search($request)

          );

      return inertia('Farmer/List',compact('farmers'));

    }

    private function saveContact(Farmer $farmer,Request $request,$contact_type)
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

    public function store(StoreFarmerRequest $request)
    {
        // dd($request->all());
        $farmer=Farmer::forceCreate($request->except(['id','email','phone_no']));
        $this->saveContact($farmer,$request,'email');
        $this->saveContact($farmer,$request,'phone_no');
        return redirect (route('farmer.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Farmer $farmer)
    {
        // This view will show farmer details
        FarmerResource::make($farmer->load('contacts')
                                     );
       return inertia('Farmer/Show',compact('farmer'));
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
    public function update(UpdateFarmerRequest $request)
    {

        $farmer=Farmer::find($request->id)->update($request->except(['id','email','phone_no']));
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
}
