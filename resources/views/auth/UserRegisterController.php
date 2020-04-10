<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use Mail;
use File;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use App\Post;
use App\Postimage;
use App\PostNotification;
use App\Mail\VerifyEmail;
use Ixudra\Curl\Facades\Curl;

class UserRegisterController extends Controller
{
	public function store(Request $request)
    {
      $validator = Validator::make($request->all(), [ 
          'username'  => 'required',
          'email'     => 'required|email', 
          'password'  => 'required|min:5',
          'confirm_pass' => 'required|min:5|same:password',
      ]);

      if ($validator->fails()) 
      { 
          return response()->json(['error'=>$validator->errors()], 401);            
      }
      else
      {
          $username       =  $request->input('username');
          $email          =  $request->input('email');
          $password       =  $request->input('password');
         
          if (!empty($request->input('email')))
          {
              $email = $request->input('email');
              $isExists = User::where('email',$email)->first();
              if ($isExists == '') 
              {
                  $user = User::create([
                  'name'       => $username,
                  'email'      => $email,
                  'password'   => bcrypt($password), 
                  'user_type'  => 1,
                  'status'     => 2,
                  'verified'   => str_random(40),                    
                    ]);

                  $userId     = $user->id;
                  $email      = $user->email; 
                  $username   = $user->name;
                  $token      = $user->verified;

                  Mail::send('emails.email-verification', ['email' => $email,'username' => $username,'token'=> $token,'userId' => $userId], function ($m) use ($email, $token, $userId, $username) 
                        { 
                          $m->to($email)->subject('Email Verification From AdMessenger'); 
                        });

                  $success['email']           =  $user->email;
                  $success['username']        =  $user->name;
                  $success['msg']             =  "Registration completed successfully. Please check your registered email for email verification";
                  $success['error_code']      =  '0';

                  return response()->json(['data'=>$success]);
              }
              elseif($isExists->status == '1')
              {
                  $success['msg'] =  'Email Already Exist';
                  $success['error_code'] = '5';
                  return response()->json(['data'=>$success]);
              }
              elseif($isExists->status  == '2')
              {
                  $success['msg'] =  'Email Already Registered. Need to Verify your account to check inbox';
                  $success['error_code'] = '5';
                  return response()->json(['data'=>$success]);
              }
          }
          else
          {
              $success['msg'] =  'Something went worng';
              return response()->json(['fail'=>$success ,'error_code'=>'5','messages' => 'error']);
          }
      }
    }

    public function verifyUser($token)
    {
        $verifyUser = User::where('verified', $token)->first();
        $status = '';
        if(isset($verifyUser) )
        {
            $user_status = $verifyUser->status;
            if($user_status == 2) 
            {
                $verifyUser->update(['status' => 1]);

                $message = "Your e-mail is verified. You can now login.";
                $email =  $verifyUser->email;

                return view("pages.verify", compact('message'));

                // $success['email']           =  $verifyUser->email;
                // $success['msg']         =  "Your e-mail is verified. You can now login.";
                // $success['error_code']      =  '0';
                // return response()->json(['data'=>$success]);
            }
            else
            {
              $message = "Your e-mail is already verified. You can now login.";


              return view("pages.verify", compact('message'));

                // $success['msg']          =  "Your e-mail is already verified. You can now login.";
                // $success['error_code']      =  '5';
                // return response()->json(['data'=>$success]);
            }
        }
        else
        {
          $message = "Sorry your email cannot be identified.";
          return view("pages.verify", compact('message'));
            // $success['msg']          =  "Sorry your email cannot be identified.";
            // $success['error_code']      =  '5';
            // return response()->json(['data'=>$success]);
        }
    }

    public function ForgetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [ 
            'email'  => 'required|email', 
        ]);

        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        else
        {
            $email  = $request->email;
            $getUserEmail = User::where('email', $email)
                                  ->where('status',"=","1")
                                  ->first();
            //echo "<pre>"; print_r($getUserEmail->name); die;                                  

            if (isset($getUserEmail)) 
            {
                $userId   = $getUserEmail->id;       
                $userName = $getUserEmail->name;                       
                
                Mail::send('emails.forget-pass', ['email' => $email,'userId' => $userId,'userName' => $userName], function ($m) use ($email,$userId,$userName) 
                      { 
                        $m->to($email)->subject('Forgot Password From AdMessenger'); 
                      });

                $success['msg'] = "Check your mail to Change Password.";
                $success['error_code'] = '0';
                return response()->json(['data'=>$success]);
            }
            else
            {
                $success['msg'] = "account does not exist.";
                $success['error_code'] = '5';
                return response()->json(['data'=>$success]);
            }            
        }        
    }

    public function ResetPassword(Request $request)
    {
        $PasswordChange = User::where('id', $request->userId)->first();
        $PasswordId     = $request->userId;
        $PasswordEmail  = $PasswordChange->email;

        return view("pages.login", compact('PasswordId','PasswordEmail'));
    }

    public function ChangePassword(Request $request)
    {
        $ChangePassword = User::where('id', $request->userId)
                                ->where('email',$request->useremail)->first();
        if (isset($ChangePassword) && count($ChangePassword) > 0 ) 
        {
            $ChangePassword->update(['status' => 1, 'password' => bcrypt($request->pwd)]);
            
        }
        return response()->json(["success"=>true,
            "msg"=>"Your password has been changed. Now your account will be logged out. Please login again with new password.",'error_code' => '5']);
    }

    public function CheckUserName(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
        ]);

        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        else
        {
            $UserName   = $request->name;
            $CheckName  = User::where('name',$UserName)->where('user_type',1)->where('status',1)->first();
            if(isset($CheckName))
            {
                $success['msg'] =  'UserName Already Exist';
                $success['error_code'] = '5';
                return response()->json(['data'=>$success]);
            }
            else
            {
                $success['msg'] =  'User Name Available';
                $success['error_code'] = '0';
                return response()->json(['data'=>$success]);
            }
        }
    }

    public function checkemails(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'email'             => 'required|email', 
        ]);

        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        else
        {
            $email      = $request->input('email');
            $CheckEmail = User::where('email',$email)->where('user_type',1)->where('status',1)->first();
            if(isset($CheckEmail))
            {
                $success['msg'] =  'Email Already Exist';
                $success['error_code'] = '5';
                return response()->json(['data'=>$success]);
            }
            else
            {
                $success['msg'] =  'Email Available';
                $success['error_code'] = '0';
                return response()->json(['data'=>$success]);
            }
        }
    }

     public function ChangeNewPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'               => 'required',
            'current_password'      => 'required|min:4',
            'new_password'          => 'required|min:4',
        ]);

        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        else
        {
            $ChangePassword = User::where('id', $request->user_id)
            					  ->where('status','1')
                                  ->where('user_type',1)->first();

      			if (isset($ChangePassword) && !empty($ChangePassword)) 
      			{
      				$hasher = app('hash');
      				if ($hasher->check($request->current_password, $ChangePassword->password)) 
      				{
      				   $ChangePassword->update(['status' => '1', 'password' => bcrypt($request->new_password)]);

      	                $success['msg'] = "Your password has been changed. Now your account will be logged out. Please login again with new password. ";
      	                $success['error_code'] = '0';
      	                return response()->json(['data'=>$success]);
      				}
      				else
      				{
      					$success['msg'] = "Incorrect Password";
      	                $success['error_code'] = '5';
      	                return response()->json(['data'=>$success]);
      				} 
      			}                                  
            else
            {
               $success['msg'] = "User Does Not Exist";
               $success['error_code'] = '5';
               return response()->json(['data'=>$success]); 
            }
        }
    }

    public function UpdateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'         => 'required',
            'user_email'      => 'required',
        ]);

        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        else
        {
            $ChangeEmail = User::where('id', $request->user_id)->first();

            if (isset($ChangeEmail) && !empty($ChangeEmail)) 
            {
              $ChangeEmail->update(['email' => $request->user_email]);

              $success['msg'] = "Email successfully updated.";
              $success['error_code'] = '0';
              return response()->json(['data'=>$success]);
              
            }                                  
            else
            {
               $success['msg'] = "User Does Not Exist";
               $success['error_code'] = '5';
               return response()->json(['data'=>$success]); 
            }
        }
    }

    public function UpdateDeviceId(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'         => 'required',
            'device_token'    => 'required',
        ]);

        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        else
        {
            $ChangeDeviceToken = User::where('id', $request->user_id)->first();

            if (isset($ChangeDeviceToken) && !empty($ChangeDeviceToken)) 
            {
              $ChangeDeviceToken->update(['device_id' => $request->device_token]);

              $success['msg'] = "Device Token successfully updated.";
              $success['error_code'] = '0';
              return response()->json(['data'=>$success]);
            }                                  
            else
            {
               $success['msg'] = "User Does Not Exist";
               $success['error_code'] = '5';
               return response()->json(['data'=>$success]); 
            }
        }
    }

    public function EmptyDeviceId(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'         => 'required',
        ]);

        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        else
        {
            $EmptyDeviceToken = User::where('id', $request->user_id)->first();

            if (isset($EmptyDeviceToken) && !empty($EmptyDeviceToken)) 
            {
              $EmptyDeviceToken->update(['device_id' => '']);
              $success['msg'] = "Logout successfully";
              $success['error_code'] = '0';
              return response()->json(['data'=>$success]);
            }                                  
            else
            {
               $success['msg'] = "User Does Not Exist";
               $success['error_code'] = '5';
               return response()->json(['data'=>$success]); 
            }
        }
    }

    public function UpdatePaypalEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'         => 'required',
            'paypal_email'    => 'required',
        ]);

        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        else
        {
            $UpdatePaypalEmail = User::where('id', $request->user_id)->first();

            if (isset($UpdatePaypalEmail) && !empty($UpdatePaypalEmail)) 
            {
              $CheckPaypalEmail = User::where('paypal_email', $request->paypal_email)->first();

              if (isset($CheckPaypalEmail) && !empty($CheckPaypalEmail)) 
              {
                $success['msg'] = "Paypal email already exist.";
                $success['error_code'] = '1';
                return response()->json(['data'=>$success]);
              }
              else
              {
                $UpdatePaypalEmail->update(['paypal_email' => $request->paypal_email]);

                $success['msg'] = "Paypal email successfully updated.";
                $success['error_code'] = '0';
                return response()->json(['data'=>$success]);
              }
            }                                  
            else
            {
               $success['msg'] = "User Does Not Exist";
               $success['error_code'] = '5';
               return response()->json(['data'=>$success]); 
            }
        }
    }
}
