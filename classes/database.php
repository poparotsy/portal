<?php
include 'includes/config.php';
class Database {
	private $db;
	public function __construct() {
		$this->connect ();
	}
	private function connect() {
		try {
			$this->db = new PDO ( "mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS );
			$this->db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( PDOException $e ) {
			$this->db = null;
		}
	}
	public function getInstance() {
		return $this->db;
	}
}

?>