<?php

namespace App\Http\Controllers\studio;

use App\Http\Controllers\Controller;
use App\PrivateClass;
use Illuminate\Http\Request;
use App\StudioLocation;
use App\Style;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $privateClasses     = PrivateClass::orderBy('class_date')->paginate(20);

        /* this code need for the LOCATION filter to work */
        // $privateClasses     = PrivateClass::join('class', 'class.class_id', 'private_classes.class_id')
        //                                     ->where('class.location_id', get_location_id())
        //                                     ->orderBy('class_date')
        //                                     ->paginate(20);

        $location           = $request->input('locationId');
        $locations          = StudioLocation::with('Location')->where('studio_id', $request->session()->get('auth')['admin_id'])->get();
        
        $styles             = Style::where('studio_id', $request->session()->get('auth')['admin_id'])->get();
        $studio_teachers    = \App\StudioTeacher::with('Teacher')->where('studio_id', $request->session()->get('auth')['admin_id'])->get();

        // if ( isset($location) ) {

        //     $studio_class->where('location_id', $location);
        //     $pending_class->where('location_id', $location);
        //    } elseif ($locations && isset($locations[0])) {

        //     $studio_class->where('location_id', $locations[0]->location_id);
        //     $pending_class->where('location_id', $locations[0]->location_id);
        // }

        return view('studio.private.bookings.list', compact('privateClasses', 'locations', 'styles', 'studio_teachers'));
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
}
