<?php

namespace Ra;

/**
 * The Settings class holds global configuration information and any non-persistent data which needs
 * to be passed around the system.
 */
class Settings extends Object {
	public function __construct() {
		$this->setArray(array(
			'docRoot'    => $_SERVER['DOCUMENT_ROOT'] . '/',
			'webRoot'    => $_SERVER['HTTP_HOST'],
			'templates'  => $_SERVER['DOCUMENT_ROOT'] . '/media/tpl/',
			'baldrRoot'  => dirname(__FILE__) . '/',
			'components' => realpath(dirname(__FILE__) . '/../../components') . '/',
			'vendor'     => realpath(dirname(__FILE__) . '/..') . '/',
		));
	}

	/**
	 * Take all settings starting with request. and apply them to the supplied Request object.
	 */
	public function applyRequestSettings(Request $request) {
		foreach($this->__data as $key => $value) {
			if(strpos($key, 'request.') === 0) {
				list($r, $newkey) = explode('.', $key);

				$request->$newkey = $value;
			}
		}
	}

	public function log($message) {
		if(!$this->debug)
			return;

		if(is_array($message) || is_object($message)) {
			echo "<pre>" . print_r($message, 1) . "</pre>";
		}
		else {
			echo "$message<br />";
		}
	}

	public function setArray($settings) {
		foreach($settings as $key => $value)
			$this->$key = $value;
	}

	public function getArray($settings) {
		$output = array();

		foreach($settings as $setting)
			if($this->$setting)
				$output[] = $this->$setting;

		return $output;
	}
}

