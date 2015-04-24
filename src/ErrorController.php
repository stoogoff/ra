<?php
/**
 * ErrorController.php
 *
 * PHP version 5.3
 *
 * @package   Ra
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra;

class ErrorController extends Controller {
	public function view($error) {
		return $this->render();
	}
}