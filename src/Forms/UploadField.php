<?php
/**
 * UploadField.php
 *
 * PHP version 5.3
 *
 * @package   Ra\Forms
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra\Forms;

class UploadField extends FormField {
	public function __construct($label, $key, $filePath, $value = '') {
		parent::__construct($label, $key, $value);

		$this->filePath = $filePath;
	}

	public function render() {
		$value = $this->getValue();

		if($value) {
			$value = "<p class='text-muted'>Current File: $value <input type='hidden' name='{$this->key}' value=\"" . htmlspecialchars($this->value) . "\" /></p>";
		}

		return $this->label() . $value . "<input name='{$this->key}' type='file' />";
	}

	public function setValue($value) {
		if(isset($value['name'])) {
			$this->value = $value['name'];
			$fullPath = $this->filePath . $value['name'];

			# create path if it doesn't exist
			if(!file_exists(dirname($fullPath))) {
				mkdir(dirname($fullPath), FILE_PERMISSION);
			}

			move_uploaded_file($value['tmp_name'], $fullPath);
		}
		else {
			$this->value = $value;
		}
	}
}