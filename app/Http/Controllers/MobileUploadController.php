<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MobileUpload;

class MobileUploadController extends Controller
{

    // Show Mobile Uploads page
    public function index()
    {
        $uploads = MobileUpload::orderBy('created_at', 'desc')->get();

        return view('mobile_uploads', compact('uploads'));
    }


    // Receive upload from MIT App Inventor
    public function upload(Request $request)
    {

        $request->validate([
            'table_name' => 'required|string',
            'attendees' => 'required|array'
        ]);


        foreach ($request->attendees as $person) {

            MobileUpload::create([

                'table_name' => $request->table_name,

                'name' => $person['name'],

                'gender' => $person['gender']

            ]);

        }


        return response()->json([

            'success' => true,

            'message' => 'Mobile upload saved successfully'

        ]);

    }

}
