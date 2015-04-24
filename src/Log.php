<?php
/**
 * Log.php
 *
 * PHP version 5.3
 *
 * @package   Ra
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra;

class Log {
	public static function Debug($message) {
		echo "$message<br />";
	}

	public static function dump($object) {
		if(is_array($object) || is_object($object)) {
			echo "<pre>" . print_r($object, 1) . "</pre>";
		}
		else {
			echo "$object<br />\n";
		}
	}
}