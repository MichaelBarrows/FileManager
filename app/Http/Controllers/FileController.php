<?php

namespace App\Http\Controllers;

use Auth;
use App;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;


class FileController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
    * Checks if the user has permission to perform the action on a file (either
    * through ownership or administrator privileges).
    *
    * @param  int  $owner_id
    * @return boolean
    */
    public function is_allowed ($owner_id) {
        if (Auth::user()->id == $owner_id or Auth::user()->is_admin == True) {
            return True;
        }
        return False;
    }

    /**
    * Display the upload file page.
    *
    * @return \Illuminate\Http\Response
    */
    public function upload_file () {
        return view('upload');
    }

    /**
    * Store the uploaded file.
    * Uploads the file to AWS and creates an entry in the files table.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store_file (Request $request) {

        $validated = $request->validate([
            'file' => 'required',
        ]);
        $extension = $request->file->extension();

        // temporarily store the file locally
        $file_path = $request->file->storeAs('/public', $validated['file'].".".$extension);

        // create DB record (requires the id for AWS filename)
        $file = File::create([
            'name' => $request->file->getClientOriginalName(),
            'local_file_location' => $validated['file'].".".$extension,
            'extension' => $extension,
            'user_id' => Auth::user()->id,
            'downloads' => 0,
        ]);

        // User messages
        if ($file) {
            Session::flash('success', 'File uploaded successfully.');
        } else {
            Session::flash('danger', 'Something went wrong.');
            return redirect('/');
        }

        // attempt file upload to AWS
        try {
            $s3 = App::make('aws')->createClient('s3');
            $s3->putObject([
                'Bucket'     => env('AWS_BUCKET'),
                'Key'        => $file->id . "." . $extension,
                'SourceFile' => $validated['file'],
            ]);
        } catch (S3Exception $e) {
            Session::flash('danger', 'Something went wrong.');
            $file->delete();
        }

        // delete the temporary file
        Storage::delete($file_path);
        return redirect('/');
    }

    /**
    * Download an existing file.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response (file download)
    */
    public function download_file ($id) {
        $file = File::findOrFail($id);

        // check user has permission to perform action
        if ($this->is_allowed($file->user->id)) {
            $s3 = App::make('aws')->createClient('s3');
            try {
                // get the file
                $result = $s3->getObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key'    => $file->id . "." . $file->extension,
                ]);

                // increment downloads counter
                $file->downloads += 1;
                $file->save();

                // set headers and make file download
                header('Content-type: ' . $result['ContentType']);
                header('Content-Disposition: attachment; filename="' . $file->name . '"');
                return $result['Body'];
            } catch (S3Exception $e) {
                Session::flash('danger', 'Something went wrong.');
                // reverse downloads increment
                $file->downloads -= 1;
                $file->save();
                return redirect()->back();
            }
        } else {
            Session::flash('danger', "You don't have permission to perform that action.");
            return redirect()->back();
        }
    }

    /**
    * Delete an existing file.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function delete_file ($id) {
        $file = File::findOrFail($id);
    // If users are allowed to delete their own files:
        // if ($this->is_allowed($file->user->id)) {
    // If only administrators can delete files:
        // Check user has permission to delete the file
        if (Auth::user()->is_admin) {
            $s3 = App::make('aws')->createClient('s3');

            // delete file in AWS
            $s3->deleteObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $file->id . "." . $file->extension,
            ]);
            // delete db file record
            $file->delete();
        } else {
            Session::flash('danger', "You don't have permission to perform that action.");
        }
        return redirect()->back();
    }

    /**
    * Show a list of files belonging to the user.
    *
    * @return \Illuminate\Http\Response
    */
    public function show_files () {
        $files = Auth::user()->files;
        return view('user_list', ['files' => $files]);
    }
}
