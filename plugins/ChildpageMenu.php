<?php

namespace WordPress\Themes\EveOnline\Plugins;

use WordPress\Themes\EveOnline;

class ChildpageMenu {
	/**
	 * constructor
	 */
	public function __construct() {
		require_once(\get_theme_file_path('/plugins/widgets/ChildpageMenuWidget.php'));

		$this->initPlugin();
	} // END public function __construct()

	/**
	 * initialze the plugin
	 */
	private function initPlugin() {
		// frontend actions
		if(!\is_admin()) {
//			$this->addStyle();
		} // END if(!\is_admin())

		$this->initWidget();
	} // END private function initPlugin()

	/**
	 * initialze the widget
	 */
	public function initWidget() {
		\add_action('widgets_init', \create_function('', 'return register_widget("WordPress\Themes\EveOnline\Plugins\Widgets\ChildpageMenuWidget");'));
	} // END public function initWidget()

	/**
	 * adding the css
	 */
//	public function addStyle() {
//		if(!\is_admin()) {
//			\add_action('wp_enqueue_scripts', array($this, 'enqueueStyle'));
//		} // END if(!\is_admin())
//	} // END public function addStyle()

	/**
	 * enqueue the style
	 */
//	public function enqueueStyle() {
//		if(\preg_match('/development/', \APPLICATION_ENV)) {
//			\wp_enqueue_style('chilpages-menu-widget', \get_theme_file_uri('/plugins/css/childpages-menu-widget.css'));
//		} else {
//			\wp_enqueue_style('chilpages-menu-widget', \get_theme_file_uri('/plugins/css/childpages-menu-widget.min.css'));
//		} // END if(\preg_match('/development/', \APPLICATION_ENV))
//	} // END public function enqueueStyle()
} // END class ChildpageMenu
