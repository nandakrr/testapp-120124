<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Studio;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Session;
use Auth;

class LoginController extends Controller
{

  function __construct()
  {
    //$this->middleware('checkauth');
  }

    public function loginForm()
    {
        $response = $this->checkRememberAuth();
        if($response['msg']=='success')
        {   
            return redirect()->route($response['url']);
        }
    	return view('login');
    }


  	public function login(Request $request)
    {

      // get the type of login user wants
      $login_type = $request->input('login_type');
        // login via cookie
        if ( !empty($request->input('remember_me')) ) {

            $minutes = 60 * 24; // 1 day
            $data       = array(
                'type'=>$login_type,
                'email' => $request->input('email'),
                'password' => encrypt_password($request->input('password'))
            );

            $cookie = Cookie::make('remember_auth', json_encode($data), $minutes);
        }

        if ( $login_type === 'studio' ) {

            $studio = Studio::where('email', $request->input('email'))->first();
            // echo decrypt_password($studio->password); die;

            if($studio)
            {
                if(decrypt_password($studio->password) == $request->input('password'))
                {
                    // Session::flash('message', 'Login Successfully.');
                    // Session::flash('alert-class', 'alert-success');
                    $request->session()->put('auth', $studio->toArray());
                    if (!empty($cookie)) {
                        return redirect('studio/manage-classes')->withCookie($cookie);
                    } else {
                        return redirect('studio/manage-classes');
                    }
                } else {
                    Session::flash('message', 'Password does not match.');
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->back();
                }
                
            } else {
                Session::flash('message', 'No record found with this data');
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
            }
        } elseif ( $login_type === 'provider' ) {

            $teacher = \App\Teacher::where('email', $request->input('email'))->first();
            if($teacher)
            {
                if(decrypt_password($teacher->password) == $request->input('password'))
                {
                    // Session::flash('message', 'Login Successfully.');
                    // Session::flash('alert-class', 'alert-success');
                    $request->session()->put('auth', $teacher->toArray());
                    if (!empty($cookie)) {
                        return redirect()->route('teacher.profile')->withCookie($cookie);
                    } else {
                        return redirect()->route('teacher.profile');
                    }
                } else {
                    Session::flash('message', 'Password does not match.');
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->back();
                }
                
            } else {
                Session::flash('message', 'No record found with this data');
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
            }
        }
    }

    public function getCookie(Request $request) {
      $value = $request->cookie('remember_auth');
      print_r(json_decode($value));
    }

    public function doLogout(Request $request)
    {
      //Session::flash('message', 'Logout Successfully.');
      Session::flash('alert-class', 'alert-success');
      $request->session()->forget('auth');

//      echo Cookie::has('remember_auth'); die;

      if ( Cookie::has('remember_auth') ) {

          Cookie::forget('remember_auth');
          Cookie::queue(Cookie::forget('remember_auth'));
      }

      return redirect('/partners/login');
    }


    private function checkRememberAuth() {

        $cookie = Cookie::get('remember_auth');
        if ( !empty($cookie) ) {

            $data = json_decode($cookie, true);
            if($data['type']=='provider')
            {
                $teacher = \App\Teacher::where('email', $data['email'])->first();
                if(decrypt_password($teacher->password) == decrypt_password($data['password']))
                {
                    
                    // Session::flash('message', 'Login Successfully.');
                    // Session::flash('alert-class', 'alert-success');   
                     $response['msg']='success';       
                    Session::put('auth', $teacher->toArray());
                } else {
                    Session::flash('message', 'Password does not match.');
                    Session::flash('alert-class', 'alert-danger');
                    $response['msg']='error';
                }
                $response['url'] = 'teacher.profile';
                return $response;
            }else{
                $studio = \App\Studio::where('email', $data['email'])->first();
                if(decrypt_password($studio->password) == decrypt_password($data['password']))
                {
                    // Session::flash('message', 'Login Successfully.');
                    // Session::flash('alert-class', 'alert-success');  
                    $response['msg']='success';    
                    Session::put('auth', $studio->toArray());          
                } else {
                    Session::flash('message', 'Password does not match.');
                    Session::flash('alert-class', 'alert-danger');
                    $response['msg']='error';
                }
                $response['url'] = 'studio';
                return $response;
            }
        }
    }
	
	
	public function registerForm()
    {
        $response = $this->checkRememberAuth();
        if($response['msg']=='success')
        {   
            return redirect()->route($response['url']);
        }
    	return view('login');
    }


    

}
