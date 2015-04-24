<?php

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