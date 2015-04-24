<?php
/**
 * AutoLoader.php
 *
 * PHP version 5.3
 *
 * @package   Ra
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra;

require_once 'Object.php';
require_once 'Settings.php';

class AutoLoader {
	protected $locations = array();
	protected $settings;

	public function __construct(Settings $settings = null) {
		spl_autoload_register(array($this, '__autoload'));

		$this->settings = $settings ? $settings : new Settings();

		$this->addPath($this->settings->raRoot);
		$this->addPath($this->settings->components);
	}

	public function addPath($path) {
		$this->locations[] = '/' . trim(realpath($path), '/');
	}

	public function addComponent($component, Application $application, $hasNamespace = false) {
		$path = $this->settings->components . $component . '/';

		if(!$hasNamespace) {
			$this->addPath($path);
		}

		$this->loadFile($path . 'main.php', $application);
	}

	public function __autoload($class) {
		$this->settings->log($this->locations);
			$class = str_replace('Ra\\', '', $class);
			$class = str_replace('\\', '/', $class);

		foreach($this->locations as $location) {

			if($this->loadFile($location . '/' . $class . '.php'))
				return;
		}

		# LOG
		$this->settings->log("<strong>ERROR:</strong> Class '$class' could not be found.");
	}

	protected function loadFile($file, Application $application = null) {
		$settings = $this->settings;
		$loader = $this;

		if(file_exists($file)) {
			# LOG
			$this->settings->log("CLASS $file");

			require_once $file;
			return true;
		}

		return false;
	}
}