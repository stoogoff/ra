<?php

namespace Ra\Forms;

class TextField extends FormField {
	public function render() {
		$key = $this->key;

		return $this->label() . "<input type='{$this->type}' class='form-control' id='$key' name='$key' value=\"" . htmlspecialchars($this->value) . "\" />";
	}
}