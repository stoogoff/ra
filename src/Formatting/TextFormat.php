<?php

namespace Ra\Formatting;

/**
 * Handles plain text formatting for templates.
 */
class TextFormat extends FormatProxy {
	/**
	 * Output text/plain content type.
	 *
	 * @return void
	 */
	protected function initialise() {
		# output headers
		header('Content-type: text/plain; charset=utf-8');
	}
}
