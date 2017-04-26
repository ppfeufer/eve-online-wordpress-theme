<?php

namespace WordPress\Themes\EveOnline\Plugins;

use WordPress\Themes\EveOnline;

class HtmlMinify {
	// Settings
	protected $compress_css = true;
	protected $compress_js = true;
	protected $info_comment = true;
	protected $remove_comments = true;

	// Variables
	protected $html;

	public function __construct($html) {
		if(!empty($html)) {
			$this->parseHTML($html);
		} // END if(!empty($html))
	} // END public function __construct($html)

	public function __toString() {
		return $this->html;
	} // END public function __toString()

	protected function bottomComment($raw, $compressed) {
		$raw = \strlen($raw);
		$compressed = \strlen($compressed);
		$savings = ($raw - $compressed) / $raw * 100;
		$savings = \round($savings, 2);

		return '<!--HTML compressed, size saved ' . $savings . '%. From ' . $raw . ' bytes, now ' . $compressed . ' bytes-->';
	} // END protected function bottomComment($raw, $compressed)

	protected function minifyHTML($html) {
		// Tese have to be removed right away ....
		if($this->remove_comments) {
			// Remove any HTML comments, except MSIE conditional comments
			$html = \preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $html);

			// Remove any JS single line comments starting with //
			$html = \preg_replace('/\/\/ (.*)\n/', ' ', $html);
		} // END if($this->remove_comments)

		$pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
		\preg_match_all($pattern, $html, $matches, \PREG_SET_ORDER);

		$overriding = false;
		$raw_tag = false;
		// Variable reused for output
		$html = '';

		foreach($matches as $token) {
			$tag = (isset($token['tag'])) ? \strtolower($token['tag']) : null;

			$content = $token[0];

			if(\is_null($tag)) {
				if(!empty($token['script'])) {
					$strip = $this->compress_js;
				} else if(!empty($token['style'])) {
					$strip = $this->compress_css;
				} else if($content == '<!--wp-html-compression no compression-->') {
					$overriding = !$overriding;

					// Don't print the comment
					continue;
				} else if($this->remove_comments) {
					if(!$overriding && $raw_tag != 'textarea') {
						// Remove any HTML comments, except MSIE conditional comments
						$content = \preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);

						// Remove any JS single or multiline comments like /* comment */
						$content = \preg_replace('/\/\*(.*)\*\//', ' ', $content);
					}
				}
			} else {
				if($tag == 'pre' || $tag == 'textarea') {
					$raw_tag = $tag;
				} else if($tag == '/pre' || $tag == '/textarea') {
					$raw_tag = false;
				} else {
					if($raw_tag || $overriding) {
						$strip = false;
					} else {
						$strip = true;

						// Remove any empty attributes, except:
						// action, alt, content, src
						$content = \preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);

						// Remove any space before the end of self-closing XHTML tags
						// JavaScript excluded
						$content = \str_replace(' />', '/>', $content);
					}
				}
			}

			if($strip) {
				$content = $this->removeWhiteSpace($content);
			} // END if($strip)

			$html .= $content;
		} // END foreach($matches as $token)

		return $html;
	} // END protected function minifyHTML($html)

	public function parseHTML($html) {
		$this->html = $this->minifyHTML($html);

		if($this->info_comment) {
			$this->html .= "\n" . $this->bottomComment($html, $this->html);
		} // END if($this->info_comment)
	} // END public function parseHTML($html)

	protected function removeWhiteSpace($str) {
		$str = \str_replace("\t", ' ', $str);
		$str = \str_replace("\n", '', $str);
		$str = \str_replace("\r", '', $str);

		while(\stristr($str, '  ')) {
			$str = \str_replace('  ', ' ', $str);
		} // END while(\stristr($str, '  '))

		return $str;
	} // END protected function removeWhiteSpace($str)
} // END class HtmlMinify

function eve_html_compression_finish($html) {
	return new HtmlMinify($html);
} // END function eve_html_compression_finish($html)

function eve_html_compression_start() {
	\ob_start('\\WordPress\\Themes\\EveOnline\\Plugins\\eve_html_compression_finish');
} // END function eve_html_compression_start()

$themeOptions = \get_option('eve_theme_options', EveOnline\Helper\ThemeHelper::getThemeDefaultOptions());

if(!empty($themeOptions['minify_html_output']['yes'])) {
	\add_action('get_header', '\\WordPress\\Themes\\EveOnline\\Plugins\\eve_html_compression_start');
} // END if(!empty($themeOptions['minify_html_output']['yes']))
