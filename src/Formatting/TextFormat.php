<?php
/**
 * TextFormat.php
 *
 * Handles plain text formatting for templates.
 *
 * PHP version 5.3
 *
 * @package   Ra\Formatting
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra\Formatting;

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
