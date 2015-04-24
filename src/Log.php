<?php

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