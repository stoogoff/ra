<?php

namespace Ra\Forms;

class ArrayField extends FormField {
	protected $separator1 = '|';
	protected $separator2 = null;

	public function __construct($label, $key, $value = '', $separator1 = '|', $separator2 = null) {
		parent::__construct($label, $key, $value);

		$this->separator1 = $separator1;
		$this->separator2 = $separator2;
	}

	public function setValue($value) {
		$this->value = explode($this->separator1, $value);

		if($this->separator2) {
			$tmp = $this->value;
			$this->value = array();

			foreach($tmp as $item) {
				list($key, $value) = explode($this->separator2, $item);
				$this->value[$key] = $value;
			}
		}
	}

	protected function savedValue() {
		if(count($this->value) == 0)
			return '';

		$values = array();

		if(is_object($this->value)) {
			$properties = get_object_vars($this->value);

			foreach($properties as $k => $v) {
				$values[] = $k . $this->separator2 . $v;
			}
		}
		else {
			$values = $this->value;
		}

		return implode($this->separator1, $values);
	}

	public function render() {
		$key = $this->key;
		$values = array();
		$initial = array();

		if(is_array($this->value)) {
			$initial = $this->value;
		}
		elseif(is_object($this->value)) {
			$properties = get_object_vars($this->value);

			foreach($properties as $k => $v) {
				$initial[] = $k . $this->separator2 . $v;
			}
		}
		elseif($this->value) {
			$initial = array($this->value);
		}

		foreach($initial as $value) {
			if(trim($value) != '') {
				$values[] = "<li><span class='label label-info'>$value</span></li>";
			}
		}

		return $this->label()
			. "<ul class='pills list-unstyled' data-save='$key' data-separator='{$this->separator1}'>" . implode("\n", $values) . "</ul>"
			. "<input type='hidden' name='$key' value=\"" . htmlspecialchars($this->savedValue()) . "\" />"
			. "<input type='text' class='arrayField form-control' name='{$key}_visible' value='' />";
	}
}