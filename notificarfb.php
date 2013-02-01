<?php
require_once('AppInfo.php');
require_once('utils.php');
require_once('sdk/src/facebook.php');
$facebook = new Facebook(array(
		'appId'  => AppInfo::appID(),
		'secret' => AppInfo::appSecret()
	));
function notificarfb($uid, $status)
{
	global $facebook;
	$permissions = $facebook->api("/$uid/permissions");
	$attachment = array(
		'message' => $status,
		'type' => 'status',
		'privacy' => array(
			'value' => 'CUSTOM',
			'friends' => 'SELF'
		)
	);
	try
	{
		$facebook->api('/'.$uid.'/feed/','POST',$attachment);
		return true;
	}
	catch (FacebookApiException $e)
	{
		return false;
	}
}
?>