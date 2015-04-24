<?php

namespace Ra\Formatting;

class JSONFormat extends FormatProxy {
	protected function initialise() {
		header('Content-type: application/json');
	}

	protected function format($input) {
		# numeric, so return it
		if(preg_match('/^[+-]?\d+(\.\d+)?$/', $input))
			return $input;

		# escape quotes and new line characters
		$input = preg_replace("/\"/", "\\\"", $input);
		$input = str_replace("\n", '\\n', $input);
		$input = str_replace("\r", '\\r', $input);
		$input = str_replace("\t", '\\t', $input);

		return $input;
	}

	public function __toString() {
		return json_encode($this->toObject());
	}
}
