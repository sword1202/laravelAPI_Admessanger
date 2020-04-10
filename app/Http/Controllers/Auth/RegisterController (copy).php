<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\VerifyEmail;
use Mail;

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

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'username'  => 'required',
            'email'     => 'required|email', 
            'password'  => 'required|min:5',
            'password_confirmation' => 'required|min:5|same:password',
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
                    'name'       => $fname,
                    'email'      => $email,
                    'password'   => bcrypt($password), 
                    'verified'   => str_random(40),
                    'status'     => 2,

                  ]);

                    $userId     = $user->id;
                    $token      = $user->email; 
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
}
