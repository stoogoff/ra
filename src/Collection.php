<?php
/**
 * Collection.php
 *
 * Collection represents a basic list class which implements Iterator, ArrayAccess and Countable. This class can be treated
 * almost identically to an array *except* is_array will return false. IT has some helper methods influenced by the underscore
 * JavaScript library.
 *
 * PHP version 5.3
 *
 * @package   Ra
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra;

class Collection implements \Iterator, \ArrayAccess, \Countable {
	private   $position = 0;
	protected $__data   = array();

	public function __construct($list = array()) {
		$this->__data = $list;
	}

	# Count implementation
	public function count() {
		return count($this->__data);
	}

	# Iterator implementation
	public function rewind() {
		$this->position = 0;
	}
	public function current() {
		return $this->__data[$this->position];
	}
	public function key() {
		return $this->position;
	}
	public function next() {
		++$this->position;
	}
	public function valid() {
		return isset($this->__data[$this->position]);
	}

	# ArrayAccess implementation
	public function offsetSet($offset, $value) {
		if(!$offset)
			$offset = $this->count();

		$this->__data[$offset] = $value;
	}
	public function offsetExists($offset) {
		return isset($this->__data[$offset]);
	}
	public function offsetUnset($offset) {
		unset($this->__data[$offset]);
	}
	public function offsetGet($offset) {
		return isset($this->__data[$offset]) ? $this->__data[$offset] : null;
	}

	public function toArray() {
		return $this->__data;
	}

	# useful array functions

	public function push($item) {
		$this->__data[] = $item;
	}

	public function pop() {
		return array_pop($this->__data);
	}

	public function shift() {
		return array_shift($this->__data);
	}

	public function unshift($item) {
		return array_unshift($this->__data, $item);
	}

	/**
	 * Remove the element at the given index and return it.
	 */
	public function remove($index) {
		$removed = array_splice($this->__data, $index, 1);

		return count($removed) > 0 ? $removed[0] : null;
	}

	/**
	 * Create a new list with the first $limit elements and return.
	 */
	public function limit($limit) {
		if($limit > count($this->__data))
			$limit = count($this->__data);

		$new = new Collection();

		for($i = 0; $i < $limit; ++$i)
			$new->push($this[$i]);

		return $new;
	}

	# Underscore collection methods

	public function each(callable $callable) {
		foreach($this->__data as $idx => $item) {
			$callable($item, $idx);
		}

		return $this;
	}

	public function map(callable $callable) {
		return new Collection(array_map($callable, $this->__data));
	}

	public function reduce(callable $callable) {
		return array_reduce($this->__data, $callable);
	}

	public function find(callable $callable) {
		foreach($this->__data as $item) {
			if($callable($item) === true)
				return $item;
		}

		return null;
	}

	public function filter(callable $callable) {
		$collection = new Collection();

		$this->each(function($item) use($callable, $collection) {
			if($callable($item) === true) {
				$collection->push($item);
			}
		});

		return $collection;
	}

	public function reject(callable $callable) {
		$collection = new Collection();

		$this->each(function($item) use($callable, $collection) {
			if($callable($item) === false) {
				$collection->push($item);
			}
		});

		return $collection;
	}

	public function all(callable $callable) {
		$total = 0;

		$this->each(function($item) use($callable, $total) {
			if($callable($item) === true)
				++$total;
		});

		return $total === $this->size();
	}

	public function any(callable $callable) {
		foreach($this->__data as $item) {
			if($callable($item) === true) {
				return true;
			}
		}

		return false;
	}

	public function pluck($property) {
		return $this->map(function($item) use($property) {
			return $this->propertyFromItem($item, $property);
		});
	}

	public function max(callable $callable) {
		$current = null;

		foreach($this->__data as $item) {
			$value = $callable($item);

			if($value > $current)
				$current = $value;
		}

		return $current;
	}

	public function min(callable $callable) {
		$current = null;

		foreach($this->__data as $item) {
			$value = $callable($item);

			if($current == null || $value < $current)
				$current = $value;
		}

		return $current;
	}

	public function sort(callable $callable) {
		$data = $this->__data;

		usort($data, $callable);

		return new Collection($data);
	}

	public function sortBy($property, $sortAscending = true) {
		return $this->sort(function($a, $b) use($property, $sortAscending) {
			$a = $this->propertyFromItem($a, $property);
			$b = $this->propertyFromItem($b, $property);

			if($a === $b)
				return 0;

			$result = $a < $b ? 1 : -1;

			if($sortAscending)
				$result *= -1;

			return $result;
		});
	}

	public function size() {
		return $this->count();
	}

	# Underscore array methods

	public function head() {
		return $this->size() === 0 ? null : $this->__data[0];
	}

	public function first() {
		return $this->head();
	}

	public function tail($start = 1) {
		return new Collection($this->size() <= 1 ? array() : array_slice($this->__data, $start));
	}

	public function rest($start = 1) {
		return $this->tail($start);
	}

	public function last() {
		$size = $this->size();

		return $size === 0 ? null : $this->__data[$size - 1];
	}

	protected function propertyFromItem($item, $property) {
		if(is_object($item)) {
			return method_exists($item, $property) ? $item->$property() : $item->$property;
		}
		elseif(is_array($item)) {
			return $item[$property];
		}		
	}
}
