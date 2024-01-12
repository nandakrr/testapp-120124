<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Studio;
use App\Teacher;
use Validator;
use DB;
use Mail;
use Session;

class ResetPasswordController extends Controller
{
    

    public function resetlink(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'reset_type'      => 'required|string|max:255',         
            'email'           => 'required|string|max:255',      
        ]); 

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $reset_type = trim($request->input('reset_type'));
        $email = trim($request->input('email'));
        $confirmation_code = str_random(30);

        if( $reset_type === 'studio') 
        {

            $studio = Studio::where('email', trim($request->input('email')))->first();

            if(!empty($studio))
            {
                $res = DB::table('studio')->where('email', $email)->update(['token' =>$confirmation_code]);
                if($res)
                {
                    $to = $email;
                    $subject = "Bigtoe Forgot Password Request";                    
                    $from    = "info@bigtoe.fit";
                    $eemail = base64_encode($to);                   
                    $etoken = base64_encode($confirmation_code);                     
                    $link = url('create-password/'.$eemail.'/'.$etoken.'/'.'studio');
                    $user = array(
                                    'link'=>$link,
                                    'name'=>$studio->name,
                                    'type'=>'studio'
                                );        
                    //return view('mailTemplate',compact('user'));die;          
                    $res = Mail::send('mailTemplate', ['user' => $user], function($message) use ($to,$from,$subject)
                     {  
                         $message->to($to)->subject($subject);
                         $message->from($from,'Bigtoe Yoga');
                    });
                    if(Mail::failures())
                    {
                        Session::flash('message', 'Please try again');
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->back();
                    }else{
                        Session::flash('message', 'Please check password reset link on your mail id');
                        Session::flash('alert-class', 'alert-success');
                        return redirect()->back();
                    }

                }
            }else{
                Session::flash('message', 'Please enter a registered email id');
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
            }
        }else{

            $teacher = Teacher::where('email', trim($request->input('email')))->first();
            if(!empty($teacher))
            {
                $res = DB::table('teacher')->where('email', $email)->update(['token' =>$confirmation_code]);
                if($res)
                {
                    $to = $email;
                    $subject = "Bigtoe Forgot Password Request";                    
                    $from    = "info@bigtoe.yoga";
                    $eemail = base64_encode($to);                   
                    $etoken = base64_encode($confirmation_code);                     
                    $link = url('create-password/'.$eemail.'/'.$etoken.'/'.'provider');
                    $user = array(
                                    'link'=>$link,
                                    'name'=>$teacher->firstname,
                                    'type'=>'provider'
                                );     
                    $res = Mail::send('mailTemplate', ['user' => $user], function($message) use ($to,$from,$subject)
                     {  
                         $message->to($to)->subject($subject);
                         $message->from($from,'Bigtoe Yoga');
                    });
                    if(Mail::failures())
                    {
                        Session::flash('message', 'Please try again');
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->back();
                    }else{
                        Session::flash('message', 'Please check password reset link on your mail id');
                        Session::flash('alert-class', 'alert-success');
                        return redirect()->back();
                    }

                }   
            }else{
                Session::flash('message', 'Please enter a registered email id');
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
            }
        }
    }

    public function newPassword(Request $request,$email='',$token='',$type='')
    {
        $validator = Validator::make($request->all(), [
            'password'    => 'required|min:6',         
            'c_password'  => 'required|min:6',      
        ]);  

        $password      =  trim($request->password);
        $c_password    =  trim($request->c_password);

        if ($validator->fails() && $password!='') {
            Session::flash('message', 'Password should be at least 6 characters.');
            Session::flash('alert-class', 'alert-danger');
            return back();
        }

        if($password!== $c_password){
            Session::flash('message', 'Password and confirmation password does not match.');
            Session::flash('alert-class', 'alert-danger');
            return back();
        }
         

        if($type!='' && $email!='' && $token!='')
        {
                        
                $email = base64_decode($email);      
                $token = base64_decode($token);   
                if($type=='studio')
                {
                    $udata = Studio::where(['email'=>$email,'token'=>$token])->first();     
                }else{
                    $udata = Teacher::where(['email'=>$email,'token'=>$token])->first();
                } 
			if(empty($udata))
            {
				Session::flash('message', 'Email Link Expired');
				Session::flash('alert-class', 'alert-danger');
				return redirect('login');
			}
			
			if (!$validator->fails()) 
            { 
					
                if(!empty($udata))
                {
                     if($type=='studio')
                    {
                        $r = DB::table('studio')->where('admin_id',$udata->admin_id)->update(['password' =>encrypt_password($request->input('password')),'token'=>'']);
                    }else{
                        $r = DB::table('teacher')->where('teacher_id', $udata->teacher_id)->update(['password' =>encrypt_password($request->input('password')),'token'=>'']);
                    }

                    if($r){
                        Session::flash('message', 'Password changed successfully');
                        Session::flash('alert-class', 'alert-success');
                        return redirect('login');
                    }else{
                        Session::flash('message', 'Something Wrong');
                        Session::flash('alert-class', 'alert-danger');
                        return redirect('login');
                    }

                }else{
                    Session::flash('message', 'Email Link Expired');
                    Session::flash('alert-class', 'alert-danger');
                    return redirect('login');
                }
            }
            return view('create_new_pass');
        }else{
                Session::flash('message', 'Email Link Expired');
                Session::flash('alert-class', 'alert-danger');
                return redirect('login');
            }
        }

        //end class
}

 

