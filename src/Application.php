<?php
/**
 * Application.php
 *
 * Basic Application class. This class handles setting up the database, storing modules, aliasing,
 * as well as generating and running the controller.
 *
 * PHP version 5.3
 *
 * @package   Ra
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra;

class Application {
	protected $router;
	protected $settings;

	/**
	 * Constructor. Starts the session and calls the application's initialise method.
	 *
	 * @return void
	 */
	public function __construct(Settings $settings = null) {
		$this->settings = $settings ? $settings : new Settings();

		$this->initialise();
	}

	/**
	 * Override this method to handle any initialisation without the need to override the constructor.
	 *
	 * @return void
	 */
	protected function initialise() {
		$this->router = new Router();
	}

	/**
	 * Set the default route which is used if no controller or method can be found.
	 *
	 * @param  $controller string The name of the controller class to use.
	 * @param  $action     string Optional method name to use. Defaults to index.
	 * @return void
	 */
	public function setDefault($controller, $action = 'index') {
		$this->router->setDefault($controller, $action);
	}

	/**
	 * Run the current URL against the URLManager and any pre/post process modules and call execute on the application.
	 * If a RaException is thrown it will call execute with the exception's url.
	 *
	 * @param  $url string Optional URL to run against. If not supplied uses $_GET['url'].
	 * @return void
	 */
	public function run($url = false) {
		if(!$url)
			$url = $_SERVER['REQUEST_URI'];

		# LOG
		$this->settings->log("REQUEST URL $url");

		$this->request = new Request($url, isset($this->settings->debug) && $this->settings->debug);
		$this->settings->applyRequestSettings($this->request);

		try {
			$this->execute($this->request->url);
		}
		catch(CoreException $ex) {
			$this->request->exception = $ex;

			if($this->settings->debug) {
				echo $ex;
			}
			else if($url != $ex->getURL()) {
				$exUrl = $ex->getURL();

				# if the URL has an extension make sure it gets passed to the execute method
				if(preg_match('/(\.[^\.\/]+)$/', $this->request->url, $ext))
					$exUrl .= $ext[1];

				$this->request->url = $exUrl;
				$this->request->exception = $ex;

				$this->execute($exUrl);
			}
		}
	}

	/**
	 * Executes the supplied URL, creating and initialising the controller, calling the action and rendering the output.
	 *
	 * @param  $url string The URL to generate the controller from.
	 * @return void
	 */
	public function execute($url) {
		# run controller / action
		$route = $this->router->parse($url);

		# try / catch this to generate appropriate error
		$class  = $route->controller;
		$action = $route->action;

		# LOG
		$this->settings->log("CONTROLLER $class");
		$this->settings->log("ACTION $action");

		$this->request->controller = strtolower(str_replace('Controller', '', $class));
		$this->request->action = strtolower($action);

		# additional properties to set on the request
		$additional = array('parameters', 'extension');

		foreach($additional as $item) {
			if(isset($route->$item)) {
				$this->request->$item = $route->$item;
			}
		}

		try {
			$controller = new $class($this, $this->settings, $this->request);
		}
		catch(CoreException $rex) {
			throw $rex;
		}
		catch(Exception $ex) {
			throw new CoreException("Error in application. Can't initialise controller '$class'.", 500, $ex);
		}

		# check for a HTTP method specific action
		$protocolSpecific = strtolower($_SERVER['REQUEST_METHOD']) . '_' . $action;

		if(method_exists($controller, $protocolSpecific))
			$action = $protocolSpecific;

		if(!method_exists($controller, $action)) {
			# check to see if any other protocol specific actions exist
			$methods = array('get', 'put', 'post', 'delete', 'head');

			foreach($methods as $method)
				# check for the method against the originally requested action
				if(method_exists($controller, "{$method}_{$this->request->action}"))
					# throw a 405 error rather than 404
					throw new CoreException("Method '{$_SERVER['REQUEST_METHOD']}' not allowed for '$action'.", 405);

			throw new CoreException("Action '$action' not found on controller of class '$class'.", 404);
		}

		try {
			echo call_user_func_array(array($controller, $action), $route->parameters ? $route->parameters : array());
		}
		catch(CoreException $rex) {
			throw $rex;
		}
		catch(Exception $ex) {
			throw new CoreException("Error in application. Method '$action' failed on controller '$class'.", 500, $ex);
		}
	}
}
