<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Check that the bucket to be used for the application exists, if not,
     * create it.
     *
     * @param  string  $bucket_name
     * @return
     */
    public function check_bucket_exists ($bucket_name) {
        $s3 = App::make('aws')->createClient('s3');

        $buckets = $s3->listBuckets();
        $exists = False;
        foreach ($buckets['Buckets'] as $bucket) {
            if ($bucket['Name'] == $bucket_name) {
                // bucket exists
                return;
            }
        }
        if (!$exists) {
            // bucket doesn't exist, create it
            $s3->createBucket(['Bucket' => $bucket_name]);
            return;
        }
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Check AWS bucket exists on first user creation
        if (User::all()->count() == 0) {
            $this->check_bucket_exists(env('AWS_BUCKET'));
        }

        Auth::login($user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => False,
        ]));

        event(new Registered($user));

        return redirect(RouteServiceProvider::HOME);
    }
}
