<?php

namespace Ra\Formatting;

class JSONPFormat extends JSONFormat {
	protected function initialise() {
		header('Content-type: application/javascript');
	}

	public function preRender($content) {
		$callback = isset($_GET['callback']) ? $_GET['callback'] : 'callback';

		return $callback . '(' . $content . ')';
	}
}
