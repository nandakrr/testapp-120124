<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Studio;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function showForm()
    {
    	//return view('signup');
    }

    public function register(Request $request)
    {	
    	$user = new Studio;

    	$user->name 	 = $request->input('name');
        $user->email 	 = $request->input('email');
        $user->password  = $request->input('password');
        $user->save();
    }
}
