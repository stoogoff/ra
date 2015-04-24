<?php
/**
 * Request.php
 *
 * PHP version 5.3
 *
 * @package   Ra
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra;

class Request extends Object {
	public function __construct($url, $debug = false) {
		$this->originalUrl = $this->url = $url;
		$this->extension = 'html';
		$this->throwException = $debug;
	}

	/**
	 * Return the separate parts of the URL.
	 */
	public function parts() {
		return explode('/', trim($this->url, '/'));
	}

	/**
	 * Return all headers or a single specific header
	 */
	public function getHeaders($header = false) {
		$headers = getallheaders();

		if($header)
			return isset($headers[$header]) ? $headers[$header] : null;

		return $headers;
	}
}