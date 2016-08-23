<?php
/**
 * Encoding Email Adresses in Content
 */

namespace WordPress\Themes\EveOnline\Plugins;

\defined('ABSPATH') or die();

class EncodeEmailAddresses {
	private $filterPriority = 1000;

	public function __construct() {
		/**
		 * Register filters to encode plain email addresses in posts, pages, excerpts,
		 * comments and text widgets.
		 */
		foreach(array('the_content', 'the_excerpt', 'widget_text', 'comment_text', 'comment_excerpt') as $filter) {
			add_filter($filter, array($this, 'encodeMails'), $this->filterPriority);
		} // END foreach(array('the_content', 'the_excerpt', 'widget_text', 'comment_text', 'comment_excerpt') as $filter)
	} // END public function __construct()

	public function encodeMails($content) {
		// abort if `$content` isn't a string
		if(!\is_string($content)) {
			return $content;
		} // END if(!\is_string($content))

		// abort if `eve-encode-email-address_at-sign-check` is true and `$content` doesn't contain a @-sign
		if(\apply_filters('eve-encode-email-address_at-sign-check', true ) && \strpos($content, '@') === false) {
			return $content;
		} // END if(\apply_filters('eve-encode-email-address_at-sign-check', true ) && \strpos($content, '@') === false)

		// override encoding function with the 'eve-encode-email-address_metod' filter
		$method = \apply_filters('eve-encode-email-address_metod', array($this, 'encodeString'));

		// override regex pattern with the 'eve-encode-email-address_regexp' filter
		$regexp = \apply_filters(
			'eve-encode-email-address_regexp',
			'{
				(?:mailto:)?
				(?:
					[-!#$%&*+/=?^_`.{|}~\w\x80-\xFF]+
				|
					".*?"
				)
				\@
				(?:
					[-a-z0-9\x80-\xFF]+(\.[-a-z0-9\x80-\xFF]+)*\.[a-z]+
				|
					\[[\d.a-fA-F:]+\]
				)
			}xi'
		);

		return \preg_replace_callback(
			$regexp,
			\create_function(
				'$matches',
				'return ' . __CLASS__ . '::' . $method[1] . '($matches[0]);'
			),
			$content
		);
	} // END public function encodeMails($content)

	public static function encodeString($string) {
		$chars = \str_split($string);
		$seed = \mt_rand(0, (int) \abs(\crc32($string) / \strlen($string)));

		foreach($chars as $key => $char) {
			$ord = \ord($char);

			if($ord < 128) { // ignore non-ascii chars
				$r = ($seed * (1 + $key)) % 100; // pseudo "random function"

				if($r > 60 && $char != '@') {
					// plain character (not encoded), if not @-sign
					;
				} elseif($r < 45) {
					$chars[$key] = '&#x' . \dechex($ord) . ';'; // hexadecimal
				} else {
					$chars[$key] = '&#' . $ord . ';'; // decimal (ascii)
				} // END if($r > 60 && $char != '@')
			} // END if($ord < 128)
		} // END foreach($chars as $key => $char)

		return \implode('', $chars);
	} // END public static function encodeString(&$string)
} // END class Ti_Encode_Email_Addresses

new EncodeEmailAddresses;