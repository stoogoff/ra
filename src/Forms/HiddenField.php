<?php

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