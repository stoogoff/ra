<?php
/**
 * YesNoSelect.php
 *
 * PHP version 5.3
 *
 * @package   Ra\Forms
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra\Forms;

class YesNoSelect extends SelectList {
	public function __construct($label, $key, $value = '') {
		parent::__construct($label, $key, array('No', 'Yes'), $value ? 'Yes' : 'No');
	}

	public function getValue() {
		return $this->value == 'Yes';
	}
}