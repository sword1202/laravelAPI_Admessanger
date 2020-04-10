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
use Ixudra\Curl\Facades\Curl;
use App\RedeemRequest;
use App\UserNotification;
use App\RedeemPayout;
class PostController extends Controller
{

     
  // public function CreateMessage(Request $request)
  // {
  //    $validator = Validator::make($request->all(), [ 
  //           'user_id'           => 'required',
  //           'title'             => 'required', 
  //           'description'       => 'required',
  //           'post_image'        => 'required|max:50000',
  //           'totalnotificatoin' => 'required',
  //           'totalamount'       => 'required',
  //           'posttype'          => 'required',
  //           'card_number'       => 'required',
  //           'card_type'         => 'required',
  //           'card_holder'       => 'required',
  //           'card_expiry'       => 'required',
  //           'cvv'               => 'required', 
             

  //       ]); 

  //       if ($validator->fails()) 
  //       { 
  //           return response()->json(['error'=>$validator->errors()], 401);            
  //       }
  //       else
  //       {
  //         $PostUserId         =  $request->user_id;
  //         $PostTitle          =  $request->title;
  //         $Description        =  $request->description;
  //         $TotalNotification  =  $request->totalnotificatoin;
  //         $TotalAmount        =  $request->totalamount;
  //         $PostType           =  $request->posttype;
  //         $CardNumber         =  $request->card_number;
  //         $CardType           =  $request->card_type;  
  //         $CardHold           =  $request->card_holder;  
  //         $CardExpiry         =  $request->card_expiry; 
  //         $ThumbImage         =  $request->thumb_image;           
  //         $CardCVV            =  $request->cvv;
  //         $SplitCardExpiry    =  explode("/",$CardExpiry);
  //         $ExpiryMonth        =  $SplitCardExpiry[0];
  //         $ExpiryYear         =  $SplitCardExpiry[1];

  //         $getUserDetails = User::where('id', $PostUserId)->where('status','1')->where('user_type','1')->first();
 
  //         if(!empty($getUserDetails))
  //         {
  //           $UserName = $getUserDetails->name;
  //           $UserMail = $getUserDetails->email;

  //           $response = Curl::to('http://138.197.0.234/AdMessage/pay/paypal/rest-api-sdk-php/sample/payments/CreatePostPayment.php')
  //                     ->withData(['card_number' => $CardNumber,'card_type' => $CardType,'expiry_month'=> $ExpiryMonth,'expiry_year'=> $ExpiryYear,'first_name'=> $UserName,'cvv'=> $CardCVV,'amount'=> $TotalAmount])
  //                     ->post();
  //           $getCardResult = json_decode($response); 
  //           $getDate = date('Y-m-d');
      
  //           if (isset($getCardResult->transaction_id) && $getCardResult->transaction_id != '') 
  //           {
  //             $post_create = Post::create([
  //                   'user_id'             =>  $PostUserId,
  //                   'title'               =>  $PostTitle,
  //                   'description'         =>  $Description,
  //                   'total_notification'  =>  $TotalNotification,
  //                   'amount'              =>  $TotalAmount,
  //                   'post_type'           =>  $PostType,
  //                   'transaction_id'      =>  $getCardResult->transaction_id,
  //                   'create_time'         =>  $getDate,
  //                 ]);

  //               //Multiple images file upload  start
  //               $img_files = $request->hasFile('post_image');
  //               $path = public_path().'/postimages/' . $post_create->user_id;
  //               File::makeDirectory($path, $mode = 0777, true, true);
  //               $postimages=array();

  //               if($img_files != '')
  //               {
  //                 $files = $request->file('post_image');
  //                 $destinationPath =  $path;                      
  //                 $filename = $files->getClientOriginalName();
  //                 $upload_success = $files->move($destinationPath, $filename);
                  
  //                 $post_images_create = Postimage::create([
  //                                               'post_id'         => $post_create->id,
  //                                               'user_id'         => $post_create->user_id,
  //                                               'images'          => $filename, 
  //                                           ]);

  //                 $GetPath = public_path().'/postimages/'.$post_create->user_id.'/'.$post_images_create->images;

  //                 if(file_exists($GetPath))
  //                 {
  //                   $postimages[] =  url('/').'/postimages/'.$post_create->user_id.'/'.$post_images_create->images;
  //                 }
  //               }

  //               $thumbFile = $request->hasFile('thumb_image');
  //               $thumbpath = public_path().'/postimages/' . $post_create->user_id;
  //               File::makeDirectory($thumbpath, $mode = 0777, true, true);
  //               $thumbimages=array();

  //               if($thumbFile != '')
  //               {
  //                 $Imagefiles = $request->file('thumb_image');
  //                 $ThumbPath =  $thumbpath;                      
  //                 $Thumbname =  $Imagefiles->getClientOriginalName();
  //                 $thumbMove =  $Imagefiles->move($ThumbPath, $Thumbname);

  //                 $GetImageDetails  = Postimage::where('id',$post_images_create->id)->first();
                  
  //                 $GetImageDetails->update(['image_thumbnail' => $Thumbname]);

  //                 $GetPath = public_path().'/postimages/'.$post_create->user_id.'/'.$GetImageDetails->image_thumbnail;

  //                 if(file_exists($GetPath))
  //                 {
  //                   $thumbimages[] =  url('/').'/postimages/'.$post_create->user_id.'/'.$GetImageDetails->image_thumbnail;
  //                 }
  //               }              

  //               $success['user_id']         =  $post_create->user_id;
  //               $success['post_id']         =  $post_create->id;
  //               $success['title']           =  $post_create->title;
  //               $success['description']     =  $post_create->description;
  //               $success['amount']          =  $post_create->amount;
  //               $success['post_type']       =  $post_create->post_type;
  //               $success['transaction_id']  =  $getCardResult->transaction_id;
  //               $success['status']          =  '0';
  //               $success['created_at']      =  $post_create->create_time;
  //               $success['post_image']      =  $postimages;
  //               $success['thumb_image']     =  $thumbimages;
  //               $success['msg'] =  'Your Payment has been successful. Your ad will be sent out shortly';
  //               $success['error_code'] = '0';

  //               return response()->json(['data'=>$success]); 
  //           }                 
  //           else
  //           {
  //               $success['msg'] =  'There seems to be a problem. Double check your card information so we can get you back on track.';
  //               $success['error_code'] = '5';
  //               return response()->json(['data'=>$success]);  
  //           }
  //         }
  //         else
  //         {
  //             $success['msg'] =  'Invalid UserId';
  //             $success['error_code'] = '5';
  //             return response()->json(['data'=>$success]);
  //         }
  //       }
  // }

  public function CreateMessage(Request $request)
  {
    echo "Create Post";exit();
     $validator = Validator::make($request->all(), [ 
            'user_id'           => 'required',
            // 'title'             => 'required', 
            // 'description'       => 'required',
            // 'post_image'        => 'required|max:50000',
            'totalnotificatoin' => 'required',
            'totalamount'       => 'required',
            'posttype'          => 'required',
            'type'              => 'required',
            'card_number'       => 'required',
            'card_type'         => 'required',
            'card_holder'       => 'required',
            'card_expiry'       => 'required',
            'cvv'               => 'required', 
        ]); 

        if ($validator->fails()) 
        { 
          return response()->json(['error'=>$validator->errors()], 401);            
        }
        else
        {
          $PostUserId         =  $request->user_id;
          $PostTitle          =  $request->title;
          $Description        =  $request->description;
          $TotalNotification  =  $request->totalnotificatoin;
          $TotalAmount        =  $request->totalamount;
          $PostType           =  $request->posttype;
          $Type               =  $request->type;
          $CardNumber         =  $request->card_number;
          $CardType           =  $request->card_type;  
          $CardHold           =  $request->card_holder;  
          $CardExpiry         =  $request->card_expiry; 
          $ThumbImage         =  $request->thumb_image;           
          $CardCVV            =  $request->cvv;
          $SplitCardExpiry    =  explode("/",$CardExpiry);
          $ExpiryMonth        =  $SplitCardExpiry[0];
          $ExpiryYear         =  $SplitCardExpiry[1];

          $getUserDetails = User::where('id', $PostUserId)->where('status','1')->where('user_type','1')->first();

          $TotalUser = User::all()->where('user_type', '=', 1)->where('status','=',1)->count();

          $getNotificationAvailableUser = array(); 

          // if($TotalUser < $TotalNotification) 
          // {
          //   $success['msg'] =  'Please select Minimum Number of user';
          //   $success['error_code'] = '5';
          //   return response()->json(['data'=>$success]);
          // }
          // else
          // {
            if ($TotalUser > 0)
            {
              $AllUsers = User::all()->where('user_type', '=', 1)->where('status','=',1);

              foreach ($AllUsers as $UserData) 
              {
                  $getUserId                = $UserData["id"];
                  $getUserNotificationCount = $UserData['notification_count'];//10

                  // if ($getUserId != $PostUserId) 
                  // {
                  //   $NotificationCount = PostNotification::where('post_notification.post_receiver_id',$getUserId)
                  //                           ->where('post_notification.post_date',Carbon::now()->format('Y-m-d'))->count();//5

                  //     if ($getUserNotificationCount > $NotificationCount ) 
                  //     {
                  //       array_push($getNotificationAvailableUser, $getUserId);
                  //     }                                            
                  // } 

                  //$NotificationCount = PostNotification::where('post_notification.post_receiver_id',$getUserId)
                                            //->where('post_notification.post_date',Carbon::now()->format('Y-m-d'))->count();//5
                  
                  $NotificationCount = PostNotification::where('post_notification.post_receiver_id',$getUserId)
                                      //->where('post_notification.post_sender_id','!=',$PostUserId)
                                      ->where('post_notification.post_receiver_id','!=',$PostUserId)
                                      ->where('post_notification.post_date',Carbon::now()->format('Y-m-d'))->count();                                            

                  if ($getUserNotificationCount > $NotificationCount ) 
                  {
                    array_push($getNotificationAvailableUser, $getUserId);
                  }
              }

              $getUserCount = count($getNotificationAvailableUser);
              //echo "<pre>"; print_r($getNotificationAvailableUser); die;

              if ($TotalNotification <= $getUserCount) 
              {
                if(!empty($getUserDetails))
                {
                  $UserName = $getUserDetails->name;
                  $UserMail = $getUserDetails->email;

                  $response = Curl::to('https://admessengerappbackend.com/pay/paypal/rest-api-sdk-php/sample/payments/CreatePostPayment.php')
                            ->withData(['card_number' => $CardNumber,'card_type' => $CardType,'expiry_month'=> $ExpiryMonth,'expiry_year'=> $ExpiryYear,'first_name'=> $UserName,'cvv'=> $CardCVV,'amount'=> $TotalAmount])
                            ->post();
                  $getCardResult = json_decode($response); 
                  $getDate = date('Y-m-d');
            
                  if (isset($getCardResult->transaction_id) && $getCardResult->transaction_id != '') 
                  {
                      $post_create = Post::create([
                          'user_id'             =>  $PostUserId,
                          'title'               =>  $PostTitle,
                          'description'         =>  $Description,
                          'total_notification'  =>  $TotalNotification,
                          'amount'              =>  $TotalAmount,
                          'post_type'           =>  $PostType,
                          'type'                =>  $Type,
                          'transaction_id'      =>  $getCardResult->transaction_id,
                          'create_time'         =>  $getDate,
                        ]);

                      //Multiple images file upload  start
                      $img_files = $request->hasFile('post_image');
                      $path = public_path().'/postimages/' . $post_create->user_id;
                      File::makeDirectory($path, $mode = 0777, true, true);
                      $postimages=array();

                      if($img_files != '')
                      {
                        $files = $request->file('post_image');
                        $destinationPath =  $path;                      
                        $filename = $files->getClientOriginalName();
                        $upload_success = $files->move($destinationPath, $filename);
                        
                        $post_images_create = Postimage::create([
                                                      'post_id'         => $post_create->id,
                                                      'user_id'         => $post_create->user_id,
                                                      'images'          => $filename, 
                                                  ]);

                        $GetPath = public_path().'/postimages/'.$post_create->user_id.'/'.$post_images_create->images;

                        if(file_exists($GetPath))
                        {
                          $postimages[] =  url('/').'/postimages/'.$post_create->user_id.'/'.$post_images_create->images;
                        }
                      }

                      $thumbFile = $request->hasFile('thumb_image');
                      $thumbpath = public_path().'/postimages/' . $post_create->user_id;
                      File::makeDirectory($thumbpath, $mode = 0777, true, true);
                      $thumbimages=array();

                      if($thumbFile != '')
                      {
                        $Imagefiles = $request->file('thumb_image');
                        $ThumbPath =  $thumbpath;                      
                        $Thumbname =  $Imagefiles->getClientOriginalName();
                        $thumbMove =  $Imagefiles->move($ThumbPath, $Thumbname);

                        $GetImageDetails  = Postimage::where('id',$post_images_create->id)->first();
                        
                        $GetImageDetails->update(['image_thumbnail' => $Thumbname]);

                        $GetPath = public_path().'/postimages/'.$post_create->user_id.'/'.$GetImageDetails->image_thumbnail;

                        if(file_exists($GetPath))
                        {
                          $thumbimages[] =  url('/').'/postimages/'.$post_create->user_id.'/'.$GetImageDetails->image_thumbnail;
                        }
                      }              

                      $success['user_id']         =  $post_create->user_id;
                      $success['post_id']         =  $post_create->id;
                      $success['title']           =  $post_create->title;
                      $success['description']     =  $post_create->description;
                      $success['amount']          =  $post_create->amount;
                      $success['post_type']       =  $post_create->post_type;
                      $success['transaction_id']  =  $getCardResult->transaction_id;
                      $success['status']          =  '0';
                      $success['total_user_count']=  $getUserCount;
                      $success['created_at']      =  $post_create->create_time;
                      $success['post_image']      =  $postimages;
                      $success['thumb_image']     =  $thumbimages;
                      $success['msg'] =  'Your payment has been successful. Your ad will be sent out shortly';
                      $success['error_code'] = '0';

                      return response()->json(['data'=>$success]); 
                  }                 
                  else
                  {
                      $success['msg'] =  'There seems to be a problem. Double check your card information so we can get you back on track.';
                      $success['error_code'] = '5';
                      return response()->json(['data'=>$success]);  
                  }
                }
                else
                {
                    $success['msg'] =  'Invalid UserId';
                    $success['error_code'] = '5';
                    return response()->json(['data'=>$success]);
                }
              }
              else
              {
                $success['msg'] = 'Available user is '.$getUserCount.'. Please Enter Minimum Number of user';
                $success['total_user_count'] = $getUserCount;
                $success['error_code'] = '5';
                return response()->json(['data'=>$success]);
              }
            }
          //}
        }
  }

  public function PostNotification(Request $request)
  {
     $validator = Validator::make($request->all(), [ 
            'postid'        => 'required',
        ]); 

        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        else
        {
          $PostId  =  $request->postid;
          $UserId  =  $request->userid;
          $getPostDetails = Post::where('id',$PostId)->first();
          $NotificationTotalCount = $getPostDetails->total_notification;
          $getSenderDetail = User::where('id','=',$getPostDetails->user_id)->first();
          
          $getUserDetails = User::all()->where('user_type', '=', 1)->where('status','=',1)->where('id','!=',$getPostDetails->user_id);

          $getFinalUserId_Array = array();

          foreach ($getUserDetails as $UserValue) 
          {
            $getUserId                = $UserValue["id"];
            $getUserDeviceId          = $UserValue["device_id"];
            $getUserNotificationCount = $UserValue['notification_count'];

            // $getUserNotification = PostNotification::where('post_notification.post_receiver_id',$getUserId)
            //                           ->where('post_notification.post_date',Carbon::now()->format('Y-m-d'))->first();
                        
            $NotificationCount = PostNotification::where('post_notification.post_receiver_id',$getUserId)
                                      //->where('post_notification.post_sender_id','!=',$getPostDetails->user_id)
                                      ->where('post_notification.post_receiver_id','!=',$getPostDetails->user_id)
                                      ->where('post_notification.post_date',Carbon::now()->format('Y-m-d'))->count();
            if ($NotificationCount < $getUserNotificationCount ) 
            {
              array_push($getFinalUserId_Array, $getUserId);
            }
          }
          //echo "<pre>"; print_r($getFinalUserId_Array); die;
          //echo count($getFinalUserId_Array); die;

          if (count($getFinalUserId_Array) > 0) 
          {
            foreach($getFinalUserId_Array as $key => $value )
            {
              if ($key == $NotificationTotalCount) 
              {
                break;
              }
              else
              {
                 //echo "<pre>"; print_r($value); 
                // $getUserDeviceToken = User::where('id','=',$value)->first();
                // echo "<pre>"; print_r($getUserDeviceToken->device_id); 
                $post_create = PostNotification::create([
                                            'post_sender_id'    =>  $getPostDetails->user_id,
                                            'post_receiver_id'  =>  $value,
                                            'post_id'           =>  $getPostDetails->id,
                                            'amount'            =>  "0.10",
                                            'status'            =>  0,
                                            'post_date'         =>  $getPostDetails->create_time, 
                                            'redeem_request'    =>  0,
                                          ]);

                $getUserDeviceToken = User::where('id','=',$value)->first();

                if (isset($getUserDeviceToken->device_id)) 
                {

                  // $getBatchCount = UserNotification::select('batch_count')->where('user_id',$value)
                  //                       ->where('sender_id',$getPostDetails->user_id)->count();

                  // $getBatchUsers = UserNotification::select('batch_count')->where('user_id',$value)
                  //                             ->where('sender_id',$getPostDetails->user_id)->first();                                      
                  // if ($getBatchCount > 0) 
                  // {
                  //   $badgeCount = (int) $getBatchUsers->batch_count + 1;
                  //   $getBatchUsers->update(['batch_count' => $badgeCount]);
                  // }
                  // else
                  // {
                  //   $NotificationCreate = UserNotification::create([
                  //                             'user_id'           =>  $value,
                  //                             'sender_id'         =>  $getPostDetails->user_id,
                  //                             'batch_count'       =>  1,
                  //                             'notification_type' =>  "post",
                  //                             'status'            =>  0,
                  //                           ]);
                  // }      

                  //$BatchCount = DB::table('user_notification')->where('user_id', '=', $UserId)->sum('batch_count');

                  $title = "New Post";
                   $message = $getSenderDetail->name. " has create a New Post ";
                  $pushparameter = '{"ResponseCode":"200","badge":"1"}';

                  // if($getPostDetails->user_id)
                  // {
                  //   $message = "Post created Successfully";
                  // }
                  // else
                  // {
                  //   $message = $getSenderDetail->name. " has create a New Post ";
                  // }

                  if(!empty($getUserDeviceToken->device_id))
                  {

                    if(strlen($getUserDeviceToken->device_id) == 64)
                    {
                      $GetMyDeviceId = $getUserDeviceToken->device_id;
                      //echo "<pre>"; print_r($GetMyDeviceId); 
                      $this->sendIosPushNotification($title, $message, $GetMyDeviceId, $pushparameter, '');
                    }
                    else
                    {
                      $GetMyDeviceId = $getUserDeviceToken->device_id;
                      $this->sendAndroidPushNotification($title, $message, $GetMyDeviceId, $pushparameter, '');
                    }
                  }                                
                }
              }
            }
             $post_create = PostNotification::create([
                                            'post_sender_id'    =>  $getPostDetails->user_id,
                                            'post_receiver_id'  =>  $getPostDetails->user_id,
                                            'post_id'           =>  $getPostDetails->id,
                                            'amount'            =>  "0.10",
                                            'status'            =>  0,
                                            'post_date'         =>  $getPostDetails->create_time, 
                                            'redeem_request'    =>  0,
                                          ]);
          }
         
          $NotificationCount = PostNotification::where('post_id',$getPostDetails->id)
                                      ->where('post_date',$getPostDetails->create_time)->count();

          $success['totalpostinsert'] =  $NotificationCount;
          $success['msg']             =  'Success';
          $success['error_code']      = '0';

          return response()->json(['data'=>$success]);
        }

  }

  function sendAndroidPushNotification($title = "",$message = "",$GetMyDeviceId = "", $pushparameter = "", $badge = 1)
  {
    if(!defined('API_ACCESS_KEY'))
    {
      define('API_ACCESS_KEY','AAAARhSD2L8:APA91bE5WyFzHTEhfwV_UOsVRU6-TvdFpQDPzJbyA8r33uwg_HM71lSn7Mv4Ja_jfX4Tghy7qp7Ci77NCyXSSeuSmZcAtCKM8FibYIkNuXGmkht3DdZyQhDvPGynhJM5sveGGps3zZo1');
    }
      //define( 'API_ACCESS_KEY', 'AIzaSyC2sZVR3fmz-e0lk5reYmHA30ZirM-L7Sg');

      // $registrationIds = 'f1FdYysnQPA:APA91bF8ElbCbRFHqBC5COD_9DrmGUNrYS2CQmD2D1hXnO-TZE12dCYMAiekidHoJAjS7Su7MNyndk-KWcr0F6xL01FQSG-0F1vSIbvtrj7Rk5xhID9VCeC7AAnyNkfxRUWkF5DVYfUz';

    $registrationIds = array();

      $msg = array
            (
            'body'  => $message,
            'title' => $title,
            //'icon'  => 'myicon',/*Default Icon*/
            //'badge' =>  $badge,
            //'sound' => 'mySound'/*Default sound*/
            );

      $fields = array(
          'to' => $GetMyDeviceId,
          'data' => array('to' => $GetMyDeviceId, 'message' => $msg)
        );
      $headers = array
        (
          'Authorization: key='.API_ACCESS_KEY,
          'Content-Type: application/json'
        );

      $ch = curl_init();
      curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
      curl_setopt( $ch,CURLOPT_POST, true );
      curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
      curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
      curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
      $result = curl_exec($ch );
      curl_close( $ch );
      return $result;
  }

  function sendIosPushNotification($title = "", $message = "", $GetMyDeviceId = "", $pushparameter = "", $badge = 1) 
  {
    //echo "<pre>"; print_r($GetMyDeviceId); die;
    try
    {
      $passphrase = '123';
      $pemfilepath = 'https://admessengerappbackend.com/AdMessenger_push.pem';
      // $Islive = 1;
      // if ($Islive == 1) {
      //  $pemfilepath = '/var/www/html/api/dev_adver_push.pem';
      // } else {
      //  $pemfilepath = '/var/www/html/api/live_adver_push.pem';
      // }
      $ctx = stream_context_create();
      stream_context_set_option($ctx, 'ssl', 'local_cert', $pemfilepath);
      stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
      $fp = stream_socket_client(
        'ssl://gateway.push.apple.com:2195', $err,
        $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
      if (!$fp) {
        exit;
      }
      $body['aps'] = array(
        'alert' => array(
          'title' => $title,
          'body' => $message,
        ),
        'badge' => (int) $badge,
        //'message' => $pushparameter,
        'sound' => '1,624',
      );
      $payload = json_encode($body);
      $msg = chr(0) . pack('n', 32) . pack('H*', $GetMyDeviceId) . pack('n', strlen($payload)) . $payload;
      $result = fwrite($fp, $msg, strlen($msg));
      fclose($fp);
      //echo "<pre>"; print_r($result);
      return 1;
    } catch (Exception $e) {
      print_r($e);
    }
  }

  public function GetNotification(Request $request)
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
                                                      //echo "<pre>"; print_r($getTotalAmount); die;
      //$amount = $getTotalAmount/100;    

      $getUserPostCount = PostNotification::join('create_post','create_post.id','=','post_notification.post_id')
                   ->join('users','users.id','=','post_notification.post_receiver_id')
                   
                   ->where('post_notification.post_receiver_id','=',$UserId)
                   ->orderBy('post_notification.post_id', 'DESC')
                   ->addselect('post_notification.amount as postamount','post_notification.status as poststatus','post_notification.*' ,'users.*','create_post.*')->count();

      $getall_user_detail  = array(); 

      if ($getUserPostCount > 0) 
      {
        // $getUserPost = PostNotification::join('create_post','create_post.id','=','post_notification.post_id')
        //            ->join('users','users.id','=','post_notification.post_receiver_id')
        //            ->join('post_images','post_images.post_id','=','create_post.id')
        //            ->where('post_notification.post_receiver_id','=',$UserId)
        //            ->orderBy('post_notification.post_id', 'DESC')
        //            ->addselect('post_notification.amount as postamount','post_notification.status as poststatus','post_notification.*' ,'users.*','post_images.*','create_post.*')->paginate(10);

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

            // return response()->json(['data' => $getall_user_detail, 'total_amount' => number_format((float)$amount,2, '.', ''),'user_name' => $UserPost['name'],'msg' => 'Success','error_code' => '0']);
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

  public function ViewMessage(Request $request)
  {
    $validator = Validator::make($request->all(), [ 
            'post_id'        => 'required',
            'user_id'        => 'required',
        ]); 

    if ($validator->fails()) 
    { 
        return response()->json(['error'=>$validator->errors()], 401);            
    }
    else
    {
      $PostId  =  $request->post_id;
      $UserId  =  $request->user_id;

      $GetMessageDetail = PostNotification::where('post_id',$PostId)
                                      ->where('post_receiver_id',$UserId)
                                      ->where('status',0)->first();

      if (isset($GetMessageDetail) && $GetMessageDetail != '' ) 
      {
          $GetMessageDetail->update(['status' => 1]);

          // $getRedeemAmount = DB::table('post_notification')->where('post_receiver_id', '=', $UserId)
          //                                             ->where('status','=',1)->sum('amount');

          // $getRedeemAmount = DB::table('post_notification')->where('post_receiver_id', '=', $UserId)
          //                                               ->where('redeem_request','=',0)
          //                                               ->where('status','=',1)->sum('amount');
          $requestAmount1 = array('0','1');                                                        
          
          $getRedeemAmount = DB::table('post_notification')->where('post_receiver_id', '=', $UserId)
                                                      ->whereIn('redeem_request',$requestAmount1)
                                                      ->where('status','=',1)->sum('amount');                                                        
                                                        
                              //echo "<pre>"; print_r($getRedeemAmount); die;
          //$UserAdAmount = $getRedeemAmount/100;

          $Success['msg']           = 'Success';
          $Success['redeem_amount'] = $getRedeemAmount;
          $Success['error_code']    = '0';

          return response()->json(['data'=>$Success]);
      }
      else
      {
        $Success['msg']          = 'Fail';
        $Success['error_code']   = '5';
        return response()->json(['data'=>$Success]);
      }
    }
  }

  public function NotificationSetting(Request $request)
  {
    $validator = Validator::make($request->all(), [ 
            'user_id' => 'required',
            'count'   => 'required',  
        ]); 

    if ($validator->fails()) 
    { 
        return response()->json(['error'=>$validator->errors()], 401);            
    }
    else
    {
      $UserId  =  $request->user_id;
      $Count   =  $request->count;

      $GetNotificationStatus  = User::where('id',$UserId)
                                      ->where('user_type',1)
                                      ->where('status',1)->first();

      if (isset($GetNotificationStatus) && $GetNotificationStatus != '' ) 
      {
          $GetNotificationStatus->update(['notification_count' => $Count]);
          $Success['msg']          = 'Notification Update Successfully';
          $Success['count']        = $Count;
          $Success['error_code']   = '0';

          return response()->json(['data'=>$Success]);
      }
      else
      {
        $Success['msg']          = 'Invalid User Id';
        $Success['error_code']   = '5';
        return response()->json(['data'=>$Success]);
      }
    }
  }


  public function RedeemRequest(Request $request)
  {
    $validator = Validator::make($request->all(), [ 
            'user_id' => 'required',
            //'amount'  => 'required',  
        ]); 

    if ($validator->fails()) 
    { 
        return response()->json(['error'=>$validator->errors()], 401);            
    }
    else
    {
      $getUserid = $request->user_id;

      $getUserDetails = User::where('id', $getUserid)->where('status','1')->where('user_type','1')->first();

      if (isset($getUserDetails) && $getUserDetails != '') 
      {
        $CheckUserPaypalEmail = User::where('id', $getUserid)->where('status','1')->where('user_type','1')->first();

        $GetpaypaEmail = isset($CheckUserPaypalEmail->paypal_email) ? $CheckUserPaypalEmail->paypal_email : "";

        if ($GetpaypaEmail != '') 
        {
          $getTotalAmount1 = DB::table('post_notification')->where('post_receiver_id', '=', $getUserid)
                                                        ->where('redeem_request','=',0)
                                                        ->where('status','=',1)->sum('amount');
          $getTotalAmount = floatval($getTotalAmount1);


          if ($getTotalAmount > 1) 
          {
            $UserName        = $getUserDetails->name;
            $UserMail        = $getUserDetails->email;
            $UserPaypalEmail = $GetpaypaEmail;
            $itmeId   = "item_".uniqid();

            $response =Curl::to('https://admessengerappbackend.com/pay/paypal/rest-api-sdk-php/sample/payouts/CreateSinglePayout.php')
                          ->withData(['paypal_email'  => $UserPaypalEmail,
                                      'redeem_amount' => $getTotalAmount,
                                      'itemId'        => $itmeId ])->post();

            $getResult = json_decode($response, true);

            if ($getResult['batch_header']['payout_batch_id'] != '') 
            {
                $RedeemCreate = RedeemPayout::create([
                                  'user_id'           =>  $getUserid,
                                  'amount'            =>  $getTotalAmount,
                                  'payout_batch_id'   =>  $getResult['batch_header']['payout_batch_id'],
                                  'batch_status'      =>  $getResult['batch_header']['batch_status'],
                                  'sender_batch_id'   =>  $getResult['batch_header']['sender_batch_header']['sender_batch_id'],
                                  'item_id'           =>  $itmeId,
                                  'item_status'       =>  '',
                                ]);

                $getRedeemAmount = DB::table('post_notification')
                                      ->where('post_receiver_id', '=', $getUserid)
                                      ->where('status','=',1)
                                      ->where('redeem_request','=',0);

                if (isset($getRedeemAmount) && $getRedeemAmount != '' ) 
                {
                  $getRedeemAmount->update(['amount' => '0','redeem_request' => '2']);
                }

                $CheckTotalAmount = RedeemRequest::where('user_id',$getUserid)->first();

                // if (isset($CheckTotalAmount) && $CheckTotalAmount != '')
                // {
                //   $GetSumAmount = DB::table('redeem_request')
                //                               ->where('user_id', '=', $getUserid)->sum('amount');
                  
                //   $GetFinalAmount = $GetSumAmount + $getTotalAmount;

                //   $UpdateTotalAmount = DB::table('redeem_request')->where('user_id', '=', $getUserid);

                //   $UpdateTotalAmount->update(['amount' => $GetFinalAmount,
                //                               'status' => '2',
                //                               'item_id'   =>  $getResult['batch_header']['payout_batch_id'],
                //                               ]);                                              
                // }
                // else
                // {
                  $post_create = RedeemRequest::create([
                            'user_id'   =>  $getUserid,
                            'amount'    =>  $getTotalAmount,
                            'status'    =>  2,
                            'item_id'   =>  $getResult['batch_header']['payout_batch_id']
                          ]);
                //}              

                $getUserName  = $getUserDetails->name;
                $getUserEmail = $getUserDetails->email;

                Mail::send('emails.redeem-amount', ['getUserEmail' => $getUserEmail,'getUserName' => $getUserName,'getTotalAmount' => $getTotalAmount,'UserPaypalEmail' => $UserPaypalEmail], function ($m) use ($getUserEmail,$getUserName,$getTotalAmount,$UserPaypalEmail) 
                { 
                  $m->to($getUserEmail)->subject('Redeem Amount From AdMessenger'); 
                });

                $success['title']       =  'Payment Success';
                $success['msg']         =  'Your redeem amount has been successfully credited in your paypal account. Please kindly check your paypal account.';
                $success['error_code']  = '0';
                $success['total_amount']  =  $getTotalAmount1;
                return response()->json(['data'=>$success]);
            }
            else
            {
              $success['title']       =  'Payment Failure';
              $success['msg']         =  'Please contact to Admin.';
              $success['error_code']  = '5';
              return response()->json(['data'=>$success]);
            }
          }
          else
          {
            $success['title'] =  'Insufficient Amount';
            $success['msg'] =  'You need more than $1 of amount.';
            $success['error_code'] = '5';
            return response()->json(['data'=>$success]);
          }
        }
        else
        {
          $success['title'] =  'Paypal email required';
          $success['msg'] =  'Paypal email is required. Please add the paypal email in account page.';
          $success['error_code'] = '5';
          return response()->json(['data'=>$success]);
        }
      }
      else
      {
        $success['title'] =  'Invalid Userid';
        $success['msg'] =  'Invalid UserId';
        $success['error_code'] = '5';
        return response()->json(['data'=>$success]);
      }
    }
  }

  public function AutopayByUser(Request $request)
  {
    $columns = array( 
                          0 =>'redeem_request.id', 
                          1 =>'redeem_request.amount',
                          2 =>'users.name',
                          3 => 'redeem_request.amount',
                          4 => 'redeem_request.status',
                          5 => 'redeem_request.user_id',
                      );

    $totalData = RedeemRequest::count();
          
      $totalFiltered = $totalData; 

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if(empty($request->input('search.value')))
      {            
          $posts = RedeemRequest::select('redeem_request.id','redeem_request.user_id','redeem_request.amount','redeem_request.status','users.name')
                  ->join('users','users.id','=','redeem_request.user_id')
                  ->orderBy('redeem_request.status','ASC')
                  ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();
            
      }
      else 
      {
          $search = $request->input('search.value'); 

          $posts =  RedeemRequest::select('redeem_request.id','redeem_request.user_id','redeem_request.amount','redeem_request.status','users.name')
                  ->join('users','users.id','=','redeem_request.user_id')
                  ->orderBy('redeem_request.status','ASC')
                  ->orWhere('users.name', 'LIKE',"%{$search}%")
                          ->orWhere('amount', 'LIKE',"%{$search}%")
                          //->orWhere('status', 'LIKE',"%{$search}%")
                          ->offset($start)
                          ->limit($limit)
                          ->orderBy($order,$dir)
                          ->get();

          $totalFiltered = RedeemRequest::select('redeem_request.id','redeem_request.user_id','redeem_request.amount','redeem_request.status','users.name')
                  ->join('users','users.id','=','redeem_request.user_id')
                  ->orderBy('redeem_request.status','ASC')
                   ->orWhere('users.name', 'LIKE',"%{$search}%")
                           ->orWhere('amount', 'LIKE',"%{$search}%")
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
              $nestedData['amount']   = $post->amount;

              if ($post->status == 1) 
              {
                $nestedData['status'] = '<span class="badge badge-warning">Request</span>';
                $nestedData['action'] = '<button type="button" class="btn btn-primary redbtn" onclick="myFunction(this);" data-requ-amount="'.$post->amount.'" data-user-id="'.$post->user_id.'"> Redeem </button>';
              }
              else
              {
                $nestedData['status'] = '<span class="badge badge-success">Success -- Amount Paid</span>';
                $nestedData['action'] = '<button type="button" class="btn btn-success">Success</button>';
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

  public function SuccessRedeemAmount(Request $request)
    {
      $ItemIds = $request->itemId;
      //echo "<pre>"; print_r($ItemIds); die;
      $response = Curl::to('https://admessengerappbackend.com/pay/paypal/rest-api-sdk-php/sample/payouts/GetPayoutBatchStatus.php')
                    ->withData(['ItemIds' => $ItemIds])
                      ->post();
        $getEncodeData = json_encode($response, true);

        $getResult1 = json_decode($getEncodeData, true);
    }

  // public function RedeemRequest(Request $request)
  // {
  //   $validator = Validator::make($request->all(), [ 
  //           'user_id' => 'required',
  //           //'amount'  => 'required',  
  //       ]); 

  //   if ($validator->fails()) 
  //   { 
  //       return response()->json(['error'=>$validator->errors()], 401);            
  //   }
  //   else
  //   {
  //     $getUserid = $request->user_id;

  //     $getUserDetails = User::where('id', $getUserid)->where('status','1')->where('user_type','1')->first();

  //     if (isset($getUserDetails) && $getUserDetails != '') 
  //     {

  //       $CheckUserPaypalEmail = User::where('id', $getUserid)->where('status','1')->where('user_type','1')->first();

  //       $GetpaypaEmail = isset($CheckUserPaypalEmail->paypal_email) ? $CheckUserPaypalEmail->paypal_email : "";

  //       if ($GetpaypaEmail != '') 
  //       {
  //           $getTotalAmount1 = DB::table('post_notification')->where('post_receiver_id', '=', $getUserid)
  //                                                       ->where('redeem_request','=',0)
  //                                                       ->where('status','=',1)->sum('amount');
  //           //$amount = $getTotalAmount/100;
            
  //           $getTotalAmount = floatval($getTotalAmount1);
  //           $SendRedeemRequest = RedeemRequest::where('user_id',$getUserid)
  //                                         ->where('status',1)->first();

  //           //Check if the user already exists of not in payment request table                              
  //           if(count($SendRedeemRequest) > 0) 
  //           {
  //               if ($SendRedeemRequest->amount < $getTotalAmount) 
  //               {
  //                   $SendRedeemRequest->update(['amount' => $getTotalAmount]);

  //                   $UpdateRedeemRequest = DB::table('post_notification')->where('post_receiver_id', '=', $getUserid)
  //                                                     ->where('redeem_request','=',0)
  //                                                     ->where('status','=',1);

  //                   if (isset($UpdateRedeemRequest) && $UpdateRedeemRequest != '' ) 
  //                   {
  //                     $UpdateRedeemRequest->update(['redeem_request' => 1]);
  //                   }


  //                   $success['title'] =  'Request Sent';
  //                   $success['msg'] =  'Your redemption request has been sent to the admin.';
  //                   $success['error_code'] = '0';
  //                   return response()->json(['data'=>$success]);
  //               }
  //               elseif ($SendRedeemRequest->amount = $getTotalAmount) 
  //               { 
  //                   $success['title'] =  'Already Sent';
  //                   $success['msg'] =  'Request Already Sent';
  //                   $success['error_code'] = '5';
  //                   return response()->json(['data'=>$success]);
  //               }
  //               else
  //               {
  //                 $success['title'] =  'Insufficient Amount';
  //                 $success['msg'] =  'You need more than $1 of amount.';
  //                 $success['error_code'] = '5';
  //                 return response()->json(['data'=>$success]);
  //               }
  //           }
  //           else 
  //           {
  //               //Fresh user so create new request
  //               if ($getTotalAmount >= 1) 
  //               {
  //                 $post_create = RedeemRequest::create([
  //                                 'user_id'   =>  $getUserid,
  //                                 'amount'    =>  $getTotalAmount,
  //                                 'status'    =>  1,
  //                               ]);

  //                 $UpdateRedeemRequest = DB::table('post_notification')->where('post_receiver_id', '=', $getUserid)
  //                                                       ->where('redeem_request','=',0)
  //                                                       ->where('status','=',1);
  //                 if (isset($UpdateRedeemRequest) && $UpdateRedeemRequest != '' ) 
  //                 {
  //                   $UpdateRedeemRequest->update(['redeem_request' => 1]);
  //                 }

  //                 $success['title'] =  'Request Sent';
  //                 $success['msg'] =  'Your redemption request has been sent to the admin.';
  //                 $success['error_code'] = '0';
  //                 return response()->json(['data'=>$success]);
  //               }
  //               else
  //               {
  //                 $success['title'] =  'Insufficient Amount';
  //                 $success['msg'] =  'You need more than $1 of amount.';
  //                 $success['error_code'] = '5';
  //                 return response()->json(['data'=>$success]);
  //               }
  //           }
  //       }
  //       else
  //       {
  //         $success['title'] =  'Paypal email required';
  //         $success['msg'] =  'Paypal email is required. Please add the paypal email in account page.';
  //         $success['error_code'] = '5';
  //         return response()->json(['data'=>$success]);
  //       }
  //     }
  //     else
  //     {
  //       $success['title'] =  'Invalid Userid';
  //       $success['msg'] =  'Invalid UserId';
  //       $success['error_code'] = '5';
  //       return response()->json(['data'=>$success]);
  //     }
  //   }
  // }

}
