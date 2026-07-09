<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MobileUpload;
use App\Models\MobileUploadAttendee;

class MobileUploadController extends Controller
{

public function index()
{
    $uploads = MobileUpload::with('attendees')
        ->orderBy('created_at','desc')
        ->get();

    dd($uploads->toArray());
}



    public function upload(Request $request)
    {

        $request->validate([

            'table_name'=>'required|string',

            'attendees'=>'required|array'

        ]);



        $upload = MobileUpload::create([

            'table_name'=>$request->table_name

        ]);



        foreach($request->attendees as $person)
        {

            MobileUploadAttendee::create([

                'mobile_upload_id'=>$upload->id,

                'name'=>$person['name'],

                'gender'=>$person['gender']

            ]);

        }



        return response()->json([

            'success'=>true,

            'message'=>'Upload successful'

        ]);

    }

}
