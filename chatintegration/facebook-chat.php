<?php
// activate full error reporting
//error_reporting(E_ALL & E_STRICT);

include 'Library/xmpphp/XMPPHP/XMPPFacebook.php';

require_once('Library/FaceBook/FaceBookHandler.php');
FaceBookHandler::initiate();

$data = FaceBookHandler::getSession();

#Use XMPPHP_Log::LEVEL_VERBOSE to get more logging for error reports
#If this doesn't work, are you running 64-bit PHP with < 5.2.6?
$conn = new XMPPFacebook('chat.facebook.com', 5222, 'akashsharmaa', 'akash@Fac3', 'xmpphp', 'chat.facebook.com', $printlog=false, $loglevel=XMPPHP_Log::LEVEL_INFO);

$conn->setSessionData($data);

try {

	$conn->autoSubscribe(); 	

	$conn->connect();
	$conn->processUntil('session_start');
	$conn->presence(true);
	
	$conn->getRoster();

    $conn->message('Send to', 'This is a test message!');



	$payloads = $conn->processUntil(
								array('presence', 'end_stream', 'vcard', 'message'),
								$timeOut = 200
							);

    $conn->disconnect();
} catch(XMPPHP_Exception $e) {
    die($e->getMessage());
}


?>