<?php

namespace App\Http\Controllers\booking\Staging;

use App\Http\Controllers\Controller; // ON live remove 
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Traits\BookingTrait;
use Validator;
use Illuminate\Support\Str;
use Session;

class BookingLoginController extends Controller
{
    use BookingTrait;

    private $activeLogin;
    protected $viewFilePath;
    
    function __construct()
    {
        $this->middleware('staging_bookingauth')->except(['index','validateZip','login','doLogin','register','doRegister','forgotPassword','processForgotPassword','createEmailLink','session_login', 'timeline']);
        $this->viewFilePath = 'staging.';
    }

    /**
     * This Method use for display zipcode function
     */
    public function index()
    {
        if(Session::get('staging_bookingAuth') == null)
        {
            return view('staging.booking.zipcode');
        }
        return redirect('staging/booking/session');

        
        // Example usage
        // $domain = 'bigtoe.yoga';
        // $this->openDomainApp($domain);
        

    }

    /** Identify app or web */
    private function openDomainApp($domainUrl) {
        // Define the custom URL schemes for iOS and Android
        $iosAppScheme = 'https://apps.apple.com/us/app/bigtoe-yoga/id1198583047'; // Replace with the custom URL scheme for your app on iOS
        $androidAppScheme = 'https://play.google.com/store/apps/details?id=fit.bigtoe.bigtoeyoga&hl=en'; // Replace with the custom URL scheme for your app on Android
        
        // Check if the app is installed on iOS
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== false) {
            $iosHeaders = @get_headers($iosAppScheme);
            if ($iosHeaders && strpos($iosHeaders[0], '200') !== false) {
                // App is installed on iOS, redirect to the app URL
                header("Location: $iosAppScheme");
                exit();
            }
        }
        
        // Check if the app is installed on Android
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false) {
            $androidHeaders = @get_headers($androidAppScheme);
            if ($androidHeaders && strpos($androidHeaders[0], '200') !== false) {
                // App is installed on Android, redirect to the app URL
                header("Location: $androidAppScheme");
                exit();
            }
        }
        
        // App is not installed or platform is not recognized, redirect to the web page URL
        $webUrl = 'https://' . $domainUrl;
        header("Location: $webUrl");
        exit();
    }
    
    /**
     * Validate Zipcode 
     */
    public function validateZip(Request $request)
    {
        abort_if(!$request->ajax() ,response()->json(['message' => 'Request not allowed.'], 422),422);

        $rules = [
            'zipcode1'  => 'required',
            'zipcode2'  => 'required',
            'zipcode3'  => 'required',
            'zipcode4'  => 'required',  
            'zipcode5'  => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        
        if ($validator->fails()) 
        {
            $validain['status'] = 422; 
            $validain['response']['ResponseCode'] = 0;
            $allerror = "";
            foreach($validator->errors()->toArray() as $k => $er)
            {
                $allerror .= $er[0]."\n";
            }
            $validain['messsage'] = $allerror;
            return response()->json($validain,$validain['status']);
        }

        $zipcode = $request->zipcode1.$request->zipcode2.$request->zipcode3.$request->zipcode4.$request->zipcode5;
        $payload['zipcode'] = $zipcode;
        $url = 'https://bigtoe.app/app/ClientSide_V1/isZipCodeServed';
        $res = $this->curlRequestCall('post', $payload, $url);

        $zipResponse = json_decode($res, true);

        $response['zipcode'] =$zipcode;
        $response['ResponseCode'] = 0;
        $response['redirect_url'] = \URL::to('');
        $response['message'] = null;
        $response['status'] = 200;
        if($zipResponse['ResponseCode'] == 1)
        {
            $response['ResponseCode'] = $zipResponse['ResponseCode'];
            $response['message'] = $zipResponse['Comments'];
            $response['redirect_url'] = \URL::to('/booking/register');
            session()->flash('success', $response['message']);
        }
        elseif($zipResponse['ResponseCode'] == 0)
        {
            $response['ResponseCode'] = $zipResponse['ResponseCode'];
            $response['message'] = $zipResponse['Comments'];
            session()->flash('error', $response['message']);
        }
        return response()->json($response,$response['status']);
    }

    public function login()
    {
        if(Session::get('staging_bookingAuth') == null)
        {
            return view('staging.booking.login.index');
        }
        return redirect('staging/booking/session');
    }

    public function doLogin(Request $request)
    {
        $email 	= trim($request->email);
        $password = trim($request->password);

        $payload = [
            'email'     => $email,
            'password'  => $password,
            'device_type' => 'android'
        ];

        $url = 'https://bigtoe.app/app/Auth_V1/login';
        $res = $this->curlRequestCall('post', $payload, $url);

        $loginResponse = json_decode($res, true);

        $response['message'] = null;
        $response['status'] = 422;
        if ($loginResponse['ResponseMessage'] == 'SUCCESS') 
        {
            $loginResponse = collect($loginResponse);
            Session::push('staging_bookingAuth', $loginResponse);
            $result = $loginResponse['Result'];
            
            $response['status'] = 200;
            $response['message'] = $loginResponse['Comments'];
            return redirect('staging/booking/session')->with('message', $response['message']);
        }
        $response['message'] = $loginResponse['Comments'];
        return redirect('staging/booking/signin')->with('message', $response['message']);        
    }

    public function register()
    {
        if(Session::get('staging_bookingAuth') == null)
        {
            return view('staging.booking.register.index');
        }
        return redirect('staging/booking/session');
    }



    public function doRegister(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'phone_no' => 'required',
            'country_code' => 'required',
            'iso_code' => 'required',
            'age_range' => 'required'    
        ]);
        
        if($validator->fails()) 
        {
            return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $email 	= trim($request->email);
        $firstname = trim($request->firstname);
        $lastname = trim($request->lastname);
        $phone_no = trim($request->phone_no_format);
        $gender = trim($request->gender);
        $password = trim($request->password);
        $age_range = trim($request->age_range);
        $country_code = trim($request->country_code);
        $iso_code = trim($request->iso_code);

        $payload = [
            'email'     => $email,
            'firstname'  => $firstname,
            'lastname'  => $lastname,
            'country_code'  => "+".$country_code,
            'iso_code'  =>  strtoupper($iso_code),
            'phone_no'  => $phone_no,
            'gender'  => $gender,
            'age_range'  => $age_range,
            'password'  => $password,
            'device_type' => 'Browser'
        ];
        
        $url = 'https://bigtoe.app/app/Auth_V1/register';
        $res = $this->curlRequestCall('post', $payload, $url);

        $loginResponse = json_decode($res, true);

        if ($loginResponse['ResponseMessage'] == 'SUCCESS') 
        {
            // $response['status'] = 200;
            // $response['message'] = $loginResponse['Comments'];
            $loginResponse = collect($loginResponse);
            Session::push('staging_bookingAuth', $loginResponse);
            $result = $loginResponse['Result'];

            return redirect('staging/booking/session')->with('message', $loginResponse['Comments']);
        }
        
        return redirect()->back()->with('message', $loginResponse['Comments']);
    }

    public function forgotPassword()
    {
        if(Session::get('staging_bookingAuth') == null)
        {
            return view('staging.booking.login.forgot-password');
        }
        return redirect('staging/booking/session');
    }

    public function processForgotPassword(Request $request)
    {
        $email 	= trim($request->email);
        $payload = [
            'email'     => $email,
            'device_type' => 'Browser'
        ];

        $url = 'https://bigtoe.app/app/Auth_V1/fogotPassword';
        $res = $this->curlRequestCall('post', $payload, $url);

        $loginResponse = json_decode($res, true);

        $response['message'] = null;
        $response['status'] = 422;
        if ($loginResponse['ResponseMessage'] == 'SUCCESS') 
        {            
            $response['status'] = 200;
            $response['message'] = $loginResponse['Comments'];
            return redirect('booking/forgot/password')->with('message', $response['message']);
        }
        $response['message'] = $loginResponse['Comments'];
        return redirect('staging/booking/forgot/password')->with('message', $response['message']);
    }



    private function curlRequestCall($method = '', $data = '', $url = '')
    {
      if ($method == 'POST' || $method == 'post') 
      {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $headers = array(
        //     "Content-Type: application/json",
        //  );
        //  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        //for debug only!
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


        $result = curl_exec($ch);
        curl_close($ch);
        //var_dump($result);
        return $result;
      }

      if ($method == 'PUT' || $method == 'put') 
      {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "Content-Type: application/json",
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        //for debug only!
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        curl_close($ch);
        //var_dump($result);
        return $result;
      }

      if ($method == 'GET' || $method == 'get') {
          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
          // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

          $result = curl_exec($ch);
          curl_close($ch);
          return $result;
      }
    }


    public function createEmailLink(Request $request){
        $emailAddress = isset($request->email) ? $request->email : null;
        abort_if( $emailAddress == null ,response()->json(['message' => 'Request not allowed.'], 422),422);
        //dd( base64_encode($emailAddress),base64_decode('cnZAZ21haWwuY29t'));
        $emailAddress = $this->encrypt_decrypt('encrypt',$emailAddress);
        $response['url'] = \URL::to('/staging/session_login?request_token='.$emailAddress);
        $response['status'] = 200;
        return response()->json($response,$response['status']);
    }


    private function encrypt_decrypt($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'bigtoys_key';
        $secret_iv = 'bigtoys_iv';
        // hash
        $key = hash('sha256', $secret_key);
    
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    public function session_login(Request $request) {
        //dd($request);
        $emailAddress = isset($request->request_token) ? $request->request_token : null;
        abort_if( $emailAddress == null ,response()->json(['message' => 'Request not allowed.'], 422),422);
        $email = $this->encrypt_decrypt('decrypt',$emailAddress);
        $payload = [
            'email'     => str_replace('"', '',$email),
            'password'  => "dummypassword",
            'auth_method' => "automatic",
        ];
        $url = 'https://bigtoe.app/app/Auth_V1/login';
        $res = $this->curlRequestCall('post', $payload, $url);
        
        $loginResponse = json_decode($res, true);
        
        $response['message'] = null;
        $response['status'] = 422;
        if ($loginResponse['ResponseMessage'] == 'SUCCESS') 
        {
            $loginResponse = collect($loginResponse);
            Session::push('staging_bookingAuth', $loginResponse);
            $result = $loginResponse['Result'];
            
            $response['status'] = 200;
            $response['message'] = $loginResponse['Comments'];
            return redirect('staging/booking/session')->with('message', $response['message']);
        } else {
            $response['message'] = $loginResponse['Comments'];
            $response['status'] = 200;
            return response()->json($response,$response['status']);
        }
        // $response['message'] = $loginResponse['Comments'];
        // return redirect('staging/booking/signin')->with('message', $response['message']);
    }

    public function timeline(Request $request) {
        
        // print "<pre>";
        //  print_r($request->all());
        // print "</pre>";

        $box1_bg    = $request->box1_bg ? $request->box1_bg :  "#9d9d9d"; 
        $box1_width = $request->box1_width ? $request->box1_width : "7.5";
        $box1_des =  $request->box1_des ? $request->box1_des :"before the start of confession ";
        
        $box2_bg    = $request->box2_bg ? $request->box2_bg : "#0cbc69"; 
        $box2_width = $request->box2_width ? $request->box2_width : "50";
        $box2_des =  $request->box2_des ? $request->box2_des :"before the start of confession ";

        $box3_bg    = $request->box3_bg ? $request->box3_bg : "#e98b04"; 
        $box3_width = $request->box3_width ? $request->box3_width : "20";
        $box3_des =  $request->box3_des ? $request->box3_des :"before the start of confession ";

        $box4_bg= $request->box4_bg ? $request->box4_bg : "#ff0000"; 
        $box4_width= $request->box4_width ? $request->box4_width : "5";
        $box4_des =  $request->box4_des ? $request->box4_des :"before the start of confession ";

        //$timeline = "Hello";
        $timeline = "<div style='width:1000px;'>";
        $timeline = "<div style=' width:100%; margin:0 auto;border-radius: 5px; height:50px;background-color:transparent;float:left;color:#fff;font-weight: 500;'>";
        $timeline .= "<div style='width:$box1_width%;background-color:$box1_bg;height: 100%;float:left; line-height: 3; padding: 0 4px; font-size: 18px; text-align: center;
        border-radius: 5px 0 0 5px;'>$box1_width% Operation fees</div>";
        $timeline .= "<div style='width:$box2_width%;background-color:$box2_bg;height: 100%;float:left; line-height: 3; padding: 0 4px; font-size: 18px; text-align: center;'>$box2_width% Refund</div>";
        $timeline .= "<div style='width:$box3_width%;background-color:$box3_bg;height: 100%;float:left; line-height: 3; padding: 0 4px; font-size: 18px; text-align: center;'>$box3_width% Refund</div>";
        $timeline .= "<div style='width:$box4_width%;background-color:$box4_bg;height: 100%;float:left; line-height: 3; padding: 0 4px; font-size: 18px; text-align: center;
        border-radius: 0 5px 5px 0;'>$box4_width% </div>";
        // $timeline .= "</div>";
        $timeline .= "</br>";
        $timeline .= "</br>";
        $timeline .= "<div style='width:$box1_width%;float:left;padding: 0 4px; font-size: 18px; text-align: center;'>$box1_des</div>";
        $timeline .= "<div style='width:$box2_width%;float:left;padding: 0 4px; font-size: 18px; text-align: center;'>$box2_des</div>";
        $timeline .= "<div style='width:$box3_width%;float:left;padding: 0 4px; font-size: 18px; text-align: center;'>$box3_des</div>";
        $timeline .= "<div style='width:$box4_width%;float:left;padding: 0 4px; font-size: 18px; text-align: center;'>$box4_des</div>";
        $timeline .= "</div>";
        $timeline .= "</div>";

        return $timeline;
    }
}