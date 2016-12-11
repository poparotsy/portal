<?php
require ('includes/config.php');
// include 'classes/user.php';

// use UserRepository;

// $user = new User($db);

// $error = array();
$validate = array ();
$userData = array ();

// Mapping $_POST variable

$submitPressed = $_POST ['submit'];
$user->username = $_POST ['username'];
$user->firstName = $_POST ['firstName'];
$user->lastName = $_POST ['lastName'];
$user->email = $_POST ['email'];
$user->password = $_POST ['password'];
$user->passwordConfirm = $_POST ['passwordConfirm'];
$user->active = $_POST ['active'];
$user->country = $_POST ['country'];
$user->state = $_POST ['state'];
$user->city = $_POST ['city'];
$user->street = $_POST ['street'];
$user->postalCode = $_POST ['postalCode'];
$user->resetToken = $_POST ['resetToken'];
$user->resetComplete = $_POST ['resetComplete'];
$user->phoneCell = $_POST ['phoneCell'];
$user->phoneOther = $_POST ['phoneOther'];

if ($user->is_logged_in ()) {
	header ( 'Location: memberArea.php' );
}

/*
 * If the form has been submitted.
 */

if (isset ( $_POST ['register'] )) {
	
	$validate [] = $user->emptyInput ( $user->firstName, "First name" );
	$validate [] = $user->emptyInput ( $user->lastName, "Last name" );
	$validate [] = $user->emptyInput ( $user->username, "Username" );
	$validate [] = $user->validEmail ( $user->email );
	$validate [] = $user->emailExists ( $user->email );
	$validate [] = $user->telefoneInput ( $user->phoneCell );
	// $validate [] = $user->telefoneInput ( $user->phoneOther );
	$validate [] = $user->validatePassword ( $user->password, $_POST ['passwordConfirm'] );
	
	if (empty ( array_filter ( $validate ) )) {
		
		$hashedPassword = $user->hashPassword ( $user->password );
		$activationCode = $user->getActivationCode ();
		$countryName = $user->getCountryNameById ( $user->country );
		$stateName = $user->getStateNameById ( $user->state );
		$cityName = $user->getCityNameById ( $user->city );
		
		if (is_array ( $userData )) {
			
			$userData ['username'] = $user->username;
			$userData ['firstName'] = $user->firstName;
			$userData ['lastName'] = $user->lastName;
			$userData ['email'] = $user->email;
			$userData ['street'] = $user->street;
			$userData ['country'] = $countryName;
			$userData ['postalCode'] = $user->postalCode;
			$userData ['phoneCell'] = $user->phoneCell;
			$userData ['phoneOther'] = $user->phoneOther;
			$userData ['password'] = $hashedPassword;
			$userData ['active'] = $activationCode;
			$userData ['state'] = $stateName;
			$userData ['city'] = $cityName;
		}
		
		$user->registerUser ( $userData );
		
		$id = $user->getIdByEmail ( $user->email );
		
		/*
		 * send activation email
		 */
		
		// $mailProperties = array(); // defined in config.php
		$mailProperties ['email'] = $user->email;
		$mailProperties ['subject'] = "Registration Confirmation";
		$mailProperties ['body'] = "<p>Thank you for registering at " . WEBSITE . " website.</p>
			<p>To activate your account, please click on this link: <a href='" . DIR . "activate.php?id=$id&active=$activationCode'>" . DIR . "activate.php?id=$id&key=$activationCode</a></p>
			<p>Regards Site Admin</p>";
		
		$user->sendEmail ( $mailProperties );
		// redirect to index page
		header ( 'Location: index.php?action=joined' );
		exit ();
	}
}

// include header template
// define page title
$title = 'SMS Portal';
require 'includes/header.php';

?>
<div class="container-fluid center-div">
	<div class="row">
		<section>
			<div class="col-lg-9 col-lg-offset-2">

				<div class="page-header">
					<h2>User Registeration</h2>
				</div>
				<!-- START FORM -->
				<form id="registrationForm" role="form" method="post" action=""
					class="form-horizontal" autocomplete="off" data-toggle="validator">
					<!---------------->
					<p>
						Already a member? <a href='index.php'>Login</a>
					</p>
					<hr>

					<!------Full name---------->
					<div class="form-group">
						<label class="col-lg-3 control-label">Full name</label>
						<div class="col-md-3">
							<input type="text" name="firstName" id="firstName"
								class="form-control" placeholder="First Name"
								value="<?php if(isset($error)){ echo $user->firstName; } ?>"
								tabindex="1">
						</div>

						<div class="col-md-3">
							<input type="text" name="lastName" id="lastName"
								class="form-control" placeholder="Last Name"
								value="<?php if(isset($error)){ echo $user->lastName; } ?>"
								tabindex="2">
						</div>
					</div>
					<!---------------->

					<!-------Username--------->
					<div class="form-group">

						<label class="col-lg-3 control-label">Username</label>
						<div class="col-md-6">

							<input type="text" name="username" id="username"
								class="form-control" placeholder="User Name"
								value="<?php if(isset($error)){ echo $user->username; } ?>"
								tabindex="3">

						</div>
					</div>
					<!---------------->
					<!-------Email--------->
					<div class="form-group has-feedback">
						<label class="col-lg-3 control-label">Email address</label>
						<div class="col-md-6">
							<input type="email" name="email" id="email" class="form-control"
								placeholder="Email Address"
								value="<?php if(isset($error)){ echo $user->email; } ?>"
								tabindex="4">


						</div>
					</div>
					<!--------------->
					<!------Adress---------->
					<!-- Country/State/City Dropdown menue -->


					<div class="form-group">
						<label class="col-lg-3 control-label">Address</label>
						<div class="col-sm-2">
							<select class="form-control countries" name="country"
								id="country" tabindex="5">
								<option selected="selected" value="">Select...</option>
							</select>
						</div>


						<div class="col-sm-2">

							<select class="form-control dropdown-toggle states" name="state"
								id="stateId" tabindex="6">
								<option value="">Select...</option>
							</select>
						</div>


						<div class="col-sm-2">
							<select class="form-control cities" name="city" id="cityId"
								tabindex="7">
								<option value="">Select...</option>
							</select>
						</div>

					</div>

					<!---------------->
					<!------Country/PostalCode---------->
					<div class="form-group">
						<label class="col-lg-3 control-label"></label>
						<div class="col-md-3">
							<input type="text" name="street" id="street" class="form-control"
								placeholder="Street (Optional)"
								value="<?php if(isset($error)){echo $user->street;}?>"
								tabindex="8">
						</div>


						<div class="col-md-3">
							<input type="text" name="postalCode" id="postalCode"
								class="form-control" placeholder="Postal/Zip Code (Optional)"
								value="<?php if(isset($error)){echo $user->postalCode;}?>"
								tabindex="9">
						</div>

					</div>
					<!---------------->



					<!---------------->
					<!-----Phone----------->
					<div class="form-group">
						<label class="col-lg-3 control-label">Telephone</label>
						<div class="col-md-3">
							<input type="tel" name="phoneCell" id="phoneCell"
								class="form-control" placeholder="Cell Phone Number"
								value="<?php if(isset($error)){echo $user->phoneCell;}?>"
								tabindex="10">

						</div>

						<div class="col-md-3">
							<input type="tel" name="phoneOther" id="phoneOther"
								class="form-control" placeholder="Other Phone Number (Optional)"
								value="<?php if(isset($error)){echo $user->phoneOther;}?>"
								tabindex="11">

						</div>
					</div>
					<!---------------->
					<!-------------->
					<div class="form-group">
						<label class="col-lg-3 control-label">Password</label>
						<div class="col-md-3">

							<input type="password" name="password" id="password"
								class="form-control" placeholder="Password" tabindex="12">

						</div>

						<div class="col-md-3">
							<input type="password" name="passwordConfirm"
								id="passwordConfirm" class="form-control"
								placeholder="Confirm Password" tabindex="13">

						</div>
					</div>
					<!-- Captcha -->
					<div class="form-group">
						<label class="col-lg-3 control-label" id="captchaOperation"></label>
						<div class="col-md-3">
							<input type="text" class="form-control" name="captcha"
								tabindex="14" />
						</div>
					</div>

					<!-- 		 -->
					<!-- Sign Up -->
					<div class="form-group">
						<div class="col-lg-9 col-lg-offset-3">

							<button type="submit" name="register" class="btn btn-primary"
								tabindex="15">Register</button>

						</div>
					</div>



					<!-- ---- -->
				</form>
				<!-- END FORM -->

			</div>
		</section>
		<hr>
		<!----Loop through the errors if any------------>
		<section>
			<div class="col-lg-9 col-lg-offset-3">
				<div class="form-group">
	
	<?php
	// check for any errors
	if (isset ( $validate )) {
		foreach ( $validate as $error ) {
			echo '<p class="bg-danger">' . $error . '</p>';
		}
	}
	
	// if action is joined show sucess
	if (isset ( $_GET ['action'] ) && $_GET ['action'] == 'joined') {
		echo "<p><h3 class='bg-success'>Registration successful, please check your email to activate your account.</h3></p>";
	}
	?>
				</div>
			</div>
		</section>
		<!----------------------------------------------->
	</div>
</div>




<!--  -->
<script type="text/javascript" src="assets/js/intlTelInput.js"></script>
<script type="text/javascript" src="assets/js/registrationForm.js"></script>
<!--  -->
<?php
// // include header template
require 'includes/footer.php';
?>