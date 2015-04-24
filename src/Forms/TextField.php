<?php
/**
 * TextField.php
 *
 * PHP version 5.3
 *
 * @package   Ra\Forms
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra\Forms;

class TextField extends FormField {
	public function render() {
		$key = $this->key;

		return $this->label() . "<input type='{$this->type}' class='form-control' id='$key' name='$key' value=\"" . htmlspecialchars($this->value) . "\" />";
	}
}