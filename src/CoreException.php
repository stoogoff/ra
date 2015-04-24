<?php

namespace Ra;

/**
 *
 * CoreException is used to trap any exceptions generated by Ra. If an Exception is thrown by a controller
 * it is caught by the Application's run method and converted to a CoreException, preserving the thrown
 * exception as the inner exception of the new object.
 *
 * If a CoreExceptions is caught by the Application class its run method is called again using the CoreException's
 * url (from the getURL method). This allows an alias to be provided for each exception dependant on its
 * status code.
 */
class CoreException extends \Exception {
	protected $innerException;

	public static $CodeNameMap = array(
		400 => 'bad-request',
		401 => 'authorization-required',
		403 => 'forbidden',
		404 => 'not-found',
		405 => 'method-not-allowed',
		412 => 'precondition-failed',
		500 => 'internal-server-error'
	);

	/**
	 * Constructor.
	 *
	 * @param  $message        string    Exception message.
	 * @param  $code           integer   Optional HTTP Error code. Defaults to 500 (server error).
	 * @param  $innerException Exception Optional exception which caused the current exception.
	 * @return void
	 */
	public function __construct($message, $code = 500, Exception $innerException = null) {
		parent::__construct($message, $code);

		if($innerException != null)
			$this->innerException = $innerException;
	}

	/**
	 * The url which handles the exception
	 *
	 * @return string
	 */
	public function getURL() {
		$code = isset(CoreException::$CodeNameMap[$this->code]) ? CoreException::$CodeNameMap[$this->code] : 'handle' . $this->code;

		return '/error/view/' . $code;
	}

	/**
	 * Return an appropriate HTTP response header for this exception.
	 */
	public function setHeader() {
		$message = isset(CoreException::$CodeNameMap[$this->code]) ? CoreException::$CodeNameMap[$this->code] : 'Unknown';
		$message = ucwords(str_replace('-', ' ', $message));

		header("HTTP/1.1 {$this->code} $message", true, $this->code);
	}

	/**
	 * Override the __toString method to return the current exception information and any inner
	 * exception information.
	 *
	 * @return string
	 */
	public function __toString() {
		$str = parent::__toString();

		if($this->innerException != null)
			$str .= "\nInner Exception: " . $this->innerException->__toString();

		return $str;
	}
}
