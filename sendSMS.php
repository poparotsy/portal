<?php
require ('includes/config.php');
include 'includes/header.php';
require 'includes/navBar.php';

// if not logged in redirect to login page
if (! $user->is_logged_in ()) {
	header ( 'Location: index.php' );
}
?>


<div class="container">

	<div class="row">

		<div
			class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">

			<h3>Send SMS, <?php echo $_SESSION['username']; ?></h3>
			<hr>

		</div>
	</div>


</div>






<?php
include 'includes/footer.php';
?>