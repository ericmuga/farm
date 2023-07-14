<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Associate;
use App\Models\Farmer;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         //when creating a contact,

        //  dd($request->all());

         switch ($request->contactable_type) {
            case 'Farmer':
                /**
                 * Add to existing farmer contacts
                 */
                    $farmer= Farmer::find($request->farmer_id);
                    $contact= new Contact();
                    $contact->contact=$request->contact;
                    $contact->contact_type=$request->contact_type;
                    $contact->isPrimary =$request->isPrimary;
                    $farmer->contacts()->save($contact);


                break;
           default:///create an associate and insert the contact
                  $associate = Associate::forceCreate(['associate_name'=>$request->name,
                                            'associate_type'=>$request->contactable_type,
                                            'farmer_id'=>$request->farmer_id,
                                            ]);


                  $contact= new Contact();
                  $contact->contact=$request->contact;
                  $contact->contact_type=$request->contact_type;
                  $contact->isPrimary =$request->isPrimary;

                  $associate->contacts()->save($contact);



            break;


         }

        return redirect(route('farmer.show',$request->farmer_id));

    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
    }
}
