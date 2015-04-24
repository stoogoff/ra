<?php

namespace Ra\Forms;

class CheckList extends ListField {
	public function render() {
		return $this->label() . $this->renderItems();
	}

	protected function item($text, $selected) {
		return "<div class='checkbox'><label><input type='checkbox' name='{$this->key}[]' value=\"" . htmlspecialchars($text) . "\" " . ($selected ? " checked='checked'" : '') . ">$text</label></div>\n";
	}
}