<?php
/**
 * CSVFormat.php
 *
 * Handles CSV formatting for templates.
 *
 * PHP version 5.3
 *
 * @package   Ra\Formatting
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra\Formatting;

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
