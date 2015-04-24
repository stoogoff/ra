<?php
/**
 * XMLFormat.php
 *
 * Handles XML formatting for templates.
 *
 * PHP version 5.3
 *
 * @package   Ra\Formatting
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra\Formatting;

class XMLFormat extends HTMLFormat {
	/**
	 * Output text/xml content type.
	 *
	 * @return void
	 */
	protected function initialise() {
		# output headers
		header('Content-type: text/xml');
	}

	/**
	 * Override toString to return the model data as XML
	 */
	public function __toString() {
		$root = get_class($this->model);

		return "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n" . $this->toXml($root, $this->__data);
	}

	protected function toXml($node, $object) {
		$str = "<$node";

		if(empty($object))
			return "$str />\n";

		$str .= ">";

		if(is_object($object) && $object instanceof Object) {
			foreach($object->__data as $key => $value)
				$str .= $this->toXml($key, $value);
		}
		else if(is_object($object)) {
			$vars = get_object_vars(($object));

			foreach($vars as $key => $value)
				$str .= $this->toXml($key, $value);
		}
		else if(is_array($object)) {
			# TODO - $key is wrong here
			foreach($object as $key => $value)
				$str .= $this->toXml($key, $value);
		}
		else
			$str .= $this->format($object);

		 return "$str</$node>\n";
	}
}
