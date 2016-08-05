<?php
namespace WordPress\Themes\EveOnline\Admin;

use WordPress\Themes\EveOnline;

\defined('ABSPATH') or die();

class ThemeSettings {
	private $eveApi = null;
	private $metaSlider = null;
	private $themeOptions = null;

	public function __construct() {
		$this->eveApi = new EveOnline\Helper\EveApi;
		$this->metaSlider = new EveOnline\Plugins\Metaslider(false);
		$this->themeOptions = \get_option('eve_theme_options', EveOnline\eve_get_options_default());

		\add_filter('register_eve_online_theme_settings_api', array($this, 'renderSettingsPage'));
	} // END public function __construct()

	public function renderSettingsPage() {
		$themeOptionsPage['eve-online-theme-settings'] = array(
			'type' => 'theme',
			'menu_title' => \__('Options', 'eve-online'),
			'page_title' => \__('EVE Online Theme Settings', 'eve-online'),
			'option_name' => 'eve_theme_options',
			'tabs' => array(
				/* general settings tab */
				'general-settings' => array(
					'tab_title' => \__('General Settings', 'eve-online'),
					'tab_description' => \__('General Theme Settings', 'eve-online'),
					'fields' => array(
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
						'show_corp_logos' => array(
							'type' => 'checkbox',
							'title' => \__('Corp Logos', 'eve-online'),
							'choices' => array(
								'show' => \__('Show corp logos in menu for corp pages.', 'eve-online')
							),
							'description' => \__('Only available if you are running an alliance website, so you can have the corp logos in your "Our Corporations" menu.', 'eve-online')
						)
					)
				),
				'slider-settings' => array(
					'tab_title' => \__('Slider Settings', 'eve-online'),
					'tab_description' => \__('Slider Settings', 'eve-online'),
					'fields' => array(
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
							'description' => \__('Show this slider only on front page in case no other slider is defined.', 'eve-online')
						),
					)
				)
			)
		);

		if(\preg_match('/development/', \APPLICATION_ENV)) {
			$themeOptionsPage['eve-online-theme-settings']['tabs']['development'] = array(
				'tab_title' => \__('Development Infos', 'eve-online'),
				'tab_description' => \__('Delevopment Information', 'eve-online'),
				'fields' => array(
					'eve_theme_options_sane' => array(
						'type' => 'custom',
						'content' => '<pre>' . \print_r(EveOnline\eve_get_options_default(), true) . '</pre>',
						'title' => \__('Options Array<br>(sane from functions.php)', 'eve-online'),
						'callback' => null,
						'description' => \__('This are the sane options defined in functions.php via <code>EveOnline\eve_get_options_default()</code>', 'eve-online')
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
						'content' => '<pre>' . \print_r(\get_option('eve_theme_options', EveOnline\eve_get_options_default()), true) . '</pre>',
						'title' => \__('Options Array<br>(merged / used for Theme)', 'eve-online'),
						'callback' => null,
						'description' => \__('This are the options used for the theme via <code>\get_option(\'eve_theme_options\', \WordPress\Themes\EveOnline\eve_get_options_default())</code>', 'eve-online')
					)
				)
			);
		}

		return $themeOptionsPage;
	} // END public function renderSettingsPage()
} // END class ThemeSettings

new ThemeSettings();