<?php

// require_once '../includes/config.php';
class Location {
	private $db;
	public function __construct(PDO $db = null) {
		$this->db = $db;
		if ($this->db === null) {
			$this->db = new PDO ( "mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS );
			$this->db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}
	}
	public function countryList() {
		$stmt = $this->db->prepare ( 'SELECT * FROM country' );
		$stmt->execute ();
		
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		
		while ( $row ) {
			
			$data ['value'] = $row ['countryid'];
			$data ['label'] = $row ['country'];
			$json [] = $data;
		}
		
		echo json_encode ( $json );
	}
	public function stateList($countryid) {
		$stmt = $this->db->prepare ( "SELECT * FROM state WHERE countryid = :countryid" );
		$stmt->execute ( array (
				':countryid' => $countryid 
		) );
		
		$row = $stmt->fetch ( PDP::FETCH_ASSOC );
		
		// output data of each row
		while ( $row ) {
			
			$data ['value'] = $row ['id'];
			$data ['label'] = $row ['statename'];
			$json [] = $data;
		}
		echo json_encode ( $json );
	}
	public function cityList($stateid) {
		$stmt = $this->db->prepare ( "SELECT * FROM city WHERE stateid = :stateid" );
		$stmt->execute ( array (
				':stateid' => $stateid 
		) );
		
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		
		while ( $row ) {
			$data ['value'] = $row ['id'];
			$data ['label'] = $row ['city'];
			$json [] = $data;
		}
		
		echo json_encode ( $json );
	}
}

?>