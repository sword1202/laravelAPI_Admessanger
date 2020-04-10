<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use Mail;
use File;
use App\User;
use App\RedeemRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use App\Post;
use App\PostNotification;
use App\Postimage;
use App\RedeemPayout;
use Ixudra\Curl\Facades\Curl;

//use App\Http\Controllers\Redirect;
use Redirect;

class UserController extends Controller
{

  	public function DashboardDetails(Request $request)
  	{
	    $getUserDetailsCount = User::all()->count();
	    $getredeemdetailsCount = RedeemRequest::all()->where('status','=',1)->count();
	    // echo $getUserDetails;  die;
        // var_dump($getUserDetailsCount);
        // var_dump($getredeemdetailsCount);die();
// echo "Secceed";exit();
        echo "kkk";exit();
	    return view("samples/dashboard", compact('getUserDetailsCount','getredeemdetailsCount'));
  	}

  	public function UserDetails(Request $request)
  	{
    	// $getUserDetails = User::all();
    	// return view("samples/user-details", compact('getUserDetails'));

    	return view("samples/user-details");
 	}

 	public function GetUserDetails(Request $request)
    {
    	$columns = array( 
                            0 =>'id', 
                            1 =>'name',
                            2=> 'email',
                            3=> 'paypal_email',
                            4=> 'user_type',
                            5=> 'status',
                            6=> 'status',
                        );

    	$totalData = User::count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $posts = User::offset($start)
                         		  ->limit($limit)
                         		  ->orderBy($order,$dir)
                         		  ->get();
        }
        else 
        {
            $search = $request->input('search.value'); 

            $posts =  User::where('id','LIKE',"%{$search}%")

                            ->orWhere('name', 'LIKE',"%{$search}%")
                            ->orWhere('email', 'LIKE',"%{$search}%")
                            ->orWhere('paypal_email', 'LIKE',"%{$search}%")
                            ->orWhere('user_type', 'LIKE',"%{$search}%")
                            ->orWhere('status', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = User::where('id','LIKE',"%{$search}%")
                            ->orWhere('name', 'LIKE',"%{$search}%")
                            ->orWhere('email', 'LIKE',"%{$search}%")
                            ->orWhere('paypal_email', 'LIKE',"%{$search}%")
                            ->orWhere('user_type', 'LIKE',"%{$search}%")
                            ->orWhere('status', 'LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
            	$edit 	=  url('edit-user',$post->id);
                $delete =  url('delete-user',$post->id);

                $nestedData['id'] 				= $post->id;
                $nestedData['name'] 			= $post->name;
                $nestedData['email'] 			= $post->email;
                $nestedData['paypal_email'] 	= $post->paypal_email;
                $nestedData['status'] 			= $post->status;
                if ($post->user_type == 2) 
                {
                	$nestedData['user_type'] = 'Admin';
                }
                else
                {
                	$nestedData['user_type'] = 'User';
                }
                if ($post->status == 1) 
                {
                	$nestedData['status'] = '<span class="badge badge-success">Active</span> ';
                }
                else
                {
                	$nestedData['status'] = '<span class="badge badge-warning">Inactive</span>';
                }

                $nestedData['action'] = '<a class="btn btn-info" href='.$edit.' title="SHOW"><i class="fa fa-edit"></i></a>
                	<a class="btn btn-danger" href="javascript:void(0)" id="delete_user" onclick="fun('.$post->id.')" data-id='.$post->id.' title="DELETE"><i class="fa fa-trash-o"></i></a>';

                $data[] = $nestedData;

            }
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data);
    }

  	public function EditUserDetails(Request $request)
  	{
    	$getUserId = $request->userid;

    	$EditUser = User::where('id',$getUserId)->first();

    	$getUserType    = $EditUser->user_type;

	    if ($getUserType == 2) 
	    {
	      $userType = 'Admin';
	    }
    	else
    	{
      	$userType = 'User';
    	}

	    $getUserStatus  = $EditUser->status;

	    if ($getUserStatus == 1) 
	    {
	      $userStatus = 'Active';
	    }
	    else
	    {
	      $userStatus = 'Inactive';
	    }
	    return view("samples/edit-profile", compact('EditUser','userType','userStatus'));
  	}

  	public function UpdateUser(Request $request)
  	{
    	$UpdateUser = User::where('id',$request->userid)->first();

    	if (isset($UpdateUser) && $UpdateUser != '' ) 
    	{
        	$UpdateUser->update(['user_type'  	=> $request->user_type,
                             	 'status'     	=> $request->user_status,
                             	 'email'       	=> $request->user_email,
                             	 'paypal_email' => $request->paypal_email,
                             	]);
        
        	return redirect('/userdetails')->with('success','User detail successfully updated!');
    	}
  	}

    public function ChangePassword(Request $request)
    {
        return view("samples/change-password");
    }

    public function UpdatePassword(Request $request)
    {
        // print_r($request->all());
        // die;
        $UpdatePassword = User::where('id',$request->user_id)->first();
        $new_password  = trim($request->new_password);
         //echo $new_password;
        // die;
        
        $confirm_user_password   =trim($request->confirm_user_password);
        // dd($request->all()); exit;   
         // echo "<pre>";print_r($request->all());  echo "</pre>";   exit;  
        //die ;  

        if (isset($UpdatePassword) && $UpdatePassword != '' )  
        {
            $UpdatePassword->update(['password'    =>bcrypt($request->new_password)]);        
            return redirect('/change_password')->with('success','Password Successfully Updated!');
        }   

        /*($new_password == $confirm_user_password)*/

        if($new_password == $confirm_user_password)
        {         
            
            if (isset($UpdatePassword) && $UpdatePassword != '' )  
            {
                $UpdatePassword->update(['password'    =>bcrypt($request->new_password)]);
            
                return redirect('/change_password')->with('success','Password Successfully Updated!');
            }
        }
        else
        {
            return redirect('/change_password')->with('success','New Password and Confirm Password must be same!');  
        }        
    }
        public function DeleteUser(Request $request)
  	{
    	$DeleteUser = User::where('id',$request->userdeleteid)->first();

    	if (isset($DeleteUser) && $DeleteUser != '' ) 
    	{
        	$DeleteUserByAdmin = User::where('id',$DeleteUser->id)->delete();
        	return redirect('/userdetails');
    	}
  	}

  	// public function RedeemDetail(Request $request)
  	// {
   //  	$getredeemdetails = RedeemRequest::paginate(10);

   //  	$getRedeemAmount = DB::table('redeem_payout')->where('user_id', '=', $request->userid)
   //                                                    ->where('status','=',1);
                                                                 
   //  	return view("samples/redeem-details", compact('getredeemdetails'));
  	// }

  	public function RedeemDetail(Request $request)
  	{
    	//$getredeemdetails = RedeemRequest::paginate(10);

    	//$getRedeemAmount = DB::table('redeem_payout')->where('user_id', '=', $request->userid)
                                                    //->where('status','=',1);
    	return view("samples/redeem-details");
  	}

 	public function GetRedeemDetails(Request $request)
    {
    	$columns = array( 
                            0 =>'redeem_request.id', 
                            1 =>'users.name',
                            2 =>'redeem_request.amount',
                            3 => 'redeem_request.status',
                            4 => 'redeem_request.created_at',
                        );

    	$totalData = RedeemRequest::count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $posts = RedeemRequest::select('redeem_request.id','redeem_request.user_id','redeem_request.amount','redeem_request.status','users.name','redeem_request.created_at')
            				->join('users','users.id','=','redeem_request.user_id')
            				->offset($start)
                         		  ->limit($limit)
                         		  ->orderBy($order,$dir)
                         		  ->get();
              
        }
        else 
        {
            $search = $request->input('search.value'); 

            $posts =  RedeemRequest::select('redeem_request.id','redeem_request.user_id','redeem_request.amount','redeem_request.status','users.name','redeem_request.created_at')
            				->join('users','users.id','=','redeem_request.user_id')
            				->orWhere('users.name', 'LIKE',"%{$search}%")
                            ->orWhere('amount', 'LIKE',"%{$search}%")
                            ->orWhere('redeem_request.created_at', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = RedeemRequest::select('redeem_request.id','redeem_request.user_id','redeem_request.amount','redeem_request.status','users.name','redeem_request.created_at')
            				->join('users','users.id','=','redeem_request.user_id')
            				 ->orWhere('users.name', 'LIKE',"%{$search}%")
                             ->orWhere('amount', 'LIKE',"%{$search}%")
                             ->orWhere('redeem_request.created_at', 'LIKE',"%{$search}%")
                             //->orWhere('status', 'LIKE',"%{$search}%")
                             ->count();
			
			//$totalFiltered1 = User::where('name','LIKE',"%{$search}%")->count();                             
        }

        $data = array();
        //echo "<pre>"; print_r($posts); 
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
            	//$getUserName = User::where('id',$post->user_id)->first();

                $nestedData['id'] = $post->id;
                $nestedData['username'] = $post->name;
                $nestedData['amount'] 	= $post->amount;

                if ($post->status == 1) 
                {
                	$nestedData['status'] = '<span class="badge badge-warning">Request</span>';
                	$nestedData['action'] = '<button type="button" class="btn btn-primary redbtn" onclick="myFunction(this);" data-requ-amount="'.$post->amount.'" data-user-id="'.$post->user_id.'"> Redeem </button>';
                }
                else
                {
                	$nestedData['status'] = '<span class="badge badge-success">Success</span>';
                	$nestedData['action'] = $post->created_at->toDateString();
                }

                $data[] = $nestedData;

            }
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data);
    }

  	public function PayRedeemAmount(Request $request)
  	{
    	//echo "<pre>"; print_r($request->all()); die;
    	$GetRedeemMail = User::where('id',$request->userid)->first();

	    $RedeemAmounts = $request->redeemAmount;
	    if(!empty($GetRedeemMail))
	    {
	      $UserName        = $GetRedeemMail->name;
	      $UserMail        = $GetRedeemMail->email;
	      $UserPaypalEmail = $GetRedeemMail->paypal_email;
	      $itmeId   = "item_".uniqid();

	      $response = Curl::to('https://admessengerappbackend.com/pay/paypal/rest-api-sdk-php/sample/payouts/CreateSinglePayout.php')
	                    ->withData(['paypal_email' => $UserPaypalEmail,'redeem_amount' => $RedeemAmounts,'itemId' => $itmeId])
	                      ->post();

	      $getResult = json_decode($response, true);

	      if ($getResult['batch_header']['payout_batch_id'] != '') 
	      {
	          $RedeemCreate = RedeemPayout::create([
	                            'user_id'           =>  $request->userid,
	                            'amount'            =>  $RedeemAmounts,
	                            'payout_batch_id'   =>  $getResult['batch_header']['payout_batch_id'],
	                            'batch_status'      =>  $getResult['batch_header']['batch_status'],
	                            'sender_batch_id'   =>  $getResult['batch_header']['sender_batch_header']['sender_batch_id'],
	                            'item_id'           =>  $itmeId,
	                            'item_status'       =>  '',
	                          ]);

	          $getRedeemAmount = DB::table('post_notification')->where('post_receiver_id', '=', $request->userid)
	                                                      ->where('status','=',1)->where('redeem_request','=',1);
	          if (isset($getRedeemAmount) && $getRedeemAmount != '' ) 
	          {
	            $getRedeemAmount->update(['amount' => '0','redeem_request' => '2']);
	          }

	          $changeRedeemAmount = DB::table('redeem_request')
	                                  ->where('user_id', '=', $request->userid) 
	                                  ->where('status','=',1);
	          if (isset($changeRedeemAmount) && $changeRedeemAmount != '' ) 
	          {
	            $changeRedeemAmount->update(['status' => '2','item_id' => $getResult['batch_header']['payout_batch_id']]);
	          }

	          $getUserName  = $GetRedeemMail->name;
	          $getUserEmail = $GetRedeemMail->email;

	          Mail::send('emails.redeem-amount', ['getUserEmail' => $getUserEmail,'getUserName' => $getUserName,'RedeemAmounts' => $RedeemAmounts], function ($m) use ($getUserEmail,$getUserName,$RedeemAmounts) 
	          { 
	            $m->to($getUserEmail)->subject('Redeem Amount From AdMessenger'); 
	          });

	          return response()->json(["success" => true]);
	      }
	    }
  	}

  	public function ChangeAutoPay(Request $request)
	{
	    $validator = Validator::make($request->all(), [ 
	            'user_id'        => 'required',
	        ]); 

	    if ($validator->fails()) 
	    { 
	        return response()->json(['error'=>$validator->errors()], 401);            
	    }
	    else
	    {
	      $UserId  =  $request->user_id;    

	      $requestAmount = array('0','1');

	      $getTotalAmount = DB::table('post_notification')->where('post_receiver_id', '=', $UserId)
	                                                      ->whereIn('redeem_request',$requestAmount)
	                                                      ->where('status','=',1)->sum('amount');

	      $getUserPostCount = PostNotification::join('create_post','create_post.id','=','post_notification.post_id')
	                   ->join('users','users.id','=','post_notification.post_receiver_id')
	                   
	                   ->where('post_notification.post_receiver_id','=',$UserId)
	                   ->orderBy('post_notification.post_id', 'DESC')
	                   ->addselect('post_notification.amount as postamount','post_notification.status as poststatus','post_notification.*' ,'users.*','create_post.*')->count();

	      $getall_user_detail  = array(); 

	      if ($getUserPostCount > 0) 
	      {

	        $getUserPost = PostNotification::join('create_post','create_post.id','=','post_notification.post_id')
	                   ->join('users','users.id','=','post_notification.post_receiver_id')
	                   ->where('post_notification.post_receiver_id','=',$UserId)
	                   ->orderBy('post_notification.post_id', 'DESC')
	                   ->addselect('post_notification.amount as postamount','post_notification.status as poststatus','post_notification.*' ,'users.*','create_post.*')->paginate(10);

	          foreach ($getUserPost as $UserPost) 
	          {

	            //$get_details['user_name']       = $UserPost['name'];
	            $get_details['owner_id']        = $UserPost['post_sender_id'];
	            $get_details['post_id']         = $UserPost['post_id'];
	            $get_details['amount']          = $UserPost['postamount'];
	            $get_details['post_date']       = $UserPost['post_date'];
	            $get_details['post_title']      = $UserPost['title'];
	            $get_details['post_description']= $UserPost['description'];
	            $get_details['post_type']       = $UserPost['post_type'];
	            $get_details['type']            = $UserPost['type'];
	            $get_details['post_status']     = $UserPost['poststatus'];

	            $getPostImage = Postimage::where('post_id','=',$UserPost['post_id'])->first();

	            if (!empty($getPostImage->images) && count($getPostImage->images) > 0) 
	            {
	              $get_details['images'] = url('/').'/postimages/'.$UserPost['post_sender_id'].'/'.$getPostImage->images;
	            }
	            else
	            {
	              $get_details['images'] = url('/').'/uploads/default_avatar.png';
	            }

	            if(isset($getPostImage->image_thumbnail) && $getPostImage->image_thumbnail != "")
	            {
	              $get_details['thumb_images'] = url('/').'/postimages/'.$UserPost['post_sender_id'].'/'.$getPostImage->image_thumbnail;
	            } 
	            else 
	            {
	              $get_details['thumb_images'] = url('/').'/uploads/default_avatar.png';
	            }

	            array_push($getall_user_detail, $get_details);
	          }
	          if (isset($UserPost['name']))
	          {
	            return response()->json(['data' => $getall_user_detail, 'total_amount' => $getTotalAmount,'user_name' => $UserPost['name'],'device_id' => $UserPost['device_id'],'msg' => 'Success','error_code' => '0']);

	         }
	          else
	          {
	            return response()->json(['data'=> "No Result Found",'msg'=> "error",'error_code'=> "5"]);
	          }
	      }
	      else
	      {
	        return response()->json(['data'=> "No Record Found",'msg'=> "error",'error_code'=> "1"]);
	      }
	    }
	}

  	public function CheckRedeemStatus(Request $request)
  	{
    	$ItemIds = $request->itemId;
    	//echo "<pre>"; print_r($ItemIds); die;
   		$response = Curl::to('https://admessengerappbackend.com/pay/paypal/rest-api-sdk-php/sample/payouts/GetPayoutBatchStatus.php')
                    ->withData(['ItemIds' => $ItemIds])
                      ->post();
      	$getEncodeData = json_encode($response, true);

      	$getResult1 = json_decode($getEncodeData, true);

      	echo "<pre>"; print_r($getResult1); die;
  	}
 

}
