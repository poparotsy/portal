<?php
//require ('includes/config.php');

/* Database connection start */
$servername = "";
$username = "";
$password = "";
$dbname = "";
$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
/* Database connection end */


// storing  request (ie, get/post) global array to a variable
$requestData= $_REQUEST;
$columns = array(
// datatable column index  => database column name
		0 =>'firstName',
		1 => 'lastName',
		2 => 'phone',
		3 => 'email',
		4 => 'groupName'
);
// getting total number records without any search

//$uid = $_SESSION ['id'];
//$uid =$_POST['uid'];
$uid = $_POST['uid'];
//$uid = $_GET['uid'];

//$uid = 2;
//echo $uid;

$sql = "SELECT contactid FROM contacts WHERE userid = $uid";

// $sql = "SELECT firstName, lastName, phone, email, groupName FROM contacts WHERE userid = $uid";

// echo $sql;

$query=mysqli_query($conn, $sql) or die("dataTablesHelper.php: Zero");

$totalData = mysqli_num_rows($query);


// echo "<pre>";
// print_r($_REQUEST);
// echo "</pre>";

// echo "<pre>";
// echo $totalData;
// echo "</pre>";

$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$sql = "SELECT contactid, firstName, lastName, phone, email, groupName FROM contacts WHERE userid = $uid AND 1=1";



if( !empty($requestData['search']['value']) ) {
	// if there is a search parameter
	// $sql = "SELECT firstName, lastName, phone, email, groupName FROM contacts WHERE userid = $uid";
	
	$sql.=" AND firstName LIKE '".$requestData['search']['value']."%' ";    // $requestData['search']['value'] contains search parameter
	$sql.=" OR lastName LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR email LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR phone LIKE '".$requestData['search']['value']."%' ";
	
	
	// $query=mysqli_query($conn, $sql) or die("dataTablesHelper.php: get First Query");
	
	// $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query
	// $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
	// $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
	// $query=mysqli_query($conn, $sql) or die("dataTablesHelper.php: Second"); // again run query with limit

} 

	$query=mysqli_query($conn, $sql) or die("dataTablesHelper.php: get First Query");
	$totalFiltered = mysqli_num_rows($query);
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	$query=mysqli_query($conn, $sql) or die("dataTablesHelper.php: Second");

	// $sql = "SELECT firstName, lastName, phone, email, groupName FROM contacts WHERE userid = $uid";
	// $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	
	
	// $query=mysqli_query($conn, $sql) or die("dataTablesHelper.php: get Third");


$data = array();
$i=1+$requestData['start'];
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array();
	
	//$nestedData[] = "<input type='checkbox'  class='deleteRow' value='".$row['id']."'  /> ".$i ;
	$nestedData[] = $row['contactid'];
	$nestedData[] = $row['firstName'];
	$nestedData[] = $row['lastName'];
	$nestedData[] = $row['phone'];
	$nestedData[] = $row['email'];
	$nestedData[] = $row['groupName'];

	$data[] = $nestedData;
	$i++;
}
$json_data = array(
		"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
		"recordsTotal"    => intval( $totalData ),  // total number of records
		"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
		"data"            => $data   // total data array
);

echo json_encode($json_data);  // send data as json format

?>

