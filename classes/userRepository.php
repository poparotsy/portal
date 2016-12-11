<?php

// namespace Repository;
include ('includes/config.php');
include 'classes/user.php';

use \PDO;
class UserRepository {
	public function __construct() {
	}
	public function find($id) {
		$stmt = $this->db->prepare ( 'SELECT username FROM users WHERE id = :id' );
		$stmt->execute ( array (
				'id' => $id 
		) );
		
		$stmt->setFetchMode ( PDO::FETCH_CLASS, 'User' );
		return $stmt->fetch ();
	}
	public function userExist($username) {
		$this->stmt->prepare ( 'SELECT username FROM userinfo WHERE username = :username' );
		$row = $this->stmt->fetch ( PDO::FETCH_ASSOC );
		
		if (! empty ( $row ['username'] )) {
			$error [] = 'Username provided is already in use.';
		}
	}
	public function query($query) {
		$this->stmt = $this->db->prepare ( $query );
	}
	public function bind($param, $value, $type = NULL) {
		if (is_null ( $type )) {
			switch (true) {
				case is_int ( $value ) :
					$type = PDO::PARAM_INT;
					break;
				case is_bool ( $value ) :
					$type = PDO::PARAM_BOOL;
					break;
				case is_null ( $value ) :
					$type = PDO::PARAM_NULL;
					break;
				default :
					$type = PDO::PARAM_STR;
			}
		}
		
		$this->stmt->bindValue ( $param, $value, $type );
	}
	public function execute() {
		return $this->stmt->execute ();
	}
	public function resultSet() {
		$this->execute ();
		return $this->stmt->fetchAll ( PDO::FETCH_ASSOC );
	}
	public function single() {
		$this->execute ();
		return $this->stmt->fetch ( PDO::FETCH_ASSOC );
	}
	public function getRowCount() {
		return $this->stmt->rowCount ();
	}
	public function lastInsertId() {
		return $this->db->lastInsertId ();
	}
	public function beginTransaction() {
		return $this->db->beginTransaction ();
	}
	public function endTransaction() {
		return $this->db->commit ();
	}
	public function cancelTransaction() {
		return $this->db->rollBack ();
	}
	public function DebugDumpParam() {
		return $this->stmt->debugDumpParams ();
	}
}
?>