<?php
/**
 * CoreString.php
 *
 * String helpers.
 *
 * PHP version 5.3
 *
 * @package   Ra
 * @author    Stoo Goff
 * @copyright 2007 (c) Stoo Goff
 * @license   MIT <http://opensource.org/licenses/MIT>
 */
namespace Ra;

class CoreString {
	public static function Summary($text, $length = 300) {
		# we have lots of text with HTML so just get the first paragraph
		if(preg_match('/<p[^>]*>/', $text)) {
			preg_match_all('@<p[^>]*>(.+)</?p>@Us', $text, $matches);

			foreach($matches[1] as $key => $match) {
				if(preg_replace('/<[^>]+>/', '', $match) != '') {
					$text = $match;
					break;
				}
			}

			return $text;
		}

		if(!$length)
			return $text;

		# it's short so nothing to do
		if(strlen($text) <= $length)
			return $text;

		while(preg_match('/[a-zA-Z-]/', $text[$length]))
			$length--;

		return preg_replace('/[^a-zA-Z-]+$/', '', substr($text, 0, $length)) . '&hellip;';
	}

	public static function ToUrl($string) {
		return preg_replace('/\s+/', '-', preg_replace('/[^\sa-z0-9]*/', '', trim(strtolower($string))));
	}

	public static function StripTags($string) {
		return preg_replace('/<[^>]+>/', '', $string);
	}

	public static function JoinPath($root, $path) {
		return '/' . trim($root, '/') . '/' . trim($path, '/') . '/';
	}
}