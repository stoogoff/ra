<?php

namespace Ra\Formatting;

/**
 * FormatProxy is the base wrapper class for templates which render something other than HTML.
 * This class acts as a proxy class for the model supplied to it in the constructor.
 */
abstract class FormatProxy extends \Ra\Object {
	protected $model;

	public function __construct(\Ra\Object $model) {
		$this->model = $model;
		$this->__data = $model->__data;
		$this->initialise();
	}

	/**
	 * 
	 */
	protected abstract function initialise();

	/**
	 * Override __get to format any resulting text.
	 */
	public function __get($name) {
		$result = $this->model->$name;

		if(is_array($result) || is_object($result) || is_bool($result) || is_int($result))
			return $result;

		return $this->format($result);
	}

	public function __isset($name) {
		return isset($this->model, $name);
	}


	/**
	 * The format method is called whenever string output is returned to the template. Inheriting
	 * classes need to format the input as they see fit and return it.
	 *
	 * @param  $input string The string to be rendered by the template.
	 * @return string
	 */
	protected function format($input) {
		return $input;
	}

	/**
	 * A final formatting pass over the content before it's rendered by the server. The defalt implementation
	 * makes no changes to the content but inheriting classes can override this.
	 *
	 * @param $content The full content to modify, if necessary.
	 * @return string
	 */
	public function preRender($content) {
		return $content;
	}
}
