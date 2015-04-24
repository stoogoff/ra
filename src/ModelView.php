<?php

namespace Ra;

class ModelView extends View {
	protected $request;

	public function setRequest(Request $request) {
		$this->request = $request;
	}

	public function render() {
		# get an extension based on the request
		if($this->request) {
			$extension = $this->request->extension;

			# load a proxy for the model based on the extension
			if(isset(self::$ExtensionMap[$extension]))
				$this->model = new View::$ExtensionMap[$extension]($this->model);
		}

		return $this->model->__toString();
	}
}