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
    try {

        // Receive data from MIT
        $title   = $request->input('title');
        $content = $request->input('content');
        $author  = $request->input('author', 'Anonymous');

        // Create one upload record
        $upload = MobileUpload::create([
            'table_name' => $title,
            'author'     => $author
        ]);

        // Split the uploaded text into lines
        $lines = explode("\n", $content);

        foreach ($lines as $line) {

            $currentLine = trim($line);

            if ($currentLine == "") {
                continue;
            }

            $parts = explode("/", $currentLine);

            $name   = isset($parts[0]) ? trim($parts[0]) : "";
            $gender = isset($parts[1]) ? trim($parts[1]) : "";

            if ($name != "") {

                MobileUploadAttendee::create([
                    'mobile_upload_id' => $upload->id,
                    'name'             => $name,
                    'gender'           => $gender
                ]);

            }

        }

        return response("UPLOAD_SUCCESS", 200)
            ->header("Content-Type", "text/plain");

    } catch (\Exception $e) {

        return response(
            "ERROR: " . $e->getMessage(),
            500
        )->header("Content-Type", "text/plain");

    }
}
}
