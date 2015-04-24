<?php

namespace Ra\Forms;

class YesNoSelect extends SelectList {
	public function __construct($label, $key, $value = '') {
		parent::__construct($label, $key, array('No', 'Yes'), $value ? 'Yes' : 'No');
	}

	public function getValue() {
		return $this->value == 'Yes';
	}
}