<?php
/**
 * HiddenField.php
 *
 * PHP version 5.3
 *
 * @package   Ra\Forms
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra\Forms;

class HiddenField extends FormField {
	public function __construct($key, $value = '') {
		$this->key = $key;
		$this->value = $value;
	}

	public function render() {
		return "<input type='hidden' name='{$this->key}' value=\"" . htmlspecialchars($this->value) . "\" />";
	}
}