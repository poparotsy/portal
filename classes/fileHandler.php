<?php
class UserFiles {
	protected $y;
	protected $m;
	protected $d;
	protected $websiteDir;
	protected $dRoot;
	protected $path;
	

	public function __construct() {
	}
	public function createDir($fileType = null) {
		$y = date ( "y" );
		$m = date ( "m" );
		$d = date ( "d" );
		$websiteDir = 'portal/client/html';
		$dRoot = $_SERVER ['DOCUMENT_ROOT'];
		$path = "$dRoot/$websiteDir/$y/$m/$d";
		
		if (! is_dir ( $path )) {
			mkdir ( $path, 0777, true ) or die ( "Could not create directory" );
		}
		
		return $path;
	}
	
	/*
	 * create a filename hash username & id [maybe something else] as a file prefix
	 * change filename
	 * save HTML PDF, args $type = null (default to html) html or pdf
	 */
	public function setFileName() {
		$filename = md5 ( rand () );
		return $filename;
	}
	public function saveFile($filename, $data, $fileType = null, $flags = 0) {
		$filename = trim ( $filename );
		
		switch ($fileType) {
			case 'html' :
				$filename = $filename . '.html';
				break;
			case 'pdf' :
				$filename = $filename . '.pdf';
				break;
			
			default :
				$filename = $filename . '.html';
				break;
		}
		
		return file_put_contents ( "$filename", $data, $flags );
	}
	public function getFileName() {
	}
	public function deleteFile() {
	}
	public function listUserFiles() {
	}
	public function getUserFileSize() {
		/*
		 * takes user id & filename/file list
		 */
	}
}

?>