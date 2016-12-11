<?php

Class Contacts extends User{
	
	public $dbColumns = array('userid', 'contactid', 'firstName', 'lastName', 'email', 'phone', 'groupName', 'groupid', 'catagory', 'catagoryid');
	
	public function __construct() {
		
		
		if (is_array ( $dbColumns )) { 
			
			$this->dbColumns['userid'];
			$this->dbColumns['contactid'];
			$this->dbColumns['firstName'];
			$this->dbColumns['lastName'];
			$this->dbColumns['email'];
			$this->dbColumns['phone'];
			$this->dbColumns['groupName'];
			$this->dbColumns['groupid'];
			$this->dbColumns['catagory'];
			$this->dbColumns['catagoryid'];
			
		}
		
	}
	
	
	public function getContacts() {
		
	}
	
	public function addContacts() {
		
	}
	
	public function updateContacts() {
		
	}
	
	
	
	
	
}

?>