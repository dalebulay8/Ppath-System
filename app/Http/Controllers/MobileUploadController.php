<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MobileUpload;

class MobileUploadController extends Controller
{

    // Display uploads page
    public function index()
    {
        $uploads = MobileUpload::orderBy('created_at','desc')->get();

        return view('mobile_uploads', compact('uploads'));
    }


    // Receive MIT App upload
    public function upload(Request $request)
    {

        $request->validate([

            'table_name'=>'required',

            'attendees'=>'required|array'

        ]);


        foreach($request->attendees as $person)
        {

            MobileUpload::create([

                'table_name'=>$request->table_name,

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
