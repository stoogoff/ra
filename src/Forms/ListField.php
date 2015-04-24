<?php
/**
 * ListField.php
 *
 * PHP version 5.3
 *
 * @package   Ra\Forms
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra\Forms;

abstract class ListField extends FormField {
	protected $items;

	public function __construct($label, $key, $items, $value = '') {
		parent::__construct($label, $key, $value);

		$this->items = $items;
	}

	protected function renderItems() {
		$html = array();
		$value = $this->value;

		foreach($this->items as $item) {
			$selected = false;

			if(is_array($value) && in_array($item, $value) || $value == $item) {
				$selected = true;
			}

			$html[] = $this->item($item, $selected);
		}

		return implode("\n", $html);
	}

	abstract protected function item($text, $selected);
}