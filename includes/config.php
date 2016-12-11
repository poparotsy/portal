<?php
ob_start ();
session_start ();

// set timezone
date_default_timezone_set ( '' );

// database credentials
define ( 'DBHOST', '' );
define ( 'DBUSER', '' );
define ( 'DBPASS', '' );
define ( 'DBNAME', '' );
// define('DSN', 'mysql:dbname=".DBNAME";host=."DBHOST"');

// application address
define ( 'DIR', 'http://domain.com/' );
define ( 'SITEEMAIL', 'noreply@domain.com' );
define ( 'WEBSITE', 'SMS Portal' );
include 'classes/user.php';
include ('classes/phpmailer/mail.php');
// include 'classes/location.php';
$user = new User ( $db );
// $location = new Location();
$mailProperties = array ();
?>

