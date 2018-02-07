<?php
    require_once('PushNotifications.php');
	// Message payload
	$msg_payload = array (
		'mtitle' => 'Test push notification title11',
		'mdesc' => 'Test push notification body111',
	);

	$deviceToken = '4a7752c491cd0edbf2111a662ba37bcb5553d7c8d10df18cf8a744de8e7c6fcb';

    echo PushNotifications::iOS($msg_payload, $deviceToken);
?>