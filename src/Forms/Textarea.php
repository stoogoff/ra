<?php

namespace Ra\Forms;

class Textarea extends FormField {
	public function render() {
		$key = $this->key;

		return $this->label() . "<textarea id='$key' class='form-control' name='$key'>" . htmlspecialchars($this->value) . "</textarea>";
	}
}