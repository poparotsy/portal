<?php
$title = 'Create Landing Page';
include 'includes/config.php';
require 'includes/landingPageHeader.php';
require 'classes/fileHandler.php';
require 'includes/navBar.php';


// if not logged in redirect to login page
if (! $user->is_logged_in ()) {
	header ( 'Location: index.php' );
}


date_default_timezone_set ( 'America/Toronto' );

$content = new UserFiles ();

// $publicContentPath = $_SERVER['DOCUMENT_ROOT'] . '/portal/client/html/';

// function saveContent($filename, $data, $flags = 0){
// $filename = trim($filename);
// if(!is_dir(dirname($filename)))
// mkdir(dirname($filename). '/', 0777, TRUE);
// return file_put_contents($filename, $data,$flags);
// }

/*
 * usage
 * saveContent($filename, $content)
 * saveContent($dir/$filename, $content)
 */
$data = $_POST ['content'];

$dirName = $content->createDir ();
$filename = $content->setFileName ();

if (isset ( $_POST ['save_content'] )) {
	
	$newFileLocation = "$dirName/$filename";
	
	// $content->saveFile($newFileLocation, $data, $fileType = 'html');
	// $content->saveFile($newFileLocation, $data, $fileType = 'pdf');
	/*
	 * TO DO, save as PDF.
	 */
	$content->saveFile ( $newFileLocation, $data );
}

?>

<!-- to be changed to CKEditor -->

<div class="container-fluid center-div">
	<div class="row">
		<section>
			<div class="col-lg-9 col-lg-offset-2">

				<div class="page-header">
					<h2>Create Landing Page.</h2>
				</div>

				<form id="createPage" role="form" method="post" action="" class="form-horizontal" autocomplete="off">

					<textarea name="content" id="content" rows="10" cols="80">Create a landing page, ad, or any message you want to share!.. </textarea>
			<script>
                // Replace the <textarea id="content"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( 'content' );
            </script>

					<div class="form-group">
						<div class="col-lg-9 col-lg-offset-3"></div>
					</div>


					<div class="form-group">
						<div class="col-lg-9 col-lg-offset-3">

							<button type="submit" name="save_content" class="btn btn-primary"
								tabindex="15">Save</button>

						</div>
					</div>

				</form>

				<div class="form-group">
					<div class="col-lg-9 col-lg-offset-3">
						<?php echo $newFileLocation; ?>
					</div>
				</div>


			</div>
		</section>
	</div>
</div>


<?php

// echo "<pre>", var_dump($_SERVER), "</pre>";

?>















<?php
// // include header template
require 'includes/footer.php';
?>