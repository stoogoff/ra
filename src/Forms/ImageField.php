<?php
/**
 * ImageField.php
 *
 * PHP version 5.3
 *
 * @package   Ra\Forms
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra\Forms;

class ImageField extends UploadField {
	public function __construct($label, $key, $filePath, $sizes = array(), $value = '') {
		parent::__construct($label, $key, $filePath, $value);

		$this->sizes = $sizes;

		if(!in_array(100, $this->sizes)) {
			$this->sizes[] = 100;
		}
	}

	public function render() {
		$value = $this->getValue();

		if($value) {
			$path = substr($this->filePath, strpos($this->filePath, '/www') + 4);
			$file = str_replace('.', '-100.', $value);
			$value = "<p class='text-muted'>Current image: $value</p><p><img src='$path$file' /> <input type='hidden' name='{$this->key}' value=\"" . htmlspecialchars($value) . "\" /></p>";
		}

		return $this->label() . $value . "<input name='{$this->key}' type='file' accepts='image/*' />";
	}

	public function setValue($value) {
		if(isset($value['name'])) {
			$this->value = $value['name'];
			$fullPath = $this->filePath . $value['name'];

			# create path if it doesn't exist
			if(!file_exists(dirname($fullPath))) {
				mkdir(dirname($fullPath), 0777, true);
			}

			move_uploaded_file($value['tmp_name'], $fullPath);

			# save resized copies
			if(count($this->sizes) > 0) {
				list($originalWidth, $originalHeight, $imgType) = getimagesize($fullPath);
				
				switch($value['type']) {
					case 'image/jpeg':
						$type = new JpegType();
						break;
					case 'image/png':
						$type = new PngType();
						break;
				}

				$info = pathinfo($fullPath);
				$originalImage = $type->create($fullPath);

				foreach($this->sizes as $width) {
					$newPath = "{$info['dirname']}/{$info['filename']}-$width.{$info['extension']}";
					$ratio  = $originalWidth / $originalHeight;
					$height = $width / $ratio;

					$newImage = imagecreatetruecolor($width, $height);

					imagecopyresampled($newImage, $originalImage, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

					$type->save($newImage, $newPath);

					imagedestroy($newImage);
				}

				imagedestroy($originalImage);
			}
		}
		else {
			$this->value = $value;
		}
	}
}

interface IImageType {
	function create($path);
	function save($image, $path);
	function mimetype();
}

class JpegType implements IImageType {
	public function create($path) {
		return imagecreatefromjpeg($path);
	}
	public function save($image, $path) {
		return imagejpeg($image, $path, 100);
	}
	public function mimetype() {
		return "image/jpeg";
	}
}

class PngType implements IImageType {
	public function create($path) {
		return imagecreatefrompng($path);
	}
	public function save($image, $path) {
		return imagepng($image, $path, 9);
	}
	public function mimetype() {
		return "image/png";
	}
}

