<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Auth;
use Redirect;

class LoginController extends Controller 
{

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home creen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {

        return view('pages.login');
        // echo "KKKKKKKKK";exit();
    }

    public function login(Request $request)
    {
        $getAdminDetails = array(
                'email'     => $request->email,
                'password'  => $request->password, 
                'user_type'    => 2);  
        if(Auth::attempt($getAdminDetails)) 
        {
            return redirect('/');
        }
        else
        {
            return Redirect::back()->withErrors(['Only Admin can access', 'msg']);                                       
        }                               
        
        
    }

    public function signIn(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'username'  => 'required',
            'password'  => 'required',
        ]);

        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);         
        }
        else
        {
            
            $UserName   = $request->username;
            $Password   = $request->password;
            $DeviceId   = $request->device_token;
            //echo "<pre>"; print_r($DeviceId); die;

            $getUserDetails = array(
                'name'     => $UserName,
                'password'  => $Password, 
                'status'    => 1);  
                                    
            if(Auth::attempt($getUserDetails)) 
            {
                $getUserId      = Auth::user()->id;
                $getUserEmail   = Auth::user()->email;
                $getUserName    = Auth::user()->name;

                $GetMessageDetail = User::where('name',$UserName)
                                        ->where('email',$getUserEmail)
                                        ->where('status',1)->first();                                       

                if (isset($GetMessageDetail) && $GetMessageDetail != '' ) 
                {
                  $GetMessageDetail->update(['device_id' => $DeviceId]);

                }

                $getPayPalEmail  = isset($GetMessageDetail->paypal_email) ? $GetMessageDetail->paypal_email : '';

                $getMessageCount = isset($GetMessageDetail->notification_count) ? $GetMessageDetail->notification_count : '';

                if ($getMessageCount == '' || $GetMessageDetail->notification_count == 0) 
                {
                    $NotificationCount = '100';
                }
                else
                {
                    $NotificationCount = $GetMessageDetail->notification_count;
                }

                $success['userid']               = $getUserId;
                $success['useremail']            = $getUserEmail;
                $success['username']             = $getUserName;
                $success['paypal_email']         = $getPayPalEmail;
                $success['notification_count']   = $NotificationCount;
                $success['error_code']           = "0";
                $success['msg']                  =  'Successfully login';
                return response()->json(['data'=>$success]);
            }
            else
            {
                $GetUserMailStatus = User::where('name',$UserName)
                                        ->where('status',2)->first();
                
                if (isset($GetUserMailStatus) && $GetUserMailStatus != '' ) 
                {
                    $success['msg'] =  'Please Verify your Email address to login';
                    $success['error_code'] = "5";
                    return response()->json(['data'=>$success]);
                }
                else
                {
                    $success['msg'] =  'Username / password is wrong';
                    $success['error_code'] = "5";
                    return response()->json(['data'=>$success]);
                }                                       
            }                          
        }
        
    }
}