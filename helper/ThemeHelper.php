<?php

namespace WordPress\Themes\EveOnline\Helper;

\defined('ABSPATH') or die();

class ThemeHelper {
	/**
	 * Return the current DB version used for the themes settings
	 *
	 * @return string
	 */
	public static function getThemeDbVersion() {
		return '20170203';
	} // END public static function getThemeDbVersion()

	/**
	 * Returning the theme's default options
	 *
	 * @return array
	 */
	public static function getThemeDefaultOptions() {
		$defaultOptions = array(
			// generel settings tab
			'type' => '',
			'name' => '',
			'show_corp_logos' => array(
				'show' => 'show'
			),
			'navigation_even_cells' => array(
				'yes' => ''
			),
			'show_post_meta' => array(
				'yes' => 'yes'
			),

			// background settings tab
			'use_background_image' => array(
				'yes' => 'yes'
			),
			'background_image' => 'amarr.jpg',
			'background_image_upload' => '',
			'background_color' => '',

			// slider settings tab
			'default_slider' => '',
			'default_slider_on' => array(
				'frontpage_only' => 'frontpage_only'
			),

			// performance tab
			'minifyHtmlOutput' => array(
				'yes' => ''
			),

			// footer settings tab
			'footertext' => '',
		);

		return \apply_filters('eve_theme_options', $defaultOptions);
	} // END public static function getThemeDefaultOptions()

	/**
	 * Returning some theme related data
	 *
	 * @param string $parameter
	 * @return string
	 *
	 * @link https://developer.wordpress.org/reference/functions/wp_get_theme/
	 */
	public static function getThemeData($parameter) {
		$themeData = \wp_get_theme();

		return $themeData->get($parameter);
	} // END function getThemeData($parameter)

	/**
	 * Return the theme's javascripts
	 *
	 * @return array
	 */
	public static function getThemeJavaScripts() {
		$enqueue_script = array(
			/* Html5Shiv */
			'Html5Shiv' => array(
				'handle' => 'html5shiv',
				'condition' => 'lt IE 9',
				'source' => \get_template_directory_uri() . '/js/html5.min.js',
				'deps' => '',
				'version' => '',
				'in_footer' => false
			),
			/* Respond JS */
			'Respond JS' => array(
				'handle' => 'respondJS',
				'condition' => 'lt IE 9',
				'source' => \get_template_directory_uri() . '/js/respond.min.js',
				'deps' => '',
				'version' => '',
				'in_footer' => false
			),
			/* Modernizr */
//			'Modernizr' => array(
//				'handle' => 'modernizr',
//				'source' => \get_template_directory_uri() . '/js/modernizr.min.js',
//				'source-development' => \get_template_directory_uri() . '/js/modernizr.js',
//				'deps' => '',
//				'version' => '',
//				'in_footer' => true
//			),
			/* Bootstrap's JS */
			'Bootstrap' => array(
				'handle' => 'bootstrap-js',
				'source' => \get_template_directory_uri() . '/bootstrap/js/bootstrap.min.js',
				'source-development' => \get_template_directory_uri() . '/bootstrap/js/bootstrap.js',
				'deps' => array(
					'jquery'
				),
				'version' => '3.3.7',
				'in_footer' => true
			),
			/* Bootstrap Toolkit */
			'Bootstrap Toolkit' => array(
				'handle' => 'bootstrap-toolkit',
				'source' => \get_template_directory_uri() . '/bootstrap/bootstrap-toolkit/bootstrap-toolkit.min.js',
				'source-development' => \get_template_directory_uri() . '/bootstrap/bootstrap-toolkit/bootstrap-toolkit.js',
				'deps' => array(
					'bootstrap-js'
				),
				'version' => '2.6.3',
				'in_footer' => true
			),
			/* Bootstrap Gallery */
			'Bootstrap Gallery' => array(
				'handle' => 'bootstrap-gallery-js',
				'source' => \get_template_directory_uri() . '/plugins/js/jquery.bootstrap-gallery.min.js',
				'source-development' => \get_template_directory_uri() . '/plugins/js/jquery.bootstrap-gallery.js',
				'deps' => array(
					'jquery'
				),
				'version' => \sanitize_title(self::getThemeData('Name')) . '-' . self::getThemeData('Version'),
				'in_footer' => true
			),
			/* The main JS */
			'EVE Online' => array(
				'handle' => 'eve-online-main-js',
				'source' => \get_template_directory_uri() . '/js/eve-online.min.js',
				'source-development' => \get_template_directory_uri() . '/js/eve-online.js',
				'deps' => array(
					'jquery'
				),
				'version' => \sanitize_title(self::getThemeData('Name')) . '-' . self::getThemeData('Version'),
				'in_footer' => true
			)
		);

		return $enqueue_script;
	} // END public static function getThemeJavaScripts()

	/**
	 * Return the theme's stylesheets
	 *
	 * @return array
	 */
	public static function getThemeStyleSheets() {
		$enqueue_style = array(
			/* Normalize CSS */
			'Normalize CSS' => array(
				'handle' => 'normalize',
				'source' => \get_template_directory_uri() . '/css/normalize.min.css',
				'source-development' => \get_template_directory_uri() . '/css/normalize.css',
				'deps' => array(),
				'version' => '3.0.3',
				'media' => 'all'
			),
			/* Bootstrap */
			'Bootstrap' => array(
				'handle' => 'bootstrap',
				'source' => \get_template_directory_uri() . '/bootstrap/css/bootstrap.min.css',
				'source-development' => \get_template_directory_uri() . '/bootstrap/css/bootstrap.css',
				'deps' => array(),
				'version' => '3.3.7',
				'media' => 'all'
			),
			/* Bootstrap Additional CSS */
			'Bootstrap Additional CSS' => array(
				'handle' => 'bootstrap-additional',
				'source' => \get_template_directory_uri() . '/css/bootstrap-additional.min.css',
				'source-development' => \get_template_directory_uri() . '/css/bootstrap-additional.css',
				'deps' => array(
					'bootstrap'
				),
				'version' => '3.3.7',
				'media' => 'all'
			),
			/* Google Font */
			'Google Font' => array(
				'handle' => 'google-font',
				'source' => '//fonts.googleapis.com/css?family=Amethysta',
				'deps' => array(),
				'version' => \sanitize_title(self::getThemeData('Name')) . '-' . self::getThemeData('Version'),
				'media' => 'all'
			),
			/* Theme Main CSS */
			'EVE Online Theme Styles' => array(
				'handle' => 'eve-online',
				'source' => \get_template_directory_uri() . '/style.min.css',
				'source-development' => \get_template_directory_uri() . '/style.css',
				'deps' => array(
					'google-font',
					'bootstrap'
				),
				'version' => \sanitize_title(self::getThemeData('Name')) . '-' . self::getThemeData('Version'),
				'media' => 'all'
			),
			/* Theme Responsive CSS */
			'EVE Online Responsive Styles' => array(
				'handle' => 'eve-online-responsive-styles',
				'source' => \get_template_directory_uri() . '/css/responsive.min.css',
				'source-development' => \get_template_directory_uri() . '/css/responsive.css',
				'deps' => array(
					'eve-online'
				),
				'version' => \sanitize_title(self::getThemeData('Name')) . '-' . self::getThemeData('Version'),
				'media' => 'all'
			),
			/* Adjustment to Plugins */
			'EVE Online Plugin Styles' => array(
				'handle' => 'eve-online-plugin-styles',
				'source' => \get_template_directory_uri() . '/css/plugin-tweaks.min.css',
				'source-development' => \get_template_directory_uri() . '/css/plugin-tweaks.css',
				'deps' => array(
					'eve-online'
				),
				'version' => \sanitize_title(self::getThemeData('Name')) . '-' . self::getThemeData('Version'),
				'media' => 'all'
			),
		);

		return $enqueue_style;
	} // END public static function getThemeStyleSheets()

	/**
	 * Return the theme's admin stylesheets
	 *
	 * @return array
	 */
	public static function getThemeAdminStyleSheets() {
		$enqueue_style = array(
			/* Adjustment to Plugins */
			'EVE Online Admin Styles' => array(
				'handle' => 'eve-online-admin-styles',
				'source' => \get_template_directory_uri() . '/admin/css/eve-online-admin-style.min.css',
				'source-development' => \get_template_directory_uri() . '/admin/css/eve-online-admin-style.css',
				'deps' => array(),
				'version' => \sanitize_title(self::getThemeData('Name')) . '-' . self::getThemeData('Version'),
				'media' => 'all'
			),
		);

		return $enqueue_style;
	} // END public static function getThemeAdminStyleSheets()

	/**
	 * Update the options array for our theme, if needed
	 *
	 * @param string $optionsName
	 * @param string $dbVersionFieldName
	 * @param string $newDbVersion
	 * @param array $defaultOptions
	 */
	public static function updateOptions($optionsName, $dbVersionFieldName, $newDbVersion, $defaultOptions) {
		$currentDbVersion = \get_option($dbVersionFieldName);

		// Check if the DB needs to be updated
		if($currentDbVersion !== $newDbVersion) {
			$currentOptions = \get_option($optionsName);

			if(\is_array($currentOptions)) {
				$newOptions = \array_merge($defaultOptions, $currentOptions);
			} else {
				$newOptions = $defaultOptions;
			} // END if(\is_array($currentOptions))

			// Update the options
			\update_option($optionsName, $newOptions);

			// Update the DB Version
			\update_option($dbVersionFieldName, $newDbVersion);
		} // END if($currentDbVersion !== $newDbVersion)
	} // END function public static function updateOptions($optionsName, $dbVersionFieldName, $newDbVersion, $defaultOptions)

	/**
	 * Alias for is_active_sidebar()
	 *
	 * @param string $sidebarPosition
	 * @return boolean
	 * @uses is_active_sidebar() Whether a sidebar is in use.
	 */
	public static function hasSidebar($sidebarPosition) {
		return \is_active_sidebar($sidebarPosition);
	} // END public static function hasSidebar($sidebarPosition)

	/**
	 * Getting the default background mages that are shipped with the theme
	 *
	 * @param boolean $withThumbnail
	 * @param string $baseClass
	 * @return array
	 */
	public static function getDefaultBackgroundImages($withThumbnail = false, $baseClass = null) {
		$imagePath = \get_template_directory() . '/img/background/';
		$handle = \opendir($imagePath);

		if($baseClass !== null) {
			$baseClass = '-' . $baseClass;
		} // END if($baseClass !== null)

		if($handle) {
			while(false !== ($entry = \readdir($handle))) {
				$files[$entry] = $entry;
			} // END while(false !== ($entry = readdir($handle)))

			\closedir($handle);

			// we are only looking for images
			$images = \preg_grep('/\.(jpg|jpeg|png|gif)(?:[\?\#].*)?$/i', $files);

			if($withThumbnail === true) {
				foreach($images as &$image) {
					$imageName = \ucwords(\str_replace('-', ' ', \preg_replace("/\\.[^.\\s]{3,4}$/", "", $image)));
					$image = '<figure class="bg-image' . $baseClass . '"><img src="' . \get_template_directory_uri() . '/img/background/' . $image . '" style="width:100px; height:auto;" title="' . $imageName . '"><figcaption>' . $imageName . '</figcaption></figure>';
				} // END foreach($images as &$image)
			} // END if($withThumbnail === true)

			return $images;
		} // END if($handle)
	} // END public static function getDefaultBackgroundImages($withThumbnail = false, $baseClass = null)

	/**
	 * Getting the themes background image
	 *
	 * @return string
	 */
	public static function getThemeBackgroundImage() {
		$themeSettings = \get_option('eve_theme_options', self::getThemeDefaultOptions());

		$backgroundImage = \get_template_directory_uri() . '/img/background/' . $themeSettings['background_image'];
		$uploadedBackground = (empty($themeSettings['background_image_upload'])) ? false : true;

		// we have an uploaded image, so overwrite the background
		if($uploadedBackground === true) {
			$backgroundImage = \wp_get_attachment_url($themeSettings['background_image_upload']);
		} // END if($uploadedBackground === true)

		return $backgroundImage;
	} // END public static function getThemeBackgroundImage()

	/**
	 * Getting the theme's name
	 *
	 * @return string Theme Name
	 */
	public static function getThemeName() {
		return 'EVE Online';
	} // END public static function getThemeName()

	/**
	 * Getting the absolute path for the cache directory
	 *
	 * @return string absolute path for the cache directory
	 */
	public static function getThemeCacheDir() {
		return \trailingslashit(\WP_CONTENT_DIR) . 'cache/' . \sanitize_title(self::getThemeName());
	} // END public static function getImagecacheDir()

	/**
	 * Getting the URI for the cache directory
	 *
	 * @return string URI for the cache directory
	 */
	public static function getThemeCacheUri() {
		return \trailingslashit(\WP_CONTENT_URL) . 'cache/' . \sanitize_title(self::getThemeName());
	} // END public static function getThemeCacheUri()

	/**
	 * creating our needed cache directories under:
	 *		/wp-content/cache/«theme-name»/
	 */
	public static function createCacheDirectory($directory = '') {
		if(\is_writable(\WP_CONTENT_DIR)) {
			if(!\is_dir(\trailingslashit(self::getThemeCacheDir()) . $directory)) {
				\mkdir(\trailingslashit(self::getThemeCacheDir()) . $directory, 0755, true);
			} // END if(!\is_dir(self::getImagecacheDir()))
		} // END if(\is_writable(\WP_CONTENT_DIR))
	} // END public static function createCacheDirectories()
} // END class ThemeHelper
