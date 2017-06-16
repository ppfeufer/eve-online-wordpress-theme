<?php
/**
 * Theme Settings
 */

namespace WordPress\Themes\EveOnline\Admin;

use WordPress\Themes\EveOnline;

\defined('ABSPATH') or die();

class ThemeSettings {
	private $eveApi = null;
	private $metaSlider = null;
	private $themeOptions = null;

	private $settingsApi = null;
	private $settingsFilter = null;

	public function __construct() {
		$this->eveApi = new EveOnline\Helper\EveApiHelper;
		$this->metaSlider = new EveOnline\Plugins\Metaslider(false);
		$this->themeOptions = \get_option('eve_theme_options', EveOnline\Helper\ThemeHelper::getThemeDefaultOptions());

		// trigger the settings API
		$this->fireSettingsApi();
	} // END public function __construct()

	private function fireSettingsApi() {
		$this->settingsFilter = 'register_eve_online_theme_settings';
		$this->settingsApi = new SettingsApi($this->settingsFilter, EveOnline\Helper\ThemeHelper::getThemeDefaultOptions());
		$this->settingsApi->init();

		\add_filter($this->settingsFilter, array($this, 'renderSettingsPage'));
	} // END public function fireSettingsApi()

	public function renderSettingsPage() {
		$themeOptionsPage['eve-online-theme-settings'] = array(
			'type' => 'theme',
			'menu_title' => \__('Options', 'eve-online'),
			'page_title' => \__('EVE Online Theme Settings', 'eve-online'),
			'option_name' => 'eve_theme_options',
			'tabs' => array(
				/**
				 * general settings tab
				 */
				'general-settings' => array(
					'tab_title' => \__('General Settings', 'eve-online'),
					'tab_description' => \__('General Theme Settings', 'eve-online'),
					'fields' => $this->getGeneralTabFields()
				),

				/**
				 * background settings tab
				 */
				'background-settings' => array(
					'tab_title' => \__('Background Settings', 'eve-online'),
					'tab_description' => \__('Background Settings', 'eve-online'),
					'fields' => $this->getBackgroundTabFields()
				),

				/**
				 * slider settings tab
				 */
				'slider-settings' => array(
					'tab_title' => \__('Slider Settings', 'eve-online'),
					'tab_description' => \__('Slider Settings', 'eve-online'),
					'fields' => $this->getSliderTabFields()
				),

				/**
				 * performance settings tab
				 */
				'performance-settings' => array(
					'tab_title' => \__('Performance Settings', 'eve-online'),
					'tab_description' => \__('Performance Settings', 'eve-online'),
					'fields' => $this->getPerformanceTabFields()
				)
			)
		);

		if($this->metaSlider->metasliderPluginExists() === false) {
			$themeOptionsPage['eve-online-theme-settings']['tabs']['slider-settings']['fields']['slider-warning']  = array(
				'type' => 'custom',
				'title' => \__('Meta Slider Warning', 'eve-online'),
				'content' => \sprintf(\__('Please make sure you have the %1$s plugin installed and activated.', 'eve-online'), '<a href="https://de.wordpress.org/plugins/ml-slider/" target="_blank">Meta Slider</a>'),
				'callback' => null
			);
		} // END if($this->metaSlider->metasliderPluginExists() === false)

		if(\preg_match('/development/', \APPLICATION_ENV)) {
			$themeOptionsPage['eve-online-theme-settings']['tabs']['development'] = array(
				'tab_title' => \__('Development Infos', 'eve-online'),
				'tab_description' => \__('Delevopment Information', 'eve-online'),
				'fields' => $this->getDevelopmentTabFields()
			);
		} // END if(\preg_match('/development/', \APPLICATION_ENV))

		return $themeOptionsPage;
	} // END public function renderSettingsPage()

	private function getGeneralTabFields() {
		return array(
			'type' => array(
				'type' => 'select',
				'choices' => array(
					'alliance' => \__('Alliance', 'eve-online'),
					'corporation' => \__('Corporation', 'eve-online')
				),
				'empty' => \__('Please Select', 'eve-online'),
				'title' => \__('Entity Type', 'eve-online'),
				'description' => \__('Is this a Corporation or an Alliance Website?', 'eve-online')
			),
			'name' => array(
				'type' => 'text',
				'title' => \__('Entity Name', 'eve-online'),
				'description' => \sprintf(\__('The Name of your Corp/Alliance %1$s', 'eve-online'),
					(!empty($this->themeOptions['name'])) ? '</p></td></tr><tr><th>' . \__('Your Logo', 'eve-online') . '</th><td>' . $this->eveApi->getEntityLogoByName($this->themeOptions['name'], false) : ''
				)
			),
			'corp_logos_in_menu' => array(
				'type' => 'checkbox',
				'title' => \__('Corp Logos', 'eve-online'),
				'choices' => array(
					'show' => \__('Show corp logos in menu for corp pages.', 'eve-online')
				),
				'description' => \__('Only available if you are running an alliance website, so you can have the corp logos in your "Our Corporations" menu. (Default: on)', 'eve-online')
			),
			'navigation' => array(
				'type' => 'checkbox',
				'title' => \__('Navigation', 'eve-online'),
				'choices' => array(
//					'sticky' => \__('Sticky Navigation', 'eve-online'),
					'even_cells' => \__('Even navigation cells in main navigation', 'eve-online'),
				),
				'description' => \__('Transforms the main navigation into even cells instead of a random width. (Default: off)', 'eve-online')
			),
			'post_meta' => array(
				'type' => 'checkbox',
				'title' => \__('Post Meta', 'eve-online'),
				'choices' => array(
					'show' => \__('Show post meta (categories and all that stuff) in article loop and article view.', 'eve-online')
				),
				'description' => \__('If checked the post meta information, such as categories, publish time and author will be displayed in article loop and article view. (Default: off)', 'eve-online')
			),
		);
	} // END private function getGeneralTabFields()

	private function getBackgroundTabFields() {
		return array(
			'use_background_image' => array(
				'type' => 'checkbox',
				'title' => \__('Use Background Image', 'eve-online'),
				'choices' => array(
					'yes' => \__('Yes, I want to use background images on this website.', 'eve-online')
				),
				'description' => \__('If this option is checked, the website will use your selected (down below) background image instead of a simple colored background. (Default: on)', 'eve-online')
			),
			'background_image' => array(
				'type' => 'radio',
				'choices' => EveOnline\Helper\ThemeHelper::getDefaultBackgroundImages(true),
				'empty' => \__('Please Select', 'eve-online'),
				'title' => \__('Background Image', 'eve-online'),
				'description' => \__('Select one of the default Background images ... (Default: Amarr)', 'eve-online'),
				'align' => 'horizontal'
			),
			'background_image_upload' => array(
				'type' => 'image',
				'title' => \__('', 'eve-online'),
				'description' => \__('... or upload your own', 'eve-online')
			),
			'background_color' => array(
				'type' => 'colorpicker',
				'title' => \__('Background Colour', 'eve-online'),
				'description' => \__('The contents background colour. If empty, the default (white) will be taken.', 'eve-online')
			)
		);
	} // END private function getBackgroundTabFields()

	private function getSliderTabFields() {
		return array(
			/**
			 * !!!
			 * Do NOT forget to change the options key in
			 * metaslider-plugin as well
			 * !!!
			 */
			'default_slider' => array(
				'type' => 'select',
				'title' => \__('Default Slider on Front Page', 'eve-online'),
				'choices' => $this->metaSlider->metasliderGetOptions(),
				'description' => ($this->metaSlider->metasliderPluginExists()) ? \__('Select the default slider for your front page', 'eve-online') : \sprintf(\__('Please make sure you have the %1$s plugin installed and activated.', 'eve-online'), '<a href="https://wordpress.org/plugins/ml-slider/">Meta Slider</a>')
			),
			'default_slider_on' => array(
				'type' => 'checkbox',
				'title' => \__('Pages with Slider', 'eve-online'),
				'choices' => array(
					'frontpage_only' => \__('Show only on front page.', 'eve-online')
				),
				'description' => \__('Show this slider only on front page in case no other slider is defined. (Default: on)', 'eve-online')
			),
		);
	} // END private function getSliderTabFields()

	private function getPerformanceTabFields() {
		return array(
			'minify_html_output' => array(
				'type' => 'checkbox',
				'title' => \__('HTML Output', 'eve-online'),
				'choices' => array(
					'yes' => \__('Minify HTML output?', 'eve-online')
				),
				'description' => \__('This feature is EXPERIMENTAL!<br>By minifying the HTML output you might boost your websites performance. NOTE: this may not work on every server, so if you experience issues, turn this option off again!', 'eve-online')
			),
			'cache' => array(
				'type' => 'checkbox',
				'title' => \__('Cache', 'eve-online'),
				'choices' => array(
					'remote-image-cache' => \__('Use imagecache for images fetched from CCP\'s image server', 'eve-online')
				),
				'description' => \__('If checked the images from CCP\'s image server will be cached locally. (Default: on)', 'eve-online')
			),
			'cron' => array(
				'type' => 'checkbox',
				'title' => \__('Cron Jobs', 'eve-online'),
				'choices' => array(
					'cleanupThemeImageCache' => \__('Use a cronjob to clear the imagecache once a day.', 'eve-online')
				),
				'description' => \__('If checked a WordPress cron will be initialized to clean up the image cache once a day. (Default: off)', 'eve-online')
			),
		);
	} // END private function getSliderTabFields()

	private function getDevelopmentTabFields() {
		return array(
			'eve_theme_options_sane' => array(
				'type' => 'custom',
				'content' => '<pre>' . \print_r(EveOnline\Helper\ThemeHelper::getThemeDefaultOptions(), true) . '</pre>',
				'title' => \__('Options Array<br>(sane from functions.php)', 'eve-online'),
				'callback' => null,
				'description' => \__('This are the sane options defined in functions.php via <code>\WordPress\Themes\EveOnline\Helper\ThemeHelper::getThemeDefaultOptions()</code>', 'eve-online')
			),
			'eve_theme_options_from_db' => array(
				'type' => 'custom',
				'content' => '<pre>' . \print_r(\get_option('eve_theme_options'), true) . '</pre>',
				'title' => \__('Options Array<br>(from DB)', 'eve-online'),
				'callback' => null,
				'description' => \__('This are the options from our database via <code>\get_option(\'eve_theme_options\')</code>', 'eve-online')
			),
			'eve_theme_options_merged' => array(
				'type' => 'custom',
				'content' => '<pre>' . \print_r(\get_option('eve_theme_options', EveOnline\Helper\ThemeHelper::getThemeDefaultOptions()), true) . '</pre>',
				'title' => \__('Options Array<br>(merged / used for Theme)', 'eve-online'),
				'callback' => null,
				'description' => \__('This are the options used for the theme via <code>\get_option(\'eve_theme_options\', \WordPress\Themes\EveOnline\Helper\ThemeHelper::getThemeDefaultOptions())</code>', 'eve-online')
			)
		);
	} // END private function getDevelopmentTabFields()
} // END class ThemeSettings
