<?php

namespace App\Http\Controllers\booking;

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
        $this->middleware('bookingauth')->except(['index','validateZip','login','doLogin','register','doRegister','forgotPassword','processForgotPassword','createEmailLink','session_login']);
        $this->viewFilePath = 'staging.';
    }

    /**
     * This Method use for display zipcode function
     */
    public function index(Request $request)
    {
        if(Session::get('bookingAuth') == null)
        {
            $referringPage = $request->server('HTTP_REFERER');
            $this->saveReferringUrl($referringPage);

			return view('booking.zipcode');
        }
        return redirect('booking/session');
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
        if(Session::get('bookingAuth') == null)
        {
            return view('booking.login.index');
        }
        return redirect('booking/session');
    }

    public function doLogin(Request $request)
    {
        $email 	= trim($request->email);
        $password = trim($request->password);

        $payload = [
            'email'     => $email,
            'password'  => $password,
            'device_type' => 'Browser'
        ];

        $url = 'https://bigtoe.app/app/Auth_V1/login';
        $res = $this->curlRequestCall('post', $payload, $url);

        $loginResponse = json_decode($res, true);

        $response['message'] = null;
        $response['status'] = 422;
        if ($loginResponse['ResponseMessage'] == 'SUCCESS') 
        {
            $loginResponse = collect($loginResponse);
            Session::push('bookingAuth', $loginResponse);
            $result = $loginResponse['Result'];
            
            $response['status'] = 200;
            $response['message'] = $loginResponse['Comments'];
            return redirect('booking/session')->with('message', $response['message']);
        }
        $response['message'] = $loginResponse['Comments'];
        return redirect('booking/signin')->with('message', $response['message']);        
    }

    public function register()
    {
        if(Session::get('bookingAuth') == null)
        {
            return view('booking.register.index');
        }
        return redirect('booking/session');
    }



    public function doRegister(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'country_code' => 'required',
            'phone_no' => 'required',
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

        $payload = [
            'email'     => $email,
            'firstname'  => $firstname,
            'lastname'  => $lastname,
            'country_code'  => "+".$country_code,
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
            Session::push('bookingAuth', $loginResponse);
            $result = $loginResponse['Result'];

            return redirect('booking/session')->with('message', $loginResponse['Comments']);
        }
        
        return redirect()->back()->with('message', $loginResponse['Comments']);
    }

    public function forgotPassword()
    {
        if(Session::get('bookingAuth') == null)
        {
            return view('booking.login.forgot-password');
        }
        return redirect('booking/session');
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
        return redirect('booking/forgot/password')->with('message', $response['message']);
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
		$currentDate = date('Ymd'); // Get the current date in yyyymmdd format
		$expiry = date('Ymd', strtotime('+3 days')); // Add 3 days to the current date


		$string='email='.$emailAddress.'&expiry='.$expiry;

        $request_token = $this->encrypt_decrypt('encrypt',$string);
        $response['url'] = \URL::to('/session_login?request_token='.$request_token);
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
        $request_token = isset($request->request_token) ? $request->request_token : null;
        abort_if( $request_token == null ,response()->json(['message' => 'Request not allowed.'], 422),422);
        $string = $this->encrypt_decrypt('decrypt',$request_token);
		
		$email = '';
		$expiry = '';

		// Parse the string and extract the values
		parse_str($string, $parsedString);

		// Assign values to variables
		if (isset($parsedString['email'])) {
			$email = $parsedString['email'];
		}

		if (isset($parsedString['expiry'])) {
			$expiry = $parsedString['expiry'];
		}
		
		
		$currentDate = date('Ymd'); 

		if ($currentDate > $expiry) {			
            $response['status'] = 200;
			return redirect('booking/session')->with('message', 'Please log in to continue');
        }			
			
        $payload = [
            'email'     => $email,
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
            Session::push('bookingAuth', $loginResponse);
            $result = $loginResponse['Result'];
            
            $response['status'] = 200;
            $response['message'] = $loginResponse['Comments'];
            return redirect('booking/session')->with('message', $response['message']);
        } else {
            $response['message'] = $loginResponse['Comments'];
            $response['status'] = 200;
            return response()->json($response,$response['status']);
        }
        // $response['message'] = $loginResponse['Comments'];
        // return redirect('staging/booking/signin')->with('message', $response['message']);
    }
	
	private function saveReferringUrl($url)
	{
		try {
			$api = 'https://bigtoe.app/app/Admin_V1/saveReferringPage';
			$payload = [
				'url' => $url,
				// Add other data in the payload if needed
			];

			// Disable cURL error handling
			$options = array(
				CURLOPT_FAILONERROR => false,
				CURLOPT_RETURNTRANSFER => true,
			);

			// Create a cURL handle
			$ch = curl_init($api);
			curl_setopt_array($ch, $options);

			// Set the POST payload
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));

			// Execute the cURL request
			curl_exec($ch);

			// Close cURL session
			curl_close($ch);

		} catch (\Exception $e) {
			// Ignore any exceptions and errors
		}
	}

}