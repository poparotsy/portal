<?php
require ('includes/config.php');

$userID = trim ( $_GET ['id'] );
$active = trim ( $_GET ['active'] );

$validate [] = $user->activateUserAccount ( $userID, $active );

if (isset ( $validate )) {
	foreach ( $validate as $error ) {
		echo '<p class="bg-danger">' . $error . '</p>';
	}
}

?>