<?php
if (!function_exists('curl_init')) {
	throw new Exception('Facebook needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
	throw new Exception('Facebook needs the JSON PHP extension.');
}

require_once('Library/FaceBook/Defines.php');
require_once('Library/FaceBook/Facebook.php');

class FaceBookHandler {
	private static $facebookObject = NULL;
	private static $session = NULL;
	private static $userSesion = NULL;

	public static function isLogin() {
		self::getSession();

		return (self::$session !== NULL);
	}

	public static function getUserUrl() {
		$userStatus = self::isLogin();

		return ($userStatus === false) ? self::$facebookObject->getLoginUrl() : self::$facebookObject->getLogoutUrl();
	}

	public static function initiate() {
		self::getSession();
	}

	
	public static function getUserUrlImage() {
		$userStatus = self::isLogin();

		return ($userStatus === false) ? FACEBOOK_API_LOGIN_URL : FACEBOOK_API_LOGOUT_URL;
	}

	public static function getUserFriendList() {
		$friendList = array();
		$userStatus = self::isLogin();

		if ($userStatus === true) {
			$friends = self::$facebookObject->getUserFriendList(self::$facebookObject);

			$friendList = $friends['data'];
		}

		return $friendList;
	}

	private static function getApiParams() {
		$facebookParams = array(
					  'appId'  => FACEBOOK_API_ID,
					  'secret' => FACEBOOK_API_SECRET_KEY,
					  'cookie' => FACEBOOK_API_COOKIE_SUPPORT,
					);

		return $facebookParams;
	}

	private static function connectApi() {
		if (self::$facebookObject !== NULL) {
			return;
		}

		$facebookParams = self::getApiParams();

		// Create our Application instance.
		self::$facebookObject = new FacebookApiClass($facebookParams);
	}

	public static function getSession() {
		self::connectApi();

		if (self::$facebookObject === NULL || self::$session !== NULL) {
			return self::$session;
		}

		// We may or may not have this data based on a $_GET or $_COOKIE based session.
		//
		// If we get a session here, it means we found a correctly signed session using
		// the Application Secret only Facebook and the Application know. We dont know
		// if it is still valid until we make an API call using the session. A session
		// can become invalid if it has already expired (should not be getting the
		// session back in this case) or if the user logged out of Facebook.


		self::$session = self::$facebookObject->getSession();
		// Session based API call.
		if (self::$session != NULL) {
		  try {
			$uid = self::$facebookObject->getUser();
			self::$userSesion = self::$facebookObject->api('/me');
		  } catch (FacebookApiException $e) {
			error_log($e);
		  }
		}

		return self::$session;
	}






}
