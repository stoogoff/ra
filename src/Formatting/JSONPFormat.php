<?php
/**
 * JSONPFormat.php
 *
 * PHP version 5.3
 *
 * @package   Ra\Formatting
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra\Formatting;

class JSONPFormat extends JSONFormat {
	protected function initialise() {
		header('Content-type: application/javascript');
	}

	public function preRender($content) {
		$callback = isset($_GET['callback']) ? $_GET['callback'] : 'callback';

		return $callback . '(' . $content . ')';
	}
}
