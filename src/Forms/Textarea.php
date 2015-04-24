<?php
/**
 * Textarea.php
 *
 * PHP version 5.3
 *
 * @package   Ra\Forms
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra\Forms;

class Textarea extends FormField {
	public function render() {
		$key = $this->key;

		return $this->label() . "<textarea id='$key' class='form-control' name='$key'>" . htmlspecialchars($this->value) . "</textarea>";
	}
}