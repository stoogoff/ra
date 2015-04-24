<?php
/**
 * FormField.php
 *
 * PHP version 5.3
 *
 * @package   Ra\Forms
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra\Forms;

abstract class FormField {
	protected $label, $key, $value;
	protected $type = 'text';

	public function __construct($label, $key, $value = '') {
		$this->label = $label;
		$this->key = $key;
		$this->value = $value;
	}

	public function getName() {
		return $this->key;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($value) {
		$this->value = $value;
	}

	protected function label() {
		return "<label for='{$this->key}'>{$this->label}</label>";
	}

	abstract public function render();
}
