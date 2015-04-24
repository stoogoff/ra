<?php

namespace Ra\Formatting;

/**
 * Handles RSS formatting for templates.
 */
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