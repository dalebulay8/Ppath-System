<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MobileUpload;
use App\Models\MobileUploadAttendee;

class MobileUploadController extends Controller
{

    /**
     * Display all uploaded attendance tables.
     */
    public function index()
    {
        $uploads = MobileUpload::with('attendees')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mobile_uploads', compact('uploads'));
    }


    /**
     * Receive upload from MIT App.
     */
    public function upload(Request $request)
    {
        try {

            // Receive JSON from MIT App
            $title   = trim($request->input('title'));
            $content = trim($request->input('content'));
            $author  = trim($request->input('author', 'Anonymous'));


            // Validate required fields
            if (empty($title) || empty($content)) {

                return response("ERROR: Missing title or content", 400)
                    ->header("Content-Type", "text/plain");

            }


            // Create one upload record
            $upload = MobileUpload::create([
                'table_name' => $title,
                'author'     => $author
            ]);


            // Split uploaded text into lines
            $lines = preg_split("/\r\n|\n|\r/", $content);


            foreach ($lines as $line) {

                $line = trim($line);


                if ($line === "") {
                    continue;
                }


                /*
                    Expected format:

                    Juan Dela Cruz/MALE
                */

                $parts = explode("/", $line);


                $name   = trim($parts[0] ?? "");
                $gender = trim($parts[1] ?? "");


                if ($name !== "") {

                    MobileUploadAttendee::create([
                        'mobile_upload_id' => $upload->id,
                        'name'             => $name,
                        'gender'           => strtoupper($gender)
                    ]);

                }

            }


            // MIT App checks for this exact text
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
