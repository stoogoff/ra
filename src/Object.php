<?php
/**
 * Object.php
 *
 * Base class for a lot of things.
 *
 * PHP version 5.3
 *
 * @package   Ra
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra;

class Object {
	protected $__data = array();
	protected $throwException = false;

	public function __construct($data = array()) {
		$this->__data = $data;
	}

	public function __set($name, $value) {
		$method = 'change' . ucfirst($name);

		if(method_exists($this, $method))
			$this->$method(isset($this->$name) ? $this->name : null, $value);

		$this->__data[$name] = $value;
	}

	public function __get($name) {
		if(isset($this->$name))
			return $this->__data[$name];

		if($this->throwException)
			throw new CoreException("Property '$name' not found on object of type '" . get_class($this) . "'");

		return null;
	}

	public function __isset($name) {
		return array_key_exists($name, $this->__data);
	}

	public function __unset($name) {
		unset($this->__data[$name]);
	}

	public function __toString() {
		return '';
	}

	public function toObject() {
		return $this->toObjectVars($this->__data);
	}

	protected function toObjectVars($object) {
		$return = array();

		if(is_object($object) && $object instanceof Object) {
			foreach($object->__data as $key => $value)
				$return[$key] = $this->toObjectVars($value);
		}
		else if(is_array($object) || (is_object($object) && $object instanceof Collection)) {
			foreach($object as $key => $value) {
				if(is_object($value) && method_exists($value, 'toObject')) {
					$return[$key] = $value->toObject();
				}
				else {
					$return[$key] = $this->toObjectVars($value);
				}
			}
		}
		else if(is_object($object)) {
			$vars = get_object_vars(($object));

			foreach($vars as $key => $value)
				$return[$key] = $this->toObjectVars($value);
		}
		else
			$return = $object;

		return $return;
	}
}