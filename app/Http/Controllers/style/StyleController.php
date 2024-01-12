<?php

namespace App\Http\Controllers\style;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Style;
use App\StylePrice;
use App\StudioLocation;
use Session;
use Illuminate\Support\Facades\Validator;

class StyleController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $styleModel = new Style;
        $this->layout = null;
        return view('style.addstylemodal',compact('styleModel'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auth_session = \ Session::get('auth');
        $studio_id = $auth_session['admin_id'];

        $locations   = StudioLocation::with('Location')->where('studio_id', $studio_id)->get();
        $locations   = $locations->toArray();

        $input       = $request->all();
        $validator   = Validator::make($request->all(), [
                       'name' => 'required|min:4 | max:70',
                       'description' => 'required|min:50 | max:1000',

                    ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 400]);
        }else{

            $name = $input['name'];
            $description = $input['description'];

            $style_name = Style::where([['name', $name], ['studio_id', $studio_id]])->first();

            if(empty($style_name)){
                $style = new Style;
                $style['name']   = $name;
                $style['description']  = $description;
                $style['studio_id']    = $studio_id;
                $style->save();
                $style_name = Style::where([['name', $name], ['studio_id', $studio_id]])->first();

                $resultArray = array('id'=>$style_name->style_id, 'name'=>$name);

                
                /* Multiple entries Style price table start here */

                    if(!empty($locations) && count($locations)>0){

                        foreach ($locations as $key => $value) {                        

                            $StylePrice                             = new StylePrice;
                            $StylePrice['style_id']                 = $style_name->style_id;
                            $StylePrice['location_id']              = $value['location_id'];                             
                            $StylePrice['default_price_credit']     = $value['location']['default_price_credits'];
                            $StylePrice['default_price_dollar']     = $value['location']['default_price_dollor'];
                            $StylePrice['created_date']             = date('Y-m-d h:i:s');

                            $StylePrice->save();

                        }
                            
                    }

                /* Multiple entries Style price table end here */




                return response()->json(['success' => 'Add Successfully.', 'status' => 200, 'statussuccess' => 1 ,'styleall'=>$resultArray]);
            }else{

               return response()->json(['warning' => 'Style Name already exists.', 'status' => 200, 'statussuccess' => 2]);
            }

        }
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
