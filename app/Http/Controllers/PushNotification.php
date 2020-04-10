<?php
//
	sendAndroidPushNotification($title = "", $message = "", $getUserDeviceId = "", $pushparameter = "", $badge=1) 
	{
		
		#API access key from Google API's Console
    	define( 'API_ACCESS_KEY', 'AIzaSyC2sZVR3fmz-e0lk5reYmHA30ZirM-L7Sg')

    	$registrationIds = $getUserDeviceId;

	    $msg = array
	          (
				'body' 	=> $message,
				'title'	=> $title,
	         	'icon'	=> 'myicon',/*Default Icon*/
	         	'badge' =>  $badge,
	          	'sound' => 'mySound'/*Default sound*/
	          );


		$fields = array
			(
				'to'		=> $registrationIds,
				'notification'	=> $msg
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
	}

//}

?>