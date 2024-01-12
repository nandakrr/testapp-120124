<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Style;

class StudioClass extends Model
{
    // use Notifiable;
    public $timestamps = false;
    protected $table = 'class';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function teacher(){
       return $this->belongsTo('App\Teacher', 'teacher_id','teacher_id');
   }
   public function student_class(){
      return $this->hasMany('App\StudentClass', 'class_id','class_id');
  }
  public function style(){
     return $this->belongsTo('App\Style', 'style_id','style_id');
 }

  public static function saveClass($class_data){
	try{
        $studio_class = new StudioClass();
		$studio_class->style_id = $class_data['style'];
		$studio_class->teacher_id = $class_data['teacher'];
		$studio_class->location_id = $class_data['location_id'];
		$studio_class->date = $class_data['date'];
		$studio_class->start_time = date("H:i:s", strtotime($class_data['start-time']));
		$studio_class->duration = $class_data['duration'];
		$studio_class->created_by = $class_data['studio_id'];
		$studio_class->status = 'active';
		$studio_class->created_type = 'studio';
		$studio_class->created_at = date("Y-m-d H:i:s");
		$studio_class->number_of_spots = $class_data['sportmanagement'];
		$studio_class->price = $class_data['price'];
		$studio_class->price_in_dollar = $class_data['price_in_dollar'];
		$studio_class->save();
		// echo "<pre>"; print_r($studio_class); die('ok');
		return $studio_class;
    }
    catch(\Exception $e){
       // do task when error
       echo $e->getMessage();   // insert query
    }
    
  }

  public static function cloneClass($class_data){
    $studio_class = new StudioClass();
    $studio_class->level_id = $class_data['level_id'];
    $studio_class->style_id = $class_data['style_id'];
    $studio_class->teacher_id = $class_data['teacher_id'];
    $studio_class->location_id = $class_data['location_id'];
    $studio_class->start_time = $class_data['start_time'];
    $studio_class->duration = $class_data['duration'];
    $studio_class->status = 'pending';
    $studio_class->avg = $class_data['avg'];
    $studio_class->no_users_rate = $class_data['no_users_rate'];
    $studio_class->price = $class_data['price'];
    $studio_class->price_in_dollar = $class_data['price_in_dollar'];
    $studio_class->created_by = $class_data['created_by'];
    $studio_class->rental = $class_data['rental'];
    $studio_class->created_type = $class_data['created_type'];
    $studio_class->fixed_payment = $class_data['fixed_payment'];
    $studio_class->variable_student_1 = $class_data['variable_student_1'];
    $studio_class->variable_payment_1 = $class_data['variable_payment_1'];
    $studio_class->variable_payment_2 = $class_data['variable_payment_2'];
    $studio_class->original_teacher_id = $class_data['original_teacher_id'];
    $studio_class->gold = $class_data['gold'];
    $studio_class->visible_to_student = $class_data['visible_to_student'];
    $studio_class->visible_to_teacher = $class_data['visible_to_teacher'];
    $studio_class->number_of_spots = $class_data['number_of_spots'];
    $studio_class->spot_management = $class_data['spot_management'];
    $studio_class->date = $class_data['date'];

    $studio_class->save();
    return $studio_class;
  }
  public static function getStyle($style_id){
    $style = Style::where('style_id', intval($style_id))->get()->toArray();
    return $style;
  }

}
