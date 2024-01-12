<?php
namespace App\Http\Controllers\studio;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\StudioLocation;
use App\Style;
use Session;
use DB;

class SettingsController extends Controller
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
    $auth_session    = \Session::get('auth');
    $studio_id       = $auth_session['admin_id'];
    $this->studio_id = $studio_id;
    $locations       = StudioLocation::with('Location')->where('studio_id', $this->studio_id)->get();  
    return view('studio.setting', compact('locations'));
 }

 public function locationServices(Request $request,$locationId)
 {
    $locationService = DB::table('location_services')->where('location_id',$locationId)->get();
	//var_dump($locationService);die;
    return view('studio.setting-data', compact('locationService'));
 }

 public function saveProgram(Request $request)
 {
    $rowId = $request->row_id;
    $price1= $request->price1;
    $price2= $request->price2;
    $price3=$request->price3;
    $price4=$request->price4;
    $price5=$request->price5;
    $price6plus=$request->price6plus;
    $new_student_price=$request->new_student_price;
    $group_rate_discounted_credits=$request->group_rate_discounted_credits;
    if($rowId!='')
    {
       $classdata = $sytledata = $locservice = [
                    'price2'=>$price2,
                    'price3'=>$price3,
                    'price4'=>$price4,
                    'price5'=>$price5,
                    'price6_plus'=>$price6plus,
                    'new_student_price'=>$new_student_price
                ];
      $locservice['group_rate_credits'] = $price1;
	  $locservice['group_rate_discounted_credits'] = $group_rate_discounted_credits;
      $sytledata['default_price_credits'] = $price1;
	  $sytledata['default_discounted_price_credits'] = $group_rate_discounted_credits;
      $classdata['price'] = $price1;
	  $classdata['discounted_price_credits'] = $group_rate_discounted_credits;

      $res = DB::table('location_services')->where('location_service_id',$rowId)->update($locservice);
      if($res)
      {
          $res1 = DB::table('style')->where('location_service_id',$rowId)->update($sytledata);
          if($res1)
          {
              $styleIds = Style::where('location_service_id',$rowId)->select('style_id')->get();
              if(count($styleIds)>0)
              {
                DB::table('class')->whereIn('style_id',$styleIds)->update($classdata);
              }              
          }
          echo 'success';
      }else{
        echo 'error';
      }

    }
 }

 public function saveStyle(Request $request)
 {
    $rowId = $request->style_id;
    $price1= $request->price1;
    $price2= $request->price2;
    $price3=$request->price3;
    $price4=$request->price4;
    $price5=$request->price5;
    $price6plus=$request->price6plus;
    $new_student_price=$request->new_student_price;
    $default_discounted_price_credits=$request->default_discounted_price_credits;
    if($rowId!='')
    {
       $udata = $classdata = [
                                'price2'=>$price2,
                                'price3'=>$price3,
                                'price4'=>$price4,
                                'price5'=>$price5,
                                'price6_plus'=>$price6plus,
                                'new_student_price'=>$new_student_price
                            ];
      $udata['default_price_credits'] = $price1;
	  $udata['default_discounted_price_credits'] = $default_discounted_price_credits;
      $classdata['price'] = $price1;
	  $classdata['discounted_price_credits'] = $default_discounted_price_credits;
      $res = DB::table('style')->where('style_id',$rowId)->update($udata);
      if($res)
      {
          DB::table('class')->whereIn('style_id',[$rowId])->update($classdata);
          echo 'success';
      }else{
        echo 'error';
      }

    }
 }

//class end
}
