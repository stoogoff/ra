<?php

namespace Ra\Formatting;

class HTMLFormat extends FormatProxy {
	/**
	 * Output text/xml content type.
	 *
	 * @return void
	 */
	protected function initialise() {
		# output headers
		header('Content-type: text/html');
	}

	/**
	 * Passes any input through htmlspecialchars and returns it.
	 */
	protected function format($input) {
		return htmlspecialchars($input, ENT_COMPAT, 'UTF-8', false);
	}
}
