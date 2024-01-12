<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Studio;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Session;
use Illuminate\Support\Facades\DB;
use Mail;
use Validator;
class WebadminController extends Controller
{

  function __construct()
  {
    //$this->middleware('authcheck');
  }
/*************WEBADMIN-LOGIN******************/
    public function signin(){
    	return view('webadmin/login');
    }
/*************WEBADMIN******************/	
	public function login(Request $request){
	  $this->validate($request, [
        'email' => 'required',
        'password' => 'required'
        ]);
		
	  $request_data = $request->all();
	  $email = $request->email;
	  $password = $request->password;
      $webadmins = DB::table('webadmin', $request->input('email'))->first();
	  $allldata= (array) $webadmins;;
	  
	if(!empty($webadmins)){
		if($webadmins->password==md5($password)){
			$request->session()->put('authentications', $allldata);
			return redirect('/webadmin/dashboard');
		}else{
	   Session::flash('message', 'Wrong password. Try again.');
       Session::flash('alert-class', 'alert-danger');
       return redirect()->back();
		}
		
	}else{
	   Session::flash('message', 'No record found with this data');
       Session::flash('alert-class', 'alert-danger');
       return redirect()->back();
	}	
 }	
/*************WEBADMIN******************/
    public function dashboard(){
		$auth_session = \ Session::get('authentications');
	  if (empty($auth_session)) {
        return redirect('webadmin/signin');
      }
	   $contact = DB::table('contact')->get();
	   $contactcount=$contact->count();
	   $studio = DB::table('partnerstudio')->get();
	   $studiocount=$studio->count();
    	return view('webadmin/dashboard',compact('studiocount','contactcount'));
		
    }
/*************CONTACT-REQUEST*************/
    public function contactrequest(){
		$auth_session = \ Session::get('authentications');
	  if (empty($auth_session)) {
        return redirect('webadmin/signin');
      }
	  $contacts = DB::table('contact')->get();
      return view('webadmin/viewcontacts',compact('contacts'));
    }
/*************STUDIO-REQUEST*************/
    public function studiorequest(){
		$auth_session = \ Session::get('authentications');
	  if (empty($auth_session)) {
        return redirect('webadmin/signin');
      }
	   $studios = DB::table('partnerstudio')->get();
    	return view('webadmin/viewstudios',compact('studios'));
    }

    /*************PROVIDER-REQUEST*************/
    public function providerrequest(){
		$auth_session = \ Session::get('authentications');
	  if (empty($auth_session)) {
        return redirect('webadmin/signin');
      }
	   $providers = DB::table('providers')->get();
    	return view('webadmin/viewsproviders',compact('providers'));
    }

    /*************PROVIDER-REQUEST*************/
    public function corporaterequest(){
    $auth_session = \ Session::get('authentications');
    if (empty($auth_session)) {
        return redirect('webadmin/signin');
      }
     $corporates = DB::table('corporates')->get();
      return view('webadmin/viewcorporates',compact('corporates'));
    }

    /*************SEO*************/
    public function seo(){
    $auth_session = \ Session::get('authentications');
    if (empty($auth_session)) {
        return redirect('webadmin/signin');
      }
     $seo = DB::table('seo_details')->get();
      return view('webadmin/viewsseo',compact('seo'));
    }

    /*************SEO-EDIT*************/
    public function seoedit($id){
    $auth_session = \ Session::get('authentications');
    if (empty($auth_session)) {
        return redirect('webadmin/signin');
      }

     $seo = DB::table('seo_details')->where('id',$id)->first();
      return view('webadmin/edit_seo',compact('seo'));
    }

    /*************SEO-UPDATE*************/
    public function seo_update(Request $request) 
    { 
        // validate incoming request
        $sid           = $request->sid;
        $validator     = Validator::make($request->all(), [
        'title'        => 'required',   
        'description'  => 'required' ,  
        'keyword'       => 'required'   
        ]);
        
        if ($validator->fails()) {
              return redirect()->back()
                  ->withErrors($validator)
                  ->withInput(); 
          }
        
        $title       =  trim($request->title);
        $description =  trim($request->description); 
        $keyword     =  trim($request->keyword);
        $res         =  DB::table('seo_details')
                        ->where('id', $sid)
                        ->update(['title'=>$title, 
                                  'description'=>$description,
                                  'keyword'=>$keyword]);
        if($res){
            return redirect()->back()->with('message', 'Information has been Updated Successfully'); 
        }else{
            return redirect()->back()->with('message', 'Do Something Change'); 
        }
    }

    

/*************LOGOUT-WEBADMIN*************/
    public function logout(Request $request){
    	$request->session()->forget('authentications');
		return redirect('/webadmin/signin');
    }


    /***************/

}
