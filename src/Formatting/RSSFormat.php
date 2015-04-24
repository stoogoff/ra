<?php
/**
 * RSSFormat.php
 *
 * Handles RSS formatting for templates.
 *
 * PHP version 5.3
 *
 * @package   Ra\Formatting
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra\Formatting;

class RSSFormat extends HTMLFormat {
	/**
	 * Output RSS mime type.
	 *
	 * @return void
	 */
	protected function initialise() {
		# output headers
		header('Content-type: application/rss+xml');
	}
}