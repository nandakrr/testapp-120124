<?php
namespace App\Http\Controllers\studio;
use App\Http\Controllers\Controller;
use App\StudentClass;
use App\StudioClass;
use App\StudioLocation;
use App\StudioTeacher;
use App\Style;
use App\StylePrice;
use App\Teacher;
use App\Studio;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use DateInterval;
use DB;

class ClassController extends Controller
{
 public $studio_id;
 public function __construct()
 {
  $this->middleware('checkauth');
 }
 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index(Request $request)
 {
  $location = $request->input('locationId');
  $date     = $cdate = $request->input('date');

  $auth_session    = \Session::get('auth');
  $studio_id       = $auth_session['admin_id'];
  $this->studio_id = $studio_id;
  $locations       = StudioLocation::with('Location')->where('studio_id', $this->studio_id)->get();
  $studio_teachers = StudioTeacher::with('Teacher')->where('studio_id', $this->studio_id)->get();

  $teachers     = Teacher::pluck('email');
  $styles       = Style::where('studio_id', $this->studio_id)->get();


  $studio_class = StudioClass::with(['Style', 'teacher:teacher_id,firstname,lastname', 'student_class' => function ($query) {
   $query->with('student:student_id,firstname,lastname');
  }])->where([['status', '!=', 'cancelled']]);

  $pending_class = StudioClass::where('status', 'pending');
  if (isset($location)) {
   $studio_class->where('location_id', $location);
   $pending_class->where('location_id', $location);
  } elseif ($locations && isset($locations[0])) {
   $studio_class->where('location_id', $locations[0]->location_id);
   $pending_class->where('location_id', $locations[0]->location_id);
  }
  if (isset($date)) {
   $date1 = json_decode($request->input('date'));
   if (is_array($date1)) {
    $studio_class->whereBetween('date', [date('Y-m-d', strtotime($date1[0])), date('Y-m-d', strtotime($date1[1]))]);
    $pending_class->whereBetween('date', [date('Y-m-d', strtotime($date1[0])), date('Y-m-d', strtotime($date1[1]))]);
   } else {
      $customFromDate = date('Y-m-d', strtotime($date));
      $date           = new \DateTime($customFromDate.' 11:59 PM');
      //$date->setTimezone(new \DateTimeZone('UTC'));
      
      $customToDate = $date->format('Y-m-d');

      /* Custom Calculation for UTC time end here*/

      $studio_class->whereBetween('date', [$customFromDate,$customToDate]);
      $pending_class->whereBetween('date', [$customFromDate,$customToDate]);

      // $studio_class->where('date', date('Y-m-d', strtotime($date)));
      // $pending_class->where('date', date('Y-m-d', strtotime($date)));

   }

  } else {

      /* Custom Calculation for UTC time start */

      $customFromDate = date('Y-m-d');      
      $date           = new \DateTime($customFromDate.' 11:59 PM');
      //$date->setTimezone(new \DateTimeZone('UTC'));
      
      $customToDate = $date->format('Y-m-d');

      /* Custom Calculation for UTC time end here*/

      $studio_class->whereBetween('date', [$customFromDate,$customToDate]);
      $pending_class->whereBetween('date', [$customFromDate,$customToDate]);
           
      // $pending_class->where('date', date('Y-m-d'));
      // $studio_class->where('date', date('Y-m-d'));
  }


  $pending_class = $pending_class->pluck('class_id');
  if ($pending_class) {
   $pending_class = $pending_class->toArray();
   $pending_class = json_encode($pending_class);
  }

  $studio_class->orderBy('date', 'asc')->orderBy('start_time', 'asc');
  $studio_class = $studio_class->get();
  if ($request->ajax()) {
   $this->layout = null;

   $ajaxDate['isDate'] = date('Y-m-d',strtotime($cdate));
      
  return view('studio.ajaxclasslist', compact('studio_class', 'locations', 'styles', 'studio_teachers', 'pending_class','ajaxDate'));

  }

  $studio_class->where('status', 'active');

  return view('studio.classlist', compact('studio_class', 'locations', 'styles', 'studio_teachers', 'teachers', 'pending_class'));
 }




 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  die($this->studio_id);
  //
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(Request $request)
 {
	
  if ($request->ajax()) {
   $this->layout = null;
   $auth_session = \Session::get('auth');
   $studio_id    = $auth_session['admin_id'];
   $class_data   = $request->get('class-form');
   $location_id  = $class_data['location_id'];



   if (isset($class_data['repeat-end-date']) && !empty($class_data['repeat-end-date']) && !array_key_exists('repeat-days', $class_data)) {
    $validator = Validator::make($request->all(), ['repeat-days' => 'required']);
    if ($validator->fails()) {
     return redirect()->back()->withInput()->withErrors($validator);
    }
   }

   if ($class_data["style"] != null && $class_data["style"] != "") {        
        $style          = new StylePrice;
        $style_details  = StylePrice::where([['style_id', $class_data["style"]], ['location_id', $location_id]])->first();
   }
	
   $class_data["price"]           = $style_details->default_price_credit;
   $class_data["price_in_dollar"] = $style_details->default_price_dollar;

   $class_data['studio_id']  = $studio_id;
   $class_data['date']       = $class_data['date'] ? date("Y-m-d", strtotime($class_data['date'])) : date("Y-m-d");
   $class_data['start-time'] = $idle_time =  $class_data['start-time'];


	if (isset($class_data['repeat-end-date']) && !empty($class_data['repeat-end-date'])) {
	
		//echo '174'; die;
                   
          $date1           = date_create(date('Y-m-d', strtotime($class_data['date'])));
          $date2           = date_create(date('Y-m-d', strtotime($class_data['repeat-end-date'])));
          $difference      = date_diff($date1, $date2);
          $difference_days = $difference->format("%a");

                if ($difference_days > 0) {

                    if (isset($class_data['repeat-days'])) {

                        sort($class_data['repeat-days']);                      

                        for ($i = 0; $i <= $difference_days; $i++) {
                                                      
                            if($i==0){

                              $class_data['date'] = date('Y-m-d', strtotime($class_data['date']));

                            } else {

                               $class_data['date'] = date('Y-m-d', strtotime($class_data['date'].' + 1 day'));
                            }

                               $week_day     = date('w', strtotime($class_data['date']));
                          
                            if (in_array($week_day, $class_data['repeat-days'])) {    
                              
                                /* Convert EST time into UTC time start here */  

                               /* $date = new \DateTime($class_data['date'].' '.$idle_time, new \DateTimeZone('America/New_York'));
                                  $date->setTimezone(new \DateTimeZone('UTC'));
                                  $getDateTime =  $date->format('Y-m-d h:i A');

                                   if(!empty($getDateTime)){

                                       $returnDate = explode(" ", $getDateTime);
                                       $class_data['date']        = $returnDate[0];
                                       $class_data['start-time']  = $idle_time;

                                   } */
                            
                                  /* Convert EST time into UTC time end here */ 
  
                                 $insert_class = StudioClass::saveClass($class_data);
                            }

                        }                      
                        
               
                    } else {
              
                      $class_data['date'] = date("Y-m-d");

                       /* Convert EST time into UTC time start here */  

                      /*  $date = new \DateTime($class_data['date'].' '.$class_data['start-time'], new \DateTimeZone('America/New_York'));
                        $date->setTimezone(new \DateTimeZone('UTC'));
                        $getDateTime =  $date->format('Y-m-d h:i A');

                         if(!empty($getDateTime)){

                             $returnDate = explode(" ", $getDateTime);
                             $class_data['date']        = $returnDate[0];
                             $class_data['start-time']  = $idle_time;

                         } */
                  
                        /* Convert EST time into UTC time end here */ 

                      $insert_class       = StudioClass::saveClass($class_data);
                    }


                } else {
                  
                        $class_data['date'] = date("Y-m-d");


                         /* Convert EST time into UTC time start here */  

                        /*  $date = new \DateTime($class_data['date'].' '.$class_data['start-time'], new \DateTimeZone('America/New_York'));
                          $date->setTimezone(new \DateTimeZone('UTC'));
                          $getDateTime =  $date->format('Y-m-d h:i A');

                           if(!empty($getDateTime)){

                               $returnDate = explode(" ", $getDateTime);
                               $class_data['date']        = $returnDate[0];
                               $class_data['start-time']  = $idle_time;

                           } */
                    
                          /* Convert EST time into UTC time end here */ 

                        $insert_class       = StudioClass::saveClass($class_data);

                      }


   } else {
			//echo '277'; die;
			$class_data['repeat-days'] =  array_filter($class_data['repeat-days']);

        if (isset($class_data['repeat-days']) && is_array($class_data['repeat-days']) && count($class_data['repeat-days'])>0) {

           foreach ($class_data['repeat-days'] as $repeatDays) { 
                $class_data['date'] = $this->getdate($repeatDays);  

                /* Convert EST time into UTC time start here */  

               /* $date = new \DateTime($class_data['date'].' '.$class_data['start-time'], new \DateTimeZone('America/New_York'));
                $date->setTimezone(new \DateTimeZone('UTC'));
                $getDateTime =  $date->format('Y-m-d h:i A');

                 if(!empty($getDateTime)){

                     $returnDate = explode(" ", $getDateTime);
                     $class_data['date']        = $returnDate[0];
                     $class_data['start-time']  = $idle_time;

                 } */
          
                /* Convert EST time into UTC time end here */ 


                $insert_class       = StudioClass::saveClass($class_data);
           }

        } else {


        /* Convert EST time into UTC time start here */  

         /* $date = new \DateTime($class_data['date'].' '.$class_data['start-time'], new \DateTimeZone('America/New_York'));
          $date->setTimezone(new \DateTimeZone('UTC'));
          $getDateTime =  $date->format('Y-m-d h:i A');

           if(!empty($getDateTime)){

               $returnDate = explode(" ", $getDateTime);
               $class_data['date']        = $returnDate[0];
               $class_data['start-time']  = $idle_time;

           } */
    
          /* Convert EST time into UTC time end here */          

          $insert_class = StudioClass::saveClass($class_data);

        }


   }


      return response()->json(array('success' => true, 'id' => $insert_class->id));

  }

  //  echo view('studio.ajaxclasslist',compact('studio_class','locations','styles','studio_teachers'));
  // $resp =  view('studio.ajaxclasslist',compact('studio_class','locations','styles','studio_teachers'))->render();

  //return response()->json(['success'=>true]);

  //Session::flash('message', 'Class added Successfully.');
  //Session::flash('alert-class', 'alert-success');
  //return redirect()->back();
 }

 // function convertTimeInUTC($datatime=null){

 //      if(!empty($datatime)){

 //          $userTimezone = new \DateTimeZone('America/New_York');
 //          $gmtTimezone = new \DateTimeZone('GMT');
 //          $myDateTime = new \DateTime($datatime, $gmtTimezone);
 //          $offset = $userTimezone->getOffset($myDateTime);
 //          $myInterval=DateInterval::createFromDateString((string)$offset . 'seconds');
 //          $myDateTime->add($myInterval);
 //          $result = $myDateTime->format('Y-m-d h:i A');
 //          return $result;
 //      }      
 // }


 /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function show($id)
 {
  //
 }

 /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function edit($id)
 {
  //
 }

 /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function update(Request $request, $id)
 {
  //
 }

 /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function destroy($id)
 {
  //
 }

 public function markattended(Request $request)
 {
  $input = $request->all();
  if (!empty($input)) {
   $id           = $input['dataStudentClassId'];
   $studentClass = StudentClass::find($id);

   //echo "<pre>"; print_r($studentClass); die('okay');

   if (!empty($studentClass)) {
    //$studentClass->checked_in = ucfirst($input['attended']);
    $studentClass->check_in = $input['attended'];
    $studentClass->save();
    return response()->json(['status' => 'Successfully', 'statuscode' => '200']);
   }

  }
  return response()->json(['status' => 'error', 'statuscode' => '404']);

 }


 public function addStyle(Request $request)
 {
  $auth_session = \Session::get('auth');
  $studio_id    = $auth_session['admin_id'];

  $style_name = Style::where('level_name', $request->input(['name']))->first();
  $style      = new Style;
  // $data1 = $request->get('add-style');
  if (empty($style_name)) {
   $style['level_name']  = $request->input(['name']);
   $style['description'] = $request->input(['description']);
   $style['studio_id']   = $studio_id;
   $style->save();

   Session::flash('success', 'Style added Successfully.');
   return redirect()->back();
  } else {
   Session::flash('failure', 'Warning! Style Name already exists.');
   return redirect()->back();
  }

 }

 public function addExistingTeacher(Request $request)
 {
  $auth_session  = \Session::get('auth');
  $studio_id     = $auth_session['admin_id'];
  $teacher_exist = Teacher::where('email', $request->input(['email']))->first();
  if ($teacher_exist) {
   $data               = new StudioTeacher;
   $data['studio_id']  = $studio_id;
   $data['teacher_id'] = $teacher_exist['teacher_id'];
   $data->save();

   Session::flash('success', 'Teacher added.');
   return redirect()->back();
  } else {
   Session::flash('failure', 'Warning! Teacher already exists.');
   return redirect()->back();
  }
 }


 public function addNewTeacher(Request $request)
 {
  $request_data = $request->all();
  $auth_session = \Session::get('auth');
  $studio_id    = $auth_session['admin_id'];
  $teacher      = Teacher::where('email', $request->input(['email']))->first();
  if (!$teacher) {

   $data = Teacher::create([
    'firstname' => $request_data['firstname'],
    'lastname'  => $request_data['firstname'],
    'email'     => $request_data['email'],
   ]);
   $teacher_id = $data->id;

   $data1               = new StudioTeacher;
   $data1['studio_id']  = $studio_id;
   $data1['teacher_id'] = $teacher_id;
   $data1->save();

   Session::flash('success', 'Teacher Added Successfully.');
   return redirect()->back();
  } else {

   Session::flash('failure', 'Warning! Email ID already exists.');
   return redirect()->back();

   Session::flash('failure', 'Warning! Teacher already exists.');
   return redirect()->back();
  }
 }




 //cancelled class
 public function cancelledclass(Request $request)
 {

  $input = $request->all();
  // echo "<pre>".print_r($input,1)."</pre>";
  // die;
  if (!empty($input)) {

      $allClassId = $input['dataclassidarray'];
      $status     = $input['status'];
      $flag       = $input['flag']; /* if flag is 1 then its cancel all classes case */

     if (is_array($allClassId) && !empty($allClassId)) {
      if ($status == 'delete') {
          $studioClass = StudioClass::whereIn('class_id', $allClassId)->delete();
      } else {


          if($flag==1){


                /**************************************************************************************/
                /* When cancelling class with same future class along with same location id and along with other fields */

                 $ClassesData   = StudioClass::whereIn("class_id", $allClassId)->where('status', 'active')->get()->toArray();
                 $ClassesData   = $ClassesData[0];

                $all_class_data = StudioClass::where([['location_id', $ClassesData['location_id']],['style_id', $ClassesData['style_id']],['start_time', $ClassesData['start_time']],['date','>=',$ClassesData['date']],['status','active']])->get()->toArray();              

                /* Check if day are not matched then exclude the data rest keep it */
                $getClassId =  array();
                foreach ($all_class_data as $key => $value) {

                  if(date('l', strtotime($ClassesData['date'])) != date('l', strtotime($value['date']))){
                      unset($all_class_data[$key]);
                  } else {

                      $getClassId[]=$value['class_id'];

                  }
                  
                }

                //echo "<pre>"; print_r($getClassId); die('ok');

                

                if(!empty($getClassId) && count($getClassId)>0){

                  $studioClass    = StudioClass::whereIn('class_id', $getClassId)->update(['status' => $status]);


                  $studentClasses = StudentClass::whereIn("class_id", $getClassId)->where('cancelled','!=',2)->get()->toArray();


                  foreach ($studentClasses as $key => $value) {
            
                      $studentClasses = StudentClass::where("id", $value['id'])->update(['cancelled' =>2,'cancellation_refund'=>$value['price']]);      

                      $student         = Student::where("student_id", $value['student_id'])->get()->toArray();

                      $updated_credits = $student[0]['credits']+$value['price'];

                      $student         = Student::where("student_id", $value['student_id'])->update(['credits'=>$updated_credits]);

                    }

                } else {
                    
                    $studioClass    = StudioClass::whereIn('class_id', $allClassId)->update(['status' => $status]);

                }


             /**************************************************************************/

            } else if($flag==0) {

                $studioClass    = StudioClass::whereIn('class_id', $allClassId)->update(['status' => $status]);

                /* Update date others record when class cancelled by studio start here */ 

                $studentClasses = StudentClass::whereIn("class_id", $allClassId)->get()->toArray();
                
                foreach ($studentClasses as $key => $value) {
                  
                  $studentClasses = StudentClass::where("id", $value['id'])->update(['cancelled' =>2,'cancellation_refund'=>$value['price']]);      

                  $student         = Student::where("student_id", $value['student_id'])->get()->toArray();

                  $updated_credits = $student[0]['credits']+$value['price'];

                  $student         = Student::where("student_id", $value['student_id'])->update(['credits'=>$updated_credits]);

                }

                /* Update date others record when class cancelled by studio end here */ 

            }

      }

      return response()->json(['status' => 'Successfully', 'statuscode' => '200']);

     }

  }

  return response()->json(['status' => 'error', 'statuscode' => '404']);
 }





 public function getAllStudentClasses(Request $request)
 {
    $input = $request->all();
    if (!empty($input)) {

     $allClassId = $input['dataclassidarray'];
     $status     = $input['status'];
     $flag       = $input['flag']; /* if flag is 1 then its cancel all classes case */


     /* If cancelling the single class the it would be work */
     if (is_array($allClassId) && !empty($allClassId) && $flag==0) {
        
        $studentClasses = StudentClass::whereIn("class_id", $allClassId)->where('cancelled','!=',2)->get()->toArray();

        return response()->json(['status' => 'Successfully', 'statuscode' => '200', 'students' => $studentClasses]);

     } else if($flag==1){


            /*******************************************************************************************************/
            /* When cancelling class with same future class along with same location id and along with other fields */

             $ClassesData   = StudioClass::whereIn("class_id", $allClassId)->where('status', 'active')->get()->toArray();
             $ClassesData   = $ClassesData[0];

            $all_class_data = StudioClass::where([['location_id', $ClassesData['location_id']],['style_id', $ClassesData['style_id']],['start_time', $ClassesData['start_time']],['date','>=',$ClassesData['date']],['status','active']])->get()->toArray();        

            /* Check if day are not matched then exclude the data rest keep it */
            $getClassId =  array();
            foreach ($all_class_data as $key => $value) {

              if(date('l', strtotime($ClassesData['date'])) != date('l', strtotime($value['date']))){
                  unset($all_class_data[$key]);
              } else {

                  $getClassId[]=$value['class_id'];

              }
              
            }

            if(!empty($getClassId) && count($getClassId)>0){

              $studentClasses = StudentClass::whereIn("class_id", $getClassId)->where('cancelled','!=',2)->get()->toArray();

              return response()->json(['status' => 'Successfully', 'statuscode' => '200', 'students' => $studentClasses]);
            
            } else {

              return response()->json(['status' => 'error', 'statuscode' => '404']);

            }

            /**************************************************************************/

        } 

     
    }
   
    return response()->json(['status' => 'error', 'statuscode' => '404']);
 }




 private function updateStudentClassCancellation($allClassId)
 {

 }

 public function getdate($repeatDays) {

  $day              = date('l', strtotime(date('Y-m-d')));
  $dayArray         = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
  $keys             = array_keys($dayArray, $day);
  $currentDayNumber = $keys[0];
  $studioClassDate  = '';
  switch ($repeatDays) {
   case 1:
    if ($currentDayNumber > $repeatDays) {
     $days            = $currentDayNumber + $repeatDays - 1;
     $studioClassDate = date("Y-m-d", strtotime(' +' . $days . ' day'));
    } elseif ($currentDayNumber < $repeatDays) {
     $days            = $repeatDays - $currentDayNumber;
     $studioClassDate = date("Y-m-d", strtotime(' +' . $days . ' day'));
    } else {
     $studioClassDate = date('Y-m-d');
    }
    break;
   case 2:
    if ($currentDayNumber > $repeatDays) {
     $days            = $currentDayNumber + $repeatDays - 1;
     $studioClassDate = date("Y-m-d", strtotime(' +' . $days . ' day'));
    } elseif ($currentDayNumber < $repeatDays) {
     $days            = $repeatDays - $currentDayNumber;
     $studioClassDate = date("Y-m-d", strtotime(' +' . $days . ' day'));
    } else {
     $studioClassDate = date('Y-m-d');
    }
    break;
   case 3:
    if ($currentDayNumber > $repeatDays) {
     $days            = $currentDayNumber + $repeatDays - 1;
     $studioClassDate = date("Y-m-d", strtotime(' +' . $days . ' day'));
    } elseif ($currentDayNumber < $repeatDays) {
     $days            = $repeatDays - $currentDayNumber;
     $studioClassDate = date("Y-m-d", strtotime(' +' . $days . ' day'));
    } else {
     $studioClassDate = date('Y-m-d');
    }
    break;
   case 4:
    if ($currentDayNumber > $repeatDays) {
     $days            = $currentDayNumber + $repeatDays - 1;
     $studioClassDate = date("Y-m-d", strtotime(' +' . $days . ' day'));
    } elseif ($currentDayNumber < $repeatDays) {
     $days            = $repeatDays - $currentDayNumber;
     $studioClassDate = date("Y-m-d", strtotime(' +' . $days . ' day'));
    } else {
     $studioClassDate = date('Y-m-d');
    }
    break;
   case 5:
    if ($currentDayNumber > $repeatDays) {
     $days            = $currentDayNumber + $repeatDays - 1;
     $studioClassDate = date("Y-m-d", strtotime(' +' . $days . ' day'));
    } elseif ($currentDayNumber < $repeatDays) {
     $days            = $repeatDays - $currentDayNumber;
     $studioClassDate = date("Y-m-d", strtotime(' +' . $days . ' day'));
    } else {
     $studioClassDate = date('Y-m-d');
    }
    break;
   case 6:
    if ($currentDayNumber > $repeatDays) {
     $days            = $currentDayNumber + $repeatDays - 1;
     $studioClassDate = date("Y-m-d", strtotime(' +' . $days . ' day'));
    } elseif ($currentDayNumber < $repeatDays) {
     $days            = $repeatDays - $currentDayNumber;
     $studioClassDate = date("Y-m-d", strtotime(' +' . $days . ' day'));
    } else {
     $studioClassDate = date('Y-m-d');
    }
    break;
   default:
    if ($currentDayNumber > $repeatDays) {
     $days            = $currentDayNumber + $repeatDays - 1;
     $studioClassDate = date("Y-m-d", strtotime(' +' . $days . ' day'));
    } elseif ($currentDayNumber < $repeatDays) {
     $days            = $repeatDays - $currentDayNumber;
     $studioClassDate = date("Y-m-d", strtotime(' +' . $days . ' day'));
    } else {
     $studioClassDate = date('Y-m-d');
    }
  }

  $studioClassDate = (isset($studioClassDate) && !empty($studioClassDate)) ? $studioClassDate : date("Y-m-d");

  return $studioClassDate;

 }

 //update class.
 public function editclass(Request $request) {
  $input     = $request->all();
  $validator = Validator::make($request->all(), [
   'class_id'        => 'required',
   'start_time'      => 'required',
   'duration'        => 'required',
   'style_id'        => 'required|integer|min:1',
   'teacher_id'      => 'required|integer|min:1',
   'number_of_spots' => 'required',

  ]);

  $class_id          = $input['class_id'];
  $studentClasses    = StudioClass::where("class_id", $class_id)->get()->toArray();

  /* Convert EST time into UTC time start here */  

      $date = new \DateTime($studentClasses[0]['date'].' '.$input['start_time'], new \DateTimeZone('America/New_York'));
      $date->setTimezone(new \DateTimeZone('UTC'));
      $getDateTime =  $date->format('Y-m-d h:i A');

       if(!empty($getDateTime)){

      $returnDate = explode(" ", $getDateTime);
      $input['date']        = $returnDate[0];
      $input['start_time']  = $returnDate[1].' '.$returnDate[2];

   } 

  /* Convert EST time into UTC time end here */  

  if ($validator->fails()) {
   return response()->json(['errors' => $validator->messages(), 'status' => 400]);
  } else {

   
   unset($input['_token']);
   unset($input['class_id']);
   $input['start_time'] = date("H:i:s", strtotime($input['start_time']));
   $studioClass         = StudioClass::where('class_id', $class_id)->update($input);
   return response()->json(['success' => 'Update Successfully.', 'status' => 200]);
  }
 }


 /* Insert clone class start here */
 public function insertCloneClassData($class_datas){


    if(!empty($class_datas) && count($class_datas)>0){
          $flag = 0;
          foreach ($class_datas as $key => $class_data) {


            if(!empty($class_data['repeat-days'][0])){

                sort($class_data['repeat-days']);                
            }

          if (isset($class_data['repeat-end-date']) && !empty($class_data['repeat-end-date'])) {
           
            $date1           = date_create(date('Y-m-d', strtotime($class_data['date'])));
            $date2           = date_create(date('Y-m-d', strtotime($class_data['repeat-end-date'])));
            $difference      = date_diff($date1, $date2);
            $difference_days = $difference->format("%a");


                // echo $class_data['date']; 
                // echo "<pre>"; print_r($difference_days); 
                // die('oka');
                //echo $difference_days." see the difference of year"; 

                  if ($difference_days > 0) {

                      if (isset($class_data['repeat-days'])) {

                          sort($class_data['repeat-days']);                      

                          for ($i = 0; $i <= $difference_days; $i++) {
                                                        
                              if($i==0){

                                $class_data['date'] = date('Y-m-d', strtotime($class_data['date']));

                              } else {

                                 $class_data['date'] = date('Y-m-d', strtotime($class_data['date'].' + 1 day'));
                              }

                                 $week_day     = date('w', strtotime($class_data['date']));
                            
                              if (in_array($week_day, $class_data['repeat-days'])) {    
                                
                                //echo "<pre> in Repeat.."; print_r($class_data);

                                $insert_class = StudioClass::saveClass($class_data);

                                //die('inserted');


                                 $flag =1;
                              }

                          }
                          
                 
                      } else {
                
                        $class_data['date'] = date("Y-m-d");
                        //echo "<pre>"; print_r($class_data);
                        $insert_class       = StudioClass::saveClass($class_data);
                        $flag =1;
                      }


                  } else {
                          //die('else');
                          $class_data['date'] = date("Y-m-d");
                          //echo "<pre>"; print_r($class_data);
                          $insert_class       = StudioClass::saveClass($class_data);
                          $flag =1;

                        }


        } else {

            $class_data['repeat-days'] =  array_filter($class_data['repeat-days']);

            if (isset($class_data['repeat-days']) && is_array($class_data['repeat-days']) && count($class_data['repeat-days'])>0) {

               foreach ($class_data['repeat-days'] as $repeatDays) { 
                    $class_data['date'] = $this->getdate($repeatDays);  
                    //echo "<pre>"; print_r($class_data);    
                    $insert_class       = StudioClass::saveClass($class_data);
                    $flag =1;
               }

            } else {

              //echo "<pre>"; print_r($class_data); die('oye');
              //echo "<pre>"; print_r($class_data);
              $insert_class = StudioClass::saveClass($class_data);
              $flag =1;
            }


       }


     }

    // die('end here');

      if($flag==1){

        return true; 

      } else {

        return false;
      }


       

  } else {

     return false;

  }


 }

 /* Insert clone class end here */




 public function classCloning(Request $request) {

  $data = $request->input();

  $auth_session    = \Session::get('auth');
  $studio_id       = $auth_session['admin_id'];
  $this->studio_id = $studio_id;

   /* Clone classes saving preocess start here */
    if(isset($data['is_static_clone']) && !empty($data['is_static_clone'])){

          $i=0;  
           $new_class_data =array(); 
          foreach ($data['class-form'] as $key => $value) {

              if($i<count($data['class-form']['clone-date'])){

                  $class_data['date']            = $data['class-form']['clone-date'][$i] ? date("Y-m-d", strtotime($data['class-form']['clone-date'][$i])) : date("Y-m-d");
                  $class_data['location_id']     = $data['location_id'];
                  $class_data['start-time']      = $data['class-form']['start-time'][$i];


                   /* Convert EST time into UTC time start here */  

                    $date = new \DateTime($class_data['date'].' '.$class_data['start-time'], new \DateTimeZone('America/New_York'));
                    $date->setTimezone(new \DateTimeZone('UTC'));
                    $getDateTime =  $date->format('Y-m-d h:i A');

                     if(!empty($getDateTime)){

                         $returnDate = explode(" ", $getDateTime);
                         $class_data['date']        = $returnDate[0];
                         $class_data['start-time']  = $returnDate[1].' '.$returnDate[2];

                     } 
              
                   /* Convert EST time into UTC time end here */   



                  $class_data['style']           = $data['class-form']['style'][$i];

                  if ($data['class-form']['style'][$i] != null && $data['class-form']['style'][$i] != "") {

                      //echo $data['class-form']['style'][$i];

                      $style                     = new StylePrice;
                      $style_details             = StylePrice::where([['style_id', $data['class-form']['style'][$i]], ['location_id', $data['location_id']]])->first();

                      //$style_details = StudioClass::getStyle($data['class-form']['style'][$i]);

                      //echo "<pre>"; print_r($style_details); die('ok');
                  }

                  $class_data["price"]           = $style_details->default_price_credit;
                  $class_data["price_in_dollar"] = $style_details->default_price_dollar;

                  // $class_data['price']           = $style_details[0]["default_price_credits"];
                  // $class_data['price_in_dollar'] = $style_details[0]["default_price_dollor"];

                  $class_data['sportmanagement'] = $data['class-form']['sportmanagement'][$i];
                  $class_data['duration']        = $data['class-form']['duration'][$i];
                  $class_data['teacher']         = $data['class-form']['teacher'][$i];
                  $class_data['repeat-end-date'] = $data['class-form']['repeat-end-date'][$i];
              
                  $j = $i+1;
                  $class_data['repeat-days']     = $data['class-form']['repeat-days-'.$j];
                  
                  $class_data['studio_id']       = $studio_id;

                  $new_class_data []             = $class_data;

                $i++;

            }
           

         }


         if(!empty($new_class_data) && count($new_class_data)>0){

            
              //echo "<pre>"; print_r($new_class_data); die('okaokao');
             $response = $this->insertCloneClassData($new_class_data);

             if($response==true){

              return response()->json(['success' => 'Cloning save done', 'status' => 200]); 

             } else {

              return response()->json(['error' => 'Cloning save error', 'status' => 201]);
             }


         } 
  

   }
   /* Clone classes saving preocess end here */


  if (!empty($data['clone_from']) && !empty($data['clone_to'])) {

      $locations       = StudioLocation::with('Location')->where('studio_id', $this->studio_id)->get();    
      $studio_teachers = StudioTeacher::with('Teacher')->where('studio_id', $this->studio_id)->get();
      $styles          = Style::where('studio_id', $this->studio_id)->get();

    
      foreach ($studio_teachers as $teacher){                
          $studio_teacherss[] = $teacher->toArray();
      }        

      foreach ($studio_teacherss as $teacher['teacher']){             
          $studio_teachers_data[$teacher['teacher']['teacher']['teacher_id']] = $teacher['teacher']['teacher']['firstname'].' '.$teacher['teacher']['teacher']['lastname'];
      }  

      foreach ($styles as $style){                
          $studio_styles[] = $style->toArray();
      }        

      foreach ($studio_styles as $styless){             
          $studio_styles_data[$styless['style_id']] = $styless['name'];
      }  


   if (!$data['is_array']) {

        $from    = date('Y-m-d', strtotime($data['clone_from']));
        $to      = date('Y-m-d', strtotime($data['clone_to']));

        $classes = StudioClass::where(['date' => $from, 'created_by' => $studio_id, 'location_id' => $data['location_id']])->where([['status', '!=', 'cancelled']])->get(['level_id', 'teacher_id', 'style_id', 'location_id', 'start_time', 'duration', 'status', 'avg', 'no_users_rate', 'price', 'price_in_dollar', 'created_by', 'rental', 'created_type', 'fixed_payment', 'variable_student_1', 'variable_payment_1', 'variable_payment_2', 'original_teacher_id', 'gold', 'visible_to_student', 'visible_to_teacher', 'number_of_spots', 'spot_management']);

        
            $getClasses = array();
        if ($classes->count()) {
             foreach ($classes as $key => $this_class) {
                $classData                  = array();
                $classData                  = $this_class->toArray();



                /* Convert date time from UTC to EST start here  */
                $date = new \DateTime($to.' '.$this_class->start_time, new \DateTimeZone('UTC'));
                $date->setTimezone(new \DateTimeZone('America/New_York'));
                $getDateTime =  $date->format('Y-m-d h:i A');

                if(!empty($getDateTime)){

                $returnDate = explode(" ", $getDateTime);


                $classData['date']        = $returnDate[0];
                $classData['start_time']  = $returnDate[1].' '.$returnDate[2];

                }



                $classData['date']          = $to;
                //$is_saved                 = StudioClass::cloneClass($classData);
                $classData['style_name']    = $studio_styles_data[$classData['style_id']];
                $classData['teacher_name']  = $studio_teachers_data[$classData['teacher_id']];
                $getClasses[]               = $classData;

             }
             
             return response()->json(['success' => 'Cloning done', 'status' => 200,'data'=>$getClasses]);
        
        } else {

            return response()->json(['error' => 'No Class found on selected date.', 'status' => 500]);

        }
       

   } else {

    
        $from = explode('-', $data['clone_from']);
        $to   = explode('-', $data['clone_to']);

        for ($i = 0; $i <= 6; $i++) {
           $filteredDate = date('Y-m-d', strtotime($from[0] . " + $i days"));

           $classes      = StudioClass::where(['date' => $filteredDate, 'created_by' => $studio_id, 'location_id' => $data['location_id']])->get(['level_id', 'teacher_id', 'style_id', 'location_id', 'start_time', 'duration', 'status', 'avg', 'no_users_rate', 'price', 'price_in_dollar', 'created_by', 'rental', 'created_type', 'fixed_payment', 'variable_student_1', 'variable_payment_1', 'variable_payment_2', 'original_teacher_id', 'gold', 'visible_to_student', 'visible_to_teacher', 'number_of_spots', 'spot_management']);
           $classes      = json_decode(json_encode($classes), true);

           foreach ($classes as $key => $class) {
                $classData         = array();
                $classData         = $class;
                $classData['date'] = date('Y-m-d', strtotime($to[0] . " + $i days"));
                $is_saved          = StudioClass::cloneClass($classData);
            }
        }
      
      return response()->json(['success' => 'Cloning done', 'status' => 200]);

   }


  } else {
   return response()->json(['failed' => 'Please enter the required fields.', 'status' => 500]);
  }

 }


  function showChangePasswordForm(){
         
      return view('studio.change-password');

  }

  function updatePassword(Request $request){

      $validatedData = $request->validate([
          'password' => 'required|min:6|max:16|confirmed',
          'password_confirmation' => 'required|min:6|max:16',
      ]);
      
      if($validatedData){
        $input['password']  = encrypt_password($request->input('password'));

        $auth_session    = \Session::get('auth');      
        $admin_id        = $auth_session['admin_id'];

         $response =  Studio::where('admin_id', $admin_id)->update($input);
       
         if($response){

          Session::flash('message', 'Password updated Successfully.');
          Session::flash('alert-class', 'alert-success');
          
          return redirect()->back();

         } else {

          Session::flash('message', 'Password not updated Successfully.');
          Session::flash('alert-class', 'alert-danger');
          
          return redirect()->back();

         }
        return redirect()->back();

      }
    

  } 

  /*
  * Function Name : bookings
  * Description   : use to display the bookings those are related to the location id or the log in studio
  * Params        : None
  * Created By    : Vishal
  * Date          : 13-March-2019
  * Modify On     : 13-March-2019
  */
  function bookings(){

        $auth_session    = \Session::get('auth');
        $studio_id       = $auth_session['admin_id'];
        $this->studio_id = $studio_id;
        $locations       = StudioLocation::with('Location')->where('studio_id', $this->studio_id)->get();

         $lockArr = array();

        foreach ($locations as $location){
            $lockArr[] = $location->location_id;
        }

        $studio_class  =  DB::table('class')
                          ->whereIn('class.location_id', $lockArr)
                          ->where('class.date','<',date('Y-m-d'))
                          ->where('student_classes.revenue_transfer','!=',0)
                          ->Join('style', 'style.style_id', '=', 'class.style_id')
                          ->Join('location', 'location.location_id', '=', 'class.location_id')

                          ->join('student_classes','student_classes.class_id','=','class.class_id')
                          ->join('student','student.student_id','=','student_classes.student_id')

                          ->select('class.class_id','class.style_id','class.location_id','class.date','class.start_time','style.name','student_classes.class_id','student_classes.revenue_transfer','student_classes.revenue_status','student.firstname','student.lastname','student.email','location.name1')
                          ->get();


        return view('studio.bookings', compact('studio_class'));

  }


  /**
   * Change class visibility from student
   */
  public function chnageClassVisibility(Request $request) {

      $classModel = \App\ClassModel::where('class_id', $request->input('class_id'))->first();

      // if bulk change has selected
      if ( !empty($request->input('hide_future_class')) ) {

          $classModels      = \App\ClassModel::where('style_id', $classModel->style_id)
                                              ->where('location_id', $classModel->location_id)
                                              ->where('start_time', $classModel->start_time)
                                              ->whereDate('date', '>=', $classModel->date)
                                              ->get();

          $status           = '';

          // filter the results by weekday
          $filteredClasses  = filter_as_weekdays($classModels, $classModel);

          // update the filtered item data
          foreach ( $filteredClasses as $class ) {

              // check the select class status if it's Yes or No
              // depend on that the related classes will change their status
              if ( $classModel->visible_to_student == 'Yes' ) {

                  $class->visible_to_student = 'No';
              } else {

                  $class->visible_to_student = 'Yes';
              }

              $status = $class->visible_to_student;

              $class->save();
          }

          return response()->json(['status' => 'success', 'data' => $status]);

      } else {

          if ( !empty($classModel) ) {

              // if the visible_to_student has Yes, make it No and vice-versa
              if ( $classModel->visible_to_student == 'Yes' ) {

                  $classModel->visible_to_student = 'No';
              } else {

                  $classModel->visible_to_student = 'Yes';
              }

              $classModel->save();

              return response()->json(['status' => 'success', 'data' => $classModel->visible_to_student]);
          }
      }

      return response()->json(['status' => 'error', 'message' => 'Something is wrong! Please try again later']);
  }


  /**
   * Edit spot number
   *
   * @param Request $request
   */
  public function editSpot(Request $request) {

      $classModel = \App\ClassModel::where('class_id', $request->input('class_id'))->first();

      // if bulk change option selected
      // change those class that will match the teacher_id, style_id, location_id, date
      if ( !empty($request->input('bulk_spots_change')) ) {

          $classModels = \App\ClassModel::where('style_id', $classModel->style_id)
                                        ->where('location_id', $classModel->location_id)
                                        ->where('start_time', $classModel->start_time)
                                        ->whereDate('date', '>=', $classModel->date)
                                        ->get();

          // filter the results by weekday and return the result as an array
          $filteredClasses  = filter_as_weekdays($classModels, $classModel);

          // update the filtered items
          foreach ( $filteredClasses as $class ) {

              $class->number_of_spots = $request->input('spot_number');
              $class->save();
          }

          return response()->json(['status' => 'success']);

      } else {

          if ( !empty($classModel) ) {

            $classModel->number_of_spots = $request->input('spot_number');
            $classModel->save();

            return response()->json(['status' => 'success']);
          }
      }

      return response()->json(['status' => 'error', 'message' => 'Something is wrong! Please try again later']);
  }

}