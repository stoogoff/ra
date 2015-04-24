<?php
/**
 * SelectList.php
 *
 * PHP version 5.3
 *
 * @package   Ra\Forms
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra\Forms;

class SelectList extends ListField {
	public function render() {
		$key = $this->key;

		return $this->label() . "<select id='$key' name='$key' class='form-control'>" . $this->renderItems() . "</select>";
	}

	protected function item($text, $selected) {
		return "<option" . ($selected ? " selected='selected'" : '') . ">" . htmlspecialchars($text) . "</option>\n";
	}
}