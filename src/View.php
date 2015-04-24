<?php

namespace Ra;

abstract class View {
	protected $template;
	protected $proxy;
	protected $model;
	protected $basePath = '';
	protected $settings;

	public static $ExtensionMap = array(
		'csv'   => 'Ra\Formatting\CSVFormat',
		'xml'   => 'Ra\Formatting\XMLFormat',
		'rss'   => 'Ra\Formatting\RSSFormat',
		'json'  => 'Ra\Formatting\JSONFormat',
		'jsonp' => 'Ra\Formatting\JSONPFormat',
		'html'  => 'Ra\Formatting\HTMLFormat',
		'txt'   => 'Ra\Formatting\TextFormat',
	);

	public function __construct(Settings $settings, Object $model) {
		$this->settings = $settings;
		$this->basePath = $settings->templates;
		$this->model = $model;

		$this->initialise();
	}

	protected function initialise() {}

	public function loadDefaultTemplate(Request $request) {
		$extension  = $request->extension == 'ajax' ? 'html' : $request->extension;
		$controller = $request->controller;
		$action     = $request->action;
		$separators = array('_', '/');
		$template   = false;

		foreach($separators as $separator) {
			$path = $this->basePath . $controller . $separator . $action . '.' . $extension;

			if(file_exists($path)) {
				$template = $controller . $separator . $action;
				break;
			}
		}

		$this->loadTemplate($template, $extension);
	}

	public function loadTemplate($path, $extension) {
		# LOG
		$this->settings->log("<strong>STATE:</strong> view->loadTemplate(path = '$path', extension = '$extension')");

		# load a proxy for the model based on the extension
		if(isset(self::$ExtensionMap[$extension]))
			$this->model = new View::$ExtensionMap[$extension]($this->model);

		# use JSON template for JSONP requests
		if($extension == 'jsonp')
			$extension = 'json';

		$template = $this->basePath . $path . '.' . $extension;

		$this->template = file_get_contents($template);

		# no template was found
		if($this->template == false)
			throw new CoreException("Template file '{$template}' not found.", 404);
	}

	public abstract function render();
}