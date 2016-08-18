<?php
/**
 * Helper for all stuff we may do with strings that is not covered by WordPress
 */

namespace WordPress\Themes\EveOnline\Helper;

\defined('ABSPATH') or die();

class String {
	public function cutString($string, $pos) {
		$string = strip_tags($string);

		if($pos < \strlen($string)) {
			$text = \substr($string, 0, $pos);

			if(($strrpos = \strrpos($text,' ')) !== false) {
				$text = \substr($text, 0, $strrpos);
			} // END if(($strrpos = strrpos($text,' ')) !== false)

			$string = $text . ' [...]';
		} // END if($pos < strlen($string))

		return $string;
	} // END function cutString($string, $pos)

	/**
	 * converts a hex color string into an array with it's respective rgb(a) values
	 *
	 * @param string $hex
	 * @param string $alpha
	 * @return array
	 */
	public function hextoRgb($hex, $alpha = false) {
		$hex = \str_replace('#', '', $hex);

		if(\strlen($hex) == 6) {
			$rgb['r'] = \hexdec(\substr($hex, 0, 2));
			$rgb['g'] = \hexdec(\substr($hex, 2, 2));
			$rgb['b'] = \hexdec(\substr($hex, 4, 2));
		} elseif(\strlen($hex) == 3) {
			$rgb['r'] = \hexdec(\str_repeat(\substr($hex, 0, 1), 2));
			$rgb['g'] = \hexdec(\str_repeat(\substr($hex, 1, 1), 2));
			$rgb['b'] = \hexdec(\str_repeat(\substr($hex, 2, 1), 2));
		} else {
			$rgb['r'] = '0';
			$rgb['g'] = '0';
			$rgb['b'] = '0';
		} // END if(\strlen($hex) == 6)

		if($alpha !== false) {
			$rgb['a'] = $alpha;
		} // END if($alpha !== false)

		return $rgb;
	 } // END public function hextoRgb($hex, $alpha = false)
} // END class String