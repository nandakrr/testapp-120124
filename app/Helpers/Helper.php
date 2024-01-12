<?php

if (!function_exists('encrypt_password')) {
    /**
     * Returns a human readable file size
     *
     * @param integer $bytes
     * Bytes contains the size of the bytes to convert
     *
     * @param integer $decimals
     * Number of decimal places to be returned
     *
     * @return string a string in human readable format
     *
     * */

    // function encrypt($string){
    //     $key = "dsfsdfsdfsd3sdfsd32213";
    //         $iv = mcrypt_create_iv(
    //     mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC),
    //     MCRYPT_DEV_URANDOM
    // );

    // $encrypted = base64_encode(
    //     $iv .
    //     mcrypt_encrypt(
    //         MCRYPT_RIJNDAEL_128,
    //         hash('sha256', $key, true),
    //         $string,
    //         MCRYPT_MODE_CBC,
    //         $iv
    //     )
    // );
    // }

    // function decrypt($encrypted){
    //     $key = "dsfsdfsdfsd3sdfsd32213";
    //     $data = base64_decode($encrypted);
    //     $iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));

    //     $decrypted = rtrim(
    //         mcrypt_decrypt(
    //             MCRYPT_RIJNDAEL_128,
    //             hash('sha256', $key, true),
    //             substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC)),
    //             MCRYPT_MODE_CBC,
    //             $iv
    //         ),
    //         "\0"
    //     );
    // }
    define('SECRETKEY', 'bigtoe');


    function encrypt_password($plaintext)
    {
        // $key="";
        // $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        // $iv = openssl_random_pseudo_bytes($ivlen);
        // $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        // $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
        // $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
        // return $ciphertext;
        $cipher = openssl_encrypt(json_encode($plaintext), "AES-128-ECB", SECRETKEY);
        return $cipher;

    }

    function decrypt_password($ciphertext)
    {
        // $key="";
        // $c = base64_decode($ciphertext);
        // $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        // $iv = substr($c, 0, $ivlen);
        // $hmac = substr($c, $ivlen, $sha2len=32);
        // $ciphertext_raw = substr($c, $ivlen+$sha2len);
        // $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        // $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
        // if (hash_equals($hmac, $calcmac))
        // {
        //     return $original_plaintext;
        // }
        // return null;
        $cipher = json_decode(openssl_decrypt($ciphertext, "AES-128-ECB", SECRETKEY));
        return $cipher;

    }


    /**
     * get the location ID
     */
    function get_location_id()
    {

        if (empty($_COOKIE['location_id'])) {

            $location_id = \App\Location::join('studio_location', 'location.location_id', '=', 'studio_location.location_id')
                ->where('studio_location.studio_id', \Session::get('auth')['admin_id'])
                ->first()->location_id;
        } else {

            $location_id = $_COOKIE['location_id'];
        }

        return $location_id;
    }


    /**
     * get the studio ID
     */
    function get_studio_id()
    {

        $teacher = Teacher::find($request->session()->get('auth')['teacher_id']);

        if (empty($_COOKIE['studio_id'])) {

            $studio_id = $teacher->studios->where('admin_id', current($teacher->studios)[0]->admin_id)->first()->studio_id;
        } else {

            $studio_id = $_COOKIE['studio_id'];
        }

        return $studio_id;
    }


    /**
     * check if teacher has any booking by using the teacher ID,
     * week index like 0,1 and a cookie of location ID
     */
    function is_teacher_booked_timing($teacher_id, $week_index)
    {

        $privateClassesWeekdayAvailabilitysCount = \App\PrivateClassesWeekdayAvailability::where('location_id', get_location_id())
            ->where('teacher_id', $teacher_id)
            ->where('day_of_week', $week_index)
            ->get()
            ->count();

        return $privateClassesWeekdayAvailabilitysCount;
    }

    /**
     * check if teacher has any special avaibilities by using the teacher ID,
     * week index like 0,1 and a cookie of location ID
     */
    function has_special_hours($teacher_id)
    {

        $privateClassesWeekdayAvailabilitysCount = \App\PrivateClassesSpecificDateAvailability::where('location_id', get_location_id())
            ->where('teacher_id', $teacher_id)
            ->get()
            ->count();

        $privateClassesSpecificDateUnavailabilityCount = \App\PrivateClassesSpecificDateUnavailability::where('location_id', get_location_id())
            ->where('teacher_id', $teacher_id)
            ->get()
            ->count();


        // return true if any of the table contains data for the specific teacher
        return ($privateClassesWeekdayAvailabilitysCount || $privateClassesSpecificDateUnavailabilityCount);
    }


    /**
     *
     */

    function is_location_booked_timing($location_id, $week_index)
    {

        // echo $location_id; die;

        $privateClassesWeekdayAvailabilitysCount = \App\PrivateClassesWeekdayAvailability::where('teacher_id', \Session::get('auth')['teacher_id'])
            ->where('location_id', $location_id)
            ->where('day_of_week', $week_index)
            ->get()
            ->count();

        return $privateClassesWeekdayAvailabilitysCount;
    }

    function getNeighborhood($neighborhoodId)
    {
        $result = DB::table('neighborhoods')->where('neighborhood_id',$neighborhoodId)->first();
        if(!empty($result))
        {
            return $result->name;
        }
        return '';
    }

    /**
     * check if teacher has any special avaibilities by using the teacher ID,
     * week index like 0,1 and a cookie of location ID
     */
    function location_has_special_hours($location_id)
    {

        $privateClassesWeekdayAvailabilitysCount = \App\PrivateClassesSpecificDateAvailability::where('location_id', $location_id)
            ->where('teacher_id', \Session::get('auth')['teacher_id'])
            ->get()
            ->count();

        $privateClassesSpecificDateUnavailabilityCount = \App\PrivateClassesSpecificDateUnavailability::where('location_id', $location_id)
            ->where('teacher_id', \Session::get('auth')['teacher_id'])
            ->get()
            ->count();


        // return true if any of the table contains data for the specific teacher
        return ($privateClassesWeekdayAvailabilitysCount || $privateClassesSpecificDateUnavailabilityCount);
    }


    /**
     * filter the input by same weekday and return newly generate list
     */
    function filter_as_weekdays($classModels, $classModel)
    {
        $filteredClasses = array();

        // filter the result by same weekday
        foreach ($classModels as $class) {

            if (date('w', strtotime($class->date)) == date('w', strtotime($classModel->date))) {

                array_push($filteredClasses, $class);
            }
        }

        return $filteredClasses;
    }

    /**
     *
     */
    function get_special_hour_availability_timings($date, $teacher_id, $location_id)
    {

        return \App\PrivateClassesSpecificDateAvailability::where('date', $date)
            ->where('location_id', $location_id)
            ->where('teacher_id', $teacher_id)
            ->get();
    }

    /**
     * get week list by week name
     */
    function getWeekList()
    {

        // get week list
        $timestamp = strtotime('next Sunday');
        $week_lists = array();

        for ($i = 0; $i < 7; $i++) {
            $week_lists[] = strftime('%a', $timestamp);
            $timestamp = strtotime('+1 day', $timestamp);
        }

        return $week_lists;
    }


    /**
     * get timing list of format 0:00 am/pm
     */
    function getTimingList()
    {

        // timing lists
        $open_time = strtotime("5:00");
        $close_time = strtotime("22:00");
        $now = time();
        // $output         = "";
        $timing_list = "";

        for ($i = $open_time; $i < $close_time; $i += 900) {

            // if ( $i < $now ) continue;

            // $output         .= "<option>".date("l - h:i", $i)."</option>";
            $timing_list .= '<a class="dropdown-item" href="#">' . date("g:i a", $i) . '</a>';
        }

        return $timing_list;
    }


    /**
     * check if the weekday has been booked by the current logged in teacher
     */
    function has_weekday_booked_by_teacher($teacher_id, $weekday)
    {
        $teacher = \App\Teacher::find($teacher_id);

        if (!empty($teacher->weeklyAvailabilities)) {

            foreach ($teacher->weeklyAvailabilities as $weeklyAvailability) {

                if ($weeklyAvailability->weekday == $weekday) {

                    return true;
                }
            }
        }

        return false;
    }


    /**
     * get availability info based on the teacher Id and weekday
     */
    function get_teacher_availability($teacher_id, $weekday)
    {
        $teacher = \App\Teacher::find($teacher_id);
        $availabilityList = array();

        if (!empty($teacher->weeklyAvailabilities)) {

            foreach ($teacher->weeklyAvailabilities as $weeklyAvailability) {

                if ($weeklyAvailability->weekday == $weekday) {

                    array_push($availabilityList, $weeklyAvailability);
                }
            }
        }

        return $availabilityList;
    }

    /**
     *
     */
    function get_specific_hour_availability_timings($date, $teacher_id)
    {
        return \App\TeacherOnDemandSpecificDateAvailability::where('date', $date)
            ->where('teacher_id', $teacher_id)
            ->get();
    }


    /**
     *
     */
    function get_specific_availability_times($teacher_id, $date)
    {

        return \App\TeacherOnDemandSpecificDateAvailability::where('date', $date)
            ->where('teacher_id', $teacher_id)
            ->get(['from_time', 'to_time'])
            ->toArray();
    }


    /**
     * get service categories
     */
    function get_service_categories($ui = false, $with_existing=false)
    {
        // Service categories
        $serviceCategories = \App\ServiceCategory::orderBy('service_category')
            ->get();

        $html = '<option value="">Select Service</option>';

        // if ui is true, returns the output with <li>
        if ($ui) {

            foreach ($serviceCategories as $serviceCategory) {

                if ( $with_existing === false ) {

                    if (!is_all_skills_used($serviceCategory)) {

                        $html .= '<option value = "' . $serviceCategory->service_categories_id . '" data-attr="' . $serviceCategory->skills->count() . '">' . $serviceCategory->service_category . '</option>';
                    }
                } else {

                    $html .= '<option value = "' . $serviceCategory->service_categories_id . '" data-attr="' . $serviceCategory->skills->count() . '">' . $serviceCategory->service_category . '</option>';
                }
            }

            return $html;
        } else {

            return $serviceCategories;
        }
    }


    /**
     * get service categories
     */
    function get_skills_ui($service_category_id, $ui = true, $with_existing=false)
    {

        $skills = \App\Competence::where('service_category_id', $service_category_id)
            ->orderBy('skill_id')
            ->get();

        $filteredSkills = array();


        // filter skills
        if ($skills->count()) {

            // get all the teacher's existing skills to check
            // whether any of the skills already exists
            $teacherSkills = \App\TeacherSkill::where('teacher_id', \App\Teacher::find(session('auth')['teacher_id'])->teacher_id)
                ->get();

            foreach ($skills as $skill) {

                $status = false;

                if ( $with_existing === false ) {

                    // checking the match skill
                    foreach ($teacherSkills as $teacherSkill) {

                        if ($skill->skill_id == $teacherSkill->skill_id) {

                            // found the matching
                            $status = true;
                            break;
                        }
                    }

                    // we do not include the skill that already present on the teacher table
                    if (!$status) {

                        array_push($filteredSkills, $skill);
                    }
                } else {

                    array_push($filteredSkills, $skill);
                }
            }


            // if UI is true
            if ( $ui ) {

                // create the HTML <select> for the skills
                $html = '<select class="form-control service-list" name="skill_id" required>';
                $html .= '<option value="">Select Skill</option>';

                foreach ($filteredSkills as $filteredSkill) {

                    $html .= '<option value="' . $filteredSkill->skill_id . '">' . $filteredSkill->skills . '</option>';
                }

                $html .= '</select>';

                return $html;
            } else {

                return $filteredSkills;
            }
        }

        return false;
    }


    function is_all_skills_used($serviceCategory)
    {
        $counter = 0;

//        echo '<pre>'; print_r($serviceCategory->skills); die;

        foreach ( $serviceCategory->skills as $skill ) {

            $teacherSkill = \App\TeacherSkill::where('teacher_id', \App\Teacher::find(session('auth')['teacher_id'])->teacher_id)
                                            ->where('skill_id', $skill->skill_id)
                                            ->first();

            if ( !empty($teacherSkill) ) {

                $counter++;
            }
        }

        return ( $serviceCategory->skills->count() == $counter );
    }


    /*
     * get skill's service category
     *
     */
    function get_skill_service_category($skill_id)
    {
        return \App\Competence::where('skill_id', $skill_id)
                                ->orderBy('skill_id')
                                ->first();
    }


    /**
     * display teacher image
     * $image_name can be a full path name or may have only image name
     * if it represent only name, have to added the path before display
     * this can be done checking if the / exists on the db value
     */
    function display_image($image_name)
    {
        if ( strpos($image_name, '/') === false ) {

            return asset(env('TEACHER_IMAGE_UPLOAD_DIR') . $image_name);
        } else {

            if ( strpos($image_name, 'teacher/profile') !== false ) {

                return asset(env('TEACHER_IMAGE_UPLOAD_DIR') . basename($image_name));
            }
        }

        return $image_name;
    }

    function getStyle($location_service_id='')
    {
        $response = DB::table('style')->where('location_service_id',$location_service_id)->get();
        return $response;
    }
}