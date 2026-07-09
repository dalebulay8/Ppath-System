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

        $title = $request->input('title');
        $content = $request->input('content');
        $author = $request->input('author', 'Anonymous');

        if (empty($title) || empty($content)) {
            return response("ERROR: Missing title or content", 400)
                ->header("Content-Type", "text/plain");
        }

        // Create the upload record
        $upload = MobileUpload::create([
            'table_name' => $title,
            'author' => $author
        ]);

        // Split each attendee
        $lines = preg_split("/\r\n|\n|\r/", $content);

        foreach ($lines as $line) {

            $line = trim($line);

            if ($line == "") {
                continue;
            }

            $parts = explode("/", $line);

            $name = trim($parts[0] ?? "");
            $gender = trim($parts[1] ?? "");

            if ($name != "") {

                MobileUploadAttendee::create([
                    'mobile_upload_id' => $upload->id,
                    'name' => $name,
                    'gender' => $gender
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
