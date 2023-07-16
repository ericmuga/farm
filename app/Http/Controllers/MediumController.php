<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use App\Models\Medium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
class MediumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

      //Display all users


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
        //upload the photo
        // dd();
         $path='';
        //dd(json_encode($request->geolocation));
        if ((!$request->hasFile('photo')) && $request->photo!='')
        {
            //convert blob to string

            // dd('here');
            $blobString = substr($request->photo, 22);
            // Save the Blob string as a temporary file
            $tempFilePath = sys_get_temp_dir() . '/' . uniqid() . '.png';
            file_put_contents($tempFilePath, base64_decode($blobString));

            // Use Intervention Image to open and convert the temporary file
            $image = Image::make($tempFilePath)->encode('png');
        //   dd($image);
            // Set the image type explicitly as PNG
            $image->mime('image/png');

            // Store the converted image in the desired storage location
            $mediaId=uniqid();
            $path=storage_path('app/public/'.$mediaId.'.png');
            File::move($tempFilePath, $path);




        }
        else{

        $path = $request->photo->store('public');
        }
        $values=$request->except('photo','created_geolocation');
        $savedValues=array_merge($values,['url'=>$path,
                                'created_by_user_id'=>$request->user()->id,
                                'created_geolocation'=>json_encode($request->created_geolocation)
                                ]);

        Medium::create($savedValues);

        return redirect(route('farmer.show',$request->recordable_id));




    }

    /**
     * Display the specified resource.
     */
    public function show(Medium $medium)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medium $medium)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medium $medium)
    {
        $redirect_id='';
        if ($medium->recordable_type=='App\\Models\\Farmer') {
            $redirect_id=$medium->recordable_id;
        }
        else{
            $redirect_id=Associate::find($medium->recordable_id)->farmer_id;
        }
        File::delete($medium->url);
        $medium->delete();

        return redirect(route('farmer.show',$redirect_id));
    }
}
