<?php
/**
 * EVE Killboard Theme Plugin for fetching Killmails from EDK killboard software
 */

namespace WordPress\Themes\EveOnline\Plugins;

use WordPress\Themes\EveOnline;

\defined('ABSPATH') or die();

class Killboard {
	private $themeSettings = null;
	private $pluginSettings = null;
//	private $kbDB = null;

	private $settingsApi = null;
	private $settingsFilter = null;

	/**
	 * constructor
	 */
	public function __construct($init = true) {
		if($init === true) {
			$this->initPlugin();
		}
	} // END public function __construct()

	private function initPlugin() {
		$this->themeSettings = \get_option('eve_theme_options', EveOnline\eve_get_options_default());
		$this->pluginSettings = \get_option('eve_theme_killboard_plugin_options', $this->getDefaultPluginOptions());

		// backend actions
		if(\is_admin()) {
			$this->fireSettingsApi();
		} // END if(\is_admin())

		// frontend actions
		if(!\is_admin()) {
			$this->addStyle();
		} // END if(!\is_admin())

		// common actions
		$this->initWidget();
	}

	public function initWidget() {
		\add_action('widgets_init', \create_function('', 'return register_widget("WordPress\Themes\EveOnline\Plugins\Widgets\KillboardWidget");'));
	} // END public function initWidget()

	public function addStyle() {
		if(!\is_admin()) {
			\add_action('wp_enqueue_scripts', array($this, 'enqueueStyle'));
		} // END if(!\is_admin())
	} // END public function addStyle()

	public function enqueueStyle() {
		if(\preg_match('/development/', \APPLICATION_ENV)) {
			\wp_enqueue_style('eve-killboard', \get_template_directory_uri() . '/plugins/css/killboard-widget.css');
		} else {
			\wp_enqueue_style('eve-killboard', \get_template_directory_uri() . '/plugins/css/killboard-widget.min.css');
		} // END if(\preg_match('/development/', \APPLICATION_ENV))
	} // END public function enqueueStyle()

	public function getDefaultPluginOptions() {
		$defaultOptions = array(
			// generel settings tab
			'number_of_kills' => 5,
//			'show_losses' => array(
//				'yes' => 'yes'
//			),
			'killboard_db_host' => 'localhost',
			'killboard_db_name' => '',
			'killboard_db_user' => '',
			'killboard_db_password' => '',
			'killboard_domain' => ''
		);

		return \apply_filters('eve_theme_killboard_plugin_options', $defaultOptions);
	} // END private function getDefaultPluginOptions()

	/**
	 * Our Plugin settings
	 */
	private function fireSettingsApi() {
		$this->settingsFilter = 'register_eve_online_theme_killboard_plugin_settings';
		$this->settingsApi = new EveOnline\Admin\SettingsApi($this->settingsFilter, $this->getDefaultPluginOptions());
		$this->settingsApi->init();

		\add_filter($this->settingsFilter, array($this, 'renderSettingsPage'));
	} // END private function fireSettingsApi()

	public function renderSettingsPage() {
		$pluginOptionsPage['eve-online-theme-killboard-plugin-settings'] = array(
			'type' => 'plugin',
			'menu_title' => \__('Killboard Settings', 'eve-online'),
			'page_title' => \__('Killboard Settings', 'eve-online'),
			'option_name' => 'eve_theme_killboard_plugin_options',
			'tabs' => array(
				/**
				 * general settings
				 */
				'general-settings' => $this->getGeneralSettings(),

				/**
				 * database settings tab
				 */
				'database-settings' => $this->getDatabaseSettings()
			)
		);

		if(\preg_match('/development/', \APPLICATION_ENV)) {
			$pluginOptionsPage['eve-online-theme-killboard-plugin-settings']['tabs']['development'] = $this->getDevelopmentSettings();
		} // END if(\preg_match('/development/', \APPLICATION_ENV))

		return $pluginOptionsPage;
	} // END public function renderSettingsPage()

	private function getGeneralSettings() {
		return array(
			'tab_title' => \__('General Settings', 'eve-online'),
			'tab_description' => \__('Killboard General Settings', 'eve-online'),
			'fields' => $this->getGeneralTabFields()
		);
	} // END private function getGeneralSettings()s

	private function getGeneralTabFields() {
		return array(
			'number_of_kills' => array(
				'type' => 'text',
				'title' => \__('Number Of Kills', 'eve-online'),
				'description' => \__('Number of kills to show', 'eve-online'),
				'default' => 5
			),
//			'show_losses' => array(
//				'type' => 'checkbox',
//				'title' => \__('Show Losses', 'eve-online'),
//				'choices' => array(
//					'yes' => \__('Show your losses as well?', 'eve-online')
//				),
//				'default' => 'yes',
//				'description' => 'Only if you are tough enough :-P'
//			),
		);
	} // END private function getGeneralTabFields()

	private function getDatabaseSettings() {
		return array(
			'tab_title' => \__('Database Settings', 'eve-online'),
			'tab_description' => \__('Killboard Database Settings', 'eve-online'),
			'fields' => $this->getDatabaseTabFields()
		);
	} // END private function getDatabaseSettings()

	private function getDatabaseTabFields() {
		return array(
			'killboard_db_host' => array(
				'type' => 'text',
				'title' => \__('DB Host', 'eve-online'),
				'default' => 'localhost'
			),
			'killboard_db_name' => array(
				'type' => 'text',
				'title' => \__('DB Name', 'eve-online'),
			),
			'killboard_db_user' => array(
				'type' => 'text',
				'title' => \__('DB User', 'eve-online'),
			),
			'killboard_db_password' => array(
				'type' => 'password',
				'title' => \__('DB Password', 'eve-online'),
			)
		);
	} // END private function getDatabaseTabFields()

	private function getDevelopmentSettings() {
		return array(
			'tab_title' => \__('Development Infos', 'eve-online'),
			'tab_description' => \__('Delevopment Information', 'eve-online'),
			'fields' => $this->getDevelopmentTabFields()
		);
	}

	private function getDevelopmentTabFields() {
		return array(
			'plugin_options_sane' => array(
				'type' => 'custom',
				'content' => '<pre>' . \print_r($this->getDefaultPluginOptions(), true) . '</pre>',
				'title' => \__('Options Array<br>(sane from functions.php)', 'eve-online'),
				'callback' => null,
				'description' => \__('This are the sane options defined in plugin file via <code>$this->getDefaultPluginOptions()</code>', 'eve-online')
			),
			'plugin_options_from_db' => array(
				'type' => 'custom',
				'content' => '<pre>' . \print_r(\get_option('eve_theme_killboard_plugin_options'), true) . '</pre>',
				'title' => \__('Options Array<br>(from DB)', 'eve-online'),
				'callback' => null,
				'description' => \__('This are the options from our database via <code>\get_option(\'eve_theme_killboard_plugin_options\')</code>', 'eve-online')
			),
			'plugin_options_merged' => array(
				'type' => 'custom',
				'content' => '<pre>' . \print_r(\get_option('eve_theme_killboard_plugin_options', $this->getDefaultPluginOptions()), true) . '</pre>',
				'title' => \__('Options Array<br>(merged / used for Theme)', 'eve-online'),
				'callback' => null,
				'description' => \__('This are the options used for the theme via <code>\get_option(\'eve_theme_killboard_plugin_options\', $this->getDefaultPluginOptions())</code>', 'eve-online')
			)
		);
	} // END private function getDevelopmentTabFields()
} // END class Killboard

new Killboard;