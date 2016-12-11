<?php

//require ('includes/config.php');

/* Database connection start */
$servername = "";
$username = "";
$password = "";
$dbname = "";
$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
/* Database connection end */

$rids = $_POST['rids'];
$uid  = $_POST['uid'];


$rids_array = explode(",", $rids);
if(!empty($rids_array)) {
	foreach($rids_array as $id) {
		$sql = "DELETE FROM contacts ";
		$sql.=" WHERE userid = $uid AND contactid = '".$id."'";
		$query=mysqli_query($conn, $sql) or die("contactDelete.php: delete contacts");
	}
}

//var_dump($_REQUEST);

var_dump($rids);
//echo json_encode( array( 'Rows have been deleted successfully' ) );

?>
