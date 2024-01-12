<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location_mapped_regions;
use App\Studio;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Session;
use Illuminate\Support\Facades\DB;
use Mail;
use Validator;

class ApiController extends Controller {

    public function appointmentmap1(Request $request){
        //$request->session()->flush();

        $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
        $data['title'] = $seodata->title;
        $data['desc'] = $seodata->description;
        $data['keyword'] = $seodata->keyword;
        
        return view('appointment-map-1', compact('data'));
    }

    public function index() {
        $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
        $data['title'] = $seodata->title;
        $data['desc'] = $seodata->description;
        $data['keyword'] = $seodata->keyword;
        return view('appointment-map', compact('data'));
    }

    public function searchAddressId($addressId = '') {
        session()->put('searchAddressId', $addressId);
        return redirect('search-professional');
    }

    public function filterProfessional(Request $request) {

        $addressId = '';
        $addses = session()->get('searchAddressId');
        if ($addses > 0) {
            $addressId = $addses;
        }

        $skill_id = '';
        $skills = $request['skill_id'];
        if (!empty($skills)) {
            $skill_id = implode(',', $skills);
        }

        $service_id = '';
        $serviceids = $request['service_category_id'];
        if (!empty($serviceids)) {
            $service_id = implode(',', $serviceids);
        }

        if ($service_id != '' || $skill_id != '') {
            session()->put('filterData', array('service_id' => $service_id, 'skill_id' => $skill_id));
        }

        $response = $this->getToken();
        if ($response['msg'] == 'success') {
            $token = $response['token'];
            $dataAry = array(
                'token' => $token,
                'date' => date('Y-m-d'),
                'student_id' => 1,
                'address_id' => $addressId,
                'skill_id' => $skill_id
            );
            $url = 'https://bigtoe.app/TeacherOnDemand/getTeacherOnDemand';
            $res = $this->appCall('post', $dataAry, $url);

            $json_result = json_decode($res, true);
            if ($json_result['ResponseMessage'] == 'SUCCESS') {
                $result = $json_result['Result'];
            } else {
                $result = array();
            }

            return view('filter-professional', compact('result'));
        } else {
            return 'Token Problem';
        }
    }

    public function clearFilter() {
        session()->forget('filterData');
        return 'success';
    }

    public function searchProfessional() {

        $addressId = '';
        $addses = session()->get('searchAddressId');
        if ($addses > 0) {
            $addressId = $addses;
        }

        $response = $this->getToken();
        if ($response['msg'] == 'success') {
            $skill_id = '';
            if (session()->has('filterData')) {
                $sfdata = session()->get('filterData');
                $skill_id = $sfdata['skill_id'];
            }

            $token = $response['token'];
            $dataAry = array(
                'token' => $token,
                'date' => date('Y-m-d'),
                'student_id' => 1,
                'address_id' => $addressId,
                'skill_id' => $skill_id
            );
            $url = 'https://bigtoe.app/TeacherOnDemand/getTeacherOnDemand';
            $url1 = 'https://bigtoe.app/TeacherOnDemand/AppointmentFilterCategories';
            $res = $this->appCall('post', $dataAry, $url);
            $sdata = $this->appCall('post', ['token' => $token], $url1);

            $json_result1 = json_decode($sdata, true);
            if ($json_result1['ResponseMessage'] == 'SUCCESS') {
                $services = $json_result1['Result'];
            } else {
                $services = array();
            }


            $json_result = json_decode($res, true);
            if ($json_result['ResponseMessage'] == 'SUCCESS') {
                $result = $json_result['Result'];
            } else {
                $result = array();
            }

            $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
            $data['title'] = $seodata->title;
            $data['desc'] = $seodata->description;
            $data['keyword'] = $seodata->keyword;
            return view('search-professional', compact('data', 'result', 'services'));
        } else {
            return 'Token Problem';
        }
    }

    public function getPendingBookingList() {

        $addressId = '';
        $addses = session()->get('searchAddressId');
        if ($addses > 0) {
            $addressId = $addses;
        }

        $response = $this->getToken();
        if ($response['msg'] == 'success') {
            $skill_id = '';
            if (session()->has('filterData')) {
                $sfdata = session()->get('filterData');
                $skill_id = $sfdata['skill_id'];
            }

            $token = $response['token'];
            $dataAry = array(
                'token' => $token,
                'date' => date('Y-m-d'),
                'student_id' => 1,
                'address_id' => $addressId,
                'skill_id' => $skill_id
            );
            $url = 'https://bigtoe.app/TeacherOnDemand/getTeacherOnDemand';
            $url1 = 'https://bigtoe.app/TeacherOnDemand/AppointmentFilterCategories';
            $res = $this->appCall('post', $dataAry, $url);
            $sdata = $this->appCall('post', ['token' => $token], $url1);

            $json_result1 = json_decode($sdata, true);
            if ($json_result1['ResponseMessage'] == 'SUCCESS') {
                $services = $json_result1['Result'];
            } else {
                $services = array();
            }


            $json_result = json_decode($res, true);
            if ($json_result['ResponseMessage'] == 'SUCCESS') {
                $result = $json_result['Result'];
            } else {
                $result = array();
            }

            $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
            $data['title'] = $seodata->title;
            $data['desc'] = $seodata->description;
            $data['keyword'] = $seodata->keyword;
            return view('teacher.bookings.list', compact('data', 'result', 'services'));
        } else {
            return 'Token Problem';
        }
    }

    public function professionalDetail($url) {
        //set professional pass and get id
                $id = $this->setProfessionalId($url);
        //end
        $response = $this->getToken();
        if ($response['msg'] == 'success') {
            $token = $response['token'];
            $url = 'https://bigtoe.app/TeacherOnDemand/searchByTeacherOnDemand';
            $dataAry = [
                'token' => $token,
                'date' => date('Y-m-d'),
                'student_id' => 1,
                'teacher_id' => $id,
            ];
            $res = $this->appCall('post', $dataAry, $url);
            $json_result = json_decode($res, true);
            if ($json_result['ResponseMessage'] == 'SUCCESS') {
                $result = $json_result['Result'];
            } else {
                $result = array();
            }
            $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
            $data['title'] = $seodata->title;
            $data['desc'] = $seodata->description;
            $data['keyword'] = $seodata->keyword;
            return view('professional-details', compact('data', 'result'));
        } else {
            return 'Token Problem';
        }
    }

    public function saveSearch(Request $request) {
        $student_address_id = 0;
        $response = $this->getToken();
        if ($response['msg'] == 'success') {
            $token = $response['token'];
            $dataAry = array(
                "token" => $token,
                "student_id" => "1",
                "street_address" => $request->address,
                "unit_number" => "123787554",
                "city" => $request->city,
                "state" => $request->state,
                "zipcode" => $request->zipcode,
                "country" => $request->country,
                "latitude" => $request->latitude,
                "longitude" => $request->longitude,
                "is_default" => "1"
            );
            $token = $response['token'];
            $url = 'https://bigtoe.app/Studentv4/saveStudentAddress';
            $res = $this->appCall('post', $dataAry, $url);
            $json_result = json_decode($res, true);
            if ($json_result['ResponseMessage'] == 'SUCCESS') {
                $result = $json_result['Result'];
                $student_address_id = $result['student_address_id'];
            }
        }

        $searchAry = [
            'city' => $request->city,
            'state' => $request->state,
            'zipcode' => $request->zipcode,
            'country' => $request->country,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'title' => $request->title,
            'address' => $request->address,
            'student_address_id' => $student_address_id
        ];

        if (session()->has('userSearches')) {
            $newadd = session()->get('userSearches');
            array_push($newadd, $searchAry);
            session()->put('userSearches', $newadd);
        } else {
            $searchAry = [$searchAry];
            session()->put('userSearches', $searchAry);
        }

//return view('map-li');

        return url('search-professional/' . $student_address_id);
    }

    public function appCall($method = '', $data = '', $url = '') {
        if ($method == 'POST' || $method == 'post') {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
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

    public function appCalls($method = '', $data = '', $url = '') {
        if ($method == 'POST' || $method == 'post') {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
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

    public function getToken() {
        $response['msg'] = 'success';
        $response['token'] = '502eb263d95fa888db6064a611e725f4c2a13bb';
        return $response;
    }

    public function setProfessionalId($url) {
//this table used for routes
        if (session()->has('professionalCollection')) {
            $urlCollection = session()->get('professionalCollection');
            if (count($urlCollection) > 0) {
                foreach ($urlCollection as $value) {
                    $check = DB::table('page_urls')->where('index_name', $value['index_name'])->first();
                    if (!empty($check)) {
                        DB::table('page_urls')->where('id', $check->id)->update(['index_value' => $value['index_value']]);
                    } else {
                        DB::table('page_urls')->insert(['index_name' => $value['index_name'], 'index_value' => $value['index_value']]);
                    }
                }
            }
        }

        $urldata = DB::table('page_urls')->where('index_value', $url)->first();

        if (!empty($urldata)) {
            return $urldata->index_name;
        }

        return 135;
    }

    public function signupNew() {
        return view('signup');
    }

    public function loginNew() {
        $loginId = session()->get('loginStudentId');
        if (!empty($loginId)) {
            return view('login-new-loggedin');
        } else {
            return view('login-new');
        }
    }

    public function forgotNew() {
        return view('forgot-new');
    }

    public function reset_password(Request $request) {
        $email = $request['email'];
        $data = array('email' => $email);
        $url = 'https://bigtoe.app/Authv3/fogotPassword';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $json_result = json_decode($result, true);
        if ($json_result['ResponseMessage'] == 'SUCCESS') {
            return response()->json(['msg' => TRUE, 'html' => $json_result['Comments']]);
        } else {
            return response()->json(['msg' => false, 'html' => $json_result['Comments']]);
        }
    }

    public function loggedInAlready() {
        if (session()->has('loginStudentId')) {
            $loginStudentId = session()->get('loginStudentId');
            $loginToken = session()->get('loginToken');
            $page = session()->get('page');
            $response['msg'] = 'success';
            $response['token'] = $loginToken;
            /* === appointments-map === */
            //echo $page; die('JJJJJJJJ');
            //echo "<pre>";
            //print_r(session()->all());exit;
            if($page == '' && $loginToken !='' && $loginStudentId != ''){
                $page = 'appointments-map';
            }
          
            if ($page == 'appointments-map') {
                $addresses = $this->getAllAddressesUser($loginStudentId, $loginToken);
                $view = view("appointments-map")->with('addresses', $addresses)->render();
            }
            /* === appointments-map === */
            if ($page == 'search-professional-new') {
                $responses = $this->searchProfessionalNew();
                $data = $responses['data'];
                $result = $responses['result'];
                $services = $responses['services'];
                $provider = $responses['provider'];
                $region_available = $responses['region_available'];
                $region_message = $responses['region_message'];
                $view = view("search-professional-new")->with('data', $data)->with('result', $result)->with('services', $services)->with('provider', $provider)->with('region_available', $region_available)->with('region_message', $region_message)->render();
            }
            /* === professional-details-new === */
            if ($page == 'professional-details-new') {
                $urls = session()->get('detailpageurl');
                $id = $this->setProfessionalId($urls);
                $url = 'https://bigtoe.app/TeacherOnDemand/searchByTeacherOnDemand';
                $dataAry = [
                    'token' => $loginToken,
                    'date' => date('Y-m-d'),
                    'student_id' => $loginStudentId,
                    'teacher_id' => $id,
                ];
                $res = $this->appCall('post', $dataAry, $url);
                $json_result = json_decode($res, true);
                if ($json_result['ResponseMessage'] == 'SUCCESS') {
                    $result = $json_result['Result'];
                } else {
                    $result = array();
                }
                $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
                $data['title'] = $seodata->title;
                $data['desc'] = $seodata->description;
                $data['keyword'] = $seodata->keyword;
                session()->put('page', 'professional-details-new');
                $view = view("professional-details-new")->with('data', $data)->with('result', $result)->render();
            }
            /* === appointments-map === */
            if ($page == 'search-professional-new') {
                $responses = $this->searchProfessionalNew();
                $data = $responses['data'];
                $result = $responses['result'];
                $services = $responses['services'];
                $provider = $responses['provider'];
                $region_available = $responses['region_available'];
                $region_message = $responses['region_message'];
                $view = view("search-professional-new")->with('data', $data)->with('result', $result)->with('services', $services)->with('provider', $provider)->with('region_available', $region_available)->with('region_message', $region_message)->render();
            }
            /* === professional-details-new === */
            if ($page == 'professionals-details-new') {
                $urls = session()->get('detailpageurl');
                $id = $this->setProfessionalId($urls);
                $url = 'https://bigtoe.app/TeacherOnDemand/SearchByBigtoeTeacherOnDemand';
                $dataAry = [
                    'token' => $loginToken,
                    'date' => date('Y-m-d'),
                    'student_id' => $loginStudentId,
                    'teacher_id' => '',
                    'address_id' => '',
                    'latitude' => '',
                    'longitude' => '',
                ];
                $res = $this->appCall('post', $dataAry, $url);
                $json_result = json_decode($res, true);
                if ($json_result['ResponseMessage'] == 'SUCCESS') {
                    $result = $json_result['Result'];
                } else {
                    $result = array();
                }
                $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
                $data['title'] = $seodata->title;
                $data['desc'] = $seodata->description;
                $data['keyword'] = $seodata->keyword;
                session()->put('page', 'professionals-details-new');
                $view = view("professionals-details-new")->with('data', $data)->with('result', $result)->render();
            }
            /* === professional-details-new === */
            if ($page == 'booking-form-new') {
                $skillIDselected = session()->get('skillIDselected');
                $noofpeop = session()->get('noofpeop');
                $slotssel = session()->get('slotssel');
                $pageid = session()->get('detailpageurl');
                $teacher_id = $this->setProfessionalId($pageid);
                $addressId = '';
                $addses = session()->get('searchAddressId');
                if ($addses > 0) {
                    $addressId = $addses;
                }
               
                if (session()->has('loginStudentId')) {
                    $loginStudentId = session()->get('loginStudentId');
                    $loginToken = session()->get('loginToken');
                }
//end
                ///echo '$loginStudentId'.$loginStudentId;
                //echo '$loginToken'.$loginToken;exit;

                $response = $this->getToken();
                if ($response['msg'] == 'success') {
                    $token = $response['token'];
                    $urls = 'https://bigtoe.app/Studentv4/GetStudentDetailsForPrivates';
                    $dataAry = [
                        'token' => $loginToken,
                        'student_id' => $loginStudentId,
                        'student_address_id' => $addressId,
                        'skill_id' => $skillIDselected,
                        'number_of_people' => $noofpeop,
                    ];
                    $res = $this->appCall('post', $dataAry, $urls);
                    $json_result = json_decode($res, true);
                   
                    if ($json_result['ResponseMessage'] == 'SUCCESS') {
                        $result = $json_result['Result'];
                        $result['teacher_id'] = $teacher_id;
                    } else {
                        $result = array();
                    }
                    session()->put('page', 'booking-form-new');
                    $view = view("booking-form-new")->with('result', $result)->render();
                }
            }
            /* === booking-form-new === */
            if ($page == 'booking-preview') {
                session()->put('page', 'booking-preview');
                $view = view("booking-preview")->render();
            }
            /* === booking-form-new === */
            if ($page == 'booking-payment') {
                session()->put('page', 'booking-payment');
                $view = view("booking-payment")->render();
                $response['page'] = 'payment';
            }
            $response['html'] = $view;
            return response()->json($response);
        }
    }

    public function AppointmentsMapNew() {
        $addresses = array();
        return view('appointments-map')->with('addresses', $addresses);
    }

    public function bookingAppointmentNew() {
        return view('bookingAppointmentNew');
    }

    public function getAllAddressesUser($stdID, $tkn) {
        if ($tkn != '') {
            $dataAry = array(
                'student_id' => $stdID,
                'token' => $tkn
            );
            $url = 'https://bigtoe.app/Studentv4/getStudentAddress';
            $rest = $this->appCall('post', $dataAry, $url);
            $json_result1 = json_decode($rest, true);

            if ($json_result1['ResponseMessage'] == 'SUCCESS') {
                return $addresses = $json_result1['Result'];
            } else {
                return $addresses = array();
            }
        }
    }

    public function signupNewFacebook(Request $request) {
        

        $guest_login = 0;
        if(isset($request['guestuser']) && $request['guestuser'] == 'yes'){
            $guest_login = 1;
            
            //booking page
            $skill_category_name = $request['skill_category_name'];
            $skillIDselected = $request['skillIDselected'];
            $skillIDvalue = $request['skillIDvalue'];
            $price1 = $request['price1'];
            $genderpref = $request['genderpref'];
            $noofpeop = $request['noofpeop'];
            $slotssel = $request['slotssel'];
            $dateofbooking = $request['dateofbooking'];
            $pageid = session()->get('detailpageurl');
            
            $teacher_id = $this->setProfessionalId($pageid);

            $data_tax = $request['data_tax'];
            $data_tip = $request['data_tip'];

            session()->put('skillIDselected', $skillIDselected);
            session()->put('skillIDvalue', $skillIDvalue);
            session()->put('noofpeop', $noofpeop);
            session()->put('slotssel', $slotssel);
            session()->put('dateofbooking', $dateofbooking);
            session()->put('genderpref', $genderpref);

            session()->put('skill_category_name', $skill_category_name);
            session()->put('price1', $price1);

            session()->put('data_tax', $data_tax);
            session()->put('data_tip', $data_tip);
            
        }

        if(isset($guest_login) && $guest_login == 1){
            if(session()->has('userSearches')){
                $addresses = session()->get('userSearches'); 
                $search_address_id = session()->get('searchAddressId');
                if(isset($addresses) && count($addresses) > 0){
                    foreach($addresses as $k => $v){
                        if($addresses[$k]['student_address_id'] != $search_address_id){
                            unset($addresses[$k]);
                        }
                    }
                    session()->put('userSearches','');
                    session()->put('userSearches',$addresses);
                }
                
            }
        }

        $accessToken = $request['accessToken'];
        $userID = $request['userID'];
        $firstname = $request['firstname'];
        $lastname = $request['lastname'];
        $email = $request['email'];
        // $data = array('city' => '', 'device_token' => '', 'email' => $email, 'facebook_token' => $accessToken, 'firstname' => $firstname, 'lastname' => $lastname, 'latitude' => '', 'longitude' => '', 'password' => '', 'profile_picture' => '');
        //print_r($data);
        // die;
        $data = array('city' => '', 'device_token' => '', 'email' => $email, 'facebook_token' => $userID, 'firstname' => $firstname, 'lastname' => $lastname, 'latitude' => '', 'longitude' => '', 'password' => '', 'profile_picture' => '');
        $url = 'https://bigtoe.app/Authv3/signup_with_facebook';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $json_result = json_decode($result, true);
        //echo "<pre>";print_r($json_result); 
        if ($json_result['ResponseMessage'] == 'SUCCESS') {
            $res = $json_result['Result'];
            $response['msg'] = 'success';
            $response['token'] = $res['token'];
            session()->put('firstname', $res['firstname']);
            session()->put('loginToken', $res['token']);
            session()->put('loginStudentId', $res['student_id']);
            session()->put('page', 'appointments-map');
            
            if(isset($guest_login) && $guest_login == 1){

                $matches = 0;
                $match_address_id = 0;
                $addressesexisting = $this->getAllAddressesUser($res['student_id'], $res['token']);
                $requested_city = '';
                $requested_zip = '';
                $requested_state = '';
                $requested_address = '';
                foreach($addresses as $k => $v){
                    $requested_city = $addresses[$k]['city'];
                    $requested_zip = $addresses[$k]['zipcode'];
                    $requested_state = $addresses[$k]['state'];
                    $requested_address = $addresses[$k]['address'];
                }

                if(count($addressesexisting) > 0){
                    foreach($addressesexisting as $k => $v){
                        
                        if($addressesexisting[$k]['city'] == $requested_city && $addressesexisting[$k]['zipcode'] == $requested_zip && $addressesexisting[$k]['state'] == $requested_state && $addressesexisting[$k]['street_address'] == $requested_address){
                            $matches = 1;
                            $matched_lattitude  = $addressesexisting[$k]['stu_add_latitude'];
                            $matched_longitude  = $addressesexisting[$k]['stu_add_longitude'];
                            $match_address_id = $addressesexisting[$k]['student_address_id'];
                        }
                    }
                }
                //echo '$match_address_id'.$match_address_id;
                if(isset($matches) && $matches == 0){
                    foreach($addresses as $k => $v){
                                
                        $city = $addresses[$k]['city'];
                        $zipcode = $addresses[$k]['zipcode'];
                        $state = $addresses[$k]['state'];
                        $street_address = $addresses[$k]['address'];
                
                        $country = $addresses[$k]['country'];
                        $latitude = $addresses[$k]['latitude'];
                        $longitude = $addresses[$k]['longitude'];
                
                        $token = $response['token'];
                        $dataAry = array(
                            "token" => $res['token'],
                            "student_id" => $res['student_id'],
                            "street_address" => $street_address,
                            "unit_number" => "123787554",
                            "city" => $city,
                            "state" => $state,
                            "zipcode" => $zipcode,
                            "country" => $country,
                            "latitude" => $latitude,
                            "longitude" => $longitude,
                            "is_default" => "1"
                        );
                        $token = $response['token'];
                        $url = 'https://bigtoe.app/Studentv4/saveStudentAddress';
                        $res = $this->appCall('post', $dataAry, $url);
                        $json_result = json_decode($res, true);
                        
                        //echo "<pre>";
                        //print_r($json_result);
                        if ($json_result['ResponseMessage'] == 'SUCCESS') {
                            $result = $json_result['Result'];
                            $student_address_id = $result['student_address_id'];
                            $addses = session()->put('searchAddressId',$student_address_id);
                        } else {

                        }
                    }
                    $addressId = '';
                    $addses = session()->get('searchAddressId');
                    if ($addses > 0) {
                        $addressId = $addses;
                    }
                } else {
                    $addressId = $match_address_id;
                }

                if (session()->has('loginStudentId')) {
                    $loginStudentId = session()->get('loginStudentId');
                    $loginToken = session()->get('loginToken');
                }

                $urls = 'https://bigtoe.app/Studentv4/GetStudentDetailsForPrivates';
                $dataAry = [
                    'token' => $loginToken,
                    'student_id' => $loginStudentId,
                    'student_address_id' => $addressId,
                    'skill_id' => $skillIDselected,
                    'number_of_people' => $noofpeop,
                ];

                $res = $this->appCall('post', $dataAry, $urls);
                $json_result = json_decode($res, true);
                if ($json_result['ResponseMessage'] == 'SUCCESS') {
                    $result = $json_result['Result'];
                    $result['teacher_id'] = $teacher_id;
                } else {
                    $result = array();
                }
                session()->put('page', 'booking-form-new');
                $view = view("booking-form-new")->with('result', $result)->render();
                $response['html'] = $view;
                $response['url'] = 'booking/session';
                return response()->json($response);

            }
            /* =========== Getting Student Saved Searches ======== */
            $addresses = $this->getAllAddressesUser($res['student_id'], $res['token']);
            /* echo "<pre>";print_r($addresses); */
            /* ============ X ============================ X ======= */

            $view = view("appointments-map")->with('addresses', $addresses)->render();
            $response['html'] = $view;
            return response()->json($response);
        } else {
            if ($json_result['msg'] == 'null' || $json_result['msg'] == null) {
                $response['status'] ='sucess';
                $response['msg'] = 'Signup with facebook completed , Please login to continue.';
            } else {
                $response['status'] ='false';
                $msg = $json_result['Comments'];
                $response['msg'] = $msg;
            }
            return response()->json($response);
        }
    }

    public function loginNewFacebook(Request $request) {
        $accessToken = $request['accessToken'];
        $userID = $request['userID'];
        $firstname = $request['firstname'];
        $lastname = $request['lastname'];
        $email = $request['email'];

        $data = array('city' => '', 'device_token' => '', 'email' => $email, 'facebook_token' => $accessToken, 'firstname' => $firstname, 'lastname' => $lastname, 'latitude' => '', 'longitude' => '', 'password' => '', 'profile_picture' => '');
        $url = 'https://bigtoe.app/Authv2/signup_with_facebook';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);
        $json_result = json_decode($result, true);
        /* echo "<pre>";print_r($json_result); */

        if ($json_result['ResponseMessage'] == 'SUCCESS') {
            $res = $json_result['Result'];
            $response['msg'] = 'success';
            $response['token'] = $res['token'];
            session()->put('loginToken', $res['token']);
            session()->put('loginStudentId', $res['student_id']);
            session()->put('page', 'appointments-map');

            /* =========== Getting Student Saved Searches ======== */
            $addresses = $this->getAllAddressesUser($res['student_id'], $res['token']);
            /* echo "<pre>";print_r($addresses); */
            /* ============ X ============================ X ======= */

            $view = view("appointments-map")->with('addresses', $addresses)->render();
            $response['html'] = $view;
            return response()->json($response);
        } else {
            $msg = $json_result['Comments'];
            $response['msg'] = $msg;
            return response()->json($response);
        }
    }

    public function loginpost(Request $request) {
        $loginId = session()->get('loginStudentId');
        
        if (!empty($loginId)) {
            return view('login-new-loggedin');
        } else {

            $guest_login = 0;
            if(isset($request['guestuser']) && $request['guestuser'] == 'yes'){
                $guest_login = 1;
                
                //booking page
                $skill_category_name = $request['skill_category_name'];
                $skillIDselected = $request['skillIDselected'];
                $skillIDvalue = $request['skillIDvalue'];
                $price1 = $request['price1'];
                $genderpref = $request['genderpref'];
                $noofpeop = $request['noofpeop'];
                $slotssel = $request['slotssel'];
                $dateofbooking = $request['dateofbooking'];
                $pageid = session()->get('detailpageurl');
                
                $teacher_id = $this->setProfessionalId($pageid);

                $data_tax = $request['data_tax'];
                $data_tip = $request['data_tip'];

                session()->put('skillIDselected', $skillIDselected);
                session()->put('skillIDvalue', $skillIDvalue);
                session()->put('noofpeop', $noofpeop);
                session()->put('slotssel', $slotssel);
                session()->put('dateofbooking', $dateofbooking);
                session()->put('genderpref', $genderpref);

                session()->put('skill_category_name', $skill_category_name);
                session()->put('price1', $price1);

                session()->put('data_tax', $data_tax);
                session()->put('data_tip', $data_tip);
                
            }

            if(isset($guest_login) && $guest_login == 1){
                if(session()->has('userSearches')){
                    $addresses = session()->get('userSearches'); 
                    $search_address_id = session()->get('searchAddressId');
                    if(isset($addresses) && count($addresses) > 0){
                        foreach($addresses as $k => $v){
                            if($addresses[$k]['student_address_id'] != $search_address_id){
                                unset($addresses[$k]);
                            }
                        }
                        session()->put('userSearches','');
                        session()->put('userSearches',$addresses);
                    }
                    
                }
            }
           
            $validator = Validator::make($request->all(), array(
                        'email' => 'required|email',
                        'password' => 'required'
                            )
            );

            if ($validator->fails()) {
                $error_messages = implode(',', $validator->messages()->all());
                return back()->with('errors', $error_messages);
            } else {
                
                $email = $request['email'];
                $password = $request['password'];
                $_token = $request['_token'];

                $data = array('email' => $email, 'password' => $password);
                /* $data = array( 'email' => 'kapilsariwal@gmail.com', 'password' => '123456' );  */
                $url = 'https://bigtoe.app/Authv3/login';
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $result = curl_exec($ch);
                curl_close($ch);
                $json_result = json_decode($result, true);
                
                if ($json_result['ResponseMessage'] == 'SUCCESS') {
                    $res = $json_result['Result'];
                    $response['msg'] = 'success';
                    $response['token'] = $res['token'];
                    session()->put('loginToken', $res['token']);
                    session()->put('loginStudentId', $res['student_id']);
                    session()->put('firstname', $res['firstname']);
//session()->forget('message');

                    if(isset($guest_login) && $guest_login == 1){
                        $matches = 0;
                        $match_address_id = 0;
                        $addressesexisting = $this->getAllAddressesUser($res['student_id'], $res['token']);
                        $requested_city = '';
                        $requested_zip = '';
                        $requested_state = '';
                        $requested_address = '';
                        foreach($addresses as $k => $v){
                            $requested_city = $addresses[$k]['city'];
                            $requested_zip = $addresses[$k]['zipcode'];
                            $requested_state = $addresses[$k]['state'];
                            $requested_address = $addresses[$k]['address'];
                        }

                        if(count($addressesexisting) > 0){
                            foreach($addressesexisting as $k => $v){
                                
                                if($addressesexisting[$k]['city'] == $requested_city && $addressesexisting[$k]['zipcode'] == $requested_zip && $addressesexisting[$k]['state'] == $requested_state && $addressesexisting[$k]['street_address'] == $requested_address){
                                    $matches = 1;
                                    $matched_lattitude  = $addressesexisting[$k]['stu_add_latitude'];
                                    $matched_longitude  = $addressesexisting[$k]['stu_add_longitude'];
                                    $match_address_id = $addressesexisting[$k]['student_address_id'];
                                }
                            }
                        }
                        /*echo "<pre>";
                        print_r($addresses);
                        print_r($addressesexisting);
                        echo '$matches'.$matches;
                        echo '$match_address_id'.$match_address_id;
                        exit;*/
                        if(isset($matches) && $matches == 0){
                            foreach($addresses as $k => $v){
                                
                                $city = $addresses[$k]['city'];
                                $zipcode = $addresses[$k]['zipcode'];
                                $state = $addresses[$k]['state'];
                                $street_address = $addresses[$k]['address'];
                        
                                $country = $addresses[$k]['country'];
                                $latitude = $addresses[$k]['latitude'];
                                $longitude = $addresses[$k]['longitude'];
                        
                                $token = $response['token'];
                                $dataAry = array(
                                    "token" => $res['token'],
                                    "student_id" => $res['student_id'],
                                    "street_address" => $street_address,
                                    "unit_number" => "123787554",
                                    "city" => $city,
                                    "state" => $state,
                                    "zipcode" => $zipcode,
                                    "country" => $country,
                                    "latitude" => $latitude,
                                    "longitude" => $longitude,
                                    "is_default" => "1"
                                );
                                $token = $response['token'];
                                $url = 'https://bigtoe.app/Studentv4/saveStudentAddress';
                                $res = $this->appCall('post', $dataAry, $url);
                                $json_result = json_decode($res, true);
                               
                                //echo "<pre>";
                                //print_r($json_result);
                                if ($json_result['ResponseMessage'] == 'SUCCESS') {
                                    $result = $json_result['Result'];
                                    $student_address_id = $result['student_address_id'];
                                    $addses = session()->put('searchAddressId',$student_address_id);
                                } else {

                                }
                             }

                            $addressId = '';
                            $addses = session()->get('searchAddressId');
                            if ($addses > 0) {
                                $addressId = $addses;
                            }
                        } else {
                            $addressId = $match_address_id;
                        }

                        if (session()->has('loginStudentId')) {
                            $loginStudentId = session()->get('loginStudentId');
                            $loginToken = session()->get('loginToken');
                        }

                         $urls = 'https://bigtoe.app/Studentv4/GetStudentDetailsForPrivates';
                            $dataAry = [
                                'token' => $loginToken,
                                'student_id' => $loginStudentId,
                                'student_address_id' => $addressId,
                                'skill_id' => $skillIDselected,
                                'number_of_people' => $noofpeop,
                            ];
                            session()->put('searchAddressId',$addressId);
                            $res = $this->appCall('post', $dataAry, $urls);
                            $json_result = json_decode($res, true);
                            /*echo "<pre>";
                            print_r($json_result);
                            print_r(session()->all());exit;*/
                            if ($json_result['ResponseMessage'] == 'SUCCESS') {
                                $result = $json_result['Result'];
                                $result['teacher_id'] = $teacher_id;
                            } else {
                                $result = array();
                            }
                            session()->put('page', 'booking-form-new');
                            session()->forget('guestuser');
                            $view = view("booking-form-new")->with('result', $result)->render();
                            $response['html'] = $view;
                            $response['url'] = 'booking/session';
                            return response()->json($response);
                    }
                    

                    /* =========== Getting Student Saved Searches ======== */
                    $addresses = $this->getAllAddressesUser($res['student_id'], $res['token']);
                    /* echo "<pre>";print_r($addresses); */
                    /* ============ X ============================ X ======= */

                    return view("appointments-map")->with('addresses', $addresses);
                    /* $view = view("appointments-map")->with('addresses', $addresses)->render();
                      $response['html'] = $view;
                      return response()->json($response); */
                } else {
                    session()->put('message', 'Invalid Email or Password !!');
                    return back()->with('success', 'your message,here');
                }
            }
        }
    }

    public function registerpost(Request $request) {

        
        if(session()->has('userSearches')){
            $addresses = session()->get('userSearches'); 
            $search_address_id = session()->get('searchAddressId');
            if(isset($addresses) && count($addresses) > 0){
                foreach($addresses as $k => $v){
                    if($addresses[$k]['student_address_id'] != $search_address_id){
                        unset($addresses[$k]);
                    }
                }
                session()->put('userSearches','');
                session()->put('userSearches',$addresses);
            }
            
        }
        

        $_token = $request['_token'];
        $first_name = $request['first_name'];
        $last_name = $request['last_name'];
        $email = $request['email'];
        $password = $request['password'];

        $guest_register = 0;
        if(isset($request['guestuser']) && $request['guestuser'] == 'yes'){
            $guest_register = 1;
        }

        $data = array('firstname' => $first_name, 'lastname' => $last_name, 'email' => $email, 'password' => $password, 'city' => '', 'device_token' => '', 'facebook_token' => '', 'latitude' => '', 'longitude' => '', 'profile_picture' => '');

        $url = 'https://bigtoe.app/Authv3/register';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);
        $json_result = json_decode($result, true);
        //echo "<pre>";print_r($json_result); 
        if ($json_result['ResponseMessage'] == 'SUCCESS') {

            $data = array('email' => $email, 'password' => $password);
            $url = 'https://bigtoe.app/Authv3/login';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);
            curl_close($ch);
            $json_resultlogin = json_decode($result, true);

            if ($json_resultlogin['ResponseMessage'] == 'SUCCESS') {
                $res = $json_resultlogin['Result'];
                $response['msg'] = 'success';
                $response['token'] = $res['token'];
                session()->put('loginToken', $res['token']);
                session()->put('loginStudentId', $res['student_id']);
                session()->put('firstname', $res['firstname']);

                if(isset($request['guestuser']) && $request['guestuser'] == 'yes'){

                    if(session()->has('userSearches'))
                    {   
                        foreach($addresses as $k => $v){
                            $city = $addresses[$k]['city'];
                            $zipcode = $addresses[$k]['zipcode'];
                            $state = $addresses[$k]['state'];
                            $street_address = $addresses[$k]['address'];

                            $country = $addresses[$k]['country'];
                            $latitude = $addresses[$k]['latitude'];
                            $longitude = $addresses[$k]['longitude'];

                            $token = $response['token'];
                            $dataAry = array(
                                "token" => $res['token'],
                                "student_id" => $res['student_id'],
                                "street_address" => $street_address,
                                "unit_number" => "123787554",
                                "city" => $city,
                                "state" => $state,
                                "zipcode" => $zipcode,
                                "country" => $country,
                                "latitude" => $latitude,
                                "longitude" => $longitude,
                                "is_default" => "1"
                            );
                            $token = $response['token'];
                            $url = 'https://bigtoe.app/Studentv4/saveStudentAddress';
                            $res = $this->appCall('post', $dataAry, $url);
                            $json_result = json_decode($res, true);
                
                            //echo "<pre>";
                            //print_r($json_result);
                            if ($json_result['ResponseMessage'] == 'SUCCESS') {
                                $result = $json_result['Result'];
                                $student_address_id = $result['student_address_id'];
                                $addses = session()->put('searchAddressId',$student_address_id);
                            }
                         }


                       
                            /* =========== Getting Student Saved Searches ======== */
                            //$addresses = $this->getAllAddressesUser($student_address_id, $token);
                            /* ============ X ============================ X ======= */
                           
                            //booking page
                            $skill_category_name = $request['skill_category_name'];
                            $skillIDselected = $request['skillIDselected'];
                            $skillIDvalue = $request['skillIDvalue'];
                            $price1 = $request['price1'];
                            $genderpref = $request['genderpref'];
                            $noofpeop = $request['noofpeop'];
                            $slotssel = $request['slotssel'];
                            $dateofbooking = $request['dateofbooking'];
                            $pageid = session()->get('detailpageurl');
                            $teacher_id = $this->setProfessionalId($pageid);

                            $data_tax = $request['data_tax'];
                            $data_tip = $request['data_tip'];

                            session()->put('skillIDselected', $skillIDselected);
                            session()->put('skillIDvalue', $skillIDvalue);
                            session()->put('noofpeop', $noofpeop);
                            session()->put('slotssel', $slotssel);
                            session()->put('dateofbooking', $dateofbooking);
                            session()->put('genderpref', $genderpref);

                            session()->put('skill_category_name', $skill_category_name);
                            session()->put('price1', $price1);

                            session()->put('data_tax', $data_tax);
                            session()->put('data_tip', $data_tip);


                            $addressId = '';
                            $addses = session()->get('searchAddressId');
                            if ($addses > 0) {
                                $addressId = $addses;
                            }

                            if (session()->has('loginStudentId')) {
                                $loginStudentId = session()->get('loginStudentId');
                                $loginToken = session()->get('loginToken');
                            }
                            //end
                            $response = $this->getToken();
                            if ($response['msg'] == 'success') {
                                $token = $response['token'];
                                $urls = 'https://bigtoe.app/Studentv4/GetStudentDetailsForPrivates';
                                $dataAry = [
                                    'token' => $loginToken,
                                    'student_id' => $loginStudentId,
                                    'student_address_id' => $addressId,
                                    'skill_id' => $skillIDselected,
                                    'number_of_people' => $noofpeop,
                                ];

                                $res = $this->appCall('post', $dataAry, $urls);
                                $json_result = json_decode($res, true);
                                if ($json_result['ResponseMessage'] == 'SUCCESS') {
                                    $result = $json_result['Result'];
                                    $result['teacher_id'] = $teacher_id;
                                } else {
                                    $result = array();
                                }
                                session()->put('page', 'booking-form-new');
                                
                                $view = view("booking-form-new")->with('result', $result)->render();
                                $response['html'] = $view;
                                $response['url'] = 'booking/session';
                                return response()->json($response);
                            }
                          
                    }
                   
                } else {
                    session()->put('page', 'appointments-map');

                    /* =========== Getting Student Saved Searches ======== */
                    $addresses = $this->getAllAddressesUser($res['student_id'], $res['token']);
                    /* echo "<pre>";print_r($addresses); */
                    /* ============ X ============================ X ======= */ 
                }

                $view = view("appointments-map")->with('addresses', $addresses)->render();
                $response['html'] = $view;
                return response()->json($response);
            }

            /* $res = $json_result['Result'];
              $response['msg'] = 'success';
              $response['responsecode'] = $json_result['ResponseCode']; */

            /* $view = view("appointments-map")->render();
              $response['html'] = $view; */
//return response()->json($response);
        } else {
            $response['msg'] = $json_result['Comments'];
            $response['ResponseCode'] = '';
            return $response;
        }
    }

    public function saveSearchNew(Request $request) {
        $student_address_id = 0;
        $response = $this->getToken();
        if(isset($request->guestuser) && $request->guestuser == 'yes'){
            if ($response['msg'] == 'success') {
                $matches = 0;
                $matched_lattitude  = '';
                $matched_longitude  = '';
                if(session()->has('userSearches'))
                {
                    $requested_city = $request->city;
                    $requested_state = $request->state;
                    $requested_zip = $request->zipcode;
                    $requested_address = $request->address;
                    $addresses = session()->get('userSearches'); 
                    
                    foreach($addresses as $k => $v){
                       if($addresses[$k]['city'] == $requested_city && $addresses[$k]['zipcode'] == $requested_zip && $addresses[$k]['state'] == $requested_state || $addresses[$k]['address'] == $requested_address){
                            $matches = 1;
                            $matched_lattitude  = $request->latitude;
                            $matched_longitude  = $request->longitude;
                       } 
                    }
                }

                if($matches == 0){
                    $token = $response['token'];
                    $dataAry = array(
                        "token" => $token,
                        "student_id" => "1",
                        "street_address" => $request->address,
                        "unit_number" => "123787554",
                        "city" => $request->city,
                        "state" => $request->state,
                        "zipcode" => $request->zipcode,
                        "country" => $request->country,
                        "latitude" => $request->latitude,
                        "longitude" => $request->longitude,
                        "is_default" => "1"
                    );

                    $token = $response['token'];
                    $url = 'https://bigtoe.app/Studentv4/saveStudentAddress';
                    $res = $this->appCall('post', $dataAry, $url);
                    $json_result = json_decode($res, true);
                   
                    if ($json_result['ResponseMessage'] == 'SUCCESS') {
                        $result = $json_result['Result'];
                        $student_address_id = $result['student_address_id'];
                    } else {
                        $response['success'] = false;
                        $response['Comments'] = $result['student_address_id'];
                        $response['matched_longitude'] = $matched_longitude;
                        return response()->json($response);
                    }
                } else {
                    $response['success'] = false;
                    return response()->json($response);
                }
                

            }

            $searchAry = [
                'city' => $request->city,
                'state' => $request->state,
                'zipcode' => $request->zipcode,
                'country' => $request->country,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'title' => $request->title,
                'address' => $request->address,
                'student_address_id' => $student_address_id
            ];

            if (session()->has('userSearches')) {
                $newadd = session()->get('userSearches');
                array_push($newadd, $searchAry);
                session()->put('userSearches', $newadd);
            } else {
                $searchAry = [$searchAry];
                session()->put('userSearches', $searchAry);
            }
            session()->put('searchAddressId', $student_address_id);
            
            $i=0;
            $addresses = array();
            if(session()->has('userSearches'))
            {
              $addresses = session()->get('userSearches'); 
            }

            $view = view("appointments-map-optimized")->with('addresses', $addresses)->with('guest', 1)->render();
            $response['success'] = true;
            $response['html'] = $view;
            return response()->json($response);
            //return url('search-professional/' . $student_address_id);

        } else {
            if (session()->has('loginStudentId')) {
                $loginStudentId = session()->get('loginStudentId');
                $loginToken = session()->get('loginToken');
            }
            
            $matches = 0;
            $matched_lattitude  = '';
            $matched_longitude  = '';
            $requested_city = $request->city;
            $requested_state = $request->state;
            $requested_zip = $request->zipcode;
            $requested_address = $request->address;
            if(session()->has('userSearches'))
            {
                $addresses = session()->get('userSearches'); 
                foreach($addresses as $k => $v){
                    if($addresses[$k]['city'] == $requested_city && $addresses[$k]['zipcode'] == $requested_zip && $addresses[$k]['state'] == $requested_state && $addresses[$k]['address'] == $requested_address){
                        $matches = 1;
                        $matched_lattitude  = $request->latitude;
                        $matched_longitude  = $request->longitude;
                    } 
                }
            } 

            $addresses = $this->getAllAddressesUser($loginStudentId, $loginToken);
            if(count($addresses) > 0){
                foreach($addresses as $k => $v){
                    
                    if($addresses[$k]['city'] == $requested_city && $addresses[$k]['zipcode'] == $requested_zip && $addresses[$k]['state'] == $requested_state && $addresses[$k]['street_address'] == $requested_address){
                        $matches = 1;
                        $matched_lattitude  = $request->latitude;
                        $matched_longitude  = $request->longitude;
                    }
                }
            }
                
           

            if($matches == 0){
                if ($response['msg'] == 'success') {
                    $token = $response['token'];
                    $dataAry = array(
                        "token" => $loginToken,
                        "student_id" => $loginStudentId,
                        "street_address" => $request->address,
                        "unit_number" => "123787554",
                        "city" => $request->city,
                        "state" => $request->state,
                        "zipcode" => $request->zipcode,
                        "country" => $request->country,
                        "latitude" => $request->latitude,
                        "longitude" => $request->longitude,
                        "is_default" => "1"
                    );
                    $token = $response['token'];
                    $url = 'https://bigtoe.app/Studentv4/saveStudentAddress';
                    $res = $this->appCall('post', $dataAry, $url);
                    $json_result = json_decode($res, true);
        
                    
                    if ($json_result['ResponseMessage'] == 'SUCCESS') {
                        $result = $json_result['Result'];
                        $student_address_id = $result['student_address_id'];
                    }
                }
        
                $searchAry = [
                    'city' => $request->city,
                    'state' => $request->state,
                    'zipcode' => $request->zipcode,
                    'country' => $request->country,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'title' => $request->title,
                    'address' => $request->address,
                    'student_address_id' => $student_address_id
                ];
                if (session()->has('userSearches')) {
                    $newadd = session()->get('userSearches');
                    array_push($newadd, $searchAry);
                    session()->put('userSearches', $newadd);
                } else {
                    $searchAry = [$searchAry];
                    session()->put('userSearches', $searchAry);
                }
            } else {
                $response['success'] = false;
                $response['matched_lattitude'] = $matched_lattitude;
                $response['matched_longitude'] = $matched_longitude;
                return response()->json($response);
            }
    
            /*session()->put('searchAddressId', $student_address_id);
            
            $responses = $this->searchProfessionalNew();
    
            $data = $responses['data'];
            $result = $responses['result'];
            $services = $responses['services'];
            $provider = $responses['provider'];
            $region_available = $responses['region_available'];
            $region_message = $responses['region_message'];
            session()->put('page', 'search-professional-new');
    
            $view = view("search-professional-new")->with('data', $data)->with('result', $result)->with('services', $services)->with('provider', $provider)->with('region_available', $region_available)->with('region_message', $region_message)->render();
            $response['html'] = $view;*/
    
            $addresses = $this->getAllAddressesUser($loginStudentId, $loginToken);
            //$view = view("appointments-map")->with('addresses', $addresses)->render();
            $view = view("appointments-map-optimized")->with('addresses', $addresses)->render();
            $response['html'] = $view;
            $response['success'] = true;
            return response()->json($response);
        }
        
    }

    public function searchProfessionalGuest() {

        $addressId = '';
        $addses = session()->get('searchAddressId');
        if ($addses > 0) {
            $addressId = $addses;
        }
      

        $response = $this->getToken();
        if ($response['msg'] == 'success') {
            $skill_id = '';
            if (session()->has('filterData')) {
                $sfdata = session()->get('filterData');
                $skill_id = $sfdata['skill_id'];
            }

            $token = $response['token'];
            $dataAry = array(
                'token' => $token,
                'date' => date('Y-m-d'),
                'student_id' => 1,
                'address_id' => $addressId,
                'skill_id' => $skill_id
            );

            $url = 'https://bigtoe.app/TeacherOnDemand/getTeacherOnDemand';
            $url1 = 'https://bigtoe.app/TeacherOnDemand/AppointmentFilterCategories';
            $res = $this->appCall('post', $dataAry, $url);
            $sdata = $this->appCall('post', ['token' => $token], $url1);

            $json_result1 = json_decode($sdata, true);

            if ($json_result1['ResponseMessage'] == 'SUCCESS') {
                $services = $json_result1['Result'];
            } else {
                $services = array();
            }


            $json_result = json_decode($res, true);
           
            $region_available = 0;
            $region_message = '';
            if ($json_result['ResponseMessage'] == 'SUCCESS') {
                if($json_result['is_region_available'] == '' && $json_result['Comments'] == 'No record found.'){
                    $region_available = 0;
                    $region_message = 'Sorry but currently, We do not have operations in your region';
                } else {
                    $region_available = 1;
                }
                $result = $json_result['Result'];
            } else {
                $result = array();
                $region_message = 'Sorry but currently, We do not have operations in your region';
            }

            $dataAry2 = array(
                "token" => $token,
                "date" => date('Y-m-d'),
                "student_id" => 1,
                "latitude" => "",
                "longitude" => "",
                "address_id" => $addressId
            );
            $url2 = 'https://bigtoe.app/TeacherOnDemand/getBigtoeTeacherOnDemand';
            $ress = $this->appCall('post', $dataAry2, $url2);
            $json_result2 = json_decode($ress, true);
           

            /* echo "<pre>";print_r($json_result2); */
            if ($json_result2['ResponseMessage'] == 'SUCCESS') {
                $provider = $json_result2['Result'];
            } else {
                $provider = array();
            }


            $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
            $data['title'] = $seodata->title;
            $data['desc'] = $seodata->description;
            $data['keyword'] = $seodata->keyword;
            $response = array(
                'data' => $data,
                'result' => $result,
                'services' => $services,
                'provider' => $provider,
                'region_available' => $region_available,
                'region_message' => $region_message
            );
           
            return $response;
//return view('search-professional',compact('data','result','services'));
        } else {
            return 'Token Problem';
        }
    }

    public function searchAddressIdGuest(Request $request) {
        $addressId = $request['addressId'];
        session()->put('searchAddressId', $addressId);

        $responses = $this->searchProfessionalGuest();
        session()->put('guestuser', 1);
        session()->put('page', 'search-professional-new');
        $data = $responses['data'];
        $result = $responses['result'];
        $services = $responses['services'];
        $provider = $responses['provider'];
        $region_available = $responses['region_available'];
        $region_message = $responses['region_message'];

        $view = view("search-professional-new")->with('data', $data)->with('result', $result)->with('services', $services)->with('provider', $provider)->with('region_available', $region_available)->with('region_message', $region_message)->with('guest', 1)->render();
        $response['html'] = $view;
        return response()->json($response);
    }

    public function searchAddressIdNew(Request $request) {
        $addressId = $request['addressId'];
        session()->put('searchAddressId', $addressId);
        $responses = $this->searchProfessionalNew();
        
        session()->put('page', 'search-professional-new');
        $data = $responses['data'];
        $result = $responses['result'];
        $services = $responses['services'];
        $provider = $responses['provider'];
        $region_available = $responses['region_available'];
        $region_message = $responses['region_message'];
       
        $view = view("search-professional-new")->with('data', $data)->with('result', $result)->with('services', $services)->with('provider', $provider)->with('region_available', $region_available)->with('region_message', $region_message)->render();
        $response['html'] = $view;
        return response()->json($response);
    }

    public function searchProfessionalNew() {

        $addressId = '';
        $addses = session()->get('searchAddressId');
        if ($addses > 0) {
            $addressId = $addses;
        }
        if (session()->has('loginStudentId')) {
            $loginStudentId = session()->get('loginStudentId');
            $loginToken = session()->get('loginToken');
        }

        $response = $this->getToken();
        if ($response['msg'] == 'success') {
            $skill_id = '';
            if (session()->has('filterData')) {
                $sfdata = session()->get('filterData');
                $skill_id = $sfdata['skill_id'];
            }

            $token = $response['token'];
            $dataAry = array(
                'token' => $loginToken,
                'date' => date('Y-m-d'),
                'student_id' => $loginStudentId,
                'address_id' => $addressId,
                'skill_id' => $skill_id
            );

            $url = 'https://bigtoe.app/TeacherOnDemand/getTeacherOnDemand';
            $url1 = 'https://bigtoe.app/TeacherOnDemand/AppointmentFilterCategories';
            $res = $this->appCall('post', $dataAry, $url);
            $sdata = $this->appCall('post', ['token' => $loginToken], $url1);

            $json_result1 = json_decode($sdata, true);

            if ($json_result1['ResponseMessage'] == 'SUCCESS') {
                $services = $json_result1['Result'];
            } else {
                $services = array();
            }


            $json_result = json_decode($res, true);
           
            $region_available = 0;
            $region_message = '';
            if ($json_result['ResponseMessage'] == 'SUCCESS') {
                if($json_result['is_region_available'] == '' && $json_result['Comments'] == 'No record found.'){
                    $region_available = 0;
                    $region_message = 'Sorry but currently, We do not have operations in your region';
                } else {
                    $region_available = 1;
                }
                $result = $json_result['Result'];
            } else {
                $result = array();
                $region_message = 'Sorry but currently, We do not have operations in your region';
            }

            $dataAry2 = array(
                "token" => $loginToken,
                "date" => date('Y-m-d'),
                "student_id" => $loginStudentId,
                "latitude" => "",
                "longitude" => "",
                "address_id" => $addressId
            );
            $url2 = 'https://bigtoe.app/TeacherOnDemand/getBigtoeTeacherOnDemand';
            $ress = $this->appCall('post', $dataAry2, $url2);
            $json_result2 = json_decode($ress, true);
           

            /* echo "<pre>";print_r($json_result2); */
            if ($json_result2['ResponseMessage'] == 'SUCCESS') {
                $provider = $json_result2['Result'];
            } else {
                $provider = array();
            }


            $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
            $data['title'] = $seodata->title;
            $data['desc'] = $seodata->description;
            $data['keyword'] = $seodata->keyword;
            $response = array(
                'data' => $data,
                'result' => $result,
                'services' => $services,
                'provider' => $provider,
                'region_available' => $region_available,
                'region_message' => $region_message
            );
           
            return $response;
//return view('search-professional',compact('data','result','services'));
        } else {
            return 'Token Problem';
        }
    }

    public function searchProfessionalNewwithcalendor(Request $request) {

        $date = $request['date'];

        $addressId = '';
        $addses = session()->get('searchAddressId');
        if ($addses > 0) {
            $addressId = $addses;
        }
        if (session()->has('loginStudentId')) {
            $loginStudentId = session()->get('loginStudentId');
            $loginToken = session()->get('loginToken');
        }

        $response = $this->getToken();
        if ($response['msg'] == 'success') {
            $skill_id = '';
            if (session()->has('filterData')) {
                $sfdata = session()->get('filterData');
                $skill_id = $sfdata['skill_id'];
            }


            $token = $response['token'];
            $dataAry = array(
                'token' => $loginToken,
                'date' => $date,
                'student_id' => $loginStudentId,
                'address_id' => $addressId,
                'skill_id' => $skill_id
            );
            $url = 'https://bigtoe.app/TeacherOnDemand/getTeacherOnDemand';
            $url1 = 'https://bigtoe.app/TeacherOnDemand/AppointmentFilterCategories';
            $res = $this->appCall('post', $dataAry, $url);
            $sdata = $this->appCall('post', ['token' => $loginToken], $url1);

            $json_result1 = json_decode($sdata, true);
            if ($json_result1['ResponseMessage'] == 'SUCCESS') {
                $services = $json_result1['Result'];
            } else {
                $services = array();
            }


            $json_result = json_decode($res, true);
            if ($json_result['ResponseMessage'] == 'SUCCESS') {
                $result = $json_result['Result'];
            } else {
                $result = array();
            }

            $dataAry2 = array(
                "token" => $loginToken,
                "date" => $date,
                "student_id" => $loginStudentId,
                "latitude" => "",
                "longitude" => "",
                "address_id" => $addressId
            );
            $url2 = 'https://bigtoe.app/TeacherOnDemand/getBigtoeTeacherOnDemand';
            $ress = $this->appCall('post', $dataAry2, $url2);
            $json_result2 = json_decode($ress, true);
            /* echo "<pre>";print_r($json_result2); */
            if ($json_result2['ResponseMessage'] == 'SUCCESS') {
                $provider = $json_result2['Result'];
            } else {
                $provider = array();
            }


            $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
            $data['title'] = $seodata->title;
            $data['desc'] = $seodata->description;
            $data['keyword'] = $seodata->keyword;
            /* $response = array(
              'data' => $data,
              'result' => $result,
              'services' => $services,
              'provider' => $provider,
              ); */
              $region_available = 1;
         
            $view = view("search-professional-new")->with('data', $data)->with('result', $result)->with('services', $services)->with('provider', $provider)->with('region_available', $region_available)->render();
            $response['html'] = $view;
            return response()->json($response);
        } else {
            return 'Token Problem';
        }
    }

    public function professionalDetailNew(Request $request) {
        $url = $request['url'];
        session()->put('detailpageurl', $url);
        $id = $this->setProfessionalId($url);

        if (session()->has('loginStudentId')) {
            $loginStudentId = session()->get('loginStudentId');
            if (session()->has('loginToken')) {
                $loginToken = session()->get('loginToken');
            }
        }
        //end
        $response = $this->getToken();
        //set professional pass and get id
        if(isset($request->guestuser) && $request->guestuser == 'yes'){
            if ($response['msg'] == 'success') {
                $token = $response['token'];
                $url = 'https://bigtoe.app/TeacherOnDemand/searchByTeacherOnDemand';
                $dataAry = [
                    'token' => $token,
                    'date' => date('Y-m-d'),
                    'student_id' => 1,
                    'teacher_id' => $id,
                ];
                $res = $this->appCall('post', $dataAry, $url);
                $json_result = json_decode($res, true);
                if ($json_result['ResponseMessage'] == 'SUCCESS') {
                    $result = $json_result['Result'];
                } else {
                    $result = array();
                }
                $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
                $data['title'] = $seodata->title;
                $data['desc'] = $seodata->description;
                $data['keyword'] = $seodata->keyword;
                session()->put('page', 'professional-details-new');
                //return view('professional-details', compact('data', 'result'));
                $view = view("professional-details-new")->with('data', $data)->with('result', $result)->with('guest', 1)->render();
                $response['html'] = $view;
                return response()->json($response);
            } else {
                return 'Token Problem';
            }
        } else {
            if ($response['msg'] == 'success') {
                $token = $response['token'];
                $urls = 'https://bigtoe.app/TeacherOnDemand/searchByTeacherOnDemand';
                $dataAry = [
                    'token' => $loginToken,
                    'date' => date('Y-m-d'),
                    'student_id' => $loginStudentId,
                    'teacher_id' => $id,
                ];
    
                $res = $this->appCall('post', $dataAry, $urls);
                $json_result = json_decode($res, true);
                //echo "<pre>";
                //print_r($json_result);exit;
                if ($json_result['ResponseMessage'] == 'SUCCESS') {
                    $result = $json_result['Result'];
                } else {
                    $result = array();
                }
                $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
                $data['title'] = $seodata->title;
                $data['desc'] = $seodata->description;
                $data['keyword'] = $seodata->keyword;
                session()->put('page', 'professional-details-new');
                /* echo "<pre>";print_r($data); */
                /* echo "<pre>";print_r($result); */
                //return view('professional-details',compact('data','result'));
                $view = view("professional-details-new")->with('data', $data)->with('result', $result)->render();
                $response['html'] = $view;
                return response()->json($response);
            } else {
                return 'Token Problem';
            }
        }
    }

    public function filterProfessionalNew(Request $request) {

        if (session()->has('loginStudentId')) {
            $loginStudentId = session()->get('loginStudentId');
            if(session()->has('loginToken')){
                $loginToken = session()->get('loginToken');
            }
        }
        $response = $this->getToken();
        if ($response['msg'] == 'success') {
            $skill_id = '';
            $skills = $request['skill_id'];
            if (!empty($skills)) {
                $skill_id = implode(',', $skills);
            }
            $service_id = '';
            $serviceids = $request['service_category_id'];
            if (!empty($serviceids)) {
                $service_id = implode(',', $serviceids);
            }
            //if ($service_id != '' || $skill_id != '') {
            session()->put('filterData', array('service_id' => $service_id, 'skill_id' => $skill_id));
            //}
        }
        $addressId = '';
        $addses = session()->get('searchAddressId');
        if ($addses > 0) {
            $addressId = $addses;
        }
        
        $response = $this->getToken();
        if($request['guestuser'] == 1){
            if ($response['msg'] == 'success') {
                $token = $response['token'];
                $dataAry = array(
                    'token' => $token,
                    'date' => date('Y-m-d'),
                    'student_id' => 1,
                    'address_id' => $addressId,
                    'skill_id' => $skill_id
                );
                $url = 'https://bigtoe.app/TeacherOnDemand/getTeacherOnDemand';
                $res = $this->appCall('post', $dataAry, $url);

                $url1 = 'https://bigtoe.app/TeacherOnDemand/AppointmentFilterCategories';
                $res = $this->appCall('post', $dataAry, $url);
                $sdata = $this->appCall('post', ['token' => $token], $url1);
                
                $json_result1 = json_decode($sdata, true);
                if ($json_result1['ResponseMessage'] == 'SUCCESS') {
                    $services = $json_result1['Result'];
                } else {
                    $services = array();
                }

                $json_result = json_decode($res, true);
                if ($json_result['ResponseMessage'] == 'SUCCESS') {
                    $result = $json_result['Result'];
                } else {
                    $result = array();
                }


                $dataAry2 = array(
                    "token" => $token,
                    "date" => date('Y-m-d'),
                    "student_id" => 1,
                    "latitude" => "",
                    "longitude" => "",
                    "address_id" => $addressId
                );
                $url2 = 'https://bigtoe.app/TeacherOnDemand/getBigtoeTeacherOnDemand';
                $ress = $this->appCall('post', $dataAry2, $url2);
                $json_result2 = json_decode($ress, true);
                if ($json_result2['ResponseMessage'] == 'SUCCESS') {
                    $provider = $json_result2['Result'];
                } else {
                    $provider = array();
                }
            
                $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
                $data['title'] = $seodata->title;
                $data['desc'] = $seodata->description;
                $data['keyword'] = $seodata->keyword;
                $response = array(
                    'data' => $data,
                    'result' => $result,
                    'services' => $services,
                    'provider' => $provider,
                );
                if (!empty($skill_id)) {
                    $skills = explode(',', $skill_id);
                }
                $region_available = 1;
                $region_message = '';
                $view = view("search-professional-new")->with('skill_id', $skills)->with('data', $data)->with('result', $result)->with('services', $services)->with('provider', $provider)->with('region_available', $region_available)->with('region_message', $region_message)->with('guest', 1)->render();
                $response['html'] = $view;
    
                return response()->json($response);
            }
        } else {
            if ($response['msg'] == 'success') {
                $skill_id = '';
                if (session()->has('filterData')) {
                    $sfdata = session()->get('filterData');
                    $skill_id = $sfdata['skill_id'];
                }
                $token = $response['token'];
                $dataAry = array(
                    'token' => $loginToken,
                    'date' => date('Y-m-d'),
                    'student_id' => $loginStudentId,
                    'address_id' => $addressId,
                    'skill_id' => $skill_id
                );
                $url = 'https://bigtoe.app/TeacherOnDemand/getTeacherOnDemand';
                $url1 = 'https://bigtoe.app/TeacherOnDemand/AppointmentFilterCategories';
                $res = $this->appCall('post', $dataAry, $url);
                $sdata = $this->appCall('post', ['token' => $loginToken], $url1);
                $json_result1 = json_decode($sdata, true);
                if ($json_result1['ResponseMessage'] == 'SUCCESS') {
                    $services = $json_result1['Result'];
                } else {
                    $services = array();
                }
                $json_result = json_decode($res, true);
                if ($json_result['ResponseMessage'] == 'SUCCESS') {
                    $result = $json_result['Result'];
                } else {
                    $result = array();
                }
                $dataAry2 = array(
                    "token" => $loginToken,
                    "date" => date('Y-m-d'),
                    "student_id" => $loginStudentId,
                    "latitude" => "",
                    "longitude" => "",
                    "address_id" => $addressId
                );
                $url2 = 'https://bigtoe.app/TeacherOnDemand/getBigtoeTeacherOnDemand';
                $ress = $this->appCall('post', $dataAry2, $url2);
                $json_result2 = json_decode($ress, true);
                if ($json_result2['ResponseMessage'] == 'SUCCESS') {
                    $provider = $json_result2['Result'];
                } else {
                    $provider = array();
                }
                $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
                $data['title'] = $seodata->title;
                $data['desc'] = $seodata->description;
                $data['keyword'] = $seodata->keyword;
                $response = array(
                    'data' => $data,
                    'result' => $result,
                    'services' => $services,
                    'provider' => $provider,
                );
                if (!empty($skill_id)) {
                    $skills = explode(',', $skill_id);
                }
                $region_available = 1;
                $region_message = '';
                $view = view("search-professional-new")->with('skill_id', $skills)->with('data', $data)->with('result', $result)->with('services', $services)->with('provider', $provider)->with('region_available', $region_available)->with('region_message', $region_message)->render();
                $response['html'] = $view;
    
                return response()->json($response);
            }
        }
    }

    public function backscreen() {
      
        if (session()->has('loginStudentId')) {
            $loginStudentId = session()->get('loginStudentId');
        } else {
            $loginStudentId = 1;
        }

        
        
        if(session()->has('loginToken')){
            $loginToken = session()->get('loginToken');
            $response['token'] = $loginToken;
        }
            
            $page = session()->get('page');
            
            $response['msg'] = 'success';
            
            /* === appointments-map === */
            if ($page == 'appointments-mapold') {
                /* $addresses = $this->getAllAddressesUser($loginStudentId, $loginToken);
                  $view = view("appointments-map")->with('addresses', $addresses)->render(); */
            }

             /* === appointments-map === */
             if ($page == 'appointments-map') {
                /*if (session()->has('loginStudentId')) {
                  $addresses = $this->getAllAddressesUser($loginStudentId, $loginToken);
                  $view = view("appointments-map")->with('addresses', $addresses)->render(); 
                }*/
            }

            /* === appointments-map === */
            if ($page == 'search-professional-new') {
                
                if(session()->has('guestuser')){
                    session()->put('page', 'appointments-map');
                    $i=0;
                    $addresses = array();
                    if(session()->has('userSearches'))
                    {
                        $addresses = session()->get('userSearches'); 
                    }

                    $view = view("appointment-map-1")->with('addresses', $addresses)->with('guest', 1)->render();
                } else {
                    session()->put('page', 'appointments-map');
                    $addresses = $this->getAllAddressesUser($loginStudentId, $loginToken);
                    $view = view("appointments-map")->with('addresses', $addresses)->render();
                }
                
            }
            /* === professional-details-new === */
            if ($page == 'professional-details-new') {
                session()->put('page', 'search-professional-new');
                $guestuser = 0;
                if(session()->has('guestuser')){
                    $responses = $this->searchProfessionalGuest();
                    $guestuser = 1;
                } else {
                    $responses = $this->searchProfessionalNew();
                }
                
                $data = $responses['data'];
                $result = $responses['result'];
                $services = $responses['services'];
                $provider = $responses['provider'];
                $region_available = $responses['region_available'];
                $region_message = $responses['region_message'];
        
                $view = view("search-professional-new")->with('data', $data)->with('result', $result)->with('services', $services)->with('provider', $provider)->with('region_available', $region_available)->with('region_message', $region_message)->with('guest', $guestuser)->render();
                //$view = view("search-professional-new")->with('data', $data)->with('result', $result)->with('services', $services)->with('provider', $provider)->render();
            }
            /* === professionals-details-new === */
            if ($page == 'professionals-details-new') {
                session()->put('page', 'search-professional-new');
                $guestuser = 0;
                if(session()->has('guestuser')){
                    $responses = $this->searchProfessionalGuest();
                    $guestuser = 1;
                } else {
                    $responses = $this->searchProfessionalNew();
                }
                $data = $responses['data'];
                $result = $responses['result'];
                $services = $responses['services'];
                $provider = $responses['provider'];
                $region_available = $responses['region_available'];
                $region_message = $responses['region_message'];
                $view = view("search-professional-new")->with('data', $data)->with('result', $result)->with('services', $services)->with('provider', $provider)->with('region_available', $region_available)->with('region_message', $region_message)->with('guest', $guestuser)->render();
            }
            /* === booking-form-new === */
            if ($page == 'booking-form-new') {
                $url = session()->get('detailpageurl');
                if ($url != 'bigtoe') {
                    session()->put('page', 'professional-details-new');
                    $id = $this->setProfessionalId($url);
                    if (session()->has('loginStudentId')) {
                        $loginStudentId = session()->get('loginStudentId');
                        $loginToken = session()->get('loginToken');
                    }//end
                    $response = $this->getToken();
                    if ($response['msg'] == 'success') {
                        $token = $response['token'];
                        $urls = 'https://bigtoe.app/TeacherOnDemand/searchByTeacherOnDemand';
                        $dataAry = [
                            'token' => $loginToken,
                            'date' => date('Y-m-d'),
                            'student_id' => $loginStudentId,
                            'teacher_id' => $id,
                        ];
                        $res = $this->appCall('post', $dataAry, $urls);
                        $json_result = json_decode($res, true);
                        if ($json_result['ResponseMessage'] == 'SUCCESS') {
                            $result = $json_result['Result'];
                        } else {
                            $result = array();
                        }
                        $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
                        $data['title'] = $seodata->title;
                        $data['desc'] = $seodata->description;
                        $data['keyword'] = $seodata->keyword;
                        session()->put('page', 'professional-details-new');
                        $view = view("professional-details-new")->with('data', $data)->with('result', $result)->render();
                    }
                } else {
                    session()->put('page', 'professionals-details-new');
                    $id = $url;
                    if (session()->has('loginStudentId')) {
                        $loginStudentId = session()->get('loginStudentId');
                        $loginToken = session()->get('loginToken');
                    }
                    $response = $this->getToken();
                    if ($response['msg'] == 'success') {
                        $token = $response['token'];
                        $urls = 'https://bigtoe.app/TeacherOnDemand/SearchByBigtoeTeacherOnDemand';
                        $dataAry = [
                            'token' => $loginToken,
                            'date' => date('Y-m-d'),
                            'student_id' => $loginStudentId,
                            'teacher_id' => '',
                            'address_id' => '',
                            'latitude' => '',
                            'longitude' => '',
                        ];
                        $res = $this->appCall('post', $dataAry, $urls);
                        $json_result = json_decode($res, true);
                        if ($json_result['ResponseMessage'] == 'SUCCESS') {
                            $result = $json_result['Result'];
                        } else {
                            $result = array();
                        }
                        $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
                        $data['title'] = $seodata->title;
                        $data['desc'] = $seodata->description;
                        $data['keyword'] = $seodata->keyword;
                        session()->put('page', 'professionals-details-new');
                        $view = view("professionals-details-new")->with('data', $data)->with('result', $result)->render();
                    }
                }
            }
            /* === booking-form-new === */
            if ($page == 'booking-preview') {
                $addressId = '';
                $addses = session()->get('searchAddressId');
                if ($addses > 0) {
                    $addressId = $addses;
                }
                $skillIDselected = session()->get('skillIDselected');
                $noofpeop = session()->get('noofpeop');
                $pageid = session()->get('detailpageurl');
                $teacher_id = $this->setProfessionalId($pageid);
                $token = $response['token'];
                $urls = 'https://bigtoe.app/Studentv4/GetStudentDetailsForPrivates';
                $dataAry = [
                    'token' => $loginToken,
                    'student_id' => $loginStudentId,
                    'student_address_id' => $addressId,
                    'skill_id' => $skillIDselected,
                    'number_of_people' => $noofpeop,
                ];
                $res = $this->appCall('post', $dataAry, $urls);
                $json_result = json_decode($res, true);
                if ($json_result['ResponseMessage'] == 'SUCCESS') {
                    $result = $json_result['Result'];
                    $result['teacher_id'] = $teacher_id;
                } else {
                    $result = array();
                }
                session()->put('page', 'booking-form-new');
                $view = view("booking-form-new")->with('result', $result)->render();
            }
            /* === booking-form-new === */
            if ($page == 'booking-payment') {
                session()->put('page', 'booking-preview');
                $view = view("booking-preview")->render();
            }
            $response['html'] = $view;
            return response()->json($response);
        
    }

    public function professionalsDetailNew(Request $request) {

        $url = $request['url'];
        session()->put('detailpageurl', $url);
        $id = $url;

        if (session()->has('loginStudentId')) {
            $loginStudentId = session()->get('loginStudentId');
            if (session()->has('loginToken')) {
                $loginToken = session()->get('loginToken');
            }
        }

        //end
        $response = $this->getToken();
        //set professional pass and get id
        if(isset($request->guestuser) && $request->guestuser == 'yes'){
            if ($response['msg'] == 'success') {
                $token = $response['token'];
                $urls = 'https://bigtoe.app/TeacherOnDemand/SearchByBigtoeTeacherOnDemand';
                $dataAry = [
                    'token' => $token,
                    'date' => date('Y-m-d'),
                    'student_id' => 1,
                    'teacher_id' => '',
                    'address_id' => '',
                    'latitude' => '',
                    'longitude' => '',
                ];
    
                $res = $this->appCall('post', $dataAry, $urls);
                $json_result = json_decode($res, true);
                //  print_r($json_result);
                
                if ($json_result['ResponseMessage'] == 'SUCCESS') {
                    $result = $json_result['Result'];
                } else {
                    $result = array();
                }
    
                $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
                $data['title'] = $seodata->title;
                $data['desc'] = $seodata->description;
                $data['keyword'] = $seodata->keyword;
                session()->put('page', 'professionals-details-new');
    
                /* echo "<pre>";print_r($result); */
    
                $view = view("professionals-details-new")->with('data', $data)->with('result', $result)->with('guest', 1)->render();
                $response['html'] = $view;
                return response()->json($response);
            } else {
                return 'Token Problem';
            }
        } else {
            if ($response['msg'] == 'success') {
                $token = $response['token'];
                $urls = 'https://bigtoe.app/TeacherOnDemand/SearchByBigtoeTeacherOnDemand';
                $dataAry = [
                    'token' => $loginToken,
                    'date' => date('Y-m-d'),
                    'student_id' => $loginStudentId,
                    'teacher_id' => '',
                    'address_id' => '',
                    'latitude' => '',
                    'longitude' => '',
                ];
    
                $res = $this->appCall('post', $dataAry, $urls);
                $json_result = json_decode($res, true);
                //  print_r($json_result);
    
                if ($json_result['ResponseMessage'] == 'SUCCESS') {
                    $result = $json_result['Result'];
                } else {
                    $result = array();
                }
    
                $seodata = DB::table('seo_details')->where('page_url', 'classes')->first();
                $data['title'] = $seodata->title;
                $data['desc'] = $seodata->description;
                $data['keyword'] = $seodata->keyword;
                session()->put('page', 'professionals-details-new');
    
                /* echo "<pre>";print_r($result); */
    
                $view = view("professionals-details-new")->with('data', $data)->with('result', $result)->render();
                $response['html'] = $view;
                return response()->json($response);
            } else {
                return 'Token Problem';
            }
        }
        
    }

    public function bookingformpage(Request $request) {
        /* echo "<pre>";print_r($request->all()); */
        $skill_category_name = $request['skill_category_name'];
        $skillIDselected = $request['skillIDselected'];
        $skillIDvalue = $request['skillIDvalue'];
        $price1 = $request['price1'];
        $genderpref = $request['genderpref'];
        $noofpeop = $request['noofpeop'];
        $slotssel = $request['slotssel'];
        $dateofbooking = $request['dateofbooking'];
        $pageid = session()->get('detailpageurl');
        $teacher_id = $this->setProfessionalId($pageid);

        $data_tax = $request['data_tax'];
        $data_tip = $request['data_tip'];

        session()->put('skillIDselected', $skillIDselected);
        session()->put('skillIDvalue', $skillIDvalue);
        session()->put('noofpeop', $noofpeop);
        session()->put('slotssel', $slotssel);
        session()->put('dateofbooking', $dateofbooking);
        session()->put('genderpref', $genderpref);

        session()->put('skill_category_name', $skill_category_name);
        session()->put('price1', $price1);

        session()->put('data_tax', $data_tax);
        session()->put('data_tip', $data_tip);


        $addressId = '';
        $addses = session()->get('searchAddressId');
        if ($addses > 0) {
            $addressId = $addses;
        }

        if (session()->has('loginStudentId')) {
            $loginStudentId = session()->get('loginStudentId');
            $loginToken = session()->get('loginToken');
        }
//end
        $response = $this->getToken();
        if ($response['msg'] == 'success') {
            $token = $response['token'];
            $urls = 'https://bigtoe.app/Studentv4/GetStudentDetailsForPrivates';
            $dataAry = [
                'token' => $loginToken,
                'student_id' => $loginStudentId,
                'student_address_id' => $addressId,
                'skill_id' => $skillIDselected,
                'number_of_people' => $noofpeop,
            ];

            $res = $this->appCall('post', $dataAry, $urls);
            $json_result = json_decode($res, true);
           
            if ($json_result['ResponseMessage'] == 'SUCCESS') {
                $result = $json_result['Result'];
                $result['teacher_id'] = $teacher_id;
            } else {
                $result = array();
            }
            session()->put('page', 'booking-form-new');
            $view = view("booking-form-new")->with('result', $result)->render();
            $response['html'] = $view;
            return response()->json($response);
        }
    }

    public function bookingpreview(Request $request) {
//echo "<pre>";print_r($request->all());

        $dob = $request['dob'];
        $gender = $request['gender'];
        $phone = $request['phone'];

        $flex_schedule = $request['flex_schedule'];
        $general_search = $request['general_search'];

        $auto_assign = $request['auto_assign'];
        $has_dogs = $request['has_dogs'];
        $has_cats = $request['has_cats'];
        $flight_of_stairs = $request['flight_of_stairs'];
        $directions = $request['directions'];
        $note = $request['note'];
        $addonprice = $request['addonprice'];
        $addonids = $request['addonids'];
        $streetAddress = $request['streetAddress'];

        $addressId = '';
        $addses = session()->get('searchAddressId');
        if ($addses > 0) {
            $addressId = $addses;
        }

        if (session()->has('loginStudentId')) {
            $loginStudentId = session()->get('loginStudentId');
            $loginToken = session()->get('loginToken');
        }
//end
        $response = $this->getToken();
        if ($response['msg'] == 'success') {
            /* === Save date of Birth and Gender === */

            $urls = 'https://bigtoe.app/Studentv4/updateStudentBirthDateGender';
            $dataAry = [
                'token' => $loginToken,
                'student_id' => $loginStudentId,
                'dob' => $dob,
                "gender" => $gender,
            ];

            $res = $this->appCall('post', $dataAry, $urls);
            $json_result = json_decode($res, true);
            if ($json_result['ResponseMessage'] == 'SUCCESS') {
                $result = $json_result['Result'];
            } else {
                $result = array();
            }

            /* === Save date of Birth and Gender === */

            $url2 = 'https://bigtoe.app/Studentv4/updateStudentPhoneNumber';
            $dataAry2 = [
                'token' => $loginToken,
                'student_id' => $loginStudentId,
                'phone_no' => $phone,
            ];

            $res2 = $this->appCall('post', $dataAry2, $url2);
            $json_result2 = json_decode($res2, true);
            if ($json_result2['ResponseMessage'] == 'SUCCESS') {
                $result2 = $json_result2['Result'];
            } else {
                $result2 = array();
            }

            /* === Save date of Birth and Gender === */

            /* $url3 = 'https://bigtoe.app/Studentv4/updateStudentUnitNumber';
            $dataAry3 = [
            'token'=>$loginToken,
            'student_id'=>$loginStudentId,
            'student_address_id'=>$addressId,
            'unit_number'=>"1234",
            ];

            $res3 = $this->appCall('post',$dataAry3,$url3);
            $json_result3 = json_decode($res3, true);
            if($json_result3['ResponseMessage']=='SUCCESS'){
            $result3 = $json_result3['Result'];
            }else{
            $result3 = array();
            } */
              
            session()->put('flex_schedule', $flex_schedule);
            session()->put('general_search', $general_search);
            session()->put('auto_assign', $auto_assign);

            session()->put('has_dogs', $has_dogs);
            session()->put('has_cats', $has_cats);
            session()->put('flight_of_stairs', $flight_of_stairs);
            session()->put('directions', $directions);
            session()->put('note', $note);
            session()->put('addonprice', $addonprice);
            session()->put('addonids', $addonids);
            session()->put('streetAddress', $streetAddress);

            session()->put('page', 'booking-preview');
            $view = view("booking-preview")->render();
            $response['html'] = $view;
            return response()->json($response);
        }
    }

    public function checkforcard($loginToken,$loginStudentId){
        $url1 = 'https://bigtoe.app/Studentv4/checkSavedCard';
        $dataAry1 = [
           'token' => $loginToken,
           'student_id' => $loginStudentId,
            //'token' => '8b3428bd696ad3dbbc226ce07bc5f4b3aa4f1c3c',
            //'student_id' => 2
        ];

        $res1 = $this->appCall('post', $dataAry1, $url1);
        return $res1;
    }

    public function bookingpayment(Request $request) {

        if (session()->has('loginStudentId')) {
            $loginStudentId = session()->get('loginStudentId');
            $loginToken = session()->get('loginToken');
            $price1 = session()->get('price1');
        }

        $shownewcard = 0;
        $checkforcredit = 0;
        $checkforcard = 0;
        if ($request->has('checkforcredit')) {
            $checkforcredit = 1;
        }

        if ($request->has('checkforcard')) {
            $checkforcard = 1;
        }

        if ($request->has('shownewcard')) {
            $shownewcard = 1;
        }

        $auto_assign = $request['auto_assign'];

        if($checkforcredit == 1){
            $url = 'https://bigtoe.app/Studentv4/checkCreditForPrivate';
            $dataAry = [
                'token' => $loginToken,
                'student_id' => $loginStudentId,
                'required_credit' => $price1,
            ];

            $res = $this->appCall('post', $dataAry, $url);
            $json_result = json_decode($res, true);

            if ($json_result['ResponseMessage'] == 'SUCCESS') {
                $result = $json_result['Result'];
                $Comments = $json_result['Comments'];
                $response['result'] = $result;
                $response['havecredits'] = 1;
                $response['Comments'] = $Comments;
                return response()->json($response);
            } else {
                $checkforcard = 1;
            }
        } 

        if($checkforcard == 1){

            $res1 = $this->checkforcard($loginToken,$loginStudentId);
            $json_result1 = json_decode($res1, true);
           
            if ($json_result1['ResponseMessage'] == 'FAILURE') {
                $response['result'] = 0;
                session()->put('page', 'booking-payment');
                $view = view("booking-payment")->render();
                $response['html'] = $view;
                return response()->json($response);
            } else {
                $result1 = $json_result1['Result'];
                $Comments1 = $json_result1['Comments'];
                $response['result'] = $result1;
                $response['Comments'] = $Comments1;
                $response['havesavedcard'] = 1;
                $response['savedcardlast4'] = $result1[0]['last4'];
                return response()->json($response);
            }
        }

        if($shownewcard == 1){
            $response['result'] = 0;
            session()->put('page', 'booking-payment');
            $view = view("booking-payment")->render();
            $response['html'] = $view;
            return response()->json($response);
        }


        /* $dataAry = [
          'token'=> $loginToken,
          'student_id'=> 1,
          'required_credit'=> 1,
          ]; */

        /* echo "<pre>";print_r($json_result); */

            /*$url1 = 'https://bigtoe.app/Studentv4/checkSavedCard';
            $dataAry1 = [
                //'token' => $loginToken,
                //'student_id' => $loginStudentId,
                'token' => '054619d05a31f459e9f903062b12699224aad09e',
                'student_id' => 2
            ];

            $res1 = $this->appCall('post', $dataAry1, $url1);
            $json_result1 = json_decode($res1, true);
            echo "<pre>";
            print_r($json_result1);exit;*/
       

        /*if ($json_result['ResponseMessage'] == 'SUCCESS') {
            $result = $json_result['Result'];
            $Comments = $json_result['Comments'];
            $response['result'] = $result;
            $response['havecredits'] = 1;
            $response['Comments'] = $Comments;
            return response()->json($response);
        } else {

            //check saved card
            $url1 = 'https://bigtoe.app/Studentv4/checkSavedCard';
            $dataAry1 = [
                //'token' => $loginToken,
                //'student_id' => $loginStudentId,
                'token' => '054619d05a31f459e9f903062b12699224aad09e',
                'student_id' => 2
            ];

            $res1 = $this->appCall('post', $dataAry1, $url1);
            $json_result1 = json_decode($res1, true);
            echo "<pre>";
            print_r($json_result1);exit;

            if ($json_result1['ResponseMessage'] == 'FAILURE') {
                $response['result'] = 0;
                session()->put('page', 'booking-payment');
                $view = view("booking-payment")->render();
                $response['html'] = $view;
                return response()->json($response);
            } else {
                $result1 = $json_result1['Result'];
                $Comments1 = $json_result1['Comments'];
                $response['result'] = $result1;
                $response['Comments'] = $Comments1;
                return response()->json($response);
            }
        }*/
    }

    public function changeDateHeaderNew(Request $request) {
        $date = $request->input('date');
        $filterdata = $request->input('filterdata');
        $alldates = array();
        $recordexist = 0;
        $next_date = '';
        $next_date_text = '';
        if(!empty($filterdata)){ 
            foreach($filterdata as $key => $sl){
                if($date == $sl['date']){
                    $recordexist = 1;
                }

                if($recordexist == 0 && strtotime($sl['date']) > strtotime($date) && $next_date == ''){
                    $next_date = $sl['date'];
                }

                $alldates[] = $sl['date'];
            }
        }
        
        if($next_date != ''){
            $next_date_text = date('D, M d',strtotime($next_date));
        }
        $currentdate = date('D, M d', strtotime($date));
        $prev_date = date("Y-m-d", strtotime($date . "-1 day"));
        $next_date = date("Y-m-d", strtotime($date . "+ 1 day"));
        $disabled = 0;
        $date1 = date('D, M', strtotime($date));
        $date2 = ltrim(date('d', strtotime($date)), 0);


        return $view = view('professional-date-picker-new', compact('date'))->with('next_date_text',$next_date_text);
    }

    public function finalbooking(Request $request) {
        
        $addonids = array();
        if (session()->has('loginStudentId')) {
            $loginStudentId = session()->get('loginStudentId');
            $loginToken = session()->get('loginToken');
            $price1 = session()->get('price1');
            $addonprice = session()->get('addonprice');

            $addonids = array();
            $addonids = session()->get('addonids');
            if (!empty($addonids)) {
                $addonids = explode(',', $addonids);
            }
            $data_tax = session()->get('calculated_tax');
            $data_tip = session()->get('calculated_tip');
        }

        $addressId = '';
        $addses = session()->get('searchAddressId');
        $total = $request['total'];
        if ($addses > 0) {
            $addressId = $addses;
        }
        //$save_card = $request->input('save_card');
        $number_of_people = session()->get('noofpeop');
        $skillIDselected = session()->get('skillIDselected');
        $dateofbooking = session()->get('dateofbooking');
        $dateofbookings = date('Y-m-d', strtotime($dateofbooking));
        $note = session()->get('note');
        $student_address_id = $addressId;
        $has_cats = session()->get('has_cats');
        $has_dogs = session()->get('has_dogs');
        $stairs = session()->get('flight_of_stairs');
        $directions = session()->get('directions');

        $flex_schedule = session()->get('flex_schedule');
        $general_search = session()->get('general_search');

        

        /* ====Getting Teacher ID from function === */
        $pageid = session()->get('detailpageurl');
        $teacher_id = $this->setProfessionalId($pageid);
        
        /* == For Time == */
        $start_times = session()->get('slotssel');
        $start_time = explode(',', $start_times);
        /* $result = '"' . implode ( '","', $temp ) . '"'; */

        if ($teacher_id == '135') {

            $auto_assign = session()->get('auto_assign');
            /* =========== for Bigtoe teacher  =============== */
            $url = 'https://bigtoe.app/Studentv4/BookPrivateClassWithAnyTeacherCredits';
            $dataAry = [
                'token' => $loginToken,
                'number_of_people' => $number_of_people,
                'student_id' => $loginStudentId,
                'price' => $total,
                'add_on_price' => $addonprice,
                'tip' => $data_tip,
                'tax' => $data_tax,
                'gender' => session()->get('genderpref'),
                'skill_id' => $skillIDselected,
                'date' => $dateofbookings,
                'duration' => "",
                'start_time' => $start_time,
                'add_ons_ids' => $addonids,
                'note' => $note,
                'student_address_id' => $student_address_id,
                'cats' => $has_cats,
                'dogs' => $has_dogs,
                'stairs' => $stairs,
                'directions' => $directions,
                'auto_assign' => $auto_assign,
                'save_card' => 1
            ];
        } else {

            /* =========== for other teachers  =============== */
            $url = 'https://bigtoe.app/Studentv4/BookPrivateClassWithSpecificTeacherCredits';
            $dataAry = [
                "token" => $loginToken,
                "number_of_people" => $number_of_people,
                "student_id" => $loginStudentId,
                "price" => $total,
//                "price" => "1",
                "tip" => $data_tip,
                "tax" => $data_tax,
                "teacher_id" => $teacher_id,
                "skill_id" => $skillIDselected,
                "date" => $dateofbookings,
                "duration" => "",
                "start_time" => $start_time,
                "add_ons_ids" => $addonids,
                "note" => $note,
                "student_address_id" => $student_address_id,
                "cats" => $has_cats,
                "dogs" => $has_dogs,
                "stairs" => $stairs,
                "directions" => $directions,
                "session_price" => $price1,
                "client_is_flexible" => $flex_schedule,
                "general_search" => $general_search,
                'save_card' => 1
//                "session_price" => 1
            ];
        }

        /* ======== After Appointment Book ======== */

        $res = $this->appCalls("post", $dataAry, $url);
        $json_result = json_decode($res, true);

       
        //die('jjjjjjjjjj');

        if ($json_result['ResponseMessage'] == 'SUCCESS') {
            $result = $json_result['Result'];
            $message = $json_result['Comments'];
            $private_class_id = $result['private_class_id'];

            $urls = 'https://bigtoe.app/Studentv4/afterAppointmentBook';
            $dataArys = [
                "token" => $loginToken,
                "private_class_id" => $private_class_id,
            ];

            $ress = $this->appCall('post', $dataArys, $urls);
            $json_results = json_decode($ress, true);
            
           

            $view = view("booking-thank-you-new")->with('message',$message)->render();
            $response['html'] = $view;
            return response()->json($response);
        }
    }

    public function finalbookingcard(Request $request) {

        $addonids = array();
        if (session()->has('loginStudentId')) {
            $loginStudentId = session()->get('loginStudentId');
            $loginToken = session()->get('loginToken');
            $price1 = session()->get('price1');
            $addonprice = session()->get('addonprice');
            $addonids = array();
            $addonids = session()->get('addonids');
            if (!empty($addonids)) {
                $addonids = explode(',', $addonids);
            }
            
            //$data_tax = session()->get('data_tax');
            //$data_tip = session()->get('data_tip');

            $data_tax = session()->get('calculated_tax');
            $data_tip = session()->get('calculated_tip');

        }
        $s_token = $request->input('Stoken');
        //$save_card = $request->input('save_card');
        $addressId = '';
        $addses = session()->get('searchAddressId');
        if ($addses > 0) {
            $addressId = $addses;
        }
        $number_of_people = session()->get('noofpeop');
        $skillIDselected = session()->get('skillIDselected');
        $dateofbooking = session()->get('dateofbooking');
        $dateofbookings = date('Y-m-d', strtotime($dateofbooking));
        $note = session()->get('note');
        $student_address_id = $addressId;
        $has_cats = session()->get('has_cats');
        $has_dogs = session()->get('has_dogs');
        $stairs = session()->get('flight_of_stairs');
        $directions = session()->get('directions');

        $flex_schedule = session()->get('flex_schedule');
        $general_search = session()->get('general_search');

        /* ====Getting Teacher ID from function === */
        $pageid = session()->get('detailpageurl');
        $teacher_id = $this->setProfessionalId($pageid);
        /* == For Time == */
        $start_times = session()->get('slotssel');
        $start_time = explode(',', $start_times);

        $use_saved_card = 0;
        if($request->has('usesavedcard')){
            $use_saved_card = 1;
        }

        /* $result = '"' . implode ( '","', $temp ) . '"'; */
        if ($teacher_id == '135') {

            $auto_assign = session()->get('auto_assign');

            /* =========== for Bigtoe teacher  =============== */
            $url = 'https://bigtoe.app/Studentv4/BookPrivateClassWithAnyTeacherDollars';
            $dataAry = [
                'token' => $loginToken,
                'number_of_people' => $number_of_people,
                'student_id' => $loginStudentId,
                'price' => $price1,
                'tip' => $data_tip,
                'tax' => $data_tax,
                'customer_token' => '',
                'gender' => session()->get('genderpref'),
                'skill_id' => $skillIDselected,
                'date' => $dateofbookings,
                'duration' => "",
                'start_time' => $start_time,
                'add_on_price' => $addonprice,
                'session_price' => $price1,
                'add_ons_ids' => $addonids,
                'note' => $note,
                'student_address_id' => $student_address_id,
                'cats' => $has_cats,
                'dogs' => $has_dogs,
                'stairs' => $stairs,
                'directions' => $directions,
                'auto_assign' => $auto_assign,
                'use_saved_card' => $use_saved_card,
                'save_card' => 1
            ];
        } else {
            /* =========== for other teachers  =============== */
            $url = 'https://bigtoe.app/Studentv4/BookPrivateClassWithSpecificTeacherDollars';
            $dataAry = [
                'token' => $loginToken,
                'number_of_people' => $number_of_people,
                'student_id' => $loginStudentId,
                'price' => $price1, // G Total
                'tip' => $data_tip,
                'tax' => $data_tax,
                'customer_token' => $s_token,
                'teacher_id' => $teacher_id,
                'gender' => session()->get('genderpref'),
                'skill_id' => $skillIDselected,
                'date' => $dateofbookings,
                'duration' => "",
                'start_time' => $start_time,
                'add_on_price' => $addonprice,
                'session_price' => $price1, // Price of Item
                'add_ons_ids' => $addonids,
                'note' => $note,
                'student_address_id' => $student_address_id,
                'cats' => $has_cats,
                'dogs' => $has_dogs,
                'stairs' => $stairs,
                'directions' => $directions,
                "client_is_flexible" => $flex_schedule,
                "general_search" => $general_search,
                'use_saved_card' => $use_saved_card,
                'save_card' => 1
            ];
            //echo "<pre>";
            //print_r($dataAry);
//            die;
        }
        //echo "<pre>";
        //print_r($dataAry);exit;
        
        $res = $this->appCalls('post', $dataAry, $url);
        //print_r($res); die('hhhhhhhhh');

        /* ======== After Appointment Book ======== */


        $json_result = json_decode($res, true);
        if ($json_result['ResponseMessage'] == 'SUCCESS') {
            $result = $json_result['Result'];
            $private_class_id = $result['private_class_id'];
            $urls = 'https://bigtoe.app/Studentv4/afterAppointmentBook';
            $dataArys = [
                'token' => $loginToken,
                'private_class_id' => $private_class_id
            ];
            $ress = $this->appCall('post', $dataArys, $urls);
            $json_results = json_decode($ress, true);

            if ($json_results['ResponseMessage'] == 'SUCCESS') {
                
            }
            $view = view("booking-thank-you-new")->render();
            $response['html'] = $view;
            $response['status'] = 'success';
            return response()->json($response);
        } else {
            $response['html'] = 'Error while processing your payment';
            $response['status'] = 'error';
            return response()->json($response);
        }
    }

    public function backtohomescreen(Request $request) {
        if (session()->has('loginStudentId')) {
            $loginStudentId = session()->get('loginStudentId');
            $loginToken = session()->get('loginToken');
        }

        $addresses = $this->getAllAddressesUser($loginStudentId, $loginToken);
        $view = view("appointments-map")->with('addresses', $addresses)->render();

        session()->put('page', 'appointments-map');
        $response['html'] = $view;
        return response()->json($response);
    }

    public function deleteAddress(Request $request) {

        $address_id = $request['id'];
        if (session()->has('loginStudentId')) {
            $loginStudentId = session()->get('loginStudentId');
            $loginToken = session()->get('loginToken');
        }

        $urls = 'https://bigtoe.app/Studentv4/deleteStudentAddress';
        $dataAry = [
            'token' => $loginToken,
            'student_id' => $loginStudentId,
            'student_address_id' => $address_id,
            'is_default' => 1,
        ];

        $res = $this->appCall('post', $dataAry, $urls);
        $json_result = json_decode($res, true);

        if ($json_result['ResponseMessage'] == 'SUCCESS') {
            $result = $json_result['Result'];
        } else {
            $result = array();
        }

        $addresses = $this->getAllAddressesUser($loginStudentId, $loginToken);
        $view = view("appointments-map")->with('addresses', $addresses)->with('excludelayout',1)->render();

        $response['html'] = $view;
        return response()->json($response);
    }

    public function DoFavourite() {

        if (session()->has('loginStudentId')) {
            $loginStudentId = session()->get('loginStudentId');
            $loginToken = session()->get('loginToken');
            /* ====Getting Teacher ID from function === */
            $pageid = session()->get('detailpageurl');
            $teacher_id = $this->setProfessionalId($pageid);
        }


        $urls = 'https://bigtoe.app/Studentv4/doFavourite';
        $dataAry = [
            'token' => $loginToken,
            'student_id' => $loginStudentId,
            'teacher_id' => $teacher_id
        ];

        $res = $this->appCall('post', $dataAry, $urls);
        $json_result = json_decode($res, true);

        if ($json_result['ResponseMessage'] == 'SUCCESS') {
            $result['success'] = true;
        } else {
            $result['success'] = false;
        }
        return json_encode($result);
        exit;
    }

}
