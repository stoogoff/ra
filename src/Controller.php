<?php
/**
 * Controller.php
 *
 * Base controller class.
 *
 * PHP version 5.3
 *
 * @package   Ra
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra;

/**
 * Base controller class;
 */
abstract class Controller {
	protected $application;
	protected $settings;
	protected $model;
	protected $request;

	public function __construct(Application $application, Settings $settings, Request $request) {
		$this->application = $application;
		$this->settings = $settings;
		$this->request = $request;

		# LOG
		$this->settings->log("<strong>STATE:</strong> controller->__construct");

		$this->initialise();
	}

	public function initialise() {
		$this->model = new Object();
		$this->model->request = $this->request;

		# LOG
		$this->settings->log("<strong>STATE:</strong> controller->initialise");
	}

	protected function render($path = false) {
		# LOG
		$this->settings->log("<strong>STATE:</strong> controller->render(path = '$path')");

		$viewClass = $this->settings->view;

		if(!$viewClass)
			throw new CoreException('View not set.', 500);

		# LOG
		$this->settings->log("VIEW $viewClass");

		$view = new $viewClass($this->settings, $this->model);

		if($path)
			$view->loadTemplate($path, $this->request->extension);
		else
			$view->loadDefaultTemplate($this->request);

		return $view->render();
	}

	/**
	 * Render the model directly without using a template. This is mainly for non-HTML requests.
	 */
	protected function renderModel($object = false) {
		# LOG
		$this->settings->log("<strong>STATE:</strong> controller->renderModel");

		$view = new ModelView($this->settings, $object ? $object : $this->model);
		$view->setRequest($this->request);

		return $view->render();
	}
}