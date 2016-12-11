<?php
require ('includes/config.php');

// if not logged in redirect to login page
if (! $user->is_logged_in ()) {
	header ( 'Location: index.php' );
}

// define page title
$title = 'Contacts';

// include header template
require 'includes/contacstHeader.php';
require 'includes/navBar.php';
?>



<div class="container">

	<div class="row">

		<div
			class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		
			<?php $uid = $_SESSION['id']; ?>		
			<h3>Contacts, <?php echo $_SESSION['username']; ?></h3>
			<h3>user id, <?php echo $uid; ?></h3>


			<hr>

		</div>

		<!--  -->
		<!-- 					<div class="form-group"> -->
		<!-- 						<div class="col-lg-9 col-lg-offset-3"> -->
		<!--button type="submit" name="addRow" id="addRow" class="btn btn-primary" onclick="numOfRows()">add row</button-->
		<!-- 							<button type="submit" name="addRow" id="addRow" class="btn btn-primary">add row</button> -->
		<!-- 						</div> -->
		<!-- 					</div> -->
		<!--  -->
		
		<div>
		
		<form action="importContact" action="" method="post">
		
		
		
		</form>
		</div>

		<form id="contactsForm" action="" method="POST">
		<input type="hidden" id="uid" name="uid" value="<?php echo $_SESSION['id'];?>" />
			<!--  -->
			<!-- to do, create a hiddin column to hold the id on the server side and remove it from the JavaScript, and let the JS get the IDs to be deleted -->
			<!--  -->

			<table id="contactsTable" class="table table-hover display select">
				<thead>
					<tr>
						<th><input type="checkbox"></th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Phone Number</th>
						<th>Email</th>
						<th>Group Name</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<th></th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Phone Number</th>
						<th>Email</th>
						<th>Group Name</th>
					</tr>
				</tfoot>
			</table>
			<p class="form-group">
				<button type="submit" class="btn btn-primary">Delete</button>
			</p>



			<b>Data submitted to the server:</b><br>
			<pre id="console">
</pre>

		</form>
		<!--  -->

	</div>
</div>






<script type="text/javascript" src="assets/js/contacts.js"></script>

<?php
// echo '<pre>'.var_dump($_SESSION).'</pre>';
require ('includes/footer.php');
?>