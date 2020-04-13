<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
/* CoreUI templates */

Route::middleware('auth')->group(function() {
	//Route::view('/', 'panel.blank');
	//Route::view('/', 'samples.dashboard');
	//Route::post('/getlogin', 'Auth\LoginController@login');
	
	Route::get('/', 'UserController@DashboardDetails');
	Route::get('/userdetails', 'UserController@UserDetails');  
	Route::get('/edit-user/{userid}', 'UserController@EditUserDetails'); 
	Route::post('update-user-details', 'UserController@UpdateUser');
	Route::post('/delete-user', 'UserController@DeleteUser');
	Route::get('/redeem-details','UserController@RedeemDetail');
	Route::post('/pay-redeem-amount','UserController@PayRedeemAmount');
	Route::post('/check-redeem-status','UserController@CheckRedeemStatus');

	Route::post('/getdata', 'UserController@GetRedeemDetails');
	Route::post('/getuserdata', 'UserController@GetUserDetails');
	// Section CoreUI elements
	Route::view('/sample/dashboard','samples.dashboard');
	Route::view('/sample/buttons','samples.buttons');
	Route::view('/sample/social','samples.social');
	Route::view('/sample/cards','samples.cards');
	Route::view('/sample/forms','samples.forms');
	Route::view('/sample/modals','samples.modals');
	Route::view('/sample/switches','samples.switches');
	Route::view('/sample/tables','samples.tables');
	Route::view('/sample/tabs','samples.tabs');
	Route::view('/sample/icons-font-awesome', 'samples.font-awesome-icons');
	Route::view('/sample/icons-simple-line', 'samples.simple-line-icons');
	Route::view('/sample/widgets','samples.widgets');
	Route::view('/sample/charts','samples.charts');

	Route::get('/change_password', 'UserController@ChangePassword');
	Route::post('/update_password', 'UserController@UpdatePassword');
	

});
// Section Pages
Route::view('/sample/error404','errors.404')->name('error404');
Route::view('/sample/error500','errors.500')->name('error500');

//Route::post('signup', 'Auth\RegisterController@store');
Route::post('getlogin', [ 'as' => 'getlogin', 'uses' => 'Auth\LoginController@login']);
Route::post('/v1/user/register', 'Auth\RegisterController@store');