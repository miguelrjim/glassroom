<?php

include 'Library/xmpphp/XMPPHP/XMPP.php';

class XMPPFacebook extends XMPPHP_XMPP  {
	private $sessionData = NULL;

	public function __construct($host, $port, $user, $password, $resource, $server = null, $printlog = false, $loglevel = XMPPHP_Log::LEVEL_INFO, $caller = NULL) {
		parent::__construct($host, $port, $user, $password, $resource, $server, $printlog, $loglevel, $caller);
		$this->addXPathHandler('{urn:ietf:params:xml:ns:xmpp-sasl}challenge', 'sasl_challenge_handler');

	}


	public function setSessionData($sessionData) {
		$this->sessionData = $sessionData;
	}

	public function getSessionData() {
		return $this->sessionData;
	}

	protected function sasl_challenge_handler($xml) {
		$this->log->log("Auth challenge!");

		$challenge = (string) $xml->data;
		$challenge = base64_decode($challenge);

		$sessions = $this->getSessionData();

		$fbSessionKey = $sessions['session_key'];

		$vars = array();
		parse_str($challenge, $vars);

		if (!empty($vars['nonce'])) {
			$response = array(
						'api_key'     => FACEBOOK_API_ID,
						'call_id'     => time(),
						'method'      => $vars['method'],
						'nonce'       => $vars['nonce'],
						'session_key' => $fbSessionKey,
						'v'           => '1.0',
			);

			$response['sig'] = 'api_key=' . $response['api_key']
			. 'call_id=' . $response['call_id']
			. 'method=' . $response['method']
			. 'nonce=' . $response['nonce']
			. 'session_key=' . $response['session_key']
			. 'v=' . $response['v']
			. FACEBOOK_API_SECRET_KEY;

			$response['sig'] = md5($response['sig']);
			$response = http_build_query($response);
			$response = base64_encode($response);

			$this->send("<response xmlns='urn:ietf:params:xml:ns:xmpp-sasl'>{$response}</response>");
		}

	}

	/**
	 * Features handler
	 *
	 * @param string $xml
	 */
	protected function features_handler($xml) {

		if($xml->hasSub('starttls') and $this->use_encryption) {
			$this->send("<starttls xmlns='urn:ietf:params:xml:ns:xmpp-tls'><required /></starttls>");
		} elseif($xml->hasSub('bind') and $this->authed) {
			$id = $this->getId();
			$this->addIdHandler($id, 'resource_bind_handler');
			$this->send("<iq xmlns=\"jabber:client\" type=\"set\" id=\"$id\"><bind xmlns=\"urn:ietf:params:xml:ns:xmpp-bind\"><resource>{$this->resource}</resource></bind></iq>");
		} else {
			$this->addXPathHandler('{urn:ietf:params:xml:ns:xmpp-sasl}success', 'sasl_success_handler');
			$this->log->log("Attempting Auth...");
			$this->send("<auth xmlns='urn:ietf:params:xml:ns:xmpp-sasl' mechanism='X-FACEBOOK-PLATFORM' />");
		}
	}

}
