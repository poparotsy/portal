<?php
require_once 'includes/config.php';

// header('Content-type: application/json');

$email = $_POST ['email'];

$resetEmail = $_POST ['resetEmail'];

$resetPasswordEmail = $_POST ['resetPasswordEmail'];
$resetPasswordKey = $_POST ['key'];

/*
 * Check if the resetPasswordKey from the email link matches the user email
 */

if (! is_null ( $resetPasswordKey ) && ! is_null ( $resetPasswordEmail )) {
	$valieEmail = $user->validEmail ( $resetPasswordEmail );
	$accountKey = $user->checkResetRequest ( $resetPasswordKey, $resetPasswordEmail );
	$valid = false;
	
	if (is_null ( $valieEmail )) {
		
		if (! isset ( $accountKey )) {
			$valid = true;
		}
	}
	
	echo json_encode ( array (
			'valid' => $valid 
	) );
}

/*
 * Email already regiesterd check [we don't want that]
 */

if (! is_null ( $email )) {
	$valieEmail = $user->validEmail ( $email );
	$exist = $user->emailExists ( $email );
	if (is_null ( $valieEmail )) {
		
		if (isset ( $exist )) {
			
			http_response_code ( 418 );
			
			$valid = false;
		} else {
			
			http_response_code ( 200 );
			
			$valid = true;
		}
	} else {
		$valid = false;
	}
	echo json_encode ( array (
			'valid' => $valid 
	) );
}

/*
 * Provided is an already registerd email [we want that]
 */

if (! is_null ( $resetEmail )) {
	$valieEmail = $user->validEmail ( $resetEmail );
	$exist = $user->emailExists ( $resetEmail );
	
	if (is_null ( $valieEmail )) {
		
		if (isset ( $exist )) {
			
			$valid = true;
		} else {
			
			$valid = false;
		}
	}
	
	echo json_encode ( array (
			'valid' => $valid 
	) );
}

// echo "\r\n";

// echo "resetEmail: " . $resetEmail;

// echo "\r\n";

// echo "exist: " . $exist;

// echo "\r\n";

// echo "emailFormat: " . $valieEmail;

?>