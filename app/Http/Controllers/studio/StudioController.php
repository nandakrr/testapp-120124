<?php

namespace App\Http\Controllers\studio;

use App\Http\Controllers\Controller;
use App\PrivateClassesWeekdayAvailability;
use App\PrivateClassesSpecificDateAvailability;
use App\PrivateClassesSpecificDateUnavailability;
use Illuminate\Http\Request;
use App\Teacher;
use App\TeacherAvailability;

class StudioController extends Controller
{
  function __construct()
  {
    $this->middleware('checkauth');
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return view('studio.dashboard');
        return redirect('studio/manage-classes');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        //
    }

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

    /**
     * private module index
     */
    public function showPrivateModules(Request $request) {

        // get all the teachers of the current studio
        $teachers = Teacher::join('studio_teachers', 'teacher.teacher_id', '=', 'studio_teachers.teacher_id')
                        ->where('studio_teachers.studio_id', $request->session()->get('auth')['admin_id'])
                        ->paginate(20);

        $studioLocations = \DB::table('location')
                                ->join('studio_location', 'location.location_id', '=', 'studio_location.location_id')
                                ->where('studio_location.studio_id', $request->session()->get('auth')['admin_id'])
                                ->get();

        // print_r(current($studioLocations)[0]); die;

        $serviceCategories = \DB::table('service_categories')
                                ->join('location_private_services', 'service_categories.service_categories_id', '=', 'location_private_services.service_categories_id')
                                ->where('location_private_services.location_id', current($studioLocations)[0]->location_id)
                                ->get();
                                

        foreach ( $teachers as $teacher ) {

            if ( !empty($_COOKIE['location_id']) ) {
                $teacher->avaibilities = $teacher->avaibilities->where('location_id', $_COOKIE['location_id']);
            } else {
                $teacher->avaibilities = $teacher->avaibilities->where('location_id', current($studioLocations)[0]->location_id);
            }
        }                                

        // get week list
        $week_lists = getWeekList();

        // timing lists
        $timing_list = getTimingList();
        

        // \Cookie::set('Test', 'XXXX', true, 10);
        // setcookie('location_id',  (!empty($_COOKIE['location_id'])) ? $_COOKIE['location_id'] : current($studioLocations)[0]->location_id, time() + (86400 * 30), "/"); //name,value,time,url

        return view('studio.private.list', compact('teachers', 'week_lists', 'timing_list', 'studioLocations', 'serviceCategories')); //->withCookie(cookie('location_id', (!empty($_COOKIE['location_id'])) ? $_COOKIE['location_id'] : current($studioLocations)[0]->location_id, 45000));
    }
    

    /**
     * get teacher timings
     * 
     * @param  int  $id
     */
    public function getTeacherTimings(Request $request, $id) {

        $teacher                    = Teacher::where('teacher_id', $id)->first();
        $avaibilities               = $teacher->avaibilities()->where('location_id', $request->input('location_id'))->get();

        // get week list
        $week_lists                 = getWeekList();
        

        // timing lists
        $timing_list                = getTimingList();

        $teacher->avaibilityView    = (string) \View::make('studio.private.partials.modal-add-time', array('avaibilities' => $avaibilities, 'teacher' => $teacher, 'week_lists' => $week_lists, 'timing_list' => $timing_list));

        // echo '<pre>'; print_r($_COOKIE); die;

        return response()->json($teacher);
    }



    public function saveTeacherTimings(Request $request) {

        $timings = $request->input('timings');

        // clear the matched data to save new items
        $this->clearMatchedData($request->input('location_id'), $request->input('teacher_id'));

        // 
        foreach ( $timings['status'] as $k=>$week ) {

            if ( !empty($timings['from'][$k]) ) {

                foreach ( $timings['from'][$k] as $i=>$from_time ) {

                    $privateClassesWeekdayAvailability                          = new PrivateClassesWeekdayAvailability();
                    $privateClassesWeekdayAvailability->location_id             = $request->input('location_id');
                    $privateClassesWeekdayAvailability->teacher_id              = $request->input('teacher_id');
                    $privateClassesWeekdayAvailability->service_categories_id   = 1;
                    $privateClassesWeekdayAvailability->day_of_week             = $k;
                    $privateClassesWeekdayAvailability->from_time               = $from_time;
                    $privateClassesWeekdayAvailability->to_time                 = $timings['to'][$k][$i];
                    $privateClassesWeekdayAvailability->save();
                }
            }
        }     
        
        return response()->json(['status' => 'success', 'url' => route('studio.private-module')]);
    }


    /**
     * match all the data related to location_id and teacher_id
     * delete all the match data
     * 
     * @param int       $location_id
     * @param int       $teacher_id
     * @param boolean   $special_hours_entry
     */
    private function clearMatchedData($location_id, $teacher_id, $special_hours_entry = false) {

        if ( $special_hours_entry ) {

            // delete the first table
            PrivateClassesSpecificDateAvailability::where('location_id', $location_id)
                                                    ->where('teacher_id', $teacher_id)
                                                    ->delete();

            // delete the last table
            PrivateClassesSpecificDateUnavailability::where('location_id', $location_id)
                                                    ->where('teacher_id', $teacher_id)
                                                    ->delete();
            
        } else {

            $privateClassesWeekdayAvailabilities = PrivateClassesWeekdayAvailability::where('location_id', $location_id)
                                                                                    ->where('teacher_id', $teacher_id)
                                                                                    ->delete();
        }
    }

 
    /**
     * remove data
     */
    public function removetTeacherTimings(Request $request, $teacher_id) {

        $result = PrivateClassesWeekdayAvailability::where('location_id', $request->input('location_id'))
                                                ->where('teacher_id', $teacher_id)
                                                ->delete();

        if ( $result ) {
            return response()->json(['status' => 'success', 'url' => route('studio.private-module')]);
        }

        return response()->json(['status' => 'error', 'message' => __('Something went wrong. Please try again later')]);
    }



    /**
     * save special hours
     */
    public function saveSpecialHours(Request $request) {

//        echo '<pre>'; print_r($request->all()); die;

        $special_hours = $request->input('special_hours');

        // clear the matched data to save new items
        $this->clearMatchedData($request->input('location_id'), $request->input('teacher_id'), true);

        // form date data entry
        if ( !empty($special_hours['from_date']) ) {

            foreach ( $special_hours['from_date'] as $k=>$from_date ) {

                // this data will go to unavaibility table
                if ( array_search($k, $special_hours['avaibility']) === FALSE ) {

                    // loop through start and end date to make entry in DB
                    $begin = new \DateTime( $from_date );
                    $end   = new \DateTime( $special_hours['to_date'][$k] );

                    for($i = $begin; $i <= $end; $i->modify('+1 day')) {

                        $privateClassesSpecificDateUnavailability                           = new PrivateClassesSpecificDateUnavailability();
                        $privateClassesSpecificDateUnavailability->location_id              = $request->input('location_id');
                        $privateClassesSpecificDateUnavailability->teacher_id               = $request->input('teacher_id');
                        $privateClassesSpecificDateUnavailability->service_categories_id    = 1;
                        $privateClassesSpecificDateUnavailability->date                     = $i->format("Y-m-d"); // date('Y-m-d', strtotime($i));
                        $privateClassesSpecificDateUnavailability->save();
                    }

                } else {

                    // loop through start and end date to make entry in DB
                    $begin = new \DateTime( $from_date );
                    $end   = new \DateTime( $special_hours['to_date'][$k] );

                    for($i = $begin; $i <= $end; $i->modify('+1 day')) {

                        foreach ( $special_hours['from'][$k] as $j=>$from_special_hour ) {

                            $privateClassesSpecificDateAvailability                           = new PrivateClassesSpecificDateAvailability();
                            $privateClassesSpecificDateAvailability->location_id              = $request->input('location_id');
                            $privateClassesSpecificDateAvailability->teacher_id               = $request->input('teacher_id');
                            $privateClassesSpecificDateAvailability->service_categories_id    = 1;
                            $privateClassesSpecificDateAvailability->date                     = $i->format("Y-m-d"); // date('Y-m-d', strtotime($i));
                            $privateClassesSpecificDateAvailability->from_time                = $from_special_hour;
                            $privateClassesSpecificDateAvailability->to_time                  = $special_hours['to'][$k][$j];
                            $privateClassesSpecificDateAvailability->save();
                        }
                    }
                }                    
            }
        }
        
        return response()->json(['status' => 'success', 'url' => route('studio.private-module')]);
    }


    /**
     * Remove special hours from two table
     */
    function removeSpecialHours(Request $request, $teacher_id) {

        // delete the first table
        PrivateClassesSpecificDateAvailability::where('location_id', $request->input('location_id'))
                                                ->where('teacher_id', $teacher_id)
                                                ->delete();

        // delete the last table
        $result = PrivateClassesSpecificDateUnavailability::where('location_id', $request->input('location_id'))
                                                        ->where('teacher_id', $teacher_id)
                                                        ->delete();

        if ( $result ) {
            return response()->json(['status' => 'success', 'url' => route('studio.private-module')]);
        }

        return response()->json(['status' => 'error', 'message' => __('Something went wrong. Please try again later')]);
    }


    /**
     * get special hours view
     */
    public function getSpecialHours(Request $request, $id) {

        $teacher                        = Teacher::where('teacher_id', $id)->first();    
        // echo '<pre>'; print_r($teacher->specialHoursAvaibilities); die;    
//        $specialHoursAvaibilities       = $teacher->specialHoursAvaibilities()->where('location_id', $request->input('location_id'))
//                                                                                ->groupBy('date')
//                                                                                ->get();
//
//        $specialHoursUnavaibilities     = $teacher->specialHoursUnavaibilities()->where('location_id', $request->input('location_id'))
//                                                                                ->groupBy('date')
//                                                                                ->get();

        $specialHoursAvaibilities = Teacher::selectRaw('teacher.teacher_id, location_id, date, COUNT(`date`)')
                                            ->join('private_classes_specific_date_availability', 'private_classes_specific_date_availability.teacher_id', 'teacher.teacher_id')
                                            ->where('teacher.teacher_id', $id)
                                            ->where('location_id', $request->input('location_id'))
                                            ->groupBy('date', 'teacher.teacher_id', 'location_id')
                                            ->get();

        $specialHoursUnavaibilities = Teacher::selectRaw('teacher.teacher_id, location_id, date, COUNT(`date`)')
                                            ->join('private_classes_specific_date_unavailability', 'private_classes_specific_date_unavailability.teacher_id', 'teacher.teacher_id')
                                            ->where('location_id', $request->input('location_id'))
                                            ->where('teacher.teacher_id', $id)
                                            ->groupBy('date', 'teacher.teacher_id', 'location_id')
                                            ->get();

        $teacher->specialHoursView      = (string) \View::make('studio.private.partials.modal-add-special-hours', array('teacher' => $teacher, 'specialHoursAvaibilities' => $specialHoursAvaibilities, 'specialHoursUnavaibilities' => $specialHoursUnavaibilities, 'timing_list' =>  getTimingList()));

        return response()->json($teacher);
    }
}
