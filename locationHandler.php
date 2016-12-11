<?php

require 'classes/database.php';


// header("content-type:application/json");

// $countries = trim($_GET['countries']);
$countries = trim ( $_POST ['countries'] );
$country = $_POST ['country'];
$state = $_POST ['state'];

$db = new Database ();
function countryList(Database $db) {
	$stmt = $db->getInstance ()->prepare ( 'SELECT * FROM country' );
	$stmt->execute ();
	
	// $row = $stmt->fetch ( PDO::FETCH_ASSOC );
	// ini_set('memory_limit', '-1');
	while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
		
		$data ['value'] = $row ['countryid'];
		$data ['label'] = $row ['country'];
		$json [] = $data;
	}
	
	echo json_encode ( $json );
}
function stateList(Database $db, $country) {
	$stmt = $db->getInstance ()->prepare ( "SELECT * FROM state WHERE countryid = :countryid" );
	$stmt->execute ( array (
			':countryid' => $country 
	) );
	
	while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
		
		$data ['value'] = $row ['id'];
		$data ['label'] = $row ['statename'];
		$json [] = $data;
	}
	echo json_encode ( $json );
}
function cityList(Database $db, $state) {
	$stmt = $db->getInstance ()->prepare ( "SELECT * FROM city WHERE stateid = :stateid" );
	$stmt->execute ( array (
			':stateid' => $state 
	) );
	
	while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
		$data ['value'] = $row ['id'];
		$data ['label'] = $row ['city'];
		$json [] = $data;
	}
	echo json_encode ( $json );
}

if (! empty ( $countries )) {
	
	if ($countries == "all") {
		
		countryList ( $db );
	}
}

if (isset ( $country )) {
	
	stateList ( $db, $country );
}

if (isset ( $state )) {
	
	cityList ( $db, $state );
}

?>