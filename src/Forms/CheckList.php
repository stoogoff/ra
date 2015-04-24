<?php
/**
 * CheckList.php
 *
 * PHP version 5.3
 *
 * @package   Ra\Forms
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra\Forms;

class CheckList extends ListField {
	public function render() {
		return $this->label() . $this->renderItems();
	}

	protected function item($text, $selected) {
		return "<div class='checkbox'><label><input type='checkbox' name='{$this->key}[]' value=\"" . htmlspecialchars($text) . "\" " . ($selected ? " checked='checked'" : '') . ">$text</label></div>\n";
	}
}