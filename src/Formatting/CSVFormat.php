<?php

namespace Ra\Formatting;

/**
 * Handles CSV formatting for templates.
 */
class CSVFormat extends FormatProxy {
	/**
	 * Output text/csv content type.
	 *
	 * @return void
	 */
	protected function initialise() {
		# output headers
		header('Content-type: text/csv');
	}

	/**
	 * Duplicates any double quotes (" becomes "") and surrounds the string with double quotes.
	 */
	protected function format($input) {
		return preg_match('/[,\n]/', $input) ? '"' . str_replace('"', '""', $input) . '"' : $input;
	}
}
