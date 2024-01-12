<?php

namespace App\Http\Controllers\booking;

use App\Http\Controllers\Controller; // ON live remove 
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Traits\CommonTrait;
use Validator;
use Illuminate\Support\Str;
use Session;

class BookingStepController extends Controller
{
    //use CommonTrait;

    protected $activeAuth,$allDates,$displayRatingScreen,$ratingData;

    function __construct()
    {
        $this->middleware('bookingauth');
        $this->allDates = [];
        $this->ratingData = [];
        $this->displayRatingScreen = false;
       
    }

    public function loadActiveUser()
    {
        $this->activeAuth = Session::get('bookingAuth');
    }

    public function index()
    {
        $this->loadActiveUser();

        $seodata = DB::table('seo_details')->where('page_url','book-session')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
        $data['service_type'] = [];
        $data['parking_type'] = $this->ParkingTypeList('web');
        $data['active_user'] = $this->activeAuth != null && isset($this->activeAuth[0]['Result']) && !empty($this->activeAuth[0]['Result']) ? $this->activeAuth[0]['Result'] : null;
        session()->put('student_id',$data['active_user']['student_id']);
        session()->put('token',$data['active_user']['token']);
        session()->put('student_email',$data['active_user']['email']);
        session()->put('student_full_naame',$data['active_user']['firstname'].' '.$data['active_user']['lastname']);
        $services_list = $this->initServiceTypeList();
        $isRatingScreen = $this->displayRatingScreen;
        $ratingRecords = $this->ratingData;
        session()->put('ratingData',$ratingRecords);
        $address_list = $this->initAddress();
        $people_list = [];
        $people_list['list'] = $this->initPeopleList();
        $people_list['default'] = $this->initPeopleDefaultList();
        if($services_list == "tokanmismatch")
        {
            return redirect('booking/session/logout?do=token');
        }
        return view('booking.index',compact('data','address_list','services_list','people_list','isRatingScreen','ratingRecords'));
    }


    public function getAddressList() 
    {
        $address_list = $this->initAddress();
        $addressListRender = view('booking.address.list',compact('address_list'))->render();
        $response['address_list_html'] = $addressListRender;
        $response['address_list'] = $address_list;
        $response['status'] = 200;
        return response()->json($response,$response['status']);
    }

    private function initAddress()
    {
        session()->forget('add_list');
        $addPayload = [
            'token' =>  session()->get('token'),
            'student_id' => session()->get('student_id')
        ];

        $getAddressURL = 'https://bigtoe.app/app/ClientSide_V1/getStudentAddress';
        $addList = $this->curlRequestCall('post', $addPayload, $getAddressURL);

        $addListResult = json_decode($addList, true);
        $listAdd =  $addListResult['ResponseCode'] != 0 ? array_column($addListResult['Result'], null, 'student_address_id') : [];
        session()->push('add_list',$listAdd);
        return $listAdd;
    }
    
     /**
     * This Method will use when Address step is active
     * @method : POST using AJAX
     */
    public function addressDetails(Request $request)
    {
        abort_if(!$request->ajax() ,response()->json(['message' => 'Request not allowed.'], 422),422);

        $rules = [
            'student_id'    => 'required',
            'locationtype'  => 'required',
            //'locationname'  => 'required',
            //'residencetype' => 'required',
            //'street_address'=> 'required',
            //'unit_number'   => 'required',
            // 'city'          => 'required',
            // 'state'         => 'required',
            // 'zipcode'       => 'required',
            // 'country'       => 'required',
            // 'latitude'      => 'required',
            // 'longitude'     => 'required',
            'new_token'     => 'required',
            'cats'          => 'required',
            'dogs'          => 'required',
            'parkingtype'   => 'required',
            'parkingfee'    => 'required',
            'stairs'        => 'required',  
        ];

        $isHotel = false;
        if($request->locationtype == "hotel")
        {
            $isHotel = true;
            // $rules['locationname'] = 'required';
            // $rules['unit_number'] = 'required';
        }
        if($request->locationtype == "residence")
        {
            $rules['residencetype'] = 'required';
        }
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

        $payload = $request->all();
        $payload['token'] = $request->new_token; 
        $payload['locationtype'] = ucwords($request->locationtype); 
        $payload['residencetype'] = !empty($request->residencetype) ? strtolower($request->residencetype) : null;
        if($isHotel === true)
        {
            $payload['hotel_id'] = !empty($request->hotel_id) ? strtolower($request->hotel_id) : null;
        }
        $payload['client_id'] = session()->get('student_id');
        $payload['device_type'] = 'Browser';
        $payload['unit_number'] = !empty($request->unit_number) ? strtolower($request->unit_number) : "";
        $payload['address'] = !empty($request->street_address) ? $request->street_address : "";
        unset($payload['new_token']);
        //print_r($payload); die;
        $url = 'https://bigtoe.app/app/ClientSide_V1/addClientAddress';
        $res = $this->curlRequestCall('post', $payload, $url);

        $json_result = json_decode($res, true);

        if ($json_result['ResponseMessage'] == 'SUCCESS') 
        {
            $result = $json_result['Result'];
            $address = collect($result);
            
            $response['status'] = 200;
            $response['response'] = $json_result;
            $response['message'] = 'address added successfully.';
        }
        else
        {
            $response['status'] = 422; 
            $response['response']['ResponseCode'] = $json_result['ResponseCode'];
            $response['messsage'] = $json_result['Comments'];
        }
        return response()->json($response,$response['status']);
    }

    public function initServiceTypeList()
    {
        $url = 'https://bigtoe.app/app/ClientSide_V1/getHomeScreen';
        
        $payload = [
            'client_id' => session()->get('student_id'),
            'token' => session()->get('token'),
        ];
        $res = $this->curlRequestCall('POST', $payload, $url);

        $json_result = json_decode($res, true);  
        //print_r( $json_result);
        $skillCategory = [];
        if($json_result['ResponseCode'] == 1)
        {
            $skillCategory = $json_result['Result']['skill_category'];
            $this->ratingData = isset($json_result['Result']['rating_data']) ? $json_result['Result']['rating_data'] : [];
            $this->displayRatingScreen = isset($json_result['Result']['rating_data']) ? true : false;
        }
        if($json_result['ResponseCode'] == 0 && $json_result['Comments'] == "Token Mismatch")
        {
            $skillCategory = "tokanmismatch";
        }
        return $skillCategory;
    }

    /**
     * This Method use for get list of service type 
     */
    public function ServiceTypeList($type="API")
    {
        $skillCategory = $this->initServiceTypeList();
        if($skillCategory)
        {
            $categories = [];
            foreach($skillCategory as $singlecat)
            {
                $service_category_id = $singlecat['service_category_id'];
                $service_category = $singlecat['service_category'];
                $categories[$service_category_id] = $service_category; 
            }
            return response()->json(['ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => '', 'status' => 'accepted', 'headline' =>'Extra Time Accepted', 'time_options' => array(), 'text' => $categories], 200);
        }
        return response()->json(['ResponseCode' => 0, 'ResponseMessage' => 'FAILER', 'Comments' => '', 'status' => 'regect', 'time_options' => array(), 'text' => $categories], 200);
    }

    /**
     * This Method use for get list of child service base on parent services 
     */
    public function ChildServiceTypeList(Request $request,$parent_id)
    {
        abort_if(!$parent_id ,response()->json(['message' => 'Service type is is invalid.'], 422),422);
        
        $url = 'https://bigtoe.app/app/ClientSide_V1/getAllActiveServices'; // todo : change api name TO : getHomeScreen
        
        $payload = [
            'student_id' => session()->get('student_id'),
            'token' => session()->get('token'),
        ];

        //print_r($payload);
        $res = $this->curlRequestCall('POST', $payload, $url);
        
        $json_result = json_decode($res, true);
        //print_r($json_result);
        if($json_result['ResponseCode'] == 1)
        {
            $subCategories = [];

            $skillCategory = $json_result['Result']['skill_category'];
            if($skillCategory)
            {
                // /$serviceType = collect([ 'private_yoga' => 'Private Yoga', 'massage' => 'Massage']);

                foreach($skillCategory as $singlecat)
                {
                    $service_category_id = $singlecat['service_category_id'];
                    $skills = $singlecat['skill'];
                    foreach($skills as $skill)
                    {
                        $skill_id = $skill['skill_id'];
                        $subCategories[$service_category_id][$skill_id] = $skill['name'];
                    }
                }
            }
            $serviceType = collect($subCategories);
            return response()->json(['ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => '', 'status' => 'accepted', 'headline' =>'Extra Time Accepted', 'time_options' => array(), 'text' => $serviceType->get($parent_id)], 200);
        }
    }

    /**
     * This Method use for get list of service type 
     */
    public function ParkingTypeList($type="API")
    {
        $serviceType = collect([ 'parking_1' => 'Street', 'parking_2' => 'Lot or Garage', 'parking_3' => 'Valet', 'parking_4' => 'Not Sure']);
        return $type == "API" ? response()->json(['ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => '', 'status' => 'accepted', 'headline' =>'Extra Time Accepted', 'time_options' => array(), 'text' => $serviceType->toArray()], 200) : $serviceType->toArray();

        //return response()->json(['status' => 200, 'data' => $serviceType->toArray()], 200);
    }

    public function initPeopleDefaultList()
    {
        $this->loadActiveUser();

        $data['active_user'] = $this->activeAuth != null && isset($this->activeAuth[0]['Result']) && !empty($this->activeAuth[0]['Result']) ? $this->activeAuth[0]['Result'] : null;

        $peopleList = [
            'client_id' => $data['active_user']['student_id'],
            'firstname' => $data['active_user']['firstname'],
            'lastname' => $data['active_user']['lastname'],
            'age_range' => $data['active_user']['age_range'],
            'gender' => $data['active_user']['gender'],
            'phone_no' => $data['active_user']['phone_no']
        ];

        session()->put('people_list',$peopleList);
        return $peopleList;
    }
    public function initPeopleList()
    {
        $url = 'https://bigtoe.app/app/ClientSide_V1/getClientGuests';
        
        $payload = [
            'client_id' => session()->get('student_id'),
            'token' => session()->get('token'),
        ];
        $res = $this->curlRequestCall('POST', $payload, $url);

        $json_result = json_decode($res, true);
        //dd($json_result);
        $mxPpl = $json_result && isset($json_result['max_people']) ? $json_result['max_people'] : 4;
        session()->put('max_people_allow',$mxPpl);
        $guestList = [];
        if($json_result['ResponseCode'] == 1)
        {
            $guestList = !empty($json_result['Result']) ? $json_result['Result'] : [];

            session()->put('max_people_allow',$json_result['max_people']);
            session()->put('people_hed_tooltip',$json_result['header_information']);
            session()->put('two_people_headline',$json_result['two_people_headline']);

            $back_to_back_text = isset($json_result['two_people_options']['back_to_back_text']) ? $json_result['two_people_options']['back_to_back_text'] : null;
            $couple_text = isset($json_result['two_people_options']['couple_text']) ? $json_result['two_people_options']['couple_text'] : null;
            session()->put('back_to_back_text',$back_to_back_text);
            session()->put('couple_text',$couple_text);

            session()->put('multiple_people_headline',$json_result['multiple_people_headline']);
            session()->put('multiple_people_text',$json_result['multiple_people_text']);
            session()->put('quest_list',$guestList);
        }
        return $guestList;
    }

    public function saveReview(Request $request) {
        abort_if(!$request->ajax() ,response()->json(['message' => 'Request not allowed.'], 422),422);
        
        $rules = [
            'rating'  => 'required',
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
        $retingInfo = session()->get('ratingData');

        $rating = 0;
        if ($request->rating) {
            if($request->rating == "danger") $rating = 1;
            if($request->rating == "warning") $rating = 2;
            if($request->rating == "info") $rating = 3;
            if($request->rating == "primary") $rating = 4;
            if($request->rating == "success") $rating = 5;
        }
        $qualities = isset($request->qualities) ? $request->qualities : null;
        if($qualities) {
            if( !is_array($qualities)) {
                $qualities = [$qualities];
            }
        }

        $payload['student_id'] = session()->get('student_id');
        $payload['private_class_id'] = $retingInfo["private_class_id"];
        $payload['provider_id'] = $retingInfo["provider_id"];
        $payload['rating'] =  $rating;
        $payload['review'] = isset($request->review) ? $request->review : null;
        $payload['qualities'] = $qualities ? implode(",",$qualities) : [];
        $payload['additional_tip'] = isset($request->additional_tip) ? $request->additional_tip : null;
        $payload['token'] =  session()->get('token');
        
        $url = 'https://bigtoe.app/app/ClientSide_V1/submitProviderRating';
        $res = $this->curlRequestCall('post', $payload, $url);
        $json_result = json_decode($res, true);

        if ($json_result['ResponseMessage'] == 'SUCCESS') 
        {
            $response['response']['ResponseCode'] = $json_result['ResponseCode'];
            $response['messsage'] = $json_result['Comments'];
            $response['status'] = 200;

        }
        else
        {
            $response['status'] = 422; 
            $response['response']['ResponseCode'] = $json_result['ResponseCode'];
            $response['messsage'] = $json_result['Comments'];
        }

        return response()->json($response,$response['status']);
    }
    
    public function savePeople(Request $request)
    {
        abort_if(!$request->ajax() ,response()->json(['message' => 'Request not allowed.'], 422),422);

        $rules = [
            'first_name'  => 'required',
            'age'   => 'required',
            'gender'   => 'required',
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
        //$payload = $request->all();
        $payload['client_id'] = session()->get('student_id');
        $payload['firstname'] = $request->first_name;
        $payload['lastname'] = !empty($request->last_name) ? $request->last_name : "";
        $payload['gender'] =  $request->gender;
        $payload['age_range'] = $request->age;
        $payload['phone_no'] = $request->phone_no;
        $payload['country_code'] = "+".$request->country_code;
        $payload['iso_code'] = strtoupper($request->iso_code);
        $payload['token'] =  session()->get('token');

        $url = 'https://bigtoe.app/app/ClientSide_V1/saveClientGuest';
        $res = $this->curlRequestCall('post', $payload, $url);
        $json_result = json_decode($res, true);

        if ($json_result['ResponseMessage'] == 'SUCCESS') 
        {
            $people_list['list'] = $this->initPeopleList();
            $people_list['default'] = $this->initPeopleDefaultList();

            $addressListRender = view('booking.people.list',compact('people_list'))->render();
            $response['people_list_html'] = $addressListRender;
            $response['people_list'] = $people_list;
            $response['response']['ResponseCode'] = $json_result['ResponseCode'];
            $response['status'] = 200;

        }
        else
        {
            $response['status'] = 422; 
            $response['response']['ResponseCode'] = $json_result['ResponseCode'];
            $response['messsage'] = $json_result['Comments'];
        }
        $response['two_people_headline'] = session()->get('two_people_headline');
        $response['back_to_back_text'] = session()->get('back_to_back_text');
        $response['couple_text'] = session()->get('couple_text');
        $response['multiple_people_headline'] = session()->get('multiple_people_headline');
        $response['multiple_people_text'] = session()->get('multiple_people_text');
        $response['multiple_people_text'] = session()->get('multiple_people_text');
        $response['max_people_allow'] = session()->get('max_people_allow');
        return response()->json($response,$response['status']);
    }

    /**
     * This Method use for get booking option.
     * @param student_id,cat_id,skill_id,student_address_id
     * @return  DURATION,GENDER,TIME_SLOT
     * 
     * AFTER returned value select other filed will be display
     */
    public function getBookingOptions(Request $request)
    {
        abort_if(!$request->ajax() ,response()->json(['message' => 'Request not allowed.'], 422),422);
        session()->forget('client_list_id');
        $rules = [
            'student_id'    => 'required',
            'service_category_id'  => 'required',
            'address_id'        => 'required',  
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
        $payload = $request->all();

        $is_client_included = 1;
        $client_list = 0;
        $number_of_people = $request->number_of_people;
        if($request->service_category_id == 7)
        {
            $is_client_included = $request->is_client_included;
            $client_list = $request->client_list != 0 ?  $request->client_list : null;
        }
        
        session()->put('client_list_id',$client_list);

        $guestLists = session()->get('quest_list');
        $ppltLists = session()->get('people_list');
        $selectedGuest = session()->get('client_list_id');
        $selectedGuest = !empty($selectedGuest) ? explode(",",$selectedGuest) : [];
        $client_lists = [];
        if($guestLists)
        {
            foreach($guestLists as $t => $singleQuest)
            {
                if( in_array($singleQuest['guest_id'],$selectedGuest))
                {
                    $client_lists[$t]['guest_id'] = $singleQuest['guest_id'];
                    //$client_lists[$t]['preferred_gender'] = null;
                }
            }
        }


        $payload['student_id'] = session()->get('student_id');
        $payload['token'] =  session()->get('token');
        $payload['number_of_people'] = $number_of_people;
        $payload['is_client_included'] = $is_client_included;
        $payload['client_list'] = $client_lists;
        $payload['is_couple'] = $request->is_couple;
        $payload['is_rebook'] = 0;
        $payload['provider_id'] = "";

        session()->put('category_name',$request->service_category_name);
        session()->put('category_id',$request->service_category_id);
        session()->put('student_address_id',$request->address_id);
        
        session()->put('number_of_people',$number_of_people);
        session()->put('is_client_included', $is_client_included);
        session()->put('client_list',$client_lists);
        session()->put('is_couple',$payload['is_couple']);
        session()->put('is_couple_gender',$request->is_couple_gender);
        
        // print "<pre>";
        // print_r(session()->get('add_list'));
        $addSelected = isset(session()->get('add_list')[0][session()->get('student_address_id')]) ? session()->get('add_list')[0][session()->get('student_address_id')] : null;
        $response['add_listt'] = $addSelected != null ? $addSelected['street_address']. ' ' .$addSelected['city']. ' ' .$addSelected['state']. ' ' .$addSelected['zipcode'] : null;
        session()->put('street_address',$response['add_listt']);


        $url = 'https://bigtoe.app/app/ClientSide_V1/getBookingPreferences';
        $res = $this->curlRequestCall('post', $payload, $url);
        $json_result = json_decode($res, true);
        
        if ($json_result['ResponseMessage'] == 'SUCCESS') 
        {
            $result = $json_result['Result'];            
            $durations = $result['duration'];
            $gender = $result['provider_gender'];
        
            $response['status'] = 200;
            //            $response['response'] = $json_result;
            
            session()->put('tooltip_text',$json_result['header_information']);
            session()->put('cancellation_policy',$result['cancellation_policy']);
            
            $response['message'] = 'address added successfully.'; 
            $response['content']['duration'] = view('booking.booking-preferences.duration',compact('durations'))->render();
            if(session()->get('is_couple_gender') == 1)
            {
                $guestLists = session()->get('quest_list');
                $ppltLists = session()->get('people_list');
                $selectedGuest = session()->get('client_list_id');
                $selectedGuest = !empty($selectedGuest) ? explode(",",$selectedGuest) : [];
                $activeGuest = [];
                if($guestLists)
                {
                    foreach($guestLists as $singleQuest)
                    {
                        if( in_array($singleQuest['guest_id'],$selectedGuest))
                        {
                            $activeGuest[] = $singleQuest;
                        }
                    }
                }
                //print_r($ppltLists); die;
                $response['content']['gender']   = view('booking.booking-preferences.couple-gender',compact('gender','activeGuest','selectedGuest','ppltLists'))->render();
            }
            else
            {
                $response['content']['gender']   = view('booking.booking-preferences.gender',compact('gender'))->render();
            }
            $response['category_name']   = session()->get('category_name');
            $response['tooltip_text']   = session()->get('tooltip_text');
            $response['payload']   = $payload;
            //$response['content']['time_slots'] = view('booking.booking-preferences.time-slots',compact('finalSlots'))->render();
        }
        else
        {
            $response['status'] = 422; 
            $response['response']['ResponseCode'] = $json_result['ResponseCode'];
            $response['messsage'] = $json_result['Comments'];
        }
        return response()->json($response,$response['status']);
    }


    /**
     * After select durection,gender and skill on next step this method call
     */
    public function getDateOptions(Request $request)
    {
        abort_if(!$request->ajax() ,response()->json(['message' => 'Request not allowed.'], 422),422);

        $rules = [
            'student_id'    => 'required',
            'service_category_id'  => 'required',
            'skill_id'  => 'required',
            'address_id'        => 'required',  
            'duration'        => 'required',  
            'gender'        => 'required',  
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

        $payload = $request->all();
        $payload['student_id'] = session()->get('student_id');
        $payload['token'] =  session()->get('token');

        $number_of_people = session()->get('number_of_people');
        if($request->service_category_id == 1)
        {
            $number_of_people = $request->number_of_people;
            session()->put('number_of_people',$number_of_people);
        }

        $payload['number_of_people'] = session()->get('number_of_people');
        $payload['is_client_included'] = session()->get('is_client_included');
        $payload['client_list'] = !empty($request->client_list) ? $request->client_list : session()->get('client_list');
        $payload['is_couple'] = session()->get('is_couple');
        $payload['is_rebook'] = 0;
        $payload['provider_id'] = "";
        $payload['preferred_gender'] = $request->gender;

        session()->put('skill_name',$request->skill_name);
        session()->put('skill_id',$request->skill_id);
        session()->put('skill_img',$request->skill_img);
        session()->put('duration',$request->duration);
        session()->put('gender',$request->gender);
        session()->put('client_list',$payload['client_list']);

        //print_r($payload); die;
        $url = 'https://bigtoe.app/app/ClientSide_V1/getDateOptions';
        $res = $this->curlRequestCall('post', $payload, $url);
        $json_result = json_decode($res, true);
        //print_r($json_result);
        if ($json_result['ResponseMessage'] == 'SUCCESS') 
        {
            session()->forget('all_slot');

            $result = $json_result['Result'];            
            
            $startDate = $result['start_date'];
            $number_dates = $result['number_dates'];
            $summary_text = $result['summary_text'];
            $header_information = $result['header_information'];
            session()->put('summary_text',$summary_text);
            $first_date_times = $result['first_date_times'];
            $first_date['start_time'] = $first_date_times['first_start_time'];
            $first_date['step'] = $first_date_times['slot_length'];
            $first_date['session_duration'] = $first_date_times['session_duration'];
            $first_date['default_window_length'] = $first_date_times['default_window_length'];
            $first_date['end_time'] = $first_date_times['last_finish_time'];

            $second_date_times = $result['second_date_times'];
            $second_date['start_time'] =  $second_date_times['first_start_time'];
            $second_date['step'] =  $second_date_times['slot_length'];
            $second_date['session_duration'] =  $second_date_times['session_duration'];
            $second_date['default_window_length'] =  $second_date_times['default_window_length'];
            $second_date['end_time'] =  $second_date_times['last_finish_time'];

            $remaining_date_times = $result['remaining_date_times'];
            $remaining_date['start_time'] = $remaining_date_times['first_start_time'];
            $remaining_date['step'] = $remaining_date_times['slot_length'];
            $remaining_date['session_duration'] = $remaining_date_times['session_duration'];
            $remaining_date['default_window_length'] = $remaining_date_times['default_window_length'];
            $remaining_date['end_time'] = $remaining_date_times['last_finish_time'];

            $loopStartDate = \Carbon\Carbon::parse($startDate);
            $loopEndDate = \Carbon\Carbon::now()->addDays($number_dates);
            $endDate = $loopEndDate;

            $i=1;
            $timeRanges = [];
            while ($loopStartDate->lte($loopEndDate))
            {
                $dt = $loopStartDate->toDateString();
                if($i == 1)
                {
                    $this->allDates[$dt]['date'] = $dt;
                    $this->allDates[$dt]['start_time'] = $first_date['start_time'];
                    $this->allDates[$dt]['step'] =  $first_date['step'];
                    $this->allDates[$dt]['end_time'] = $first_date['end_time'];
                    $this->allDates[$dt]['session_duration'] = $first_date['session_duration'];
                    $this->allDates[$dt]['default_window_length'] = $first_date['default_window_length'];

                    $timeRanges['star_times'][] = $first_date['start_time'];
                    $timeRanges['end_times'][] = $first_date['end_time'];
                    $timeRanges['session_duration'] = $first_date['session_duration'];
                }
                elseif($i == 2)
                {
                    $this->allDates[$dt]['date'] = $dt;
                    $this->allDates[$dt]['start_time'] = $second_date['start_time'];
                    $this->allDates[$dt]['step'] =  $second_date['step'];
                    $this->allDates[$dt]['end_time'] = $second_date['end_time'];
                    $this->allDates[$dt]['session_duration'] = $second_date['session_duration'];
                    $this->allDates[$dt]['default_window_length'] = $second_date['default_window_length'];

                    $timeRanges['star_times'][] = $second_date['start_time'];
                    $timeRanges['end_times'][] = $second_date['end_time'];
                    $timeRanges['session_duration'] = $second_date['session_duration'];


                }
                else{
                    $this->allDates[$dt]['date'] = $dt;
                    $this->allDates[$dt]['start_time'] = $remaining_date['start_time'];
                    $this->allDates[$dt]['step'] =  $remaining_date['step'];
                    $this->allDates[$dt]['end_time'] = $remaining_date['end_time'];
                    $this->allDates[$dt]['session_duration'] = $remaining_date['session_duration'];
                    $this->allDates[$dt]['default_window_length'] = $remaining_date['default_window_length'];

                    $timeRanges['star_times'][] = $remaining_date['start_time'];
                    $timeRanges['end_times'][] = $remaining_date['end_time'];
                    $timeRanges['session_duration'] = $remaining_date['session_duration'];
                }

                session::push('all_slot',$this->allDates);
                $loopStartDate->addDay();
                $i++;    
            }
            //date_default_timezone_set("Europe/London");

            $sessionDuration = $timeRanges['session_duration'];
            $loopStartTime = min($timeRanges['star_times']);
            $loopEndTime = $this->convertTimeToSecond(max($timeRanges['end_times']),$sessionDuration);

            $timeSlots = range(strtotime($loopStartTime),strtotime($loopEndTime),$first_date['step']*60);
            
            $response['content']['date_slider'] = view('booking.booking-details.date-slider',compact('startDate','endDate','number_dates'))->render();
            $response['content']['time_slots'] = view('booking.booking-details.time-slots',compact('timeSlots'))->render();
            $response['summary_text'] = $summary_text;
            $response['header_information'] = $header_information;
            $response['payload']   = $payload;
            $response['status'] = 200;
        }
        else
        {
            $response['status'] = 422; 
            $response['response']['ResponseCode'] = $json_result['ResponseCode'];
            $response['messsage'] = $json_result['Comments'];
        }
        return response()->json($response,$response['status']);
    }

    private function convertTimeToSecond(string $time,$reduceSection=0)
    {
        $d = explode(':', $time);
        $sec = (($d[0] * 3600) + ($d[1] * 60))/60;
        if($reduceSection != 0)
        {
            $secnd = $sec - $reduceSection;
            $tt = gmdate("i:s", $secnd);
            return $tt;
        }
        else
        {
            return $sec;
        }
    }

    private function refreshDateOptions()
    {        
        $payload['student_id'] = session()->get('student_id');
        $payload['token'] =  session()->get('token');
        $payload['number_of_people'] = 1;
        $payload['is_client_included'] = 1;
        $payload['is_couple'] = 0;
        $payload['is_rebook'] = 0;
        $payload['provider_id'] = "";

        $payloaad['skill_name']  = session()->get('skill_name');
        $payloaad['skill_id']    = session()->get('skill_id');
        $payloaad['skill_img']   = session()->get('skill_img');
        $payloaad['duration']    = session()->get('duration');
        $payloaad['gender']      = session()->get('gender');

        //print_r($payload); die;
        $url = 'https://bigtoe.app/app/ClientSide_V1/getDateOptions';
        $res = $this->curlRequestCall('post', $payload, $url);
        $json_result = json_decode($res, true);
        if ($json_result['ResponseMessage'] == 'SUCCESS') 
        {
            session()->forget('all_slot');

            $result = $json_result['Result'];            
            
            $startDate = $result['start_date'];
            $number_dates = $result['number_dates'];
            session()->put('summary_text',$result['summary_text']);
            $first_date_times = $result['first_date_times'];
            $first_date['start_time'] = $first_date_times['first_start_time'];
            $first_date['step'] = $first_date_times['slot_length'];
            $first_date['session_duration'] = $first_date_times['session_duration'];
            $first_date['default_window_length'] = $first_date_times['default_window_length'];
            $first_date['end_time'] = $first_date_times['last_finish_time'];

            $second_date_times = $result['second_date_times'];
            $second_date['start_time'] =  $second_date_times['first_start_time'];
            $second_date['step'] =  $second_date_times['slot_length'];
            $second_date['session_duration'] =  $second_date_times['session_duration'];
            $second_date['default_window_length'] =  $second_date_times['default_window_length'];
            $second_date['end_time'] =  $second_date_times['last_finish_time'];

            $remaining_date_times = $result['remaining_date_times'];
            $remaining_date['start_time'] = $remaining_date_times['first_start_time'];
            $remaining_date['step'] = $remaining_date_times['slot_length'];
            $remaining_date['session_duration'] = $remaining_date_times['session_duration'];
            $remaining_date['default_window_length'] = $remaining_date_times['default_window_length'];
            $remaining_date['end_time'] = $remaining_date_times['last_finish_time'];

            $loopStartDate = \Carbon\Carbon::parse($startDate);
            $loopEndDate = \Carbon\Carbon::now()->addDays($number_dates);
            $endDate = $loopEndDate;

            $i=1;
            $timeRanges = [];
            while ($loopStartDate->lte($loopEndDate))
            {
                $dt = $loopStartDate->toDateString();
                if($i == 1)
                {
                    $this->allDates[$dt]['date'] = $dt;
                    $this->allDates[$dt]['start_time'] = $first_date['start_time'];
                    $this->allDates[$dt]['step'] =  $first_date['step'];
                    $this->allDates[$dt]['end_time'] = $first_date['end_time'];
                    $this->allDates[$dt]['session_duration'] = $first_date['session_duration'];
                    $this->allDates[$dt]['default_window_length'] = $first_date['default_window_length'];

                    $timeRanges['star_times'][] = $first_date['start_time'];
                    $timeRanges['end_times'][] = $first_date['end_time'];
                }
                elseif($i == 2)
                {
                    $this->allDates[$dt]['date'] = $dt;
                    $this->allDates[$dt]['start_time'] = $second_date['start_time'];
                    $this->allDates[$dt]['step'] =  $second_date['step'];
                    $this->allDates[$dt]['end_time'] = $second_date['end_time'];
                    $this->allDates[$dt]['session_duration'] = $second_date['session_duration'];
                    $this->allDates[$dt]['default_window_length'] = $second_date['default_window_length'];

                    $timeRanges['star_times'][] = $second_date['start_time'];
                    $timeRanges['end_times'][] = $second_date['end_time'];


                }
                else{
                    $this->allDates[$dt]['date'] = $dt;
                    $this->allDates[$dt]['start_time'] = $remaining_date['start_time'];
                    $this->allDates[$dt]['step'] =  $remaining_date['step'];
                    $this->allDates[$dt]['end_time'] = $remaining_date['end_time'];
                    $this->allDates[$dt]['session_duration'] = $remaining_date['session_duration'];
                    $this->allDates[$dt]['default_window_length'] = $remaining_date['default_window_length'];

                    $timeRanges['star_times'][] = $remaining_date['start_time'];
                    $timeRanges['end_times'][] = $remaining_date['end_time'];
                }

                session::push('all_slot',$this->allDates);
                $loopStartDate->addDay();
                $i++;    
            }
        }
    }


    public function renderTimeBaseDate(Request $request)
    {
        abort_if(!$request->ajax() ,response()->json(['message' => 'Request not allowed.'], 422),422);

        $rules = [
            'selected_date'    => 'required' 
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
        $this->refreshDateOptions();
        $selectedDate = $request->selected_date;
        $sessionSlot = session()->get('all_slot');
        
        $lastKey = key(array_slice($sessionSlot, -1, 1, true));
        $allDatesSlots =  $sessionSlot[$lastKey];
        //print_r($allDatesSlots);
        if(isset($allDatesSlots[$selectedDate]))
        {
            //print_r($allDatesSlots[$selectedDate]); 
            //$timeSlots = $allDatesSlots[$selectedDate];
            $sessionDuration = $allDatesSlots[$selectedDate]['session_duration'];
            $loopStartTime = $allDatesSlots[$selectedDate]['start_time'];
            $DfltLoopEndTime = $allDatesSlots[$selectedDate]['end_time'];
            $loopEndTime = $this->convertTimeToSecond($DfltLoopEndTime,$sessionDuration);
            $step = $allDatesSlots[$selectedDate]['step'];
            $defaultWindowLength = $allDatesSlots[$selectedDate]['default_window_length'];

            //$loopStartTimeSec = explode(":",$loopStartTime);
            //$startSecond = (ltrim($loopStartTimeSec[0],0)*60) + $loopStartTimeSec[1];
            $startSecond = $this->convertTimeToSecond($loopStartTime);
            //$loopEndTimeSec = explode(":",$loopEndTime);
            //$endSecond = (ltrim($loopEndTimeSec[0],0)*60) + $loopEndTimeSec[1];
            $endSecond = $this->convertTimeToSecond($DfltLoopEndTime);

            $timeSlots = range(strtotime($loopStartTime),strtotime($loopEndTime),$step*60);
            $response['content']['time_slots_start'] = view('booking.booking-details.time-slots',compact('timeSlots'))->render();

            $timeSlots = range(strtotime($loopStartTime),strtotime($DfltLoopEndTime),$step*60);
            $response['content']['time_slots_end'] = view('booking.booking-details.time-slots',compact('timeSlots'))->render();
            
            $response['status'] = 200; 
            $response['response']['ResponseCode'] = 1;
            $response['response']['sessionDuration'] = "0".$sessionDuration.":00";
            $response['startSecond'] = $startSecond;
            $response['endSecond'] = $endSecond;
            $response['step'] = (int) $step;
            $response['sessionDuration'] = (int) $sessionDuration;
            $response['messsage'] = $allDatesSlots;
            $response['summary_text'] = session()->get('summary_text');
            $response['default_window_length'] = $defaultWindowLength;

            return response()->json($response,$response['status']);
        }

    }
    

    private function checkSavedCard()
    {
        $payload['student_id'] = session()->get('student_id');
        $payload['client_id'] = session()->get('student_id');
        $payload['token'] =  session()->get('token');

        $url = 'https://bigtoe.app/app/ClientSide_V1/checkSavedCards';
        $res = $this->curlRequestCall('post', $payload, $url);
        $json_result = json_decode($res, true);

        $response['ResponseCode']  = 0;
        $response['list_of_cards']  = [];
        if ($json_result['ResponseMessage'] == 'SUCCESS') 
        {
            $results = $json_result['Result'];
            $response['response'] = $results;
            $response['ResponseCode'] = $json_result['ResponseCode'];
            
            $last4 = $list_of_cards = [];
            if($results) {
                foreach($results as $k => $result) {
                    $last4[$k] = $result['last4'];
                    $list_of_cards[$k]['last4'] =  $result['last4'];
                    $list_of_cards[$k]['is_default'] =  $result['is_default'];
                    $list_of_cards[$k]['id'] =  $result['id'];
                }
            }
            $response['digit_4'] = $last4;
            $response['list_of_cards'] = $list_of_cards;
            //$response['head_text'] = "Use the card ending with {$last4} to send your booking request? Your card is not being charged.";
            $response['head_text'] = "This will send your booking request to the providers in your location. Continue?";
            $response['message'] = $json_result['Comments'];
        }
        else
        {
            $response['ResponseCode']  = $json_result['ResponseCode'];
            $response['messsage'] = $json_result['Comments'];
        }
        //print_r($response); die;
        return $response;
    }

    public function summeryDetails(Request $request)
    {
        abort_if(!$request->ajax() ,response()->json(['message' => 'Request not allowed.'], 422),422);

        $rules = [
            "requested_date"   => "required",
            "requested_time"   => "required",  
            "finish_time"      => "required",  
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
        $payload = $request->all();
        $payload['student_id'] = session()->get('student_id');
        $payload['token'] =  session()->get('token');
        $payload['service_category_id'] = session()->get('category_id');
        $payload['address_id'] = session()->get('student_address_id');
        $payload['skill_id'] = session()->get('skill_id');
        $payload['requested_date'] = $request->requested_date;
        $payload['skill_id'] = session()->get('skill_id');
        $payload['preferred_gender'] = session()->get('gender');
        $payload['duration'] = session()->get('duration');
        $payload['coupon_code'] = '';
        $payload['requested_time'] = date("H:i", strtotime($request->requested_time));
        $payload['finish_time'] = date("H:i", strtotime($request->finish_time));

        $payload['number_of_people'] = session()->get('number_of_people');
        $payload['is_client_included'] = session()->get('is_client_included');
        $payload['client_list'] = session()->get('client_list');
        $payload['is_couple'] = session()->get('is_couple');

        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $request->requested_date);
        session()->put('requested_date',$date->format('M j'));

        //$requestedDateTime = $request->requested_date.' '.$request->requested_time.':00';

        session()->put('requested_time',$request->requested_time);
        session()->put('finish_time',$request->finish_time);

        $ppltxt = $endtxt = null;
        if(session()->get('category_id') == 7 && session()->get('number_of_people') > 1)
        {
            $ppltxt = session()->get('is_couple') == 1 ? "Couple," : session()->get('number_of_people')." People,";
            $endtxt = " each";
        }
        

        session()->put('duration_convert',$ppltxt.' '.str_replace("m"," mins",session()->get('duration')).$endtxt);
        session()->put('book_session_record',$payload);
        

        $url = 'https://bigtoe.app/app/ClientSide_V1/priceSession';
        $res = $this->curlRequestCall('post', $payload, $url);
        $json_result = json_decode($res, true);

        if ($json_result['ResponseMessage'] == 'SUCCESS') 
        {
            session()->put('net_cost',0);

            $returnCardList = $this->checkSavedCardResponse();
            
            $isCardSaved = $returnCardList["isCardSaved"];
            $response['is_card_saved'] = $isCardSaved;
            $response['head_text'] = $returnCardList["cardSavedText"];
            $response['added_card_list'] = $returnCardList["paymentAddedMethodHtml"];
            $response['last_4digit'] = $returnCardList["card4digits"];
            $response['payment_method_html'] = $returnCardList["paymentMethodHtml"];
            $response['defult_card_last4'] = $returnCardList["defult_card_last4"];
            $response['defult_card_id'] = $returnCardList["defult_card_id"];

            $result = $json_result['Result'];
            $response['status'] = 200;
            $response['response'] = $result;
            $response['ResponseCode'] = $json_result['ResponseCode'];
            $response['message'] = $json_result['Comments'];
            $prices = $result['price'];
            $netCost = $json_result['net_cost'];
            $parking = $json_result['parking'];
            session()->put('net_cost',$netCost);
            $response['category_name'] = session()->get('category_name');
            $response['skill_name']    = session()->get('skill_name');
            $response['skill_img'] = session()->get('skill_img');
            $response['summary_name']    = session()->get('category_name').' / '.session()->get('skill_name');
            $response['requested_date'] = session()->get('requested_date');
            $response['requested_time'] = session()->get('requested_time');
            $response['finish_time'] = session()->get('finish_time');
            $response['duration'] = trim(session()->get('duration_convert'));
            
            $response['street_address'] = session()->get('street_address');
            $response['short_street_address'] = Str::limit(session()->get('street_address'), 18);
            $response['number_of_people'] = session()->get('number_of_people');
            $response['cancellation_policy'] = session()->get('cancellation_policy');
            $response['content'] = view('booking.summery.summery-details',compact('prices','netCost','parking','isCardSaved','cardSavedText'))->render();
            $response['response']['ResponseCode'] = $json_result['ResponseCode'];
        }
        else
        {
            $response['status'] = 422; 
            $response['response']['ResponseCode'] = $json_result['ResponseCode'];
            $response['messsage'] = $json_result['Comments'];
        }
        
        return response()->json($response,$response['status']);
    }

     /**
     * tHIS FUNCTION FOR PREPARE CARD LIST
     */
    private function checkSavedCardResponse() {
        $checkCards = $this->checkSavedCard();
        $isCardSaved = false;
        $cardSavedText = null;
        $card4digits = $paymentMethodHtml = $paymentAddedMethodHtml = null; 
        $defult_card_number = $defult_card_last4 = $defult_card_id = null;
        if( isset($checkCards['ResponseCode']) && $checkCards['ResponseCode'] == 1  )
        {
            $isCardSaved = true;
            $cardSavedText = $checkCards['head_text'];
            $card4digits = $checkCards['digit_4'];
            $list_of_cards = $checkCards['list_of_cards'];
            
            foreach($list_of_cards as $k => $list_of_card) {
                $cardNumber = $list_of_card['last4'];
                $isCardDefault = $list_of_card['is_default'];
                $cardId = $list_of_card['id'];

                if ($k == 0 || $isCardDefault == true){
                    //Default Card Identify >> if is_defaulkt is true then save as default Otherwise >> ZERO index will be default
                    $defult_card_number =   '**** **** **** '.$cardNumber;
                    $defult_card_id =  $cardId;                    
                    $defult_card_last4 =  $cardNumber;                    
                }

                $paymentAddedMethodHtml .= '<span class="cart-head" id="card_head" > <strong data-last4="'.$cardNumber.'" data-id="'.$cardId.'" data-action="change_default"> **** **** **** '.$cardNumber.'</strong> <a href="javascript:void(0)" id="stripe_trash_btn"  data-id="'.$cardId.'" data-action="trash"> <i class="fa fa-trash"></i></a></span>';
            }
            $paymentMethodHtml .= '<label class="cart-head" id="display_added_payment_list" data-last4="'.$defult_card_last4.'" data-id="'.$defult_card_id.'">Payment Method&nbsp;<br /><small id="last_digit" >'.$defult_card_number.'</small><a href="javascript:void(0)" id="" style="display: block; margin-top: -7px;"><i class="fa fa-chevron-right"></i></a></label>';
        } else {
            $paymentMethodHtml .= '<label class="cart-head" id="stripe_add_btn" data-last4="'.$defult_card_last4.'" data-id="'.$defult_card_id.'">Payment Method&nbsp;<br /><small id="last_digit" >'.$defult_card_number.'</small><a href="javascript:void(0)" id="" style="display: block; margin-top: -7px;"><i class="fa fa-plus"></i></a></label>';
        }
        return [ 
                    "isCardSaved" => $isCardSaved, 
                    "paymentAddedMethodHtml" => $paymentAddedMethodHtml,
                    "paymentMethodHtml" => $paymentMethodHtml, 
                    "card4digits" => $card4digits, 
                    "cardSavedText" => $cardSavedText, 
                    "defult_card_last4" => $defult_card_last4, 
                    "defult_card_id" => $defult_card_id, 
                ];
    }

    /**
     * On add newcard this API will call and refresh check card list API
     */
    public function addOrUpdateCard(Request $request) {
        $rules = [ "stripeToken" => "required" ];
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
        
        $stripeToken = $request->stripeToken;

        $payload['student_id'] = session()->get('student_id');
        $payload['token'] =  session()->get('token');
        $payload['customer_token'] =  $stripeToken;
        $payload['payment_method_id'] =  $stripeToken;
        session()->put('customer_token',$stripeToken);
 
        $url = 'https://bigtoe.app/app/ClientSide_V1/addOrUpdateCard';
        $res = $this->curlRequestCall('post', $payload, $url);
        $json_result = json_decode($res, true);
        $response['response']['ResponseCode'] = $json_result['ResponseCode'];
        $response['message'] = $json_result['Comments'];

        $response['status'] = 422; 
        
        $response['is_card_saved'] = null;
        $response['head_text'] = null;
        $response['added_card_list'] = null;
        $response['last_4digit'] = null;
        $response['payment_method_html'] = null;
        if ($json_result['ResponseMessage'] == 'SUCCESS') 
        {
            $response['status'] = 200;
            $returnCardList = $this->checkSavedCardResponse();
            $response['is_card_saved'] = $returnCardList["isCardSaved"];
            $response['head_text'] = $returnCardList["cardSavedText"];
            $response['added_card_list'] = $returnCardList["paymentAddedMethodHtml"];
            $response['last_4digit'] = $returnCardList["card4digits"];
            $response['payment_method_html'] = $returnCardList["paymentMethodHtml"];
            $response['defult_card_last4'] = $returnCardList["defult_card_last4"];
            $response['defult_card_id'] = $returnCardList["defult_card_id"];

            return response()->json($response,$response['status']);
        }     
        return response()->json($response,$response['status']);
    }

    public function cardDelete(Request $request)
    {
        $rules = [ "client_id" => "required","payment_method_id" => "required" ];
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
        $payload['client_id'] = session()->get('student_id');
        $payload['token'] =  session()->get('token');
        $payload['payment_method_id'] =  $request->payment_method_id;
       

        $url = 'https://bigtoe.app/app/ClientSide_V1/deleteCard';
        $res = $this->curlRequestCall('post', $payload, $url);
        $json_result = json_decode($res, true);

        $response['response']['ResponseCode'] = $json_result['ResponseCode'];
        $response['message'] = $json_result['Comments'];
        $response['status'] = 422; 
        $response['is_card_saved'] = null;
        $response['head_text'] = null;
        $response['added_card_list'] = null;
        $response['last_4digit'] = null;
        $response['payment_method_html'] = null;

        
        if ($json_result['ResponseMessage'] == 'SUCCESS') 
        {
            $response['status'] = 200;
            $returnCardList = $this->checkSavedCardResponse();
            $response['is_card_saved'] = $returnCardList["isCardSaved"];
            $response['head_text'] = $returnCardList["cardSavedText"];
            $response['added_card_list'] = $returnCardList["paymentAddedMethodHtml"];
            $response['last_4digit'] = $returnCardList["card4digits"];
            $response['payment_method_html'] = $returnCardList["paymentMethodHtml"];
            $response['defult_card_last4'] = $returnCardList["defult_card_last4"];
            $response['defult_card_id'] = $returnCardList["defult_card_id"];

            return response()->json($response,$response['status']);
        }
        else
        {
            $response['status'] = 422; 
            $response['response']['ResponseCode'] = $json_result['ResponseCode'];
            $response['messsage'] = $json_result['Comments'];
        }
        return response()->json($response,$response['status']);

    }

    /**
     * THIS METHOD USE FOR FINAL REQUEST
     * If Your will come 1st time OR choose USE DIFF CARD option then this method will call
     * 
     * NOT USING NOW >> Delete It's once new flow work
     */
    public function stripeget(Request $request)
    {
        $rules = [
            "stripeToken"          => "required",
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
        
        $stripeToken = $request->stripeToken;

        $payload['student_id'] = session()->get('student_id');
        $payload['token'] =  session()->get('token');
        $payload['customer_token'] =  $stripeToken;
        session()->put('customer_token',$stripeToken);

        $url = 'https://bigtoe.app/app/ClientSide_V1/addCard';
        $res = $this->curlRequestCall('post', $payload, $url);
        $json_result = json_decode($res, true);

        if ($json_result['ResponseMessage'] == 'SUCCESS') 
        {
            $response = $this->bookSessionProcess();
            return redirect('booking/session/thank-you')->with('message', $response['message']);
        }
        else
        {
            $response['status'] = 422; 
            $response['response']['ResponseCode'] = $json_result['ResponseCode'];
            $response['message'] = $json_result['Comments'];
        }
        return redirect('booking/session/thank-you')->with('message', $response['message']);
    }

    /**
     * THIS METHOD USE FOR FINAL REQUEST
     * IF USE CONFIRM for existing card THEN THIS METHOD CALL FOR DO DIRECT BOOK SESSION
     */
    public function bookSession(Request $request)
    {
        abort_if(!$request->ajax() ,response()->json(['message' => 'Request not allowed.'], 422),422);

        $rules = [ "payment_method_id" => "required"];

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

        $response = $this->bookSessionProcess($request->payment_method_id);
        if($response['status'] == 200) {
            $response['redirect_url'] = \URL::to('booking/session/thank-you');
            return response()->json($response,$response['status']);
        }else {
            $returnCardList = $this->checkSavedCardResponse();
            $response['is_card_saved'] = isset($returnCardList["isCardSaved"]) ? $returnCardList["isCardSaved"] : false;
            $response['head_text'] = isset($returnCardList["cardSavedText"]) ? $returnCardList["cardSavedText"] : null;
            $response['added_card_list'] = isset($returnCardList["paymentAddedMethodHtml"]) ? $returnCardList["paymentAddedMethodHtml"] : null;
            $response['last_4digit'] = isset($returnCardList["card4digits"]) ? $returnCardList["card4digits"] : null;
            $response['payment_method_html'] = isset($returnCardList["paymentMethodHtml"]) ? $returnCardList["paymentMethodHtml"] : null;
            $response['defult_card_last4'] = $returnCardList["defult_card_last4"];
            $response['defult_card_id'] = $returnCardList["defult_card_id"];
            return response()->json($response,$response['status']);
        }
    }

    private function bookSessionProcess($payment_method_id)
    {
        $bookSessionRecord = session()->get('book_session_record');
        $bookSessionRecord['payment_method_id'] = $payment_method_id;
        $bookSessionRecord['client_id'] = session()->get('student_id');
        $bookSessionRecord['source_type'] = 'card';        
        
        //$url = 'https://bigtoe.app/app/ClientSide_V1/bookSession';
        $url = 'https://bigtoe.app/app/ClientSide_V1/bookAppointment';
        $res = $this->curlRequestCall('post', $bookSessionRecord, $url);
        $bookSessionResult = json_decode($res, true);
        $result = $bookSessionResult['Result'];

        $response['status'] = 200;
        $response['response'] = $bookSessionResult;
        $response['message'] = $bookSessionResult['Comments'];
        $requestId = $result['request_id'];                
        //$authorize_response = isset($result['authorize_response']) ? $result['authorize_response'] : null;
        $authorize_response = isset($bookSessionResult['authorize_response']) ? $bookSessionResult['authorize_response'] : null;
        //$url = 'https://bigtoe.app/app/ClientSide_V1/getProviders';
        $url = 'https://bigtoe.app/app/ClientSide_V1/sendRequestToProviders';
        $providerPayload = [
            'student_id' => session()->get('student_id'),
            'client_id' => session()->get('student_id'),
            'request_id' => $requestId,
            'token' => session()->get('token'),
            'number_of_people' => session()->get('number_of_people'),
            'is_client_included' => session()->get('is_client_included'),
            'client_list' => session()->get('client_list'),
            'authorize_response' => $authorize_response,
        ];
        $res = $this->curlRequestCall('post', $providerPayload, $url);

        if( $bookSessionResult['ResponseCode'] != 1)
        {
            $response['status'] = 422; 
            $response['response']['ResponseCode'] = $bookSessionResult['ResponseCode'];
            $response['messsage'] = $bookSessionResult['Comments'];
        }
        //$this->distroyAllSession();
        return $response;
    }

    public function thankYou()
    {
        return view('booking-thank-you');
    }
    
    private function distroyAllSession()
    {
        session()->forget('token');
        session()->forget('student_id');
        session()->forget('student_address_id');
        session()->forget('student_email');
        session()->forget('student_full_naame');
        session()->forget('token');
        session()->forget('requested_date');
        session()->forget('requested_time');
        session()->forget('duration');
        session()->forget('book_session_record');
        session()->forget('category_name');
        session()->forget('skill_name');
        session()->forget('customer_token');
        session()->forget('tooltip_text');
        session()->forget('number_of_people');
        session()->forget('category_id');
        session()->forget('street_address');
        session()->forget('is_client_included');
        session()->forget('client_list');
        session()->forget('client_list_id');
        session()->forget('is_couple');
    }


    /**
     * THIS METHOD MANAGE ALL CURL REQUEST FOR THIS CONTROLLER 
     */
    private function curlRequestCall($method = '', $data = '', $url = '')
    {
      if ($method == 'POST' || $method == 'post') 
      {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
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


    public function logOut(Request $request)
    {
        $payload['student_id'] = session()->get('student_id');
        $payload['token'] = session()->get('token');

        $url = 'https://bigtoe.app/app/Auth_V1/logout';
        $res = $this->curlRequestCall('post', $payload, $url);
        $json_result = json_decode($res, true);
        
        if ($json_result['ResponseCode'] == 1 && $json_result['ResponseMessage'] == 'SUCCESS') 
        {
            Session::forget('bookingAuth');
            $this->distroyAllSession();

            if( isset($request->do) && $request->do == "token" )
            {
                session()->flash('tokenmismatch','You have been logged out as you logged in on a different device');
               return redirect('booking/signin')->with('tokenmismatch', 'You have been logged out as you logged in on a different device');;
            }
            return redirect('booking/session');
        }

    }

    public function hotelList(Request $request)
    {
        $payload = [
            'token' => session()->get('token'),
            "client_id" => $request->client_id,
            "street_address" => $request->street_address,
            "city" => $request->city,
            "state" => $request->state,
            "zipcode" => $request->zipcode,
            "latitude" => $request->latitude,
            "longitude" => $request->longitude,
            "neighborhood" => $request->neighborhood
        ];
        $url = 'https://bigtoe.app/app/ClientSide_V1/getNearbyHotels';
        $res = $this->curlRequestCall('post', $payload, $url);
        $json_result = json_decode($res, true);
        $result = $json_result['Result'];

        if ($json_result['ResponseCode'] ==  1 ) 
        {
            $response['status'] = 200;
            $response['response']['ResponseCode'] = $json_result['ResponseCode'];
            $response['response'] = $result;
        }
        else
        {
            $response['status'] = 422; 
            $response['response']['ResponseCode'] = $json_result['ResponseCode'];
        }
        $response['message'] = $json_result['Comments'];
        return response()->json($response,$response['status']);
    }

    public function saveHotelAddress(Request $request) 
    {
		if (!$request->hotel_id) {
			$hotel_id='0';
		} else {
			$hotel_id=$request->hotel_id;
		}
		
        $payload = [
            'token' => session()->get('token'),
            "client_id" => $request->client_id,
            "temporary_address_id" => $request->temporary_address_id,
            "locationname" => $request->locationname,
            "unit_number" => $request->unit_number,
            "hotel_id" => $hotel_id
        ];

        $url = 'https://bigtoe.app/app/ClientSide_V1/saveHotelAddress';
        $res = $this->curlRequestCall('post', $payload, $url);
        $json_result = json_decode($res, true);
        //print_r($json_result); die;   
        $result = $json_result['Result'];

        if ($json_result['ResponseCode'] ==  1 ) 
        {
            $response['status'] = 200;
            $response['response']['ResponseCode'] = $json_result['ResponseCode'];
            $response['response'] = $result;
        }
        else
        {
            $response['status'] = 200; 
            $response['response']['ResponseCode'] = $json_result['ResponseCode'];
        }
        $response['message'] = $json_result['Comments'];
        return response()->json($response,$response['status']);

    }

}
