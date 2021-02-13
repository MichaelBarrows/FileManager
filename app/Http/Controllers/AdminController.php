<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\File;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware('auth');

    }

    /**
    * Display the administrator file list (if they are an admin).
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show_files () {
        // check the user is an administrator
        if (Auth::user()->is_admin) {
            $all_files = File::all();
            return view('admin_list', ['all_files' => $all_files]);
        }
        // user is not an administrator
        Session::flash('danger', "You don't have permission to perform that action.");
        return redirect('/');
    }
}
