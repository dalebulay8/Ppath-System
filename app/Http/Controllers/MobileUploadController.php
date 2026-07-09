<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendee;
use App\Models\Activity;

class MobileUploadController extends Controller
{

    public function upload(Request $request)
    {

        $request->validate([
            'activity_id' => 'required',
            'attendees' => 'required|array'
        ]);


        $activity = Activity::find($request->activity_id);


        if(!$activity){

            return response()->json([
                "success"=>false,
                "message"=>"Activity not found"
            ]);

        }


        foreach($request->attendees as $person){

            Attendee::create([

                'activity_id'=>$request->activity_id,

                'name'=>$person['name'],

                'gender'=>$person['gender']

            ]);

        }


        return response()->json([

            "success"=>true,

            "message"=>"Upload successful"

        ]);

    }

}
