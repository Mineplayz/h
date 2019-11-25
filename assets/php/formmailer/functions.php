<?php

function DoProcessFormAndRedirect( 	$formData,
        							$successPage,
        							$errorPage )
{
	global $antispamField;

	if ( isset($formData["url"] )) {
		$antispamField = trim($formData["url"]);
		if ( !empty($antispamField) ) {
			// we got spammed, but won't tell and instead redirect to success page without sending any mail
			header("Location: $successPage");
			exit;
		}
	}

	// https://github.com/PHPMailer/PHPMailer/blob/master/examples/exceptions.phps
	try {
		DoSendEmail( $formData );
		header("Location: $successPage");

		// 2015-04-12, Uwe Keim: Im Fehlerfall _nicht_ mehr auf die Fehlerseite weiterleiten,
		// da ansonsten die detaillierte Fehlermeldung nicht mehr zu sehen ist.

		// 2016-12-16 11:53 StS: Doch wieder umleiten, wenn SMTP nicht genutzt wird, weil manche Kunden trotz
		// Fehler (returncode von mail()) erfolgreich mails bekommen und man somit einfach die erfolgs url als fehler url übergeben kann…
		// Fehlerdetails sind ja im log greifbar und werden nun auch als Query-String an die URL gehängt (für uns)

	} catch (phpmailerException $e) {
		error_log( $e->errorMessage() ); // http://stackoverflow.com/a/3531852/107625
		echo $e->errorMessage();
		if ( isset(Configuration::$conf_smtp_useSmtp) && Configuration::$conf_smtp_useSmtp == "0" ){
			header("Location: $errorPage?e=". urlencode($e->errorMessage()));
		}
		die;
	} catch (Exception $e) {
		error_log( $e->getMessage() ); // http://stackoverflow.com/a/3531852/107625
		echo $e->errorMessage();
		if ( isset(Configuration::$conf_smtp_useSmtp) && Configuration::$conf_smtp_useSmtp == "0" ){
			header("Location: $errorPage?e=". urlencode($e->errorMessage()));
		}
		die;
	}
}

function CatchUploads( &$formData )
{
	global $uploadBaseDir, $maxAgeUploads, $scriptName, $uploadedFiles;

	$uploadDir = $uploadBaseDir . "/upload_" . uniqid() . "/";
	$uploadDirRel = StripLeft( $uploadDir, $uploadBaseDir );
	$scriptCmd = $scriptName;
	$whitelistedExtensions = array('pdf','png','jpg','jpeg','tif','bmp','tiff','svg','doc','docx','xls','xlsx','zip','gz','bz','7z','bzip2','tar','txt');
	CleanupOldUploads( $uploadBaseDir, $maxAgeUploads );

	if ( count( $_FILES ) >= 1 )
	{
		if ( isset ( $formData["sc"] ))
		{
			$scriptCmd .="?sc";
		}

		foreach ( $_FILES as $key  => $file )
		{
			if( $file["error"]  == 0)
			{
				$uploadedFiles = true;
				if ( !is_dir( $uploadDir ))
				{
					mkdir( $uploadDir );
				}
				//remove any non ascii chars from filename
				$sanitizedFileName = preg_replace('/[^a-zA-Z0-9-_ .]/','', $file['name']);
				$ext = strtolower(pathinfo($sanitizedFileName, PATHINFO_EXTENSION));
				//make sure non one can upload executable .php files or such
				if ( !in_array($ext,$whitelistedExtensions) ){
					// $sanitizedFileName = preg_replace('"\.php$"', '.phps', $sanitizedFileName);
					$sanitizedFileName = $sanitizedFileName . ".txt";
				}
				
				move_uploaded_file($file['tmp_name'], $uploadDir . $sanitizedFileName);

				$formData[$key] = '<a href="' . StripRight( currPageURL(), $scriptCmd ) . $uploadDirRel . rawurlencode($sanitizedFileName) . '">' . $sanitizedFileName . '</a>';
			}
		}
	}
}

// Delete occurance of $remove from left of $s
function StripLeft($s, $remove)
{
	$len = strlen($remove);
	if (strcmp(substr($s, 0, $len), $remove) === 0)
	{
			$s = substr($s, $len);
	}
	return $s;
}
// Delete occurance of $remove from right of $s
function StripRight($s, $remove)
{
	$len = strlen($remove);
	if (strcmp(substr($s, -$len, $len), $remove) === 0)
	{
			$s = substr($s, 0, -$len);
	}
	return $s;
}

function CleanupOldUploads( $baseDir, $olderThanDays )
{
	$deltaSeconds = ( $olderThanDays * 60 * 60 * 24 );

	if ( is_dir( $baseDir ))
	{
		$dirContent = scanDirEx( $baseDir, "upload*" );

		foreach ( $dirContent as $fileOrDir )
		{
			$fullFilePath = $baseDir . "/" . $fileOrDir;

            if ( is_dir ( $fullFilePath ))
            {
            	$changeTime = filemtime( $fullFilePath );

            	if ( ($changeTime + $deltaSeconds) < time() )
            	{
            		//echo "DIR '" . $fullFilePath . "' would be removed.";
            		rrmdir( $fullFilePath );
            	}
            }
		}
    }
}

function ValidateLoadMainFormData( $formData )
{
	global $error;
	global $successPage;
	global $errorPage;
	global $receiverEmailAddress;
	global $cryptKey;

	if ( isset($formData["f_success"] ))
	{
		$successPage = $formData["f_success"];
	}
	else
	{
		$error .="Keine Erfolgsseite angegeben. Prüfen Sie die Formularkonfiguration.</br>";
	}
	if ( isset($formData["f_error"] ))
	{
		$errorPage = $formData["f_error"];
	}
	else
	{
		$error .="Keine Fehlerseite bei Übermittlungsfehler angegeben. Prüfen Sie die Formularkonfiguration.</br>";
	}

	if ( isset($formData["f_receiver"] ))
	{
		$receiverEmailAddress = decrypt( $formData["f_receiver"], $cryptKey );
		$arrReceiverEmail = explode( ",", $receiverEmailAddress );

		foreach ( $arrReceiverEmail as $testEmail ){
			if( !isValidEmail( trim($testEmail) ) || strpos($testEmail, "@example.com") ){
				$error .="Die E-Mail-Adresse des Formularempfängers ist ungültig. Der Betreiber der Website sollte die Formularkonfiguration überprüfen.</br></br>The recipient-email of the form is invalid.The owner of this website should verify the form-configuration.</br>";
				break;
			}
		}
	}
	else
	{
		$error .="Kein Formularempfänger angegeben. Der Betreiber der Website sollte die Formularkonfiguration überprüfen.</br>";
	}

	return $error;
}

function DisplayCaptchaChallenge()
{
	global $error, $publickey, $encodedFormData;
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	  <head>
		<title>Spam-Schutz</title>
	  </head>
	  <body>
	  	<style>
			body, input, submit
			{
				font-family: Verdana;
				font-size: 10pt;
			}
			h1
			{
				font-size: 16pt;
				font-weight: bold;
				font-family: Verdana;
			}
	 	</style>
	  	<script>
			var RecaptchaOptions =
				{
					lang : 'de',
					theme : 'clean'
				};
		</script>
		<h1>Spam-Schutz</h1>
		<p style="width:600px;">Um sicherzustellen, dass dieser Formularservice nicht missbräuchlich verwendet wird, geben Sie bitte die 2 Wörter im Feld unten ein und bestätigen Sie mit "Absenden". Ihre Nachricht wird dann umgehend an den Empfänger weitergeleitet.</p>
	    <form action="" method="post">
	    <input type="hidden" name="formData" value="<?php echo $encodedFormData ?>" />
	    <input type="hidden" name="pending" value="true" />
		<?php echo recaptcha_get_html($publickey, $error); ?>
	    <br />
	    <input type="submit" value="Absenden" />
	    </form>
	  </body>
	</html>
<?php
}

function DisplayCaptchaV2Challenge()
{
	global $error, $publickey, $encodedFormData;
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	  <head>
		<title>Spam-Schutz</title>
	  </head>
	  <body>
	  	<div class="formvalidateerror" style="max-width: 460px; margin: 40px auto; font-family: helvetica, arial; color: #fff; background-color: red; padding: 6px 12px;">
        	<h2>Spam-Schutz / Spam-Protection</h2>
				<p><strong>Um das Kontaktformular zu nutzen, aktivieren Sie bitte JavaScript!</strong></p>
				<p>In order to use this form, you need to activate JavaScript!</p>
	  	</div>
	  </body>
	</html>
<?php
}


function DoSendEmail(
			$formData )
{
	global $cryptKey, $maxAgeUploads, $uploadedFiles;

	mb_internal_encoding("UTF-8");

	$receiverEmail = decrypt( $formData["f_receiver"], $cryptKey );
	$arrReceiverEmail = explode( ",", $receiverEmail );

	$senderEmail = trim($arrReceiverEmail[0]);
	$senderName = "Formular";
	$searchSenderName = true;
	$searchSenderEmail = true;

	$subject = $formData["f_title"];
	$subject = expandFieldnamePlaceholders($subject, $formData);
	$subject = strip_tags($subject); // sanitize input

	$css = file_get_contents('css.inc');
	$emailTemplate = isset(Configuration::$conf_email_template) ? Configuration::$conf_email_template : "<p>Es wurde keine Vorlage eingegeben!<br />No template has been set!</p>";
	$body[] = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\">";
    $body[] = "<head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><title>E-Mail</title><style type=\"text/css\">" . $css . "</style></head><body>";
    //$body[] = "<h1>Ein Formular wurde Ihnen von Ihrer Website gesendet</h1>";
    //$body[] = "<p>Jemand hat Ihnen ein Formular mit den folgenden Werten gesendet.</p>";

    $formbody[] = "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";

	for ($i = 1; $i <= 1000; $i++)
	{
		if ( isset( $formData["NAME".$i] ))
		{
			$name = $formData["NAME".$i];

			if ( $name === "(Beschreibungstext)" )
			{
				continue;
			}

			if( $name == "——————————————" )
			{
					$name = "&nbsp;";
					$value = "&nbsp;";
			}
			else
			{
				if( isset( $formData["F".$i] ))
				{
					if (is_array($formData["F".$i])) {
						$value = nl2br(implode( ", ", $formData["F".$i] ));
					}
					else {
						$value = nl2br( $formData["F".$i] );
					}
				}
				else
				{
					$value = "-";
				}

				if (is_array($value))
	        	{
	        		$value = implode( ", ", $value );
	        	}
	        	if ( $searchSenderEmail &&
	        		 (stripos($name, "email") !== false ||
					 stripos($name, "e-mail") !== false ))
				{
					if ( !empty($value) && isValidEmail($value) ) {
						$senderEmail = $value;
					}
					$searchSenderEmail = false;
				}

				if ( $searchSenderName &&
					 stripos($name, "name") !== false )
				{
					if ( !empty($value) ) {
						$senderName = $value;
					}
					$searchSenderName = false;
				}
			}

			$formbody[] = "<tr><th class=\"left\">" . strip_tags($name) . ": </th><td class=\"right\">" . $value . "</td></tr>";
		}
		else
		{
			break;
		}
	}

	// --

	$deleteDate = strtotime("+$maxAgeUploads day");
	$deleteDate = date('d.m.Y', $deleteDate);
	$formbody[] = "</table>";
	// replace the text makro in the template with the transferred form fields
	$emailTemplate = expandFieldnamePlaceholders($emailTemplate, $formData);
	$body[] = str_replace("[FORM_TABLE]", implode("\r\n",$formbody), $emailTemplate);
	$body[] = "<br />";
	//$body[] = "<hr/>";
	if ( $uploadedFiles ) {
		$body[] = "<p class=\"footer\">* Dateien werden nach $maxAgeUploads Tagen, ab $deleteDate automatisch vom Server gelöscht.<br />";
	}
	//$body[] = "<p class=\"footer\">Besucher-IP-Adresse: " . $_SERVER["REMOTE_ADDR"] . "<br/>";
	//$body[] = "Besucher-Hostname: " . gethostbyaddr( $_SERVER["REMOTE_ADDR"] ) . "<br/>";
    $body[] = "</body></html>";

	// --

	// Passing true to the constructor enables the use of exceptions for error handling.
	$mail = new PHPMailer(true);

	$mail->setLanguage('de', 'language');
	$mail->CharSet = 'utf-8';

	$mail->From      = trim($arrReceiverEmail[0]);
	$mail->FromName  = $senderName;
	$mail->Subject   = $subject;
	$mail->Body      = implode("\r\n",$body);
	$mail->isHTML(true);
	// generate TEXT-Part of the mail to lower spam scores
	//$mail->AltBody   = "Alle Informationen dieser E-Mail finden Sie im HTML-Teil dieser E-Mail. \n\nAll content is contained in the HTML-part of this email.";
	$mail->AltBody	 = $mail->html2text(implode("\r\n",$body));
	// Support multiple komma-separated receipients
	foreach($arrReceiverEmail as $remail)
	{
	   $mail->AddAddress(trim($remail));
	}
	$mail->addReplyTo( $senderEmail, $senderName );

	// optionally send via SMTP
	if ( isset(Configuration::$conf_smtp_useSmtp) ){
		if ( Configuration::$conf_smtp_useSmtp == "1" ){
			if ( Configuration::$conf_smtp_debug == "1" ){
				$mail->Debugoutput ="html";
				$mail->SMTPDebug = 4;
			}

			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = Configuration::$conf_smtp_servername;   // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = Configuration::$conf_smtp_username; // SMTP username
			$mail->Password = decrypt(Configuration::$conf_smtp_password, $cryptKey); // SMTP password
			// accept self signed certs by default
			$mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
                
			switch ( Configuration::$conf_smtp_useSsl ){
				case "autotls":
					$mail->SMTPAutoTLS = true;
					break;
				case "ssl":
					$mail->SMTPSecure = 'ssl';
					break;
				case "tls":
					$mail->SMTPSecure = 'tls';
					break;
				case "none":
					$mail->SMTPSecure = '';
					break;
			}
			$mail->Port = intval(Configuration::$conf_smtp_port); // TCP port to connect to
		}
	}

	$mail->Send();

	/*
    $header = array();
	$header[] = "From: =?UTF-8?B?" . base64_encode($senderName) . "?= <" . $receiverEmail . ">";
	$header[] = "Reply-To: =?UTF-8?B?" . base64_encode($senderName) . "?= <" . $senderEmail . ">";
	$header[] = "MIME-Version: 1.0";
	$header[] = "Content-Type: text/html; charset=UTF-8";

    if( mail( $receiverEmail,
	      	   mb_mime_header($subject, "utf-8"),
	    	   implode("\r\n",$body),
	    	   implode(PHP_EOL, $header)))
	{
		return true;
	}
	else
	{
		return false;
	}
	*/
}

function decrypt($encrypted_text, $key){
	$cryptKeyString = null;
	foreach( $key as $val )
	{
		$cryptKeyString .= chr($val);
	}

	$decrypted = openssl_decrypt($encrypted_text, "des-cbc", $cryptKeyString, 0, $cryptKeyString);
 	return $decrypted;
}

function rrmdir($dir)
{
   if (is_dir($dir)) {
     $objects = scandir($dir);

     foreach ($objects as $object)
     {
       if ($object != "." && $object != "..")
       {
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
       }
     }

     reset($objects);
     rmdir($dir);
   }
}

function scanDirEx( $path='.', $mask='*' )
{
	$sdir = array();
    $entries = scandir($path);

    foreach ($entries as $i=>$entry) {
        if ($entry!='.' && $entry!='..' && fnmatch($mask, $entry) ) {
            $sdir[] = $entry;
        }
    }
    return ($sdir);
}

function isValidEmail( $email )
{
	return preg_match("/^[_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,63})$/i", $email);
}

function mb_mime_header($string, $encoding=null, $linefeed="\r\n")
{
   if(!$encoding) $encoding = mb_internal_encoding();
   $encoded = '';

  while($length = mb_strlen($string))
  {
     $encoded .= "=?$encoding?B?"
              . base64_encode(mb_substr($string,0,24,$encoding))
              . "?=$linefeed";

    $string = mb_substr($string,24,$length,$encoding);
   }

  return $encoded;
}

function currPageURL()
{
	$pageURL = ( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

 return $pageURL;
}

function expandFieldnamePlaceholders( $inputString, $formData){
	//xdebug_break();
	$resultString = $inputString;
	preg_match_all('/\[[^\]]+\]/', $inputString, $matches);

	foreach ($matches[0] as $value) {
		$fieldDisplayname = substr($value, 1, -1); 					// strip out leading "[" and trailing "]" to get the form-fields name
		$fieldname = array_search($fieldDisplayname, $formData);
		preg_match('/[0-9]+$/', $fieldname, $matches); 				// gets the sequential number of the fieldname
		$fieldname = "F" . $matches[0]; 							// e.g. "F10"

		if ( isset($formData[$fieldname]) ){
			if ( is_array($formData[$fieldname]) ){
				$resultString = str_replace($value, implode(", ",$formData[$fieldname]), $resultString);
			}
			else{
				$resultString = str_replace($value, $formData[$fieldname], $resultString);
			}
		}
	}

	return $resultString;
}

?>
