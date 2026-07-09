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

        return view('mobile_uploads', compact('uploads'));
    }

    public function upload(Request $request)
    {
        // 1. Validates table_name, attendees list array, and the author metadata
        $request->validate([
            'table_name' => 'required|string',
            'attendees'  => 'required|array',
            'author'     => 'nullable|string' // 👈 Added author validation line
        ]);

        // 2. Creates the parent table entry with the author tracking tag
        $upload = MobileUpload::create([
            'table_name' => $request->table_name,
            'author'     => $request->author ?? 'Anonymous' // 👈 Saves the author name
        ]);

        // 3. Loops through each attendee data pair element
        foreach($request->attendees as $person)
        {
            MobileUploadAttendee::create([
                'mobile_upload_id' => $upload->id,
                'name'             => $person['name'],
                'gender'           => $person['gender']
            ]);
        }

        // 4. Returns plain text UPLOAD_SUCCESS so the phone app registers a clear success event!
        return response('UPLOAD_SUCCESS', 200)
            ->header('Content-Type', 'text/plain');
    }
}
