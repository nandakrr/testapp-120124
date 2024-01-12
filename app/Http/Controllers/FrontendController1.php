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
class FrontendController1 extends Controller
{

  function __construct()
  {
    //$this->middleware('checkauth');
  }

    public function home(Request $request){
    	$host = $request->getHttpHost();
       
    	if($host=='bigtoeyogaapp.com' || $host=='www.bigtoeyogaapp.com' || $host=='partners.bigtoe.yoga')
    	{
			
    		return redirect('login');
    	}
       
    	$seodata = DB::table('seo_details')->where('page_url','home')->first();
        //print_r($seodata); die('herererere');
        $data['title']   = isset($seodata->title)?$seodata->title:'';
        $data['desc']    = isset($seodata->description)?$seodata->description:'';
        $data['keyword'] = isset($seodata->keyword)?$seodata->keyword:'';
    //    print_r($data);die('herer');
    	return view('home',compact('data'));
    }

    public function landing(){
        return view('landing-page');
    }
	

	 public function landingmassage(Request $request) {
	   $values = [
			'url' => $request->fullUrl(), // Get the full URL of the request
			// Add other values for insertion if needed
		];
		
		$inserts = DB::table('visited_urls')->insert($values);		
		$ad_keyword = $request->get('ad_keyword');
		
		return view('landing-page-massage', ['ad_keyword' => $ad_keyword]);		 
	}


	
	
	public function landingmassage2(Request $request) {
		$url=$request->fullUrl();
		$values = [
			'url' => $url, 
		];
		
		$ad_keyword = $request->get('ad_keyword');		
		$campaign = $request->get('campaign');		
		
		if ($ad_keyword == 'mobile') {
			$headline='Spa-Quality Mobile Massage';
		} elseif ($ad_keyword == 'sameday') {
			$headline='In-home Same-Day Massage';
		} elseif ($ad_keyword == 'hotel') {
			$headline='Spa-Quality In-Room Massage Delivered to Your Hotel';
		} else {
			$headline='In-home Spa-Quality Massage';						
		}

		if ($campaign) { 		
		    $campaign_data = DB::table('google_ad_campaigns')->where('campaign_id',$campaign)->first();
			$city=$campaign_data->city_name;
			if ($city) {
				$headline=$headline.' in '.$city.' and Surrounding Areas for $99.99';
			} else {
				$headline=$headline.' for $99.99';
			}				
		} else {
			$headline=$headline.' for $99.99';
		}
		
		
	/*	
		if (empty($keyword)) {
			$ad_keyword = $request->get('ad_keyword');			
		} else {
			if (strpos(strtolower($keyword), 'today') !== false) {
				$headline='In-home Same-Day<br>Massage for $99.99';
				$ad_keyword = 'sameday';
				$values['redirect_ad_keyword']=$ad_keyword;
			} else {
				$ad_keyword=null;
			}
		}  */
		

		$inserts = DB::table('visited_urls')->insert($values);

		return view('landing-page-massage2', ['headline' => $headline, 'ad_keyword' => $ad_keyword]);
	}
	
	

	
/*	 public function landingyoga(Request $request) {
        $inserts=DB::table('totally_temp')->insert($values);            

		return view('landing-page-yoga');
	} */

	public function landingyoga(Request $request) {
    $values = [
        'url' => $request->fullUrl(), // Get the full URL of the request
        // Add other values for insertion if needed
    ];
    
    $inserts = DB::table('totally_temp')->insert($values);
    
    return view('landing-page-yoga');
}
	
	public function stripesuccess() {
		return view('stripe_setup_success');
	}
	
	public function stripefailure() {
		return view('stripe_setup_failure');
	}
	
	
    public function classes(){
    	$regionId=1;
    	$studio = DB::table('locations_mapped_regions')
    			 ->join('location', 'locations_mapped_regions.location_id', '=', 'location.location_id')
    			 ->join('studio_location', 'location.location_id', '=', 'studio_location.location_id')
    			 ->leftJoin('neighborhoods', 'location.neighborhood_id', '=', 'neighborhoods.neighborhood_id')
	              ->where('locations_mapped_regions.mapped_region_id',$regionId)
	              ->where('location.visible_to_student_loc','Yes')
	              ->select('location.name1 as name', 'location.address_1', 'location.address_2','location.picture','location.city','neighborhoods.name as neighborhoods_name')
	              ->get();
	    $cities = DB::table('mapped_regions')->get();
	    $seodata = DB::table('seo_details')->where('page_url','classes')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('classes',compact('studio','cities','data'));
    }

    public function corporate()
    {
        $seodata = DB::table('seo_details')->where('page_url','corporate')->first();
        $data=array();
        if(!empty($seodata))
        {
            $data['title']   = $seodata->title;
            $data['desc']    = $seodata->description;
            $data['keyword'] = $seodata->keyword;
        }        
        return view('corporate',compact('data'));
    }   
	
	
	public function events()
    {
        $seodata = DB::table('seo_details')->where('page_url','events')->first();
        $data=array();
        if(!empty($seodata))
        {
            $data['title']   = $seodata->title;
            $data['desc']    = $seodata->description;
            $data['keyword'] = $seodata->keyword;
        }        
        return view('events',compact('data'));
    }

    public function storeCorporate(Request $request)
    {
         $this->validate($request, [
        'first_name'   => 'required',
        'last_name'    => 'required',
        'company_name' => 'required',
        'email' => 'required',
        'job_title' => 'required',
        'city' => 'required',
        'no_of_employee' => 'required',
        ]);

        $values = array_except($request->all(), ['_token']);
        $inserts=DB::table('corporates')->insert($values);            
        if(!empty($inserts)){
         /******/  Mail::send('corporate-mail', ['firstname' => $request->first_name, 'lastname' => $request->last_name,'email'=>$request->email,'company_name'=>$request->company_name,'job_title'=>$request->job_title,'city'=>$request->city,'no_of_employee'=>$request->no_of_employee], function ($msg){
                    $msg->from('info@bigtoe.fit', 'BigToe Yoga');
                    $msg->to('deepak@bigtoe.yoga');
                    $msg->subject('Request from corporate form');
                }); /*****/
        return redirect()->back()->with('message','Your contact submitted successfully.');
        }else{
        return redirect()->back()->with('message', 'Something went wrong, Please try again.');
        }
    }
	
	public function storeEvents(Request $request)
    {
         $this->validate($request, [
        'first_name'   => 'required',
        'phone_no' => 'required',
        'email' => 'required',
        'city' => 'required',
        'date' => 'required',
        'services' => 'required',
        'no_of_employee' => 'required',
        ]);

        // $values = array_except($request->all(), ['_token']);
        // $inserts=DB::table('corporates')->insert($values);    
        
        $name = $request->first_name;
        $phone_no = $request->phone_no;
        $email = $request->email;
        $location = $request->city;
        $date = $request->date;
        $services_required = $request->services;
        $party_size = $request->no_of_employee;
        $message = $request->message;
        
        $values = array('name' => $name, 'phone_no' => $phone_no, 'email' => $email, 'location' => $location, 'date' => $date, 'services_required' => $services_required, 'party_size' => $party_size, 'message' => $message );
        $inserts = DB::table('event_requests')->insert($values);


        if(!empty($inserts))
        {
            Mail::send('event-mail', [
                'firstname' => $name,
                'phone_no' => $phone_no, 
                'email'=> $email,
                'city'=> $location,
                'date' => $date, 
                'services' => $services_required, 
                'no_of_employee'=> $party_size, 
                'message_res' => $message 
            ], 
            function ($msg) {
                $msg->from('info@bigtoe.yoga', 'BigToe Yoga');
                $msg->to('deepak@bigtoe.yoga');
                $msg->subject('Request for an event');
            });                    
            return redirect()->back()->with('message','Your request was submitted successfully and we will get back to you very shortly!');
        }
        else
        {
            return redirect()->back()->with('message', 'Something went wrong, Please try again.');
        }
    }

    public function getStudios($value='')
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $geopluginURL='http://www.geoplugin.net/php.gp?ip='.$ip;
        $unarr= file_get_contents($geopluginURL);     
        $AddArr = unserialize($unarr);  
        $lat = $AddArr['geoplugin_latitude'];
        $long = $AddArr['geoplugin_longitude'];

         $getNearBy = DB::select( DB::raw("SELECT location_id FROM ( SELECT *, ( ( ( acos( sin(( $lat * pi() / 180)) * sin(( `latitude` * pi() / 180)) + cos(( $lat * pi() /180 )) * cos(( `latitude` * pi() / 180)) * cos((( $long - `longitude`) * pi()/180))) ) * 180/pi() ) * 60 * 1.1515 ) as distance FROM `location` ) location WHERE visible_to_student_loc='Yes' order by distance") );

        $date = date('Y-m-d');
        $regionId=1;
        $studio = array();
        if(count($getNearBy)>0)
        {
            foreach($getNearBy as $row)
            {
                 $res = DB::table('locations_mapped_regions')
                 ->join('location', 'locations_mapped_regions.location_id', '=', 'location.location_id')
                 ->join('studio_location', 'location.location_id', '=', 'studio_location.location_id')
                 ->leftJoin('neighborhoods', 'location.neighborhood_id', '=', 'neighborhoods.neighborhood_id')
                  ->where('locations_mapped_regions.mapped_region_id',$regionId)
                  ->where('location.location_id',$row->location_id)
                  ->where('location.visible_to_student_loc','Yes')
                  ->select('location.location_id','location.name1 as name','location.description', 'location.address_1', 'location.address_2','location.picture','location.city','neighborhoods.name as neighborhoods_name')
                  ->first();
                  if(!empty($res))
                  {
                    array_push($studio, $res);
                  }                  
            }
        }

        $locationId = Location_mapped_regions::select('location_id')->where('mapped_region_id',$regionId)->get();

        $classes = DB::table('class')
                    ->join('teacher', 'class.teacher_id','=','teacher.teacher_id')
                    ->join('style', 'class.style_id','=','style.style_id')
                    ->join('location', 'class.location_id', '=', 'location.location_id')
                    ->leftJoin('neighborhoods', 'location.neighborhood_id', '=', 'neighborhoods.neighborhood_id')
                    ->whereIn('class.location_id',$locationId)
                    ->where('class.status','active')
                    ->where('class.date',$date)
                    ->where('location.visible_to_student_loc','Yes')
                    ->distinct('class.style_id')
                    ->orderBy('class.start_time','asc')
                    ->select('location.location_id','location.latitude','location.longitude','class.style_id','class.start_time','class.duration','teacher.firstname','teacher.lastname' ,'style.name as class_name','location.name1 as name','neighborhoods.name as neighborhoods_name')
                    ->get();

        $cities = DB::table('mapped_regions')->get();
        $seodata = DB::table('seo_details')->where('page_url','classes')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
        return view('studios',compact('studio','cities','data','classes'));
    }

    public function getStudioAjax($regionId=1)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $geopluginURL='http://www.geoplugin.net/php.gp?ip='.$ip;
        $unarr= file_get_contents($geopluginURL);     
        $AddArr = unserialize($unarr);  
        $lat = $AddArr['geoplugin_latitude'];
        $long = $AddArr['geoplugin_longitude'];

         $getNearBy = DB::select( DB::raw("SELECT location_id FROM ( SELECT *, ( ( ( acos( sin(( $lat * pi() / 180)) * sin(( `latitude` * pi() / 180)) + cos(( $lat * pi() /180 )) * cos(( `latitude` * pi() / 180)) * cos((( $long - `longitude`) * pi()/180))) ) * 180/pi() ) * 60 * 1.1515 ) as distance FROM `location` ) location WHERE visible_to_student_loc='Yes' order by distance") );

        $date = date('Y-m-d');
        $studio = array();
        if(count($getNearBy)>0)
        {
            foreach($getNearBy as $row)
            {
                 $res = DB::table('locations_mapped_regions')
                 ->join('location', 'locations_mapped_regions.location_id', '=', 'location.location_id')
                 ->join('studio_location', 'location.location_id', '=', 'studio_location.location_id')
                 ->leftJoin('neighborhoods', 'location.neighborhood_id', '=', 'neighborhoods.neighborhood_id')
                  ->where('locations_mapped_regions.mapped_region_id',$regionId)
                  ->where('location.location_id',$row->location_id)
                  ->where('location.visible_to_student_loc','Yes')
                  ->select('location.location_id','location.name1 as name','location.description', 'location.address_1', 'location.address_2','location.picture','location.city','neighborhoods.name as neighborhoods_name')
                  ->first();
                  if(!empty($res))
                  {
                    array_push($studio, $res);
                  }                  
            }
        }

        $locationId = Location_mapped_regions::select('location_id')->where('mapped_region_id',$regionId)->get();

        $classes = DB::table('class')
                    ->join('teacher', 'class.teacher_id','=','teacher.teacher_id')
                    ->join('style', 'class.style_id','=','style.style_id')
                    ->join('location', 'class.location_id', '=', 'location.location_id')
                    ->leftJoin('neighborhoods', 'location.neighborhood_id', '=', 'neighborhoods.neighborhood_id')
                    ->whereIn('class.location_id',$locationId)
                    ->where('class.status','active')
                    ->where('class.date',$date)
                    ->where('location.visible_to_student_loc','Yes')
                    ->distinct('class.style_id')
                    ->orderBy('class.start_time','asc')
                    ->select('location.location_id','class.style_id','class.start_time','class.duration','teacher.firstname','teacher.lastname' ,'style.name as class_name','location.name1 as name','neighborhoods.name as neighborhoods_name')
                    ->get();
	    return view('studio-filter',compact('studio','classes'));
    }

    public function getLatLong($regionId=1)
    {

        $ip = $_SERVER['REMOTE_ADDR'];
        $geopluginURL='http://www.geoplugin.net/php.gp?ip='.$ip;
        $unarr= file_get_contents($geopluginURL);     
        $AddArr = unserialize($unarr);  
        $lat = $AddArr['geoplugin_latitude'];
        $long = $AddArr['geoplugin_longitude'];

         $getNearBy = DB::select( DB::raw("SELECT location_id FROM ( SELECT *, ( ( ( acos( sin(( $lat * pi() / 180)) * sin(( `latitude` * pi() / 180)) + cos(( $lat * pi() /180 )) * cos(( `latitude` * pi() / 180)) * cos((( $long - `longitude`) * pi()/180))) ) * 180/pi() ) * 60 * 1.1515 ) as distance FROM `location` ) location WHERE visible_to_student_loc='Yes' order by distance") );
        $studio = array();
        if(count($getNearBy)>0)
        {
            foreach($getNearBy as $row)
            {
                 $res = DB::table('locations_mapped_regions')
                 ->join('location', 'locations_mapped_regions.location_id', '=', 'location.location_id')
                 ->join('studio_location', 'location.location_id', '=', 'studio_location.location_id')
                 ->leftJoin('neighborhoods', 'location.neighborhood_id', '=', 'neighborhoods.neighborhood_id')
                  ->where('locations_mapped_regions.mapped_region_id',$regionId)
                  ->where('location.location_id',$row->location_id)
                  ->where('location.visible_to_student_loc','Yes')
                  ->select('location.location_id','location.name1 as name','location.description', 'location.address_1', 'location.address_2','location.picture','location.city','neighborhoods.name as neighborhoods_name','location.latitude','location.longitude')
                  ->first();
                  if(!empty($res))
                  {
                    array_push($studio, $res);
                  }                  
            }
        }
        return $studio;
    }

    public function getStudiodetail($url='')
    {
        $temp1 = $url;
        $date = date('Y-m-d');

        //get studio details
        $studio = $this->getLocationId($temp1); 
        $classes = DB::table('class')
                    ->join('teacher', 'class.teacher_id','=','teacher.teacher_id')
                    ->join('style', 'class.style_id','=','style.style_id')
                    ->where('class.location_id',$studio->location_id)
                    ->where('class.status','active')
                    ->where('class.date',$date)
                    ->distinct('class.style_id')
                    ->orderBy('class.start_time','asc')
                    ->select('class.style_id','class.start_time','class.duration','teacher.firstname','teacher.lastname' ,'style.name as class_name')
                    ->get();

        $classesname = DB::table('class')
                    ->join('teacher', 'class.teacher_id','=','teacher.teacher_id')
                    ->join('style', 'class.style_id','=','style.style_id')
                    ->where('class.location_id',$studio->location_id)
                    ->where('class.status','active')
                    ->distinct('class.style_id')
                    ->select('class.style_id','class.start_time','class.duration','teacher.firstname','teacher.lastname' ,'style.name as class_name')
                    ->get();
        $locId = $studio->location_id;
        $lat = $studio->latitude;
        $long = $studio->longitude;
        $getNearBy = DB::select( DB::raw("SELECT location_id FROM ( SELECT *, ( ( ( acos( sin(( $lat * pi() / 180)) * sin(( `latitude` * pi() / 180)) + cos(( $lat * pi() /180 )) * cos(( `latitude` * pi() / 180)) * cos((( $long - `longitude`) * pi()/180))) ) * 180/pi() ) * 60 * 1.1515 ) as distance FROM `location` ) location WHERE distance <= 50 and location_id!=$locId and visible_to_student_loc='Yes' order by distance ASC LIMIT 3") );
        $datarj = collect($getNearBy)->map(function($x){ return (array) $x; })->toArray();         
        $studios = DB::table('locations_mapped_regions')
                 ->leftJoin('location', 'locations_mapped_regions.location_id', '=', 'location.location_id')
                 ->leftJoin('neighborhoods', 'location.neighborhood_id', '=', 'neighborhoods.neighborhood_id')
                  ->where('location.visible_to_student_loc','Yes')
                  ->whereIn('location.location_id',$datarj)
                  ->select('location.name1 as name','location.description', 'location.address_1', 'location.address_2','location.picture','location.city','neighborhoods.name as neighborhoods_name')
                  ->get();
        $style = array();
        $classUrl = '';

        $data['title']   = $studio->name1.', '.$studio->city.' - Book classes on Bigtoe app';
        $data['desc']    = $studio->name1.', '.$studio->city.' - Browse schedule, check out teachers and book classes at discounted prices';
        return view('studio-details',compact('studio','classes','classesname','style','classUrl','temp1','data','studios'));
    }

    public function getStudioClasses($temp1,$temp2,$className)
    {   
        $styleId='';
        $classUrl = $className;
        
        $className = str_replace('-', ' ', $className);
        $className = str_replace(',', '-', $className);

        $date = date('Y-m-d');
        $studio = $this->getLocationId($temp1);
        $locationId = $studio->location_id;
        $style = DB::table('style')
                  ->where('name', 'like', '%'.$className.'%')
                  ->where('name', 'like', $className.'%')
                  ->where('name', 'like', '%'.$className)
                  ->first();  

        $classes = DB::table('class')
                    ->join('teacher', 'class.teacher_id','=','teacher.teacher_id')
                    ->join('style', 'class.style_id','=','style.style_id')
                    ->where('class.location_id',$locationId)
                    ->where('class.date',$date)
                    ->where(function($classes) use ($className)
                    {
                        if($className!='all')
                        {
                            $classes->where('style.name', 'like', '%'.$className.'%');
                            $classes->where('style.name', 'like', $className.'%');
                            $classes->where('style.name', 'like', '%'.$className);
                        }                       
                    })
                    ->where('class.status','active')
                    ->distinct('class.style_id')
                    ->orderBy('class.start_time','asc')
                    ->select('class.style_id','class.start_time','class.duration','teacher.firstname','teacher.lastname' ,'style.name as class_name')
                    ->get();

        $classesname = DB::table('class')
                    ->join('teacher', 'class.teacher_id','=','teacher.teacher_id')
                    ->join('style', 'class.style_id','=','style.style_id')
                    ->where('class.location_id',$locationId)
                    ->where('class.status','active')
                    ->distinct('class.style_id')
                    ->select('class.style_id','class.start_time','class.duration','teacher.firstname','teacher.lastname' ,'style.name as class_name')
                    ->get();
        $locId = $studio->location_id;
        $lat = $studio->latitude;
        $long = $studio->longitude;
        $getNearBy = DB::select( DB::raw("SELECT location_id FROM ( SELECT *, ( ( ( acos( sin(( $lat * pi() / 180)) * sin(( `latitude` * pi() / 180)) + cos(( $lat * pi() /180 )) * cos(( `latitude` * pi() / 180)) * cos((( $long - `longitude`) * pi()/180))) ) * 180/pi() ) * 60 * 1.1515 ) as distance FROM `location` ) location WHERE distance <= 50 and location_id!=$locId and visible_to_student_loc='Yes' order by distance ASC LIMIT 3") );
        $datarj = collect($getNearBy)->map(function($x){ return (array) $x; })->toArray();         
        $studios = DB::table('locations_mapped_regions')
                 ->leftJoin('location', 'locations_mapped_regions.location_id', '=', 'location.location_id')
                 ->leftJoin('neighborhoods', 'location.neighborhood_id', '=', 'neighborhoods.neighborhood_id')
                  ->where('location.visible_to_student_loc','Yes')
                  ->whereIn('location.location_id',$datarj)
                  ->select('location.name1 as name','location.description', 'location.address_1', 'location.address_2','location.picture','location.city','neighborhoods.name as neighborhoods_name')
                  ->get();
        return view('studio-details',compact('studio','classes','classesname','style','classUrl','temp1','studios'));
    }

    public function getLocationId($url='')
    {
        //this table used for routes
        if(session()->has('urlCollection'))
        {
            $urlCollection =  session()->get('urlCollection');
            if(count($urlCollection)>0)
            {
                foreach($urlCollection as $key=>$value)
                {
                    $check = DB::table('page_urls')->where('index_name',$value)->first();
                    if(!empty($check))
                    {
                        DB::table('page_urls')->where('id',$check->id)->update(['index_value'=>$key]);
                    }else{
                        DB::table('page_urls')->insert(['index_name'=>$value,'index_value'=>$key]);
                    }
                }
            }
        }

        //get url location from page_urls table
        $urldata = DB::table('page_urls')->where('index_value',$url)->first();
        $name = 'illumina east yoga';
        if(!empty($urldata))
        {
            $url = str_replace('-', ' ', $urldata->index_name);
            $name = str_replace(',', '-', $url);
        }
        
        $date = date('Y-m-d');
        $studio = DB::table('location')
                  ->where('name1', 'like', '%'.$name.'%')
                  ->where('name1', 'like', $name.'%')
                  ->where('name1', 'like', '%'.$name)
                  ->first();
        return $studio;
    }

    public function getStudioClassesdatewise($date,$regionId)
    {   
        $locationId = Location_mapped_regions::select('location_id')->where('mapped_region_id',$regionId)->get();

        $classes = DB::table('class')
                    ->join('teacher', 'class.teacher_id','=','teacher.teacher_id')
                    ->join('style', 'class.style_id','=','style.style_id')
                    ->join('location', 'class.location_id', '=', 'location.location_id')
                    ->leftJoin('neighborhoods', 'location.neighborhood_id', '=', 'neighborhoods.neighborhood_id')
                    ->whereIn('class.location_id',$locationId)
                    ->where('location.visible_to_student_loc','Yes')
                    ->where('class.status','active')
                    ->where('class.date',$date)
                    ->distinct('class.style_id')
                    ->orderBy('class.start_time','asc')
                    ->select('location.location_id','class.style_id','class.start_time','class.duration','teacher.firstname','teacher.lastname' ,'style.name as class_name','location.name1 as name','neighborhoods.name as neighborhoods_name')
                    ->get();
        return view('class-date-wise-filter',compact('classes','date'));
    }


    public function changeDateHeader($date)
    {   
        return view('professional-date-picker',compact('date'));
    }

    public function getStudioClassesdate($date,$styleId,$locationId)
    {   
        $classes = DB::table('class')
                    ->join('teacher', 'class.teacher_id','=','teacher.teacher_id')
                    ->join('style', 'class.style_id','=','style.style_id')
                    ->where('class.location_id',$locationId)
                    ->where(function($classes) use ($styleId)
                    {
                        if($styleId!='all')
                        {
                            $classes->where('class.style_id',$styleId);
                        }                       
                    })
                    ->where('class.date',$date)
                    ->where('class.status','active')
                    ->distinct('class.style_id')
                    ->orderBy('class.start_time','asc')
                    ->select('class.style_id','class.start_time','class.duration','teacher.firstname','teacher.lastname' ,'style.name as class_name')
                    ->get();
        return view('class-filter',compact('classes','date'));
    }

    public function appointments(){
       
        $seodata = DB::table('seo_details')->where('page_url','appointments')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('appointments',compact('data'));
    }
	
	public function howitworks(){
       
        $seodata = DB::table('seo_details')->where('page_url','how-it-works')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('howitworks',compact('data'));
    }
	
	public function massageLosAngeles(){
       
        $seodata = DB::table('seo_details')->where('page_url','massage-los-angeles')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('massage-los-angeles',compact('data'));
    }

	public function massageMiami(){
       
        $seodata = DB::table('seo_details')->where('page_url','massage-miami')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('massage-miami',compact('data'));
    }


	public function massageHouston(){
       
        $seodata = DB::table('seo_details')->where('page_url','massage-houston')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('massage-houston',compact('data'));
    }


	public function massagePhoenix(){
       
        $seodata = DB::table('seo_details')->where('page_url','massage-phoenix')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('massage-phoenix',compact('data'));
    }	
		
	public function massageAustin(){
       
        $seodata = DB::table('seo_details')->where('page_url','massage-austin')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('massage-austin',compact('data'));
    }	
	
	public function temp(){
       
       
    	return view('temp',compact('data'));
    }	
		
	public function massageDallas(){
       
        $seodata = DB::table('seo_details')->where('page_url','massage-dallas')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('massage-dallas',compact('data'));
    }	
	
	public function massageLasVegas(){
       
        $seodata = DB::table('seo_details')->where('page_url','massage-las-vegas')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('massage-las-vegas',compact('data'));
    }	
	
	public function massageNewYork(){
       
        $seodata = DB::table('seo_details')->where('page_url','massage-new-york')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('massage-new-york',compact('data'));
    }	
	
	public function massageSanFrancisco(){
       
        $seodata = DB::table('seo_details')->where('page_url','massage-san-francisco')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('massage-san-francisco',compact('data'));
    }
	
	public function massageSanDiego(){
       
        $seodata = DB::table('seo_details')->where('page_url','massage-san-diego')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('massage-san-diego',compact('data'));
    }
	
	public function privateYogaLosAngeles(){
       
        $seodata = DB::table('seo_details')->where('page_url','private-yoga-los-angeles')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('private-yoga-los-angeles',compact('data'));
    }

	public function privateYogaMiami(){
       
        $seodata = DB::table('seo_details')->where('page_url','private-yoga-miami')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('private-yoga-miami',compact('data'));
    }


	public function privateYogaHouston(){
       
        $seodata = DB::table('seo_details')->where('page_url','private-yoga-houston')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('private-yoga-houston',compact('data'));
    }


	public function privateYogaPhoenix(){
       
        $seodata = DB::table('seo_details')->where('page_url','private-yoga-phoenix')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('private-yoga-phoenix',compact('data'));
    }	
		
	public function privateYogaAustin(){
       
        $seodata = DB::table('seo_details')->where('page_url','private-yoga-austin')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('private-yoga-austin',compact('data'));
    }	
		
	public function privateYogaDallas(){
       
        $seodata = DB::table('seo_details')->where('page_url','private-yoga-dallas')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('private-yoga-dallas',compact('data'));
    }	
	
	public function privateYogaSanFrancisco(){
       
        $seodata = DB::table('seo_details')->where('page_url','private-yoga-san-francisco')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('private-yoga-san-francisco',compact('data'));
    }	
	
	public function privateYogaNewYork(){
       
        $seodata = DB::table('seo_details')->where('page_url','private-yoga-new-york')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('private-yoga-new-york',compact('data'));
    }	
	
	public function privateYogaSanDiego(){
       
        $seodata = DB::table('seo_details')->where('page_url','private-yoga-san-diego')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('private-yoga-san-diego',compact('data'));
    }

    public function contact(){

    	$seodata = DB::table('seo_details')->where('page_url','contact')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('contact',compact('data'));
    }

    public function partnerprovider(){
    	$seodata = DB::table('seo_details')->where('page_url','become-a-partner-provider')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('partnerprovider',compact('data'));
    }
	
	public function bookSession(){
    	/* $seodata = DB::table('seo_details')->where('page_url','book-session')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('bookSession',compact('data')); */
    }


    public function partnerstudio(){

    	$seodata = DB::table('seo_details')->where('page_url','become-a-partner-studio')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('partnerstudio',compact('data'));
    }

    public function privacypolicy(){
        
        $seodata = DB::table('seo_details')->where('page_url','privacy')->first();
        $data['title']   = $seodata->title;
        $data['desc']    = $seodata->description;
        $data['keyword'] = $seodata->keyword;
    	return view('privacypolicy',compact('data'));
    }

    public function termsofcondition(){
    	return view('termsofcondition');
    }
	
	public function providertermsofuse(){
    	return view('providertermsofuse');
    }

	/*************Contact-Post******************/	
	public function contactpost(Request $request){
	  $this->validate($request, [
        'firstname' => 'required',
        'lastname' => 'required',
        'email' => 'required'
        ]);
		
	  $request_data = $request->all();
	  $firstname 	= $request->firstname;
	  $lastname 	= $request->lastname;
	  $email 		= $request->email;
	  $subject 		= $request->subject;
	  $message 		= $request->message;
	  
	  $inserts=DB::table('contact')->insert(['firstname' => $firstname,'lastname' => $lastname, 'email' => $email,'subject'=>$subject,'message'=>$message]);
	  
	 /******/  Mail::send('contactmail', ['firstname' => $firstname, 'lastname' => $lastname,'email'=>$email,'subject'=>$subject,'bodytext'=> $message], function ($msg){
					$msg->from('info@bigtoe.fit', 'BigToe Yoga');
					$msg->to('info@bigtoe.yoga');
					$msg->subject('Contact form detail | BigToe Yoga');
				}); /*****/
	  
		if(!empty($inserts)){
		return redirect('contact')->with('message','Your contact submitted succesfully.');
		}else{
		return redirect()->back()->with('message', 'Something went wrong, Please try again.');
		}
 }
 /*************STUDIOPOST*******************/
	public function studiopost(Request $request){
	  $this->validate($request, [
        'firstname' => 'required',
        'lastname' => 'required',
        'email' => 'required',
        'studioname' => 'required'
        ]);
		
	  $request_data = $request->all();
	  $firstname    = trim($request->firstname);
	  $lastname     = trim($request->lastname);
	  $email        = trim($request->email);
	  $phone        = trim($request->phone);
	  $studioname   = trim($request->studioname);
	  $website      = trim($request->website);
	  
	  $inserts=DB::table('partnerstudio')->insert(['firstname' => $firstname,'lastname' => $lastname, 'email' => $email,'phone'=>$phone,'studioname'=>$studioname,'website'=>$website]);
	  
	 /******/  Mail::send('studiomail', ['firstname' => $firstname, 'lastname' => $lastname,'email'=>$email,'phone'=>$phone,'studioname'=>$studioname,'website'=>$website], function ($msg){
					$msg->from('info@bigtoe.fit', 'BigToe Yoga');
					$msg->to('deepak@bigtoe.yoga');
					$msg->subject('Request for becoming a partner studio | BigToe Yoga');
				}); /*****/
	  
		if(!empty($inserts)){
		return redirect('become-a-partner-studio/#studio')->with('message','Your contact submitted succesfully.');
		}else{
		return redirect('become-a-partner-studio/#studio')->with('message', 'Something went wrong, Please try again.');
		}
 }
 /***************/

 /*************PROVIDERPOST*******************/
	public function providerpost(Request $request){
	  $this->validate($request, [
        'firstname' => 'required',
        'lastname' => 'required',
        'email' => 'required',
        'gender' => 'required',
        'expertise' => 'required',
        'phone' => 'required',
        'city' => 'required',
        'liability_insured' => 'required',
        'certified_licensed' => 'required',
        'level_of_training' => 'required',
        'experience' => 'required'
        ]);
		
	  //$request_data 	= $request->all();
	  $firstname 		= ucfirst(trim($request->firstname));
	  $lastname 		= ucfirst(trim($request->lastname));
	  $email 			= trim($request->email);
	  $phone 			= trim($request->phone);
	  $gender 			= trim($request->gender);
	  $city 			= ucfirst(trim($request->city));
	  $expertise 		= trim($request->expertise);
	  $liability_insured  = trim($request->liability_insured);
	  $certified_licensed = trim($request->certified_licensed);
	  $level_of_training  = trim($request->level_of_training);
	  $experience         = trim($request->experience);
	  
	  $inserts=DB::table('providers')->insert(['firstname' => $firstname,'lastname' => $lastname, 'email' => $email,'phone'=>$phone,'gender'=>$gender,'city'=>$city,'expertise'=>$expertise,'liability_insured'=>$liability_insured,'certified_licensed'=>$certified_licensed,'level_of_training'=>$level_of_training,'experience'=>$experience]);
	  
	/* Mail::send('providermail', ['firstname' => $firstname,'lastname' => $lastname, 'email' => $email,'phone'=>$phone,'gender'=>$gender,'city'=>$city,'expertise'=>$expertise,'liability_insured'=>$liability_insured,'certified_licensed'=>$certified_licensed,'level_of_training'=>$level_of_training,'experience'=>$experience], function ($msg){
					$msg->from('info@bigtoe.fit', 'BigToe Yoga');
					$msg->to('deepak@bigtoe.yoga');
					$msg->subject('Request for becoming a partner provider | BigToe Yoga');
				}); /*****/
	  
		if(!empty($inserts)){
		return redirect('become-a-partner-provider/#provider')->with('message','Your information has been submitted succesfully.');
		}else{
		return redirect('become-a-partner-provider/#provider')->with('message', 'Something went wrong, Please try again.');
		}
 }
 
 
 public function createClientAccount(Request $request){ 
     
	  $this->validate($request, [
        'firstname' => 'required',
        'lastname' => 'required',
        'email' => 'required',
        'gender' => 'required',
        'phone' => 'required',
        'age' => 'required'    
        ]);
		
		
	  //$request_data 	= $request->all();
	  $firstname 		= ucfirst(trim($request->firstname));
	  $lastname 		= ucfirst(trim($request->lastname));
	  $email 			= trim($request->email);
	  $phone 			= trim($request->phone);
	  $gender 			= trim($request->gender);
	  $city 			= trim($request->a);

	//	return redirect('book-session/')->with('message','Your contact submitted succesfully.');
		return redirect('appointments-mapold/');
die;  
	  		echo "Heere";die;

	 $inserts=DB::table('providers')->insert(['firstname' => $firstname,'lastname' => $lastname, 'email' => $email,'phone'=>$phone,'gender'=>$gender,'city'=>$city,'expertise'=>$expertise,'liability_insured'=>$liability_insured,'certified_licensed'=>$certified_licensed,'level_of_training'=>$level_of_training,'experience'=>$experience]);
	  
	
	  
		if(!empty($inserts)){
		return redirect('become-a-partner-provider/#provider')->with('message','Your contact submitted succesfully.');
		}else{
		return redirect('become-a-partner-provider/#provider')->with('message', 'Something went wrong, Please try again.');
		}
 }

 /***************/

}
