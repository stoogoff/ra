<?php
/**
 * Form.php
 *
 * PHP version 5.3
 *
 * @package   Ra\Forms
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra\Forms;

class Form {
	protected $fields = array();

	public function __construct($fields = array()) {
		$this->fields = $fields;
	}

	public function fields() {
		return $this->fields;
	}

	public function addField(FormField $field) {
		$this->fields[] = $field;
	}

	public function update($post, $files) {
		foreach($this->fields as $field) {
			$key = $field->getName();

			if($field instanceof UploadField && isset($files[$key]) && $files[$key]['size'] > 0) {
				$field->setValue($files[$key]);
			}
			elseif(isset($post[$key])) {
				$field->setValue($post[$key]);
			}
		}
	}

	public function save(\Ra\Object $object) {
		foreach($this->fields as $field) {
			$property = $field->getName();
			$value = $field->getValue();

			$object->$property = $value;
		}
	}
}