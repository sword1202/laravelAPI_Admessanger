<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\VerifyEmail;
use Mail;
use Illuminate\Http\Request;
use Auth;



class RegisterController extends Controller {



    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('pages.register');
    }

    

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
                    'status'     => 1,
                    'verified'   => str_random(40),                    
                  ]);

                    $userId     = $user->id;
                    $email      = $user->email; 
                    $username   = $user->name;
                    $token      = $user->verified;

                    Mail::send('emails.email-verification', ['email' => $email,'username' => $username,'token'=> $token,'userId' => $userId], function ($m) use ($email, $token, $userId, $username) 
                          { 
                            $m->to($email)->subject('Email Verification From Admessages'); 
                          });
                    $success['email']           =  $user->email;
                    $success['username']        =  $user->name;
                    $success['status']         =  "Now you check your email";
                    $success['error_code']      =  '0';

                    return response()->json(['data'=>$success]);
                }
                elseif($isExists->status == '1')
                {
                    $success['msg'] =  'Email Already Exist';
                    $success['error_code'] = '5';
                    return response()->json(['data'=>$success]);
                }
                elseif($isExists->status  == '0')
                {
                    $success['msg'] =  'Email Already Registered. Need to Verify your account to check inbox';
                    $success['error_code'] = '2';
                    return response()->json(['data'=>$success]);
                }
            }
            else
            {
                $success['msg'] =  'Something went worng';
                return response()->json(['fail'=>$success ,'error_code'=>1,'messages' => 'error']);
            }
        }
    }

    // public function verifyUser($token)
    // {
    //     $verifyUser = User::where('verified', $token)->first();
    //     $status = '';
    //     if(isset($verifyUser) )
    //     {
    //         $user_status = $verifyUser->status;
    //         if($user_status == 2) 
    //         {
    //             $verifyUser->update(['status' => 1]);

    //             $success['email']           =  $verifyUser->email;
    //             $success['status']         =  "Your e-mail is verified. You can now login.";
    //             $success['error_code']      =  '0';
    //             return response()->json(['data'=>$success]);
    //         }
    //         else
    //         {
    //             $success['status']          =  "Your e-mail is already verified. You can now login.";
    //             $success['error_code']      =  '1';
    //             return response()->json(['data'=>$success]);
    //         }
    //     }
    //     else
    //     {
    //         $success['status']          =  "Sorry your email cannot be identified.";
    //         $success['error_code']      =  '1';
    //         return response()->json(['data'=>$success]);
    //     }
    // }

    // public function ForgetPassword(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [ 
    //         'email'  => 'required|email', 
    //     ]);

    //     if ($validator->fails()) 
    //     { 
    //         return response()->json(['error'=>$validator->errors()], 401);            
    //     }
    //     else
    //     {
    //         $email  = $request->email;
    //         $getUserEmail = User::where('email', $email)
    //                               ->where('status',"=","1")
    //                               ->first();

    //         if (isset($getUserEmail)) 
    //         {
    //             $userId = $getUserEmail->id;                              
                
    //             Mail::send('emails.forget-pass', ['email' => $email,'userId' => $userId], function ($m) use ($email,$userId) 
    //                   { 
    //                     $m->to($email)->subject('Forgot Password From AdMessage'); 
    //                   });

    //             $success['msg'] = "Check your mail to Change Password.";
    //             $success['error_code'] = '1';
    //             return response()->json(['data'=>$success]);
    //         }
    //         else
    //         {
    //             $success['msg'] = "account does not exist.";
    //             $success['error_code'] = '5';
    //             return response()->json(['data'=>$success]);
    //         }            
    //     }        
    // }

    // public function ResetPassword(Request $request)
    // {
    //     $PasswordChange = User::where('id', $request->userId)->first();
    //     $PasswordId     = $request->userId;
    //     $PasswordEmail  = $PasswordChange->email;

    //     return view("pages.login", compact('PasswordId','PasswordEmail'));
    // }

    // public function ChangePassword(Request $request)
    // {
    //     $ChangePassword = User::where('id', $request->userId)
    //                             ->where('email',$request->useremail)->first();
    //     if (isset($ChangePassword) && count($ChangePassword) > 0 ) 
    //     {
    //         $ChangePassword->update(['status' => 1, 'password' => bcrypt($request->pwd)]);
            
    //     }
    //     return response()->json(["success"=>true,
    //         "msg"=>"Your password has been changed. Now your account will be logged out. Please login again with new password."]);
    // }

    // public function CheckUserName(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [ 
    //         'name' => 'required', 
    //     ]);

    //     if ($validator->fails()) 
    //     { 
    //         return response()->json(['error'=>$validator->errors()], 401);            
    //     }
    //     else
    //     {
    //         $UserName   = $request->name;
    //         $CheckName  = User::where('name',$UserName)->where('user_type',1)->where('status',1)->first();
    //         if(isset($CheckName))
    //         {
    //             $success['msg'] =  'UserName Already Exist';
    //             $success['error_code'] = '5';
    //             return response()->json(['data'=>$success]);
    //         }
    //         else
    //         {
    //             $success['msg'] =  'User Name Available';
    //             $success['error_code'] = '1';
    //             return response()->json(['data'=>$success]);
    //         }
    //     }
    // }

    // public function checkemails(Request $request)
    // {
    //     echo "trigger";
    //     // $validator = Validator::make($request->all(), [ 
    //     //     'email'             => 'required|email', 
    //     // ]);

    //     // if ($validator->fails()) 
    //     // { 
    //     //     return response()->json(['error'=>$validator->errors()], 401);            
    //     // }
    //     // else
    //     // {
    //     //     $email      = $request->input('email');
    //     //     $CheckEmail = User::where('email',$email)->where('user_type',1)->where('status',1)->first();
    //     //     if(isset($CheckEmail))
    //     //     {
    //     //         $success['msg'] =  'Email Already Exist';
    //     //         $success['error_code'] = '5';
    //     //         return response()->json(['data'=>$success]);
    //     //     }
    //     //     else
    //     //     {
    //     //         $success['msg'] =  'Email Available';
    //     //         $success['error_code'] = '1';
    //     //         return response()->json(['data'=>$success]);
    //     //     }
    //     // }
    // }

    //  public function ChangeNewPassword(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [ 
    //         'user_id'               => 'required',
    //         'current_password'      => 'required|min:4',
    //         'new_password'          => 'required|min:4|different:current_password',
    //         'password_confirmation' => 'required|same:new_password',
    //     ]);

    //     if ($validator->fails()) 
    //     { 
    //         return response()->json(['error'=>$validator->errors()], 401);            
    //     }
    //     else
    //     {
    //         $ChangePassword = User::where('id', $request->user_id)->where('status','1')
    //                               ->where('user_type',1)->first();
    //         if (!empty($ChangePassword)) 
    //         {
    //             $ChangePassword->update(['status' => '1', 'password' => bcrypt($request->new_password)]);

    //             $success['msg'] = "Your password has been changed. Now your account will be logged out. Please login again with new password. ";
    //             $success['error_code'] = '0';
    //             return response()->json(['data'=>$success]);
    //         }
    //         else
    //         {
    //             $success['msg'] = "Something went wrong";
    //             $success['error_code'] = '5';
    //             return response()->json(['data'=>$success]);
    //         }
    //     }
    // }
    
}
