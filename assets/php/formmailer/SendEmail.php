<?php

/**
 * SendEmail.php
 *
 * Zeta Producer Form-Mailer
 * 
 * $Id: SendEmail.php 60526 2018-05-02 09:26:00Z sseiz $
 */

require_once('debug.inc.php');
require_once('mailer/PHPMailerAutoload.php');
require_once('functions.php');

if( DebugConfiguration::$errorDisplay ) {
	error_reporting(E_ALL);
	ini_set('display_startup_errors', 'On');
	ini_set('display_errors', 'On');
	
	ini_set("log_errors", 1);
	ini_set('error_log', 'phperrors.log');
}
else {
	ini_set('display_errors', 'Off');
}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");				// Past date
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");	// Modified
header("Cache-Control: no-store, no-cache, must-revalidate");	// HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");										// HTTP/1.0

session_start();

$scriptName = basename($_SERVER["SCRIPT_NAME"]);


// Encryption
$cryptKey = array( 0x0E,
					0x41,
					0x6A,
					0x29,
					0x94,
					0x12,
					0xEB,
					0x63 );

$uploadBaseDir = dirname( __FILE__ );
// Max age of uploaded files - In days.
$maxAgeUploads = 30;
$uploadedFiles = false;
$request = array_merge($_GET, $_POST);

$error = null;
$successPage = null;
$errorPage = null;
$enteredWithCaptcha = null;
$receiverEmailAddress = null;
$antispamField = null;
$formData = array();

// read config file specific to the instance of the sent form
if ( isset($request["f_id"]) ){
	require_once(intval($request["f_id"]) . '_config.inc.php');
}
if( isset( $request["PHPSESSID"] ))
{
	session_id($request["PHPSESSID"] );
}

// Stage 1
if ( isset($request["f_receiver"]) )
{
	CatchUploads( $request );
	$formData = $request;
	$encodedFormData = base64_encode( serialize( $formData ));
	
	//ValidateLoadMainFormData( $formData );
	$error = ValidateLoadMainFormData( $formData );
	
	if ( $error == null ) {	
		DoProcessFormAndRedirect( 	$formData,
										$successPage,
										$errorPage );
	}
	else {
		echo $error;
	}
	exit;
}

echo "Illegal script call.";
exit;
// ========================================================

?>