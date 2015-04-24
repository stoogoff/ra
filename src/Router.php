<?php
/**
 * Router.php
 *
 * This core class manages the mapping between the url requested to
 * the action required to be performed by the system.
 *
 * There are a few main components to any call
 *
 * the controller - any class with name following form <Name>Controller
 * the action - a lower case name that is a method of the Controller class
 * the params - array of left overs
 *
 * PHP version 5.3
 *
 * @package   Ra
 * @author    Stoo Goff and Robbie Scourou
 * @copyright 2007 (c) Stoo Goff and Robbie Scourou
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra;

class Router {
	protected $default;
	protected $aliases = array();

	/**
	 * Extended constructor to set default route and custom
	 * routes
	 *
	 * @return false
	 */
	public function __construct() {
		// create routes
		$this->default = new Object();

		// set up default route - these are the values given back if nothing is
		// supplied
		$this->default->controller = 'PageController';
		$this->default->action = 'index';
	}

	public function setDefault($controller, $action = 'index') {
		$this->default->controller = $controller;
		$this->default->action = $action;
	}

	/**
	 * This is the main function that does the initial parsing
	 * of a passed in url path and works out our component parts
	 *
	 * @param $url
	 * @return void
	 */
	public function parse($url) {
		$originalUrl = $url;
		$alias = new Object();

		# strip off the query string
		if(strpos($url, '?') !== false)
			$url = substr($url, 0, strpos($url, '?'));

		# check for aliases
		foreach($this->aliases as $regex => $route) {
			if(preg_match($regex, $url)) {
				$alias->controller = $route->controller;

				if($route->action)
					$alias->action = $route->action;

				break;
			}
		}

		# extension
		if(preg_match('/\.([^\.\/]+)$/', $url, $ext)) {
			$extension = $ext[1];
			$url = preg_replace('/\.([^\.\/]+)$/', '', $url);
		}

		// strip apart url
		if (preg_match('/^[\/]{0,}(.+?)[\/]{0,}$/',$url,$matches)) {
			$url = $matches[1];
		}

		// set defaults if needed
		if ($url == '/') {
			// set defaults and return
			return $this->default;
		}

		// set cleaned stack
		$stack = explode('/',$url);

		// work out component parts
		// deal with first off the stack
		$cls = isset($alias->controller) ? $alias->controller : ucfirst($stack[0]).'Controller';
		$hasClass = false;
		$innerException = null;

		try {
			$hasClass = class_exists($cls);
		}
		catch(CoreException $ex) {
			// an exception will be thrown here if the class doesn't exist
			// this prevents the elseif(method_exists(...)) part from firing
			// so we swallow this exception as the final else clause will create
			// the correct exception
			$innerException = $ex;
		}

		$route = new Object();

		// is it a controller?
		if ($hasClass) {
			// set to route
			$route->controller = $cls;
			// remove from stack
			array_shift($stack);

			// test the next segment on the stack for a method on the controller
			if(isset($alias->action)) {
				$route->action = $alias->action;
				array_shift($stack);
			}
			else if (isset($stack[0])) {
				// we have a valid action for the route
				$route->action = $this->getMethodName($stack[0]);
				// shift from stack
				array_shift($stack);
			}
			else {
				// use default action
				$route->action = $this->getMethodName($this->default->action);
			}
		}
		// test it as a method on the default
		elseif (method_exists($this->default->controller,$stack[0])) {
			// it does so set appropriately
			$route->controller = $this->default->controller;
			$route->action = $this->getMethodName($stack[0]);
			// remove from stack
			array_shift($stack);
		}
		else {
			// if we are here then the first element is neither a controller
			// or a method for the default.
			throw new CoreException("URL routing for '$originalUrl' failed.", 404, $innerException);
		}


		if(isset($extension))
			$route->extension = $extension;

		// if we are here then we have a valid controller and action
		// rest of stack are params
		$route->parameters = $stack;

		return $route;
	}

	protected function getMethodName($input) {
		return str_replace('-', '', $input);
	}

	public function addAlias($regex, $controller, $action = false) {
		$route = new Object();
		$route->controller = $controller;
		$route->action = $action;

		$this->aliases[$regex] = $route;
	}
}