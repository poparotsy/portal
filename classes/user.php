<?php

// General queries.

/*
 * $user = new User();
 * user->validatePassword();
 * user->passwordFieldMatch();
 * user->validEmail($email); TRUE/FALSE
 * user->emptyInput($something); TRUE/FALSE
 * user->usernameExists(); TRUE/FALSE
 * user->inputLength($input, $lengh)
 * user->loggedIn(); TRUE/FALSE
 * user->login();
 * user->logout();
 * user->activationCode();
 * user->createResetToken();
 * user->checkResetToken();
 * user->isActive(); TRUE/FALSE
 * user->dbInsert($query);
 * user->dbSelect($query);
 * user->dbUpdate($query);
 *
 */
class User {
	
	// sets User attributes [matches the userDatabase fileds]
	private $db;
	public $error = array ();
	public $id;
	public $username;
	public $password;
	public $passwordConfirm;
	public $firstName;
	public $lastName;
	public $email;
	public $phoneCell;
	public $phoneOther;
	public $resetToken;
	public $resetComplete;
	public $active;
	public $postalCode;
	public $address;
	public $street;
	public $city;
	public $state;
	public $country;
	public function __construct(PDO $db = null, $userData = null) {
		$this->db = $db;
		if ($this->db === null) {
			$this->db = new PDO ( "mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS );
			$this->db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}
		
		if (is_array ( $userData )) {
			
			if (isset ( $userData ['id'] ))
				$this->id = $userData ['id'];
			
			$this->username = $userData ['username'];
			$this->password = $userData ['password'];
			$this->passwordConfirm = $userData ['passwordConfirm'];
			$this->firstName = $userData ['firstName'];
			$this->lastName = $userData ['lastName'];
			$this->email = $userData ['email'];
			$this->phoneCell = $userData ['phoneCell'];
			$this->phoneOther = $userData ['phoneOther'];
			$this->resetToken = $userData ['resetToken'];
			$this->resetComplete = $userData ['recetComplete'];
			$this->active = $userData ['active'];
			$this->postalCode = $userData ['postalCode'];
			$this->address = $userData ['address'];
			$this->country = $userData ['country'];
			$this->state = $userData ['state'];
			$this->city = $userData ['city'];
			$this->street = $userData ['street'];
		}
	}
	
	/*
	 * Start of Input Validation Methods
	 */
	public function emptyInput($input, $fieldName = null) {
		if (empty ( $input )) {
			return $error = $fieldName . " field is required and cannot be empty.";
		}
	}
	public function inputLessThan($input, $length) {
		if (strlen ( $input ) < $length) {
			return $error = "is less than" . $length . "characters.";
		}
	}
	public function telefoneInput($phone) {
		if (empty ( $phone )) {
			return $error = "Telephone number is required";
		} elseif (strlen ( $phone ) < 9) {
			return $error = "Telephone number is too short";
		} 

		elseif (! preg_match ( "#[0-9]+#", $phone )) {
			return $error = "Telephone, Only numbers, no dashes";
		}
	}
	public function validatePassword($password, $passwordConfirm = null) {
		if (empty ( $password )) {
			return $error = "Password Cannot be empty";
			// break;
		}
		
		if (strlen ( $password ) < 8) {
			return $error = "Password too short!";
			// break;
		}
		
		if (! preg_match ( "#[0-9]+#", $password )) {
			return $error = "Password must include at least one number!";
		}
		
		if (! preg_match ( "#[a-zA-Z]+#", $password )) {
			return $error = "Password must include at least one letter!";
		}
		
		if (! preg_match ( "#[A-Z]+#", $password )) {
			return $error = "Password must contain at least one capital letter.";
		}
		
		if (isset ( $passwordConfirm )) {
			if ($password != $passwordConfirm) {
				
				return $error = "Password confirmation don't match";
			}
		}
	}
	public function validEmail($email) {
		if (empty ( $email )) {
			return $error = "Email is required.";
			// break;
		}
		
		if (! filter_var ( $email, FILTER_VALIDATE_EMAIL )) {
			return $error = 'Please enter a valid email address';
		}
	}
	public function getUserEmail($email) {
		if (empty ( $email )) {
			return $error = "Please enter a valid email address";
			exit ();
		} 

		elseif (! filter_var ( $email, FILTER_VALIDATE_EMAIL )) {
			return $error = 'Please enter a valid email address';
			exit ();
		} else {
			
			$stmt = $this->db->prepare ( 'SELECT email FROM userinfo WHERE email = :email' );
			$stmt->execute ( array (
					':email' => $email 
			) );
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			if (empty ( $row ['email'] )) {
				return $error = 'Email provided is not recognized.';
			}
		}
	}
	public function emailExists($email) {
		$stmt = $this->db->prepare ( 'SELECT email FROM userinfo WHERE email = :email' );
		$stmt->execute ( array (
				':email' => $email 
		) );
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		
		if (! empty ( $row ['email'] )) {
			
			return $error = 'Email provided is already in use.';
		}
	}
	public function setResetToken($restToken, $email) {
		$stmt = $this->db->prepare ( "UPDATE userinfo SET resetToken = :token, resetComplete='No' WHERE email = :email" );
		$stmt->execute ( array (
				':email' => $email,
				':token' => $restToken 
		) );
	}
	public function getRestToken() {
		$restToken = md5 ( uniqid ( rand (), true ) );
		return $restToken;
	}
	public function checkResetRequest($key, $email = null) {
		$stmt = $this->db->prepare ( 'SELECT resetToken, resetComplete, email FROM userinfo WHERE resetToken = :token' );
		$stmt->execute ( array (
				':token' => $key 
		) );
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		
		// if no token from db then kill the page
		if (empty ( $row ['resetToken'] )) {
			return $error = 'Invalid token provided, please use the link provided in the reset email.';
		} elseif ($row ['resetComplete'] == 'Yes') {
			return $error = 'Your password has already been changed!';
		}
		
		if (! is_null ( $email )) {
			
			if ($email !== $row ['email']) {
				return $error = "Email is not recognized, please use the link provided in the reset email.";
			}
		}
	}
	public function resetPassword($hashedPassword, $key) {
		$stmt = $this->db->prepare ( "UPDATE userinfo SET password = :hashedpassword, resetComplete = 'Yes', resetToken = 'CHANGED' WHERE resetToken = :token" );
		$stmt->execute ( array (
				':hashedpassword' => $hashedPassword,
				':token' => $key 
		) );
	}
	public function activateUserAccount($userID, $active) {
		if (is_numeric ( $userID ) && ! empty ( $active )) {
			
			$stmt = $this->db->prepare ( 'SELECT active FROM userinfo WHERE id = :id' );
			$stmt->execute ( array (
					':id' => $userID 
			) );
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			// if no token from db then kill the page
			if (empty ( $row ['active'] )) {
				return $error = 'Invalid activation code, please use the link provided in the registration confirmation email.';
			} elseif ($row ['active'] == 'Yes') {
				return $error = 'Your account has been already activated!';
			} elseif ($active != $row ['active']) {
				return $error = "Wong activation code, please use the link proviede in the registration confirmation email.";
			} 

			else {
				$stmt = $this->db->prepare ( "UPDATE userinfo SET active = 'Yes' WHERE id = :id AND active = :active" );
				$stmt->execute ( array (
						':id' => $userID,
						':active' => $active 
				) );
				
				if ($stmt->rowCount () == 1) {
					
					// redirect to login page
					header ( 'Location: index.php?action=active' );
					exit ();
					
					// echo $stmt->rowCount();
				} else {
					echo "Your account could not be activated.";
				}
			}
		}
	}
	public function setActivationCode() {
	}
	public function getActivationCode() {
		$code = md5 ( uniqid ( rand (), true ) );
		return $code;
	}
	
	/*
	 * End of Input Validation Methods
	 */
	
	/*
	 * Send email function
	 */
	public function sendEmail($mailProperties) {
		$mail = new Mail ();
		$mail->setFrom ( SITEEMAIL );
		$mail->addAddress ( $mailProperties ['email'] );
		$mail->subject ( $mailProperties ['subject'] );
		$mail->body ( $mailProperties ['body'] );
		$mail->send ();
	}
	
	/*
	 * Get Country, State, City by id from DB
	 */
	public function getCountryNameById($countryid) {
		$stmt = $this->db->prepare ( 'SELECT country FROM country WHERE countryid = :countryid' );
		$stmt->execute ( array (
				':countryid' => $countryid 
		) );
		$retsult = $stmt->fetch ( PDO::FETCH_ASSOC );
		
		return $retsult ['country'];
	}
	public function getStateNameById($stateid) {
		$stmt = $this->db->prepare ( 'SELECT statename FROM state WHERE id = :stateid' );
		$stmt->execute ( array (
				':stateid' => $stateid 
		) );
		$retsult = $stmt->fetch ( PDO::FETCH_ASSOC );
		
		return $retsult ['statename'];
	}
	public function getCityNameById($cityid) {
		$stmt = $this->db->prepare ( 'SELECT city FROM city WHERE id = :cityid' );
		$stmt->execute ( array (
				':cityid' => $cityid 
		) );
		$retsult = $stmt->fetch ( PDO::FETCH_ASSOC );
		
		return $retsult ['city'];
	}
	
	/*
	 * General Queries
	 *
	 */
	public function find($id) {
		$stmt = $this->db->prepare ( 'SELECT username FROM userinfo WHERE id = :id' );
		$stmt->execute ( array (
				'id' => $id 
		) );
		
		$stmt->setFetchMode ( PDO::FETCH_CLASS, 'User' );
		return $stmt->fetch ();
	}
	public function registerUser($userData) {
		$stmt = $this->db->prepare ( 'INSERT INTO userinfo (username,firstName,lastName,email,street,country,state,city,postalCode,password,phoneCell,phoneOther,active)
		VALUES (:username, :firstName, :lastName, :email, :street, :country, :state, :city, :postalCode, :password, :phoneCell, :phoneOther, :active)' );
		$stmt->execute ( array (
				':username' => $userData ['username'],
				':firstName' => $userData ['firstName'],
				':lastName' => $userData ['lastName'],
				':email' => $userData ['email'],
				':street' => $userData ['street'],
				':country' => $userData ['country'],
				':state' => $userData ['state'],
				':city' => $userData ['city'],
				':postalCode' => $userData ['postalCode'],
				':password' => $userData ['password'],
				':phoneCell' => $userData ['phoneCell'],
				':phoneOther' => $userData ['phoneOther'],
				':active' => $userData ['active'] 
		) );
		
		// return $this->db->lastInsertId();
	}
	
	// public function lastInsertId() {
	// return $this->db->lastInsertId();
	// }
	public function getIdByEmail($email) {
		try {
			$stmt = $this->db->prepare ( 'SELECT id FROM userinfo WHERE email = :email' );
			$stmt->execute ( array (
					'email' => $email 
			) );
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		} catch ( PDOException $e ) {
			$error [] = $e->getMessage ();
		}
		$id = $row ['id'];
		
		return $id;
	}
	public function userExist($username) {
		$this->stmt->prepare ( 'SELECT username FROM userinfo WHERE username = :username' );
		$stmt->execute ( array (
				'username' => $username 
		) );
		$row = $this->stmt->fetch ( PDO::FETCH_ASSOC );
		
		if (! empty ( $row ['username'] )) {
			$error = 'Username provided is already in use.';
		}
	}
	
	/*
	 * Login methods
	 */
	private function get_user_hash($username) {
		try {
			$stmt = $this->db->prepare ( 'SELECT password, username, id FROM userinfo WHERE username = :username AND active="Yes" ' );
			$stmt->execute ( array (
					'username' => $username 
			) );
			return $stmt->fetch ();
		} catch ( \PDOException $e ) {
			echo '<p class="bg-danger">' . $e->getMessage () . '</p>';
		}
	}
	public function login($username, $password) {
		$row = $this->get_user_hash ( $username );
		
		if (password_verify ( $password, $row ['password'] ) == 1) {
			
			$_SESSION ['loggedin'] = true;
			$_SESSION ['username'] = $row ['username'];
			$_SESSION ['id'] = $row ['id'];
			
			return true;
		}
	}
	public function is_logged_in() {
		if (isset ( $_SESSION ['loggedin'] ) && $_SESSION ['loggedin'] == true) {
			return true;
		}
	}
	
	// Password methoes/queries
	public function hashPassword($password) {
		$hashedPassword = password_hash ( $password, PASSWORD_DEFAULT );
		
		return $hashedPassword;
	}
	public function logout() {
		session_destroy ();
	}
}

?>
