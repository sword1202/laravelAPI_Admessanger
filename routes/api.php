<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::post('/v1/user/register', 'Auth\RegisterController@store');
Route::post('getlogin', [ 'as' => 'getlogin', 'uses' => 'Auth\LoginController@login']);
Route::post('/v1/user/registers', 'UserRegisterController@store');
Route::get('/v1/user/verify/{token}', 'UserRegisterController@verifyUser');

Route::post('/v1/user/signin','Auth\LoginController@signIn');
Route::post('/v1/user/forget-password','UserRegisterController@ForgetPassword');
Route::get('/v1/user/reset-password/{userId}','UserRegisterController@ResetPassword');
Route::post('/v1/user/change-password','UserRegisterController@ChangePassword');
Route::post('/v1/user/change-email','UserRegisterController@UpdateEmail');
Route::post('/v1/user/empty-devie-id','UserRegisterController@EmptyDeviceId');
Route::post('/v1/user/update-devie-id','UserRegisterController@UpdateDeviceId');

Route::post('/v1/user/update-paypal-email','UserRegisterController@UpdatePaypalEmail');

Route::post('/v1/user/check-email','UserRegisterController@checkemails');
Route::post('/v1/user/check-user-name','UserRegisterController@CheckUserName');

Route::post('/v1/user/change-new-password','UserRegisterController@ChangeNewPassword');


Route::post('/v1/user/create_post','PostController@CreateMessage');
Route::post('/v1/user/select-post','PostController@PostNotification');
Route::post('/v1/user/show-post','PostController@GetNotification');
Route::post('/v1/user/view-message','PostController@ViewMessage');
Route::post('/v1/user/notification-setting','PostController@NotificationSetting');
Route::post('/v1/user/redeem-request','PostController@RedeemRequest');
