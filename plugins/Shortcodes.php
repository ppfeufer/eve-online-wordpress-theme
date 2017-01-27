<?php
/**
 * Theme Shortcodes
 */

namespace WordPress\Themes\EveOnline\Plugins;

\defined('ABSPATH') or die();

class Shortcodes {
	public function __construct() {
//		$this->changeWpAuto();
		$this->addShortcodesToWidgets();
		$this->registerShortcodes();
	}

	public function registerShortcodes() {
		\add_shortcode('two_columns_one', array($this, 'shortcodeTwoColumnsOne'));
		\add_shortcode('three_columns_one', array($this, 'shortcodeThreeColumnsOne'));
		\add_shortcode('three_columns_two', array($this, 'shortcodeThreeColumnsTwo'));
		\add_shortcode('four_columns_one', array($this, 'shortcodeFourColumnsOne'));
		\add_shortcode('four_columns_two', array($this, 'shortcodeFourColumnsTwo'));
		\add_shortcode('four_columns_three', array($this, 'shortcodeFourColumnsThree'));
		\add_shortcode('divider', array($this, 'shortcodeDivider'));
		\add_shortcode('button', array($this, 'shortcodeButton'));
	}

	public function shortcodeTwoColumnsOne($atts, $content = null) {
		return '<div class="col-md-6">' . $this->removeAutopInShortcode($content) . '</div>';
	}

	public function shortcodeThreeColumnsOne($atts, $content = null) {
		return '<div class="col-md-4">' . $this->removeAutopInShortcode($content) . '</div>';
	}

	public function shortcodeThreeColumnsTwo($atts, $content = null) {
		return '<div class="col-md-8">' . $this->removeAutopInShortcode($content) . '</div>';
	}

	public function shortcodeFourColumnsOne($atts, $content = null) {
		return '<div class="col-md-3">' . $this->removeAutopInShortcode($content) . '</div>';
	}

	public function shortcodeFourColumnsTwo($atts, $content = null) {
		return '<div class="col-md-6">' . $this->removeAutopInShortcode($content) . '</div>';
	}

	public function shortcodeFourColumnsThree($atts, $content = null) {
		return '<div class="col-md-9">' . $this->removeAutopInShortcode($content) . '</div>';
	}

	public function shortcodeDivider($atts, $content = null) {
		return '<div class="divider"></div>';
	}

	public function shortcodeButton($atts, $content = null) {
		$args = \shortcode_atts(
			array(
				'id' => '',
				'videolist' => '',
				'classes' => ''
			),
			$atts
		);

		$type = $args['type'];
		$link = $args['link'];
		$target = $args['target'];
		$size = $args['size'];

		$type = ($type) ? ' btn-' . $type : '';
		$size = ($size) ? ' btn-' . $size : '';
		$output = '<a class="btn ' . $type . $size . '" href="' . $link . '" target="' . $target . '"><span>' . \do_shortcode($content) . '</span></a>';

		return $output;
	}

	public function changeWpAuto() {
		\remove_filter('the_content', 'wpautop');
		\add_filter('the_content', 'wpautop', 99);
		\add_filter('the_content', 'shortcode_unautop', 100);
	}

	public function addShortcodesToWidgets() {
		\add_filter('widget_text', 'do_shortcode');
	}

	public function removeAutopInShortcode($content) {
		$content = \do_shortcode(\shortcode_unautop($content));
		$content = \preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $content);

		return $content;
	}
}