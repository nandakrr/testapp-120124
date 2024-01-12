<?php

namespace App\Http\Controllers\teacher;

use App\Competence;
use App\TeacherEducation;
use App\TeacherOnDemandSpecificDateAvailability;
use App\TeacherOnDemandSpecificDateUnavailability;
use App\TeacherOnDemandWeeklyAvailability;
use App\TeacherSkill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StudioTeacher;
use Intervention\Image\Facades\Image;
use Session;
use Illuminate\Support\Facades\Validator;


use App\PrivateClassesWeekdayAvailability;
use App\PrivateClassesSpecificDateAvailability;
use App\PrivateClassesSpecificDateUnavailability;
use App\Teacher;
use App\TeacherImage;
use App\TeacherAvailability;

class TeacherController extends Controller
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
    public function index(Request $request)
    {
        // print_r($request->session()->get('auth')); die; // teacher_id

        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);
        // echo '<pre>'; print_r(current($teacher->studios)[0]->admin_id); die;

        if (empty($_COOKIE['studio_id'])) {

            $teacherStudio = $teacher->studios->where('admin_id', current($teacher->studios)[0]->admin_id)->first();
        } else {

            $teacherStudio = $teacher->studios->where('admin_id', $_COOKIE['studio_id'])->first();
        }

        $studioLocations = \App\Location::join('studio_location', 'studio_location.location_id', 'location.location_id')
            ->join('studio', 'studio.admin_id', 'studio_location.studio_id')
            ->where('studio.admin_id', $teacherStudio->admin_id)
            ->orderBy('location.name1')
            ->paginate(20);

        // echo '<pre>'; print_r($studioLocations); die;

        // foreach ( $studioLocations as $location ) {

        //     if ( !empty($_COOKIE['studio_id']) ) {
        //         $location->avaibilities = $location->avaibilities->where('studio_id', $_COOKIE['studio_id']);
        //     } else {
        //         $location->avaibilities = $location->avaibilities->where('studio_id', current($teacher->studios)[0]->admin_id);
        //     }
        // } 

        foreach ($studioLocations as $studioLocation) {

            $studioLocation->avaibilities = $studioLocation->avaibilities->where('teacher_id', $teacher->teacher_id);
        }

        $specificDateAvailabilities = Teacher::selectRaw('teacher.teacher_id, date, COUNT(`date`)')
            ->join('teacher_on_demand_specific_date_availability', 'teacher_on_demand_specific_date_availability.teacher_id', 'teacher.teacher_id')
            ->where('teacher.teacher_id', $request->session()->get('auth')['teacher_id'])
            ->groupBy('date', 'teacher.teacher_id')
            ->get();

        $specificDateUnavailabilities = Teacher::selectRaw('teacher.teacher_id, date, COUNT(`date`)')
            ->join('teacher_on_demand_specific_date_unavailability', 'teacher_on_demand_specific_date_unavailability.teacher_id', 'teacher.teacher_id')
            ->where('teacher.teacher_id', $request->session()->get('auth')['teacher_id'])
            ->groupBy('date', 'teacher.teacher_id')
            ->get();

        return view('teacher.private.list', compact('teacher', 'teacherStudio', 'studioLocations', 'specificDateAvailabilities', 'specificDateUnavailabilities'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAvailability(Request $request)
    {
        // print_r($request->session()->get('auth')); die; // teacher_id

        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);
        // echo '<pre>'; print_r($teacher); die;

        if (empty($_COOKIE['studio_id'])) {

            if ( !empty(current($teacher->studios)[0]) )
                $teacherStudio = $teacher->studios->where('admin_id', current($teacher->studios)[0]->admin_id)->first();
            else
                $teacherStudio = null;

        } else {

            $teacherStudio = $teacher->studios->where('admin_id', $_COOKIE['studio_id'])->first();
        }

        $specificDateAvailabilities = Teacher::selectRaw('teacher.teacher_id, date, COUNT(`date`)')
            ->join('teacher_on_demand_specific_date_availability', 'teacher_on_demand_specific_date_availability.teacher_id', 'teacher.teacher_id')
            ->where('teacher.teacher_id', $request->session()->get('auth')['teacher_id'])
            ->groupBy('date', 'teacher.teacher_id')
            ->get();

        $specificDateUnavailabilities = Teacher::selectRaw('teacher.teacher_id, date, COUNT(`date`)')
            ->join('teacher_on_demand_specific_date_unavailability', 'teacher_on_demand_specific_date_unavailability.teacher_id', 'teacher.teacher_id')
            ->where('teacher.teacher_id', $request->session()->get('auth')['teacher_id'])
            ->groupBy('date', 'teacher.teacher_id')
            ->get();

        return view('teacher.availability.list', compact('teacher', 'teacherStudio', 'studioLocations', 'specificDateAvailabilities', 'specificDateUnavailabilities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teacherModel = new Teacher;
        $this->layout = null;
        $teachers = Teacher::pluck('email');
        return view('teacher.addteachermodal', compact('teacherModel', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request_data = $request->all();
        $auth_session = \ Session::get('auth');
        $studio_id = $auth_session['admin_id'];


        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'firstname' => 'required',
            'lastname' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 400]);
        } else {

            $teacher = Teacher::where('email', $request->input(['email']))->first();


            if (empty($teacher)) {
                $data = Teacher::create([
                    'firstname' => $request_data['firstname'],
                    'lastname' => $request_data['lastname'],
                    'email' => $request_data['email'],
                    'password' => encrypt_password('123456'),
                    'role' => 'teacher'
                ]);
                $teacher_id = $data->id;

                $data1 = new StudioTeacher;
                $data1['studio_id'] = $studio_id;
                $data1['teacher_id'] = $teacher_id;
                $data1->save();

                $teacherName = $request_data['firstname'] . ' ' . $request_data['lastname'];

                $resultArray = array('id' => $teacher_id, 'name' => $teacherName);

                return response()->json(['success' => 'Add Successfully.', 'status' => 200, 'statussuccess' => 1, 'taecherarray' => $resultArray]);

            } else {
                if (!empty($teacher)) {
                    $alreadyExitTeacher = StudioTeacher::where([['studio_id', $studio_id], ['teacher_id', $teacher->teacher_id]])->first();

                    if (empty($alreadyExitTeacher)) {
                        $data = new StudioTeacher;
                        $data['studio_id'] = $studio_id;
                        $data['teacher_id'] = $teacher['teacher_id'];
                        $data->save();

                        $teacherName = $teacher->firstname . '' . $teacher->lastname;

                        $resultArray = array('id' => $data->id, 'name' => $teacherName);

                        return response()->json(['success' => 'Add Successfully.', 'status' => 200, 'statussuccess' => 1, 'taecherarray' => $resultArray]);
                    } else {
                        return response()->json(['warning' => 'Warning! Teacher already exists.', 'status' => 200, 'statussuccess' => 2]);
                    }

                } else {

                    return response()->json(['W' => 'Warning! Teacher not found.', 'status' => 200, 'statussuccess' => 2]);
                }

                // return response()->json(['warning' => 'Warning! Teacher already exists.', 'status' => 200, 'statussuccess' => 2]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function addExistingTeacher(Request $request)
    {
        $auth_session = \ Session::get('auth');
        $studio_id = $auth_session['admin_id'];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 400]);
        } else {

            $teacher_exist = Teacher::where('email', $request->input(['email']))->first();
            if (!empty($teacher_exist)) {
                $alreadyExitTeacher = StudioTeacher::where([['studio_id', $studio_id], ['teacher_id', $teacher_exist->teacher_id]])->first();

                if (empty($alreadyExitTeacher)) {
                    $data = new StudioTeacher;
                    $data['studio_id'] = $studio_id;
                    $data['teacher_id'] = $teacher_exist['teacher_id'];
                    $data->save();

                    $teacherName = $teacher_exist->firstname . '' . $teacher_exist->lastname;

                    $resultArray = array('id' => $data->id, 'name' => $teacherName);

                    return response()->json(['success' => 'Add Successfully.', 'status' => 200, 'statussuccess' => 1, 'taecherarray' => $resultArray]);
                } else {
                    return response()->json(['warning' => 'Warning! Teacher already exists.', 'status' => 200, 'statussuccess' => 2]);
                }

            } else {

                return response()->json(['W' => 'Warning! Teacher not found.', 'status' => 200, 'statussuccess' => 2]);
            }
        }
    }




    /* ---------------------------------------------------------------------------------------------------- */


    /**
     * get teacher timings
     *
     * @param  int $id
     */
    public function getLocationTimings(Request $request, $id)
    {

        $teacher = Teacher::where('teacher_id', $request->session()->get('auth')['teacher_id'])->first();
        $avaibilities = $teacher->avaibilities();
        $location = \App\Location::find($id);

        // get week list
        $week_lists = getWeekList();


        // timing lists
        $timing_list = getTimingList();

        $location->avaibilityView = (string)\View::make('teacher.private.partials.modal-add-time', array('location' => $location, 'avaibilities' => $avaibilities, 'teacher' => $teacher, 'week_lists' => $week_lists, 'timing_list' => $timing_list));

        // echo '<pre>'; print_r($_COOKIE); die;

        return response()->json($location);
    }


    public function saveTeacherTimings(Request $request)
    {

        // echo '<pre>'; print_r($request->all()); die;

        $timings = $request->input('timings');

        // clear the matched data to save new items
        $this->clearMatchedData($request->input('location_id'), $request->session()->get('auth')['teacher_id']);

        // 
        foreach ($timings['status'] as $k => $week) {

            if (!empty($timings['from'][$k])) {

                foreach ($timings['from'][$k] as $i => $from_time) {

                    $privateClassesWeekdayAvailability = new PrivateClassesWeekdayAvailability();
                    $privateClassesWeekdayAvailability->location_id = $request->input('location_id');
                    $privateClassesWeekdayAvailability->teacher_id = $request->session()->get('auth')['teacher_id'];
                    $privateClassesWeekdayAvailability->service_categories_id = 1;
                    $privateClassesWeekdayAvailability->day_of_week = $k;
                    $privateClassesWeekdayAvailability->from_time = $from_time;
                    $privateClassesWeekdayAvailability->to_time = $timings['to'][$k][$i];
                    $privateClassesWeekdayAvailability->save();
                }
            }
        }

        return response()->json(['status' => 'success', 'url' => route('teacher.private-module')]);
    }


    /**
     * match all the data related to location_id and teacher_id
     * delete all the match data
     *
     * @param int $location_id
     * @param int $teacher_id
     * @param boolean $special_hours_entry
     */
    private function clearMatchedData($location_id, $teacher_id, $special_hours_entry = false)
    {

        if ($special_hours_entry) {

            // delete the first table
            PrivateClassesSpecificDateAvailability::where('location_id', $location_id)
                ->where('teacher_id', $teacher_id)
                ->delete();

            // delete the last table
            PrivateClassesSpecificDateUnavailability::where('location_id', $location_id)
                ->where('teacher_id', $teacher_id)
                ->delete();

        } else {

            $privateClassesWeekdayAvailabilitys = PrivateClassesWeekdayAvailability::where('location_id', $location_id)
                ->where('teacher_id', $teacher_id)
                ->delete();
        }
    }


    /**
     * match all the data related to location_id and teacher_id
     * delete all the match data
     *
     * @param int $location_id
     * @param int $teacher_id
     * @param boolean $special_hours_entry
     */
    private function clearTeacherMatchedData($teacher_id, $special_hours_entry = false)
    {

        if ($special_hours_entry) {

            // delete the first table
            TeacherOnDemandSpecificDateAvailability::where('teacher_id', $teacher_id)->delete();

            // delete the last table
            TeacherOnDemandSpecificDateUnavailability::where('teacher_id', $teacher_id)->delete();

        } else {

            TeacherOnDemandWeeklyAvailability::where('teacher_id', $teacher_id)->delete();
        }
    }


    /**
     * remove data
     */
    public function removeTeacherTimings(Request $request)
    {

        $result = TeacherOnDemandWeeklyAvailability::where('teacher_id', $request->session()->get('auth')['teacher_id'])
            ->delete();

        if ($result) {
            return response()->json(['status' => 'success', 'url' => route('teacher.private-module')]);
        }

        return response()->json(['status' => 'error', 'message' => __('Something went wrong. Please try again later')]);
    }


    /**
     * save special hours
     */
    public function saveSpecialHours(Request $request)
    {

        // echo '<pre>'; print_r($request->all()); die;

        $special_hours = $request->input('special_hours');

        // clear the matched data to save new items
        $this->clearMatchedData($request->input('location_id'), $request->session()->get('auth')['teacher_id'], true);

        // form date data entry
        if (!empty($special_hours['from_date'])) {

            foreach ($special_hours['from_date'] as $k => $from_date) {

                // this data will go to unavaibility table
                // if ( ($k != $special_hours['avaibility'][$k]) ) {
                if (array_search($k, $special_hours['avaibility']) === FALSE) {

                    $privateClassesSpecificDateUnavailability = new PrivateClassesSpecificDateUnavailability();
                    $privateClassesSpecificDateUnavailability->location_id = $request->input('location_id');
                    $privateClassesSpecificDateUnavailability->teacher_id = $request->session()->get('auth')['teacher_id'];
                    $privateClassesSpecificDateUnavailability->service_categories_id = 1;
                    $privateClassesSpecificDateUnavailability->date = date('Y-m-d', strtotime($from_date));
                    $privateClassesSpecificDateUnavailability->save();
                } else {

                    $privateClassesSpecificDateAvailability = new PrivateClassesSpecificDateAvailability();
                    $privateClassesSpecificDateAvailability->location_id = $request->input('location_id');
                    $privateClassesSpecificDateAvailability->teacher_id = $request->session()->get('auth')['teacher_id'];
                    $privateClassesSpecificDateAvailability->service_categories_id = 1;
                    $privateClassesSpecificDateAvailability->date = date('Y-m-d', strtotime($from_date));
                    $privateClassesSpecificDateAvailability->from_time = $special_hours['from'][$k];
                    $privateClassesSpecificDateAvailability->to_time = $special_hours['to'][$k];
                    $privateClassesSpecificDateAvailability->save();
                }
            }
        }

        // to date data entry
        if (!empty($special_hours['to_date'])) {

            foreach ($special_hours['to_date'] as $k => $to_date) {

                if ($special_hours['from_date'][$k] !== $to_date) {

                    // this data will go to unavaibility table
                    if (array_search($k, $special_hours['avaibility']) === FALSE) {

                        $privateClassesSpecificDateUnavailability = new PrivateClassesSpecificDateUnavailability();
                        $privateClassesSpecificDateUnavailability->location_id = $request->input('location_id');
                        $privateClassesSpecificDateUnavailability->teacher_id = $request->session()->get('auth')['teacher_id'];
                        $privateClassesSpecificDateUnavailability->service_categories_id = 1;
                        $privateClassesSpecificDateUnavailability->date = date('Y-m-d', strtotime($to_date));
                        $privateClassesSpecificDateUnavailability->save();
                    } else {

                        $privateClassesSpecificDateAvailability = new PrivateClassesSpecificDateAvailability();
                        $privateClassesSpecificDateAvailability->location_id = $request->input('location_id');
                        $privateClassesSpecificDateAvailability->teacher_id = $request->session()->get('auth')['teacher_id'];
                        $privateClassesSpecificDateAvailability->service_categories_id = 1;
                        $privateClassesSpecificDateAvailability->date = date('Y-m-d', strtotime($to_date));
                        $privateClassesSpecificDateAvailability->from_time = $special_hours['from'][$k];
                        $privateClassesSpecificDateAvailability->to_time = $special_hours['to'][$k];
                        $privateClassesSpecificDateAvailability->save();
                    }
                }
            }
        }

        return response()->json(['status' => 'success', 'url' => route('teacher.private-module')]);
    }


    /**
     * Remove special hours from two table
     */
    function removeSpecialHours(Request $request, $teacher_id)
    {

        // delete the first table
        TeacherOnDemandSpecificDateAvailability::where('teacher_id', $teacher_id)
            ->delete();

        // delete the last table
        $result = TeacherOnDemandSpecificDateUnavailability::where('teacher_id', $teacher_id)
            ->delete();

        if ($result) {
            return response()->json(['status' => 'success', 'url' => route('teacher.private-module')]);
        }

        return response()->json(['status' => 'error', 'message' => __('Something went wrong. Please try again later')]);
    }


    /**
     * get special hours view
     */
    public function getSpecialHours(Request $request, $id)
    {

        $teacher = Teacher::where('teacher_id', $request->session()->get('auth')['teacher_id'])->first();
        // echo '<pre>'; print_r($teacher->specialHoursAvaibilities); die;    
        $specialHoursAvaibilities = $teacher->specialHoursAvaibilities()->where('location_id', $id)->get();
        $specialHoursUnavaibilities = $teacher->specialHoursUnavaibilities()->where('location_id', $id)->get();
        $teacher->specialHoursView = (string)\View::make('studio.private.partials.modal-add-special-hours', array('teacher' => $teacher, 'specialHoursAvaibilities' => $specialHoursAvaibilities, 'specialHoursUnavaibilities' => $specialHoursUnavaibilities, 'timing_list' => getTimingList()));

        return response()->json($teacher);
    }

    /**
     * get teacher profile information
     */
    public function getProfileInformation(Request $request, $slug = 'basic-information')
    {

        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);
        $slug = $slug;


        $specificDateAvailabilities = Teacher::selectRaw('teacher.teacher_id, date, COUNT(`date`)')
            ->join('teacher_on_demand_specific_date_availability', 'teacher_on_demand_specific_date_availability.teacher_id', 'teacher.teacher_id')
            ->where('teacher.teacher_id', $request->session()->get('auth')['teacher_id'])
            ->groupBy('date', 'teacher.teacher_id')
            ->get();

        $specificDateUnavailabilities = Teacher::selectRaw('teacher.teacher_id, date, COUNT(`date`)')
            ->join('teacher_on_demand_specific_date_unavailability', 'teacher_on_demand_specific_date_unavailability.teacher_id', 'teacher.teacher_id')
            ->where('teacher.teacher_id', $request->session()->get('auth')['teacher_id'])
            ->groupBy('date', 'teacher.teacher_id')
            ->get();

        return view('teacher.profile.basic-information', compact('teacher', 'slug', 'specificDateAvailabilities', 'specificDateUnavailabilities'));
    }


    /**
     * upload profile image
     */
    public function uploadProfileImage(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'file' => 'required'
        ]);
        if ($validation->passes()) {

//            print_r("CROP" . $request->input('cropped')); die;

//            echo app_path(env('TEACHER_IMAGE_UPLOAD_DIR')); die;

            $image = $request->file('file');

            if ($request->input('cropped') == true) {

                $new_name = time() . '.png';

//                echo base_path() . DIRECTORY_SEPARATOR . env('TEACHER_IMAGE_UPLOAD_DIR') . $new_name; die;
                $new_image_path = public_path() . DIRECTORY_SEPARATOR . env('TEACHER_IMAGE_UPLOAD_DIR') . $new_name;

                // upload the cropped image
                $image_uploaded = $this->uploadCroppedImage($request, $new_image_path);
            } else {

                $new_name = rand() . '.' . $image->getClientOriginalExtension();
                $new_image_path = public_path(). DIRECTORY_SEPARATOR . env('TEACHER_IMAGE_UPLOAD_DIR') . $new_name;

                // crop the image
                Image::make($request->file('file'))
                    ->fit(config('imageup')['width'], config('imageup')['height'])
                    ->save($new_image_path, config('imageup')['resize_image_quality']);
            }

            // save image on DB
            $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);

            // remove old image from the disk
            $file_name = basename($teacher->profile_picture);
            $image_path = public_path(). DIRECTORY_SEPARATOR . env('TEACHER_IMAGE_UPLOAD_DIR') . $file_name;
            if (file_exists($image_path)) {

                @unlink($image_path);
            }

            $teacher->profile_picture = asset(env('TEACHER_IMAGE_UPLOAD_DIR') . $new_name);
            $teacher->save();

            return response()->json([
                'status' => 'success',
                'new_image' => asset('uploads/user/teacher/' . $new_name)
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()->all(),
                'uploaded_image' => '',
                'class_name' => 'alert-danger'
            ]);
        }
    }


    /**
     * upload additional image
     */
    public function uploadAdditionalImages(Request $request)
    {

//        return response()->json($request->all()); die;

        $validation = Validator::make($request->all(), [
            'file' => 'required'
        ]);
        if ($validation->passes()) {

            $image = $request->file('file');

            if ($request->input('cropped') == 'true') {

                $new_name = time() . '.png';
                $new_image_path = public_path() . DIRECTORY_SEPARATOR . env('TEACHER_IMAGE_UPLOAD_DIR') . $new_name;

                // upload the cropped image
                $image_uploaded = $this->uploadCroppedImage($request, $new_image_path);
            } else {

                $new_name = rand() . '.' . $image->getClientOriginalExtension();
                $new_image_path = public_path() . DIRECTORY_SEPARATOR . env('TEACHER_IMAGE_UPLOAD_DIR') . $new_name;

                // crop the image
                Image::make($request->file('file'))
                    ->fit(config('imageup')['width'], config('imageup')['height'])
                    ->save($new_image_path, config('imageup')['resize_image_quality']);
            }

            // save image on DB
            $teacherImage               = new TeacherImage();
            $teacherImage->teacher_id   = $request->session()->get('auth')['teacher_id'];
            $teacherImage->image_url    = asset(env('TEACHER_IMAGE_UPLOAD_DIR') . $new_name);
            $teacherImage->save();

            return response()->json([
                'status' => 'success',
                'new_image' => asset(env('TEACHER_IMAGE_UPLOAD_DIR') . $new_name),
                'image_id' => $teacherImage->id,
                'limit_exceed' => (Teacher::find($request->session()->get('auth')['teacher_id'])->images->count() >= 6)
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()->all(),
                'uploaded_image' => '',
                'class_name' => 'alert-danger'
            ]);
        }

    }


    /**
     * upload cropped image
     */
    private function uploadCroppedImage(Request $request, $destination_path)
    {

        if (!empty($request->file('file'))) {

            $path = $request->file('file')->getRealPath();
            $logo = file_get_contents($path);
            $base64 = base64_encode($logo);
            $image_path = $destination_path;
            $success = file_put_contents($image_path, base64_decode($base64));

            return $success;
        }

        return false;
    }


    /**
     * remove additional image
     */
    public function removeAdditionalImage(Request $request)
    {

        $teacherImage = TeacherImage::find($request->input('image_id'));
        if (!empty($teacherImage)) {

            $teacherImage->delete();

            // remove image from the disk
            $file_name = basename($teacherImage->image_url);
            $image_path = public_path(env('TEACHER_IMAGE_UPLOAD_DIR')) . $file_name;
            if (file_exists($image_path)) {

                @unlink($image_path);
            }

            return response()->json([
                'status' => 'success',
                'limit_exceed' => (Teacher::find($request->session()->get('auth')['teacher_id'])->images->count() >= 6)
            ]);
        } else {

            return response()->json([
                'status' => 'error'
            ]);
        }
    }


    /**
     * save profile
     */
//    public function saveProfile(Request $request)
//    {
//
//        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);
////        $teacher->firstname = $request->input('firstname');
////        $teacher->lastname = $request->input('lastname');
////        $teacher->email = $request->input('email');
//        $teacher->dob = date('Y-m-d', strtotime($request->input('dob')));
//        $teacher->gender = $request->input('gender');
//        $teacher->address_1 = $request->input('address_1');
//        $teacher->phone_no = $request->input('phone_no');
//
//        $teacher->save();
//
//        return response()->json([
//            'status' => 'success',
//            'message' => 'Profile has been saved successfully!'
//        ]);
//    }


    /**
     * save profile
     */
    public function saveProfile(Request $request, $info)
    {
        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);
        $saved = false;

        // save information based on field type
        if ($info == 'address') {

            $teacher->address_1 = $request->input('address');
        } elseif ($info == 'phone_no') {

            $teacher->phone_no = $request->input('phone_no');
        } elseif ($info == 'gender') {

            $teacher->gender = $request->input('gender');
        } elseif ($info == 'website') {

            $teacher->website = $request->input('website');
        } elseif ($info == 'instagram') {

            $teacher->instagram = $request->input('instagram');
        } elseif ($info == 'common_address') {

            $teacher->common_address = $request->input('common_address');
            $teacher->common_address_latitude = $request->input('common_address_latitude');
            $teacher->common_address_longitude = $request->input('common_address_longitude');
        } elseif ($info == 'adjectives') {

            if (!empty($request->input('adjective1'))) {
                $teacher->adjective1 = $request->input('adjective1');
            }

            if (!empty($request->input('adjective2'))) {
                $teacher->adjective2 = $request->input('adjective2');
            }
        } elseif ($info == 'summary') {

            $teacher->summary = $request->input('summary');
        } elseif ($info == 'about') {

            $teacher->about = $request->input('about');
        } elseif ($info == 'payee') {

            $teacher->payee = $request->input('payee');
        } elseif ($info == 'services') {

//            echo '<pre>'; print_r($request->all()); die;

            // get the skill
            $skill = Competence::find($request->input('skill_id'));

            // check for existing skill
            $oldTeacherSkill = TeacherSkill::where('teacher_id', Teacher::find($request->session()->get('auth')['teacher_id'])->teacher_id)
                ->where('skill_id', $request->input('skill_id'))
                ->first();

            // if skill is present, perform update, else perform insert operation
            if (!empty($oldTeacherSkill)) {

                $teacherSkill = $oldTeacherSkill;
            } else {

                $teacherSkill = new TeacherSkill();
            }

            $teacherSkill->teacher_id = Teacher::find($request->session()->get('auth')['teacher_id'])->teacher_id;
            $teacherSkill->skill_id = $request->input('skill_id');
            $teacherSkill->name = $skill->skills;
            $teacherSkill->description = $request->input('description');
            $teacherSkill->price1 = $request->input('price1');
            $teacherSkill->price2 = 20;
            $teacherSkill->save();

            $saved = true;
        } elseif ($info == 'update-services') {

//            echo '<pre>'; print_r($request->all()); die;

            // get the skill
            $skill = Competence::find($request->input('skill_id'));

            $teacherSkill = TeacherSkill::find($request->input('teacher_skills_id'));
            $teacherSkill->teacher_id = Teacher::find($request->session()->get('auth')['teacher_id'])->teacher_id;
            $teacherSkill->skill_id = $request->input('skill_id');
            $teacherSkill->name = $skill->skills;
            $teacherSkill->description = $request->input('description');
            $teacherSkill->price1 = $request->input('price1');
            $teacherSkill->price2 = 20;
            $teacherSkill->save();

            $saved = true;
        }

        // $saved is used to indicate whether other table will be save instead of teacher table
        // if it's true, that mean teacher table will not update
        // because, the required table already saved it's data
        if (!$saved) {

            $teacher->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Profile has been saved successfully!',
            'url' => route('teacher.profile')
        ]);
    }





    /* ---------------------------------------------------------------------------------------------------------------- */


    /**
     * get teacher weekly availability
     */
    public function getWeeklyAvailability(Request $request)
    {

        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);

        // get week list
        $week_lists = getWeekList();

        // timing lists
        $timing_list = getTimingList();

        $teacher->avaibilityView = (string)\View::make('teacher.partials.modal-weekly-availability', array('teacher' => $teacher, 'week_lists' => $week_lists, 'timing_list' => $timing_list));

        return response()->json($teacher);
    }


    /**
     * save teacher availability
     */
    public function saveTeacherAvailability(Request $request)
    {
        $timings = $request->input('timings');

        // clear the matched data to save new items
        $this->clearTeacherMatchedData($request->session()->get('auth')['teacher_id']);

        //
        foreach ($timings['status'] as $k => $week) {

            if (!empty($timings['from'][$k])) {

                foreach ($timings['from'][$k] as $i => $from_time) {

                    $teacherOnDemandWeeklyAvailability              = new TeacherOnDemandWeeklyAvailability();
                    $teacherOnDemandWeeklyAvailability->teacher_id  = $request->session()->get('auth')['teacher_id'];
                    $teacherOnDemandWeeklyAvailability->weekday     = $k + 1; // adjusted with 1
                    $teacherOnDemandWeeklyAvailability->from_time   = date('G:i', strtotime($from_time));
                    $teacherOnDemandWeeklyAvailability->to_time     = date('G:i', strtotime($timings['to'][$k][$i]));
                    $teacherOnDemandWeeklyAvailability->save();
                }
            }
        }

        return response()->json(['status' => 'success', 'url' => route('teacher.availability')]);
    }


    /**
     * get special hours view
     */
    public function getSpecificDate(Request $request)
    {

        $teacher = Teacher::where('teacher_id', $request->session()->get('auth')['teacher_id'])->first();
        $specificDateAvailabilities = Teacher::selectRaw('teacher.teacher_id, date, COUNT(`date`)')
            ->join('teacher_on_demand_specific_date_availability', 'teacher_on_demand_specific_date_availability.teacher_id', 'teacher.teacher_id')
            ->where('teacher.teacher_id', $request->session()->get('auth')['teacher_id'])
            ->groupBy('date', 'teacher.teacher_id')
            ->get();

        $specificDateUnavailabilities = Teacher::selectRaw('teacher.teacher_id, date, COUNT(`date`)')
            ->join('teacher_on_demand_specific_date_unavailability', 'teacher_on_demand_specific_date_unavailability.teacher_id', 'teacher.teacher_id')
            ->where('teacher.teacher_id', $request->session()->get('auth')['teacher_id'])
            ->groupBy('date', 'teacher.teacher_id')
            ->get();
        $teacher->specialHoursView = (string)\View::make('teacher.partials.modal-specific-date', array('teacher' => $teacher, 'specificDateAvailabilities' => $specificDateAvailabilities, 'specificDateUnavailabilities' => $specificDateUnavailabilities, 'timing_list' => getTimingList()));

        return response()->json($teacher);
    }


    /**
     * save special hours
     */
    public function saveSpecificDate(Request $request)
    {

//        echo '<pre>'; print_r($request->all()); die;

        $special_hours = $request->input('special_hours');

        // clear the matched data to save new items
        $this->clearTeacherMatchedData($request->session()->get('auth')['teacher_id'], true);

        // form date data entry
        if (!empty($special_hours['from_date'])) {

            foreach ($special_hours['from_date'] as $k => $from_date) {

                // this data will go to unavaibility table
                if(!isset($special_hours['avaibility']))
                {
                    $special_hours['avaibility'] = [];
                }
                
                if (array_search($k, $special_hours['avaibility']) === FALSE) {

                    // loop through start and end date to make entry in DB
                    $begin = new \DateTime($from_date);
                    $end = new \DateTime($special_hours['to_date'][$k]);

                    for ($i = $begin; $i <= $end; $i->modify('+1 day')) {

                        $teacherOnDemandSpecificDateUnavailability = new TeacherOnDemandSpecificDateUnavailability();
                        $teacherOnDemandSpecificDateUnavailability->teacher_id = $request->session()->get('auth')['teacher_id'];
                        $teacherOnDemandSpecificDateUnavailability->date = $i->format("Y-m-d"); // date('Y-m-d', strtotime($i));
                        $teacherOnDemandSpecificDateUnavailability->save();
                    }

                } else {

                    // loop through start and end date to make entry in DB
                    $begin = new \DateTime($from_date);
                    $end = new \DateTime($special_hours['to_date'][$k]);
                    for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
                       
                        foreach ($special_hours['from'][$k] as $j => $from_special_hour) {

                            $teacherOnDemandSpecificDateAvailability = new TeacherOnDemandSpecificDateAvailability();
                            $teacherOnDemandSpecificDateAvailability->teacher_id = $request->session()->get('auth')['teacher_id'];
                            $teacherOnDemandSpecificDateAvailability->date = $i->format("Y-m-d"); // date('Y-m-d', strtotime($i));
                            $teacherOnDemandSpecificDateAvailability->from_time = date('G:i', strtotime($from_special_hour));
                            $teacherOnDemandSpecificDateAvailability->to_time = date('G:i', strtotime($special_hours['to'][$k][$j]));
                            $teacherOnDemandSpecificDateAvailability->save();
                        }
                    }
                }
            }
        }

        return response()->json(['status' => 'success', 'url' => route('teacher.availability')]);
    }


    /**
     *
     */
    public function photos(Request $request)
    {

        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);

        return view('teacher.profile.photos', compact('teacher'));
    }


    /**
     * Teacher profile screen
     */
    public function teachingProfile(Request $request)
    {

        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);
        $service = 0;
        return view('teacher.profile.teaching-profile.index', compact('teacher', 'serviceCategories','service'));
    }

    public function teachingProfileServices(Request $request)
    {

        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);
        $service = 1;
        return view('teacher.profile.teaching-profile.index', compact('teacher', 'serviceCategories','service'));
    }


    /**
     * Save education
     */
    public function saveEducation(Request $request)
    {

        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);

        // remove old item before save new one
        if ($teacher->degrees->count() > 0) {

            foreach ($teacher->degrees as $degree) {

                $degree->delete();
            }
        }

        // save new degrees

        if (!empty($request->input('degree_name'))) {

            foreach ($request->input('degree_name') as $k => $degree_name) {

                $teacherEducation = new TeacherEducation();
                $teacherEducation->teacher_id = Teacher::find($request->session()->get('auth')['teacher_id'])->teacher_id;
                $teacherEducation->degree_name = $degree_name;
                $teacherEducation->degree_year = $request->input('degree_year')[$k];
                $teacherEducation->school_name = $request->input('school_name')[$k];

                $teacherEducation->save();
            }
        }

        return response()->json(['status' => 'success', 'url' => route('teacher.teaching-profile')]);
    }


    /**
     * get service skills
     */
    public function getServiceSkills(Request $request)
    {
        $serviceSkillUI = get_skills_ui($request->input('service_id'));


        return response()->json(['status' => 'success', 'html' => $serviceSkillUI]);
    }


    /**
     * get service skills
     */
    public function getServiceCategories(Request $request)
    {
        $serviceCategoryUI = get_service_categories(true);

        return response()->json(['status' => 'success', 'html' => $serviceCategoryUI]);
    }


    /**
     * get service skills
     */
    public function getTeacherServiceSkills(Request $request)
    {
        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);
        $html = (string)\View::make('teacher.partials.teacher-service-skills', array('teacher' => $teacher));

        return response()->json(['status' => 'success', 'html' => $html]);
    }


    /**
     * get service skills short info
     */
    public function getTeacherServiceSkillsShortInfo(Request $request)
    {
        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);
        $htmlShort = (string)\View::make('teacher.partials.teacher-service-skills-short-info', array('teacher' => $teacher));

        return response()->json(['status' => 'success', 'htmlShort' => $htmlShort]);
    }


    /**
     * remove skill
     */
    public function removeTeacherSkill(Request $request)
    {
        $teacherSkill = TeacherSkill::find($request->input('teacher_skills_id'));
        if (!empty($teacherSkill)) {

            $teacherSkill->delete();
        }

        return response()->json(['status' => 'success']);
    }


    /**
     * edit skill
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editTeacherSkill(Request $request)
    {
        $teacherSkill = TeacherSkill::find($request->input('teacher_skills_id'));
        if (!empty($teacherSkill)) {

            // parent categories
            $serviceCategories = get_service_categories();

            // get the current/saved service category's skills
            $skills = get_skills_ui(get_skill_service_category($teacherSkill->skill_id)->service_category_id, false);

            $html = (string)\View::make('teacher.partials.teacher-skill-edit-view', compact('teacherSkill', 'serviceCategories', 'skills'));
        } else {

            $html = "";
        }

        return response()->json(['status' => 'success', 'html' => $html]);
    }


    /**
     * show change password form
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function showChangePasswordForm(Request $request)
    {
        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);

        return view('teacher.change-password', compact('teacher'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function updatePassword(Request $request)
    {

        $validatedData = $request->validate([
            'password' => 'required|min:6|max:16|confirmed',
            'password_confirmation' => 'required|min:6|max:16',
        ]);

        if ($validatedData) {
            $input['password'] = encrypt_password($request->input('password'));

            $auth_session = \Session::get('auth');
            $teacher_id = $auth_session['teacher_id'];

            $response = Teacher::where('teacher_id', $teacher_id)->update($input);

            if ($response) {

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


    /**
     * bookings
     */
    public function bookings(Request $request)
    {
		$id=$request->session()->get('auth')['teacher_id'];
        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);
		
		$dataAry = array('token'=>'402eaa80fe26c771065d69dafa2fa16aad54146b','teacher_id'=>$id);
        $url = 'https://bigtoe.app/ProviderAppointment/getPendingRequestList';
            
        $res = $this->appCall('post',$dataAry,$url);
            
        $json_result = json_decode($res, true);
        if($json_result['ResponseMessage']=='SUCCESS'){
            $result = $json_result['Result'];
        }else{
            $result = array();
        }
        
        return view('teacher.bookings.list', compact('teacher','result'));
    }
	
	public function confirmBookings(Request $request)
    {
		$id=$request->session()->get('auth')['teacher_id'];
        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);
		
		$dataAry = array('token'=>'402eaa80fe26c771065d69dafa2fa16aad54146b','teacher_id'=>$id);
        $url = 'https://bigtoe.app/ProviderAppointment/getConfirmedRequestList';
            
        $res = $this->appCall('post',$dataAry,$url);
		
		//var_dump($res);die;
            
        $json_result = json_decode($res, true);
        if($json_result['ResponseMessage']=='SUCCESS'){
            $result = $json_result['Result'];
        }else{
            $result = array();
        }
        
        return view('teacher.bookings.confirmlist', compact('teacher','result'));
    }
	
	public function bookingDetails(Request $request, $id)
    {
		//echo $id;die;
        //$teacher = Teacher::find($request->session()->get('auth')['teacher_id']);
		$teacher_id=$request->session()->get('auth')['teacher_id'];
        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);
		
		$dataAry = array('token'=>'402eaa80fe26c771065d69dafa2fa16aad54146b','private_teacher_response_id'=>base64_decode($id),'teacher_id'=>$teacher_id);
        $url = 'https://bigtoe.app/ProviderAppointment/getPendingRequestListDetails';
            
        $res = $this->appCall('post',$dataAry,$url);
        //var_dump($res);die;
        $json_result = json_decode($res, true);
        if($json_result['ResponseMessage']=='SUCCESS'){
            $result = $json_result['Result'];
        }else{
            $result = array();
        }
        return view('teacher.bookings.details', compact('teacher','result'));
    }
	
	function providerConfirmsRequest(Request $request)
    {		
		$is_specific  = $request->input('is_specific');
		$private_teacher_response_id=$request->input('private_teacher_response_id');
		$times=$request->input('requested_time');
		$private_class_id=$request->input('private_class_id');
		//var_dump($times);die;
		//echo $id;die;
        //$teacher = Teacher::find($request->session()->get('auth')['teacher_id']);
		//$teacher_id=$request->session()->get('auth')['teacher_id'];
		
		if($request->submit == "Accept"){
			$type=1;
		}else{
			$type=0;
		}
        
		$auth_session = \Session::get('auth');
        $teacher_id = $auth_session['teacher_id'];
		
		$teacher = Teacher::find($teacher_id);
		//var_dump($teacher);
		$dataAry = array('token'=>'402eaa80fe26c771065d69dafa2fa16aad54146b','private_teacher_response_id'=>$private_teacher_response_id,'is_specific'=>$is_specific,'type'=>$type,'times'=>json_encode($times),'private_class_id'=>$private_class_id);
		//var_dump(json_encode($dataAry));die;
        $url = 'https://bigtoe.app/ProviderAppointment/providerConfirmsRequest';
            
        $res = $this->appCall('post',$dataAry,$url);
        //var_dump($res);die;
        $json_result = json_decode($res, true);
        if($json_result['ResponseMessage']=='SUCCESS'){
            $result = $json_result['Result'];
			Session::flash('message', 'Your request updated Successfully.');
            Session::flash('alert-class', 'alert-success');
		  return Redirect('teacher/bookings');
        }else{
            $result = array();
			$comment=$json_result['Comments'];
			Session::flash('message', $comment);
            Session::flash('alert-class', 'alert-danger');
		  return Redirect('teacher/bookings');
        }
	
        //return view('teacher.bookings.list', compact('teacher','result'));
    }


    /**
     * check the mandatory info of the teaching
     */
    public function checkTeachingInfo(Request $request)
    {
        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);

        if ( (empty($teacher->summary)) || (empty($teacher->about)) || ($teacher->skills->count() == 0) ) {

            $missing = true;
        } else {

            $missing = false;
        }

        return response()->json(['status' => 'success', 'missing' => $missing]);
    }
	
	public function appCall($method='',$data='',$url='')
    {
        if($method=='POST' || $method=='post')
        {
          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);          
          $result = curl_exec($ch);
          curl_close($ch);
          return $result;
        }

        if($method=='GET' || $method=='get')
        {
          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
          // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          
          $result = curl_exec($ch);
          curl_close($ch);
          return $result;
        }
    }

    public function getToken()
    {
      $response['msg'] = 'success';
      $response['token'] = '502eb263d95fa888db6064a611e725f4c2a13bb'; 
      return $response;
    }
}
