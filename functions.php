<?php
/**
 * Our Theme's namespace to keep the global namespace clear
 *
 * WordPress\Themes\EveOnline
 */
namespace WordPress\Themes\EveOnline;

/**
 * Just to make sure, if this line is not in wp-config, that our environment
 * variable is still set right.
 *
 * This is to determine between "development/staging" and "live/production" environments.
 * If you are testing this theme in your own test environment, make sure you
 * set the following in your webservers vhosts config.
 *		SetEnv APPLICATION_ENV "development"
 */
\defined('APPLICATION_ENV') || \define('APPLICATION_ENV', (\preg_match('/development/', \getenv('APPLICATION_ENV')) || \preg_match('/staging/', \getenv('APPLICATION_ENV'))) ? \getenv('APPLICATION_ENV') : 'production');

/**
 * Settings API
 */
require_once(\get_stylesheet_directory() . '/admin/SettingsApi.php');

/**
 * EVE API Class
 */
require_once(\get_stylesheet_directory() . '/helper/EveApiHelper.php');

/**
 * String Helper
 */
require_once(\get_stylesheet_directory() . '/helper/StringHelper.php');

/**
 * Metaslider Plugin
 */
require_once(\get_stylesheet_directory() . '/plugins/Metaslider.php');

/**
 * Theme Shortcodes
 */
require_once(\get_stylesheet_directory() . '/plugins/Shortcodes.php');

/**
 * Bootstrap Image Gallery
 */
require_once(\get_stylesheet_directory() . '/plugins/BootstrapImageGallery.php');

/**
 * Bootstrap Video Gallery
 */
require_once(\get_stylesheet_directory() . '/plugins/BootstrapVideoGallery.php');

/**
 * EVE Corp Page
 * Adds a little box to the page edit site to set a page as corp page
 */
require_once(\get_stylesheet_directory() . '/plugins/Corppage.php');

/**
 * Lazy Loading
 */
require_once(\get_stylesheet_directory() . '/plugins/LazyLoadImages.php');

/**
 * Whitelabel
 */
require_once(\get_stylesheet_directory() . '/plugins/Whitelabel.php');

/**
 * MO Cache
 */
require_once(\get_stylesheet_directory() . '/plugins/MoCache.php');

/**
 * Encode Emails Addresses
 */
require_once(\get_stylesheet_directory() . '/plugins/EncodeEmailAddresses.php');

/**
 * Killboard
 */
require_once(\get_stylesheet_directory() . '/plugins/helper/KillboardHelper.php');
require_once(\get_stylesheet_directory() . '/plugins/widgets/KillboardWidget.php');
require_once(\get_stylesheet_directory() . '/plugins/Killboard.php');

/**
 * WP Security
 */
require_once(\get_stylesheet_directory() . '/security/WordPressSecurity.php');
require_once(\get_stylesheet_directory() . '/security/WordPressCoreUpdateCleaner.php');

/**
 * Theme Options
 */
require_once(\get_stylesheet_directory() . '/admin/ThemeSettings.php');

/**
 * Maximal content width
 */
if(!isset($content_width)) {
	$content_width = 1680;
} // END if(!isset($content_width))

/**
 * Return the current DB version used for the themes settings
 *
 * @return string
 */
function eve_get_current_db_version() {
	return '20160829';
} // END function yf_get_current_db_version()

function eve_get_options_default() {
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

		// footer settings tab
		'footertext' => '',
	);

	return \apply_filters('eve_theme_options', $defaultOptions);
} // END function eve_get_options_default()

/**
 * Enqueue JavaScripts
 */
if(!\function_exists('eve_enqueue_scripts')) {
	function eve_enqueue_scripts() {
		/*
		 * Adds JavaScript to pages with the comment form to support
		 * sites with threaded comments (when in use).
		 */
		if(\is_singular() && \comments_open() && \get_option('thread_comments')) {
			\wp_enqueue_script('comment-reply');
		}

		$enqueue_script = eve_get_javascripts();

		/**
		 * Loop through the JS array and load the scripts
		 */
		foreach($enqueue_script as $script) {
			if(\preg_match('/development/', \APPLICATION_ENV)) {
				// for external scripts we might not have a development source
				if(!isset($script['source-development'])) {
					$script['source-development'] = $script['source'];
				} // END if(!isset($script['source-development']))

				\wp_enqueue_script($script['handle'], $script['source-development'], $script['deps'], $script['version'], $script['in_footer']);
			} else {
				\wp_enqueue_script($script['handle'], $script['source'], $script['deps'], $script['version'], $script['in_footer']);
			}
		} // END foreach($enqueue_script as $script)
	} // END function eve_enqueue_styles()

	\add_action('wp_enqueue_scripts', '\\WordPress\Themes\EveOnline\eve_enqueue_scripts');
} // END if(!\function_exists('eve_enqueue_scripts'))

function eve_get_javascripts() {
	$enqueue_script = array(
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
			'version' => \sanitize_title(eve_get_theme_data('Name')) . '-' . eve_get_theme_data('Version'),
			'in_footer' => true
		),
		/* Sonar for Lazy Loading */
		'Sonar for Lazy Loading' => array(
			'handle' => 'jquery-sonar',
			'source' => \get_template_directory_uri() . '/plugins/js/jquery.sonar.min.js',
			'source-development' => \get_template_directory_uri() . '/plugins/js/jquery.sonar.js',
			'deps' => array(
				'jquery',
				'bootstrap-js'
			),
			'version' => \sanitize_title(eve_get_theme_data('Name')) . '-' . eve_get_theme_data('Version'),
			'in_footer' => true
		),
		/* Lazy Loading */
		'Lazy Loading' => array(
			'handle' => 'lazy-load-js',
			'source' => \get_template_directory_uri() . '/plugins/js/jquery.lazy-load.min.js',
			'source-development' => \get_template_directory_uri() . '/plugins/js/jquery.lazy-load.js',
			'deps' => array(
				'jquery',
				'jquery-sonar'
			),
			'version' => \sanitize_title(eve_get_theme_data('Name')) . '-' . eve_get_theme_data('Version'),
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
			'version' => \sanitize_title(eve_get_theme_data('Name')) . '-' . eve_get_theme_data('Version'),
			'in_footer' => true
		)
	);

	return $enqueue_script;
} // END function eve_get_javascripts()

/**
 * Enqueue Styles
 */
if(!\function_exists('eve_enqueue_styles')) {
	function eve_enqueue_styles() {
		$enqueue_style = eve_get_stylesheets();

		/**
		 * Loop through the CSS array and load the styles
		 */
		foreach($enqueue_style as $style) {
			if(\preg_match('/development/', \APPLICATION_ENV)) {
				// for external styles we might not have a development source
				if(!isset($style['source-development'])) {
					$style['source-development'] = $style['source'];
				} // END if(!isset($style['source-development']))

				\wp_enqueue_style($style['handle'], $style['source-development'], $style['deps'], $style['version'], $style['media']);
			} else {
				\wp_enqueue_style($style['handle'], $style['source'], $style['deps'], $style['version'], $style['media']);
			}
		} // END foreach($enqueue_style as $style)
	} // END function eve_enqueue_styles()

	\add_action('wp_enqueue_scripts', '\\WordPress\Themes\EveOnline\eve_enqueue_styles');
} // END if(!\function_exists('eve_enqueue_styles'))

function eve_get_stylesheets() {
	$enqueue_style = array(
		/* Bootstrap */
		'Bootstrap' => array(
			'handle' => 'bootstrap',
			'source' => \get_template_directory_uri() . '/bootstrap/css/bootstrap.min.css',
			'source-development' => \get_template_directory_uri() . '/bootstrap/css/bootstrap.css',
			'deps' => array(),
			'version' => '3.3.7',
			'media' => 'all'
		),
//		'Bootstrap Theme' => array(
//			'handle' => 'bootstrap-theme',
//			'source' => \get_template_directory_uri() . '/bootstrap/css/bootstrap-theme.min.css',
//			'source-development' => \get_template_directory_uri() . '/bootstrap/css/bootstrap-theme.css',
//			'deps' => array(
//				'bootstrap'
//			),
//			'version' => '3.3.6',
//			'media' => 'all'
//		),
		/* Genericons (Taken from Twenty Thirteen Theme) */
//		'Genericons' => array(
//			'handle' => 'genericons',
//			'source' => \get_template_directory_uri() . '/genericons/genericons.min.css',
//			'source-development' => \get_template_directory_uri() . '/genericons/genericons.css',
//			'deps' => array(),
//			'version' => \sanitize_title(eve_get_theme_data('Name')) . '-' . eve_get_theme_data('Version'),
//			'media' => 'all'
//		),
		/* Font Awesome */
//		'Font Awesome' => array(
//			'handle' => 'font-awesome',
//			'source' => \get_template_directory_uri() . '/font-awesome/css/font-awesome.min.css',
//			'source-development' => \get_template_directory_uri() . '/font-awesome/css/font-awesome.css',
//			'deps' => array(),
//			'version' => '4.6.3',
//			'media' => 'all'
//		),
		/* Google Font */
		'Google Font' => array(
			'handle' => 'google-font',
			'source' => '//fonts.googleapis.com/css?family=Amethysta',
			'deps' => array(),
			'version' => \sanitize_title(eve_get_theme_data('Name')) . '-' . eve_get_theme_data('Version'),
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
			'version' => \sanitize_title(eve_get_theme_data('Name')) . '-' . eve_get_theme_data('Version'),
			'media' => 'all'
		),
		/* Adjustment to Plugins */
		'EVE Online Plugin Styles' => array(
			'handle' => 'eve-online-plugin-styles',
			'source' => \get_template_directory_uri() . '/plugin-tweaks.min.css',
			'source-development' => \get_template_directory_uri() . '/plugin-tweaks.css',
			'deps' => array(
				'eve-online'
			),
			'version' => \sanitize_title(eve_get_theme_data('Name')) . '-' . eve_get_theme_data('Version'),
			'media' => 'all'
		),
	);

	return $enqueue_style;
} // END function eve_get_stylesheets()

if(!\function_exists('eve_enqueue_admin_styles')) {
	function eve_enqueue_admin_styles() {
		$enqueue_style = array(
			/* Adjustment to Plugins */
			'EVE Online Admin Styles' => array(
				'handle' => 'eve-online-admin-styles',
				'source' => \get_template_directory_uri() . '/admin/css/eve-online-admin-style.min.css',
				'source-development' => \get_template_directory_uri() . '/admin/css/eve-online-admin-style.css',
				'deps' => array(),
				'version' => \sanitize_title(eve_get_theme_data('Name')) . '-' . eve_get_theme_data('Version'),
				'media' => 'all'
			),
		);

		/**
		 * Loop through the CSS array and load the styles
		 */
		foreach($enqueue_style as $style) {
			if(\preg_match('/development/', \APPLICATION_ENV)) {
				// for external styles we might not have a development source
				if(!isset($style['source-development'])) {
					$style['source-development'] = $style['source'];
				} // END if(!isset($style['source-development']))

				\wp_enqueue_style($style['handle'], $style['source-development'], $style['deps'], $style['version'], $style['media']);
			} else {
				\wp_enqueue_style($style['handle'], $style['source'], $style['deps'], $style['version'], $style['media']);
			} // END if(\preg_match('/development/', \APPLICATION_ENV))
		} // END foreach($enqueue_style as $style)
	} // END function eve_enqueue_admin_styles()

	\add_action('admin_init', '\\WordPress\Themes\EveOnline\eve_enqueue_admin_styles');
} // END if(!function_exists('\eve_enqueue_styles'))

/**
 * Theme Setup
 */
function eve_theme_setup() {
	/**
	 * Check if options have to be updated
	 */
	eve_update_options('eve_theme_options', 'eve_theme_db_version', eve_get_current_db_version(), eve_get_options_default());

	/**
	 * Loading out textdomain
	 */
	\load_theme_textdomain('eve-online', \get_stylesheet_directory() . '/l10n');

	\add_theme_support('automatic-feed-links');
	\add_theme_support('post-thumbnails');
	\add_theme_support('post-formats', array(
		'aside',
		'image',
		'gallery',
		'link',
		'quote',
		'status',
		'video',
		'audio',
		'chat'
	));

	\register_nav_menus(array(
		'main-menu' => __('Main Menu', 'eve-online'),
		'footer-menu' => __('Footer Menu', 'eve-online'),
		'header-menu' => __('Header Menu', 'eve-online'),
	));

	/**
	 * Define post thumbnail size.
	 * Add two additional image sizes.
	 */
	\set_post_thumbnail_size(1680, 500);
	\add_image_size('bootstrap-small', 300, 200);
	\add_image_size('bootstrap-medium', 360, 270);
	\add_image_size('header-image', 1680, 500, true);
	\add_image_size('post-loop-thumbnail', 705, 395, true);

	// Register Custom Navigation Walker
	require_once(\get_stylesheet_directory() .'/addons/BootstrapMenuWalker.php');

	/**
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	\add_editor_style(array(
		'css/editor-style.css',
		'genericons/genericons.css',
		eve_fonts_url()
	));
} // END function eve_theme_setup()
\add_action('after_setup_theme', '\\WordPress\Themes\EveOnline\eve_theme_setup');

/**
 * Remove integrated gallery styles in the content area of standard gallery shortcode.
 * style in css.
 */
\add_filter('gallery_style', \create_function('$a', 'return "<div class=\'gallery\'>";'));

/**
 * Update the options array for our theme, if needed
 *
 * @param string $optionsName
 * @param string $dbVersionFieldName
 * @param string $newDbVersion
 * @param array $defaultOptions
 */
function eve_update_options($optionsName, $dbVersionFieldName, $newDbVersion, $defaultOptions) {
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
} // END function yf_update_options($dbVersionName, $optionsName, $newDbVersion, $defaultOptions)

/**
 * Return the Google font stylesheet URL, if available.
 *
 * The use of Source Sans Pro and Bitter by default is localized. For languages
 * that use characters not supported by the font, the font can be disabled.
 *
 * @since EVE Online Theme 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
if(!\function_exists('eve_fonts_url')) {
	function eve_fonts_url() {
		$fonts_url = '';

		/**
		 * Translators: If there are characters in your language that are not
		 * supported by Source Sans Pro, translate this to 'off'. Do not translate
		 * into your own language.
		 */
		$source_sans_pro = \_x('on', 'Source Sans Pro font: on or off', 'eve-online');

		/**
		 * Translators: If there are characters in your language that are not
		 * supported by Bitter, translate this to 'off'. Do not translate into your
		 * own language.
		 */
		$bitter = \_x('on', 'Bitter font: on or off', 'eve-online');

		if('off' !== $source_sans_pro || 'off' !== $bitter) {
			$font_families = array();

			if('off' !== $source_sans_pro) {
				$font_families[] = 'Source Sans Pro:300,400,700,300italic,400italic,700italic';

				if('off' !== $bitter) {
					$font_families[] = 'Bitter:400,700';

					$query_args = array(
						'family' => \urlencode(\implode( '|', $font_families)),
						'subset' => \urlencode('latin,latin-ext'),
					);
					$fonts_url = \add_query_arg($query_args, 'https://fonts.googleapis.com/css');
				} // END if('off' !== $bitter)
			} // END if('off' !== $source_sans_pro)
		} // END if('off' !== $source_sans_pro || 'off' !== $bitter)

		return $fonts_url;
	} // END function eve_fonts_url()
} // END if(!\function_exists('eve_fonts_url'))

/**
 * Adding the clearfix CSS class to every paragraph in .entry-content
 */
if(!\function_exists('eve_paragraph_clearfix')) {
	function eve_paragraph_clearfix($content) {
		return \preg_replace('/<p([^>]+)?>/', '<p$1 class="clearfix">', $content);
	} // END function eve_paragraph_clearfix($content)

//	\add_filter('the_content', '\\WordPress\Themes\EveOnline\eve_paragraph_clearfix');
} // END if(!\function_exists('eve_paragraph_clearfix'))

/**
 * Picking up teh first paragraph from the_content
 */
if(!\function_exists('eve_first_paragraph')) {
	function eve_first_paragraph($content) {
		return \preg_replace('/<p([^>]+)?>/', '<p$1 class="intro">', $content, 1);
	} // END function eve_first_paragraph($content)

//	\add_filter('the_content', '\\WordPress\Themes\EveOnline\eve_first_paragraph');
} // END if(!\function_exists('eve_first_paragraph'))

/**
 * Adding a CSS class to the excerpt
 * @param string $excerpt
 * @return string
 */
if(!\function_exists('eve_add_class_to_excerpt')) {
	function eve_add_class_to_excerpt($excerpt) {
		return \str_replace('<p', '<p class="excerpt"', $excerpt);
	} // END function eve_add_class_to_excerpt($excerpt)

	\add_filter('the_excerpt', '\\WordPress\Themes\EveOnline\eve_add_class_to_excerpt');
} // END if(!\function_exists('eve_add_class_to_excerpt'))

/**
 * Alias for is_active_sidebar()
 *
 * @param string $sidebarPosition
 * @return boolean
 * @uses is_active_sidebar() Whether a sidebar is in use.
 */
function eve_has_sidebar($sidebarPosition) {
	return \is_active_sidebar($sidebarPosition);
} // END function eve_has_sidebar($sidebarPosition)

/**
 * Define theme's widget areas.
 */
function eve_widgets_init() {
	\register_sidebar(
		array(
			'name' => \__('Page Sidebar', 'eve-online'),
			'id' => 'sidebar-page',
			'before_widget' => '<aside><div id="%1$s" class="widget %2$s">',
			'after_widget' => "</div></aside>",
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>',
		)
	);

	\register_sidebar(
		array(
			'name' => \__('Post Sidebar', 'eve-online'),
			'id' => 'sidebar-post',
			'before_widget' => '<aside><div id="%1$s" class="widget %2$s">',
			'after_widget' => "</div></aside>",
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>',
		)
	);

	\register_sidebar(
		array(
			'name' => \__('General Sidebar', 'eve-online'),
			'id' => 'sidebar-general',
			'description' => \__('General sidebar that is always right from the topic, below the side specific sidebars', 'eve-online'),
			'before_widget' => '<aside><div id="%1$s" class="widget %2$s">',
			'after_widget' => "</div></aside>",
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>',
		)
	);

	\register_sidebar(
		array(
			'name' => \__('Home Column 1', 'eve-online'),
			'id' => 'home-column-1',
			'description' => \__('Home Column 1', 'eve-online'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>'
		)
	);

	\register_sidebar(
		array(
			'name' => \__('Home Column 2', 'eve-online'),
			'id' => 'home-column-2',
			'description' => \__('Home Column 2', 'eve-online'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>'
		)
	);

	\register_sidebar(
		array(
			'name' => \__('Home Column 3', 'eve-online'),
			'id' => 'home-column-3',
			'description' => \__('Home Column 3', 'eve-online'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>'
		)
	);

	\register_sidebar(
		array(
			'name' => \__('Home Column 4', 'eve-online'),
			'id' => 'home-column-4',
			'description' => \__('Home Column 4', 'eve-online'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>'
		)
	);

	\register_sidebar(
		array(
			'name' => \__('Footer Column 1', 'eve-online'),
			'id' => 'footer-column-1',
			'description' => \__('Footer Column 1', 'eve-online'),
			'before_widget' => '<aside><div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div></aside>',
			'before_title' => '<h4>',
			'after_title' => '</h4>'
		)
	);

	\register_sidebar(
		array(
			'name' => \__('Footer Column 2', 'eve-online'),
			'id' => 'footer-column-2',
			'description' => \__('Footer Column 2', 'eve-online'),
			'before_widget' => '<aside><div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div></aside>',
			'before_title' => '<h4>',
			'after_title' => '</h4>'
		)
	);

	\register_sidebar(
		array(
			'name' => \__('Footer Column 3', 'eve-online'),
			'id' => 'footer-column-3',
			'description' => \__('Footer Column 3', 'eve-online'),
			'before_widget' => '<aside><div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div></aside>',
			'before_title' => '<h4>',
			'after_title' => '</h4>'
		)
	);

	\register_sidebar(
		array(
			'name' => \__('Footer Column 4', 'eve-online'),
			'id' => 'footer-column-4',
			'description' => \__('Footer Column 4', 'eve-online'),
			'before_widget' => '<aside><div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div></aside>',
			'before_title' => '<h4>',
			'after_title' => '</h4>'
		)
	);

	// header widget sidebar
	\register_sidebar(
		array(
			'name' => \__('Header Widget Area', 'eve-online'),
			'id' => 'header-widget-area',
			'description' => \__('Header Widget Area', 'eve-online'),
			'before_widget' => '<aside><div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div></aside>',
			'before_title' => '<h4>',
			'after_title' => '</h4>'
		)
	);
} // END function eve_widgets_init()
\add_action('init', '\\WordPress\Themes\EveOnline\eve_widgets_init');

/**
 * Replaces the excerpt "more" text by a link
 */
function eve_excerpt_more($more) {
	return ' ... <br/><a class="read-more" href="'. \get_permalink(\get_the_ID()) . '">'.__('Read More', 'eve-online').'</a>';
} // END function eve_excerpt_more($more)
 \add_filter('excerpt_more', '\\WordPress\Themes\EveOnline\eve_excerpt_more');

/**
 * prevent scrolling when using more-tag
 */
function eve_remove_more_link_scroll($link) {
	$link = \preg_replace('|#more-[0-9]+|', '', $link);

	return $link;
} // END function eve_remove_more_link_scroll($link)
\add_filter('the_content_more_link', '\\WordPress\Themes\EveOnline\eve_remove_more_link_scroll');

/**
 * Display page next/previous navigation links.
 */
if(!\function_exists('eve_content_nav')) {
	function eve_content_nav($nav_id) {
		global $wp_query, $post;

		if($wp_query->max_num_pages > 1) {
			?>
			<nav id="<?php echo $nav_id; ?>" class="navigation post-navigation clearfix" role="navigation">
				<h3 class="assistive-text"><?php \_e('Post navigation', 'eve-online'); ?></h3>
				<div class="nav-previous pull-left">
					<?php eve_next_posts_link(\__('<span class="meta-nav">&larr;</span> Older posts', 'eve-online')); ?>
				</div>
				<div class="nav-next pull-right">
					<?php eve_previous_posts_link(\__('Newer posts <span class="meta-nav">&rarr;</span>', 'eve-online')); ?>
				</div>
			</nav><!-- #<?php echo $nav_id; ?> .navigation -->
			<?php
		} // END if($wp_query->max_num_pages > 1)
	} // END function eve_content_nav($nav_id)
} // END if(!\function_exists('eve_content_nav'))

/**
 * Displays the previous posts page link.
 *
 * @param string $label Optional. Previous page link text.
 */
function eve_previous_posts_link($label = null) {
	echo eve_get_previous_posts_link($label);
} // END function eve_previous_posts_link($label = null)

/**
 * Retrieves the previous posts page link.
 *
 * @global int $paged
 *
 * @param string $label Optional. Previous page link text.
 * @return string|void HTML-formatted previous page link.
 */
function eve_get_previous_posts_link($label = null) {
	global $paged;

	if(null === $label) {
		$label = __('&laquo; Previous Page');
	} // END if(null === $label)

	if(!\is_single() && $paged > 1) {
		/**
		 * Filters the anchor tag attributes for the previous posts page link.
		 *
		 * @since 2.7.0
		 *
		 * @param string $attributes Attributes for the anchor tag.
		 */
		$attr = \apply_filters('previous_posts_link_attributes', '');

		return '<a class="btn btn-default" href="' . \previous_posts(false) . "\" $attr>". \preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label) .'</a>';
	} // END if(!\is_single() && $paged > 1)
} // END function eve_get_previous_posts_link($label = null)

/**
 * Displays the next posts page link.
 *
 * @param string $label    Content for link text.
 * @param int    $max_page Optional. Max pages. Default 0.
 */
function eve_next_posts_link($label = null, $max_page = 0) {
	echo eve_get_next_posts_link($label, $max_page);
} // END function eve_next_posts_link($label = null, $max_page = 0)

/**
 * Retrieves the next posts page link.
 *
 * @global int      $paged
 * @global WP_Query $wp_query
 *
 * @param string $label    Content for link text.
 * @param int    $max_page Optional. Max pages. Default 0.
 * @return string|void HTML-formatted next posts page link.
 */
function eve_get_next_posts_link($label = null, $max_page = 0) {
	global $paged, $wp_query;

	if(!$max_page) {
		$max_page = $wp_query->max_num_pages;
	} // END if(!$max_page)

	if(!$paged) {
		$paged = 1;
	} // END if(!$paged)

	$nextpage = \intval($paged) + 1;

	if(null === $label) {
		$label = __('Next Page &raquo;');
	} // END if(null === $label)

	if(!\is_single() && ($nextpage <= $max_page)) {
		/**
		 * Filters the anchor tag attributes for the next posts page link.
		 *
		 * @since 2.7.0
		 *
		 * @param string $attributes Attributes for the anchor tag.
		 */
		$attr = \apply_filters('next_posts_link_attributes', '');

		return '<a class="btn btn-default" href="' . \next_posts( $max_page, false ) . "\" $attr>" . \preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label) . '</a>';
	} // END if(!\is_single() && ($nextpage <= $max_page))
} // END function eve_get_next_posts_link($label = null, $max_page = 0)

/**
 * Display template for comments and pingbacks.
 */
if(!\function_exists('eve_comment')) {
	function eve_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;

		switch($comment->comment_type) {
			case 'pingback' :
			case 'trackback' :
				?>
				<li class="comment media" id="comment-<?php \comment_ID(); ?>">
					<div class="media-body">
						<p>
							<?php \_e('Pingback:', 'eve-online'); ?> <?php \comment_author_link(); ?>
						</p>
					</div><!--/.media-body -->
					<?php
					break;
			default :
				// Proceed with normal comments.
				global $post;
				?>
				<li class="comment media" id="comment-<?php \comment_ID(); ?>">
					<a href="<?php echo $comment->comment_author_url; ?>" class="pull-left">
						<?php echo \get_avatar($comment, 64); ?>
					</a>
					<div class="media-body">
						<h4 class="media-heading comment-author vcard">
							<?php
							\printf('<cite class="fn">%1$s %2$s</cite>',
								\get_comment_author_link(),
								// If current post author is also comment author, make it known visually.
								($comment->user_id === $post->post_author) ? '<span class="label"> ' . \__('Post author', 'eve-online') . '</span> ' : ''
							);
							?>
						</h4>
						<?php
						if('0' == $comment->comment_approved) {
							?>
							<p class="comment-awaiting-moderation">
								<?php \_e('Your comment is awaiting moderation.', 'eve-online'); ?>
							</p>
							<?php
						} // END if('0' == $comment->comment_approved)

						\comment_text();
						?>
						<p class="meta">
							<?php
							\printf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
								\esc_url(\get_comment_link($comment->comment_ID)),
								\get_comment_time('c'),
								\sprintf(\__('%1$s at %2$s', 'eve-online'), \get_comment_date(), \get_comment_time())
							);
							?>
						</p>
						<p class="reply">
							<?php
							\comment_reply_link(\array_merge($args, array(
								'reply_text' => __('Reply <span>&darr;</span>', 'eve-online'),
								'depth' => $depth,
								'max_depth' => $args['max_depth']
							)));
							?>
						</p>
					</div> <!--/.media-body -->
					<?php
				break;
		} // END switch ($comment->comment_type)
	} // END function eve_comment($comment, $args, $depth)
} // END if(!\function_exists('eve_comment'))

/**
 * Display template for post meta information.
 */
if(!\function_exists('eve_posted_on')) {
	function eve_posted_on() {
		$options = \get_option('eve_theme_options', eve_get_options_default());

		if(!empty($options['show_post_meta']['yes'])) {
			\printf(\__('Posted on <time class="entry-date" datetime="%3$s">%4$s</time><span class="byline"> <span class="sep"> by </span> <span class="author vcard">%7$s</span></span>', 'eve-online'),
				\esc_url(\get_permalink()),
				\esc_attr(\get_the_time()),
				\esc_attr(\get_the_date('c')),
				\esc_html(\get_the_date()),
				\esc_url(\get_author_posts_url(\get_the_author_meta('ID'))),
				\esc_attr(\sprintf(\__('View all posts by %s', 'eve-online'),
					\get_the_author()
				)),
				\esc_html(get_the_author())
			);
		} // END if(!empty($options['show_post_meta']['yes']))
	} // END function eve_posted_on()
} // END if(!\function_exists('eve_posted_on'))

/**
 * Display template for post cateories and tags
 */
if(!\function_exists('eve_cats_tags')) {
	function eve_cats_tags() {
		$options = \get_option('eve_theme_options', eve_get_options_default());

		if(!empty($options['show_post_meta']['yes'])) {
			\printf('<span class="cats_tags"><span class="glyphicon glyphicon-folder-open" title="My tip"></span><span class="cats">');
			\printf(\the_category(', '));
			\printf('</span>');

			if(\has_tag() === true) {
				\printf('<span class="glyphicon glyphicon-tags"></span><span class="tags">');
				\printf(\the_tags(' '));
				\printf('</span>');
			} // END if(has_tag() === true)

			\printf('</span>');
		} // END if(!empty($options['show_post_meta']['yes']))
	} // END function eve_cats_tags()
} // END if(!\function_exists('eve_cats_tags'))

/**
 * Adds custom classes to the array of body classes.
 */
function eve_body_classes($classes) {
	if(!\is_multi_author()) {
		$classes[] = 'single-author';
	} // END if(!is_multi_author())

	return $classes;
} // END function eve_body_classes($classes)
\add_filter('body_class', '\\WordPress\Themes\EveOnline\eve_body_classes');

/**
 * Add post ID attribute to image attachment pages prev/next navigation.
 */
function eve_enhanced_image_navigation($url) {
	global $post;

	if(\wp_attachment_is_image($post->ID)) {
		$url = $url . '#main';
	} // END if(wp_attachment_is_image($post->ID))

	return $url;
} // END function eve_enhanced_image_navigation($url)
\add_filter('attachment_link', '\\WordPress\Themes\EveOnline\eve_enhanced_image_navigation');

/**
 * Define default page titles.
 */
function eve_wp_title($title, $sep) {
	global $paged, $page;

	if(\is_feed()) {
		return $title;
	} // END if(is_feed())

	// Add the site name.
	$title .= \get_bloginfo('name');

	// Add the site description for the home/front page.
	$site_description = \get_bloginfo('description', 'display');
	if($site_description && (\is_home() || \is_front_page())) {
		$title = "$title $sep $site_description";
	} // END if($site_description && (is_home() || is_front_page()))

	// Add a page number if necessary.
	if($paged >= 2 || $page >= 2) {
		$title = $title . ' ' . $sep  . ' ' . \sprintf(\__('Page %s', 'eve-online'), \max($paged, $page));
	} // END if($paged >= 2 || $page >= 2)

	return $title;
} // END function eve_wp_title($title, $sep)
\add_filter('wp_title', '\\WordPress\Themes\EveOnline\eve_wp_title', 10, 2);

/**
 * Display template for breadcrumbs.
 */
function eve_breadcrumbs($addTexts = true) {
	$home = __('Home', 'eve-online'); // text for the 'Home' link
	$before = '<li class="active">'; // tag before the current crumb
	$sep = '';
	$after = '</li>'; // tag after the current crumb

	if(!\is_home() && !\is_front_page() || \is_paged()) {
		echo '<ul class="breadcrumb">';

		global $post;

		$homeLink = \home_url();

		echo '<li><a href="' . $homeLink . '">' . $home . '</a> ' . $sep . '</li> ';

		if(\is_category()) {
			global $wp_query;

			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = \get_category($thisCat);
			$parentCat = \get_category($thisCat->parent);

			if($thisCat->parent != 0) {
				echo '<li>' . \get_category_parents($parentCat, true, $sep . '</li><li>') . '</li>';
			} // END if($thisCat->parent != 0)

			$format = $before . ($addTexts ? (__('Archive by category ', 'eve-online') . '"%s"') : '%s') . $after;

			echo \sprintf($format, \single_cat_title('', false));
		} elseif(\is_day()) {
			echo '<li><a href="' . \get_year_link(\get_the_time('Y')) . '">' . \get_the_time('Y') . '</a></li> ';
			echo '<li><a href="' . \get_month_link(\get_the_time('Y'), \get_the_time('m')) . '">' . \get_the_time('F') . '</a></li> ';
			echo $before . \get_the_time('d') . $after;
		} elseif(\is_month()) {
			echo '<li><a href="' . \get_year_link(\get_the_time('Y')) . '">' . \get_the_time('Y') . '</a></li> ';
			echo $before . \get_the_time('F') . $after;
		} elseif(\is_year()) {
			echo $before . \get_the_time('Y') . $after;
		} elseif(\is_single() && !\is_attachment()) {
			if(\get_post_type() != 'post') {
				$post_type = \get_post_type_object(\get_post_type());
				$slug = $post_type->rewrite;

				echo '<li><a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a></li> ';
				echo $before . \get_the_title() . $after;
			} else {
				$cat = \get_the_category();
				$cat = $cat[0];

				echo '<li>' . \get_category_parents($cat, true, $sep) . '</li>';
				echo $before . \get_the_title() . $after;
			} // END if(get_post_type() != 'post')
		} elseif(!\is_single() && !\is_page() && \get_post_type() != 'post' && !\is_404()) {
			$post_type = \get_post_type_object(\get_post_type());

			echo $before . $post_type->labels->singular_name . $after;
		} elseif(\is_attachment()) {
			$parent = \get_post($post->post_parent);
			$cat = \get_the_category($parent->ID);
			$cat = (isset($cat['0'])) ? $cat['0'] : '';

			echo (isset($cat['0'])) ? \get_category_parents($cat, true, $sep) : '';
			echo '<li><a href="' . \get_permalink($parent) . '">' . $parent->post_title . '</a></li> ';
			echo $before . \get_the_title() . $after;
		} elseif(\is_page() && !$post->post_parent) {
			echo $before . get_the_title() . $after;
		} elseif(\is_page() && $post->post_parent) {
			$parent_id = $post->post_parent;
			$breadcrumbs = array();

			while($parent_id) {
				$page = \get_page($parent_id);
				$breadcrumbs[] = '<li><a href="' . \get_permalink($page->ID) . '">' . \get_the_title($page->ID) . '</a>' . $sep . '</li>';
				$parent_id = $page->post_parent;
			} // END while($parent_id)

			$breadcrumbs = \array_reverse($breadcrumbs);

			foreach($breadcrumbs as $crumb) {
				echo $crumb;
			} // END foreach($breadcrumbs as $crumb)

			echo $before . \get_the_title() . $after;
		} elseif(\is_search()) {
			$format = $before . ($addTexts ? (\__('Search results for "', 'eve-online') . '"%s"') : '%s') . $after;

			echo \sprintf($format, \get_search_query());
		} elseif(\is_tag()) {
			$format = $before . ($addTexts ? (\__('Posts tagged "', 'eve-online') . '"%s"') : '%s') . $after;

			echo \sprintf($format, \single_tag_title('', false));
		} elseif(\is_author()) {
			global $author;

			$userdata = \get_userdata($author);
			$format = $before . ($addTexts ? (\__('Articles posted by ', 'eve-online') . '"%s"') : '%s') . $after;

			echo \sprintf($format, $userdata->display_name);
		} elseif(\is_404()) {
			echo $before . \__('Error 404', 'eve-online') . $after;
		} // END if(is_category())

		echo '</ul>';
	} // END if(!is_home() && !is_front_page() || is_paged())
} // END function eve_breadcrumbs($addTexts = true)

function eve_get_headerColClasses($echo = false) {
	if(eve_has_sidebar('header-widget-area')) {
		$contentColClass = 'col-xs-12 col-sm-9 col-md-6 col-lg-6';
	} else {
		$contentColClass = 'col-xs-12 col-sm-9 col-md-9 col-lg-9';
	} // END if(eve_has_sidebar('header-widget-area'))

	if($echo === true) {
		echo $contentColClass;
	} else {
		return $contentColClass;
	} // END if($echo === true)
} // END function eve_get_headerColClasses($echo = false)

function eve_get_mainContentColClasses($echo = false) {
	if(eve_has_sidebar('sidebar-page') || eve_has_sidebar('sidebar-general') || eve_has_sidebar('sidebar-post')) {
		$contentColClass = 'col-lg-9 col-md-9 col-sm-9 col-9';
	} else {
		$contentColClass = 'col-lg-12 col-md-12 col-sm-12 col-12';
	} // END if(eve_has_sidebar('sidebar-page'))

	if($echo === true) {
		echo $contentColClass;
	} else {
		return $contentColClass;
	} // END if($echo === true)
} // END function eve_get_mainContentColClasses($echo = false)

function eve_get_loopContentClasses($echo = false) {
	if(eve_has_sidebar('sidebar-page') || eve_has_sidebar('sidebar-general') || eve_has_sidebar('sidebar-post')) {
		$contentColClass = 'col-lg-4 col-md-6 col-sm-12 col-xs-12';
	} else {
		$contentColClass = 'col-lg-3 col-md-4 col-sm-6 col-xs-12';
	} // END if(eve_has_sidebar('sidebar-page'))

	if($echo === true) {
		echo $contentColClass;
	} else {
		return $contentColClass;
	} // END if($echo === true)
} // END function eve_get_loopContentClasses($echo = false)

/**
 * Returning some theme related data
 *
 * @param string $parameter
 * @return string
 *
 * @link https://developer.wordpress.org/reference/functions/wp_get_theme/
 */
function eve_get_theme_data($parameter) {
	$themeData = \wp_get_theme();

	return $themeData->get($parameter);
} // END function eve_get_theme_data($parameter)

/**
 * Link Pages
 * @author toscho
 * @link http://wordpress.stackexchange.com/questions/14406/how-to-style-current-page-number-wp-link-pages
 * @param  array $args
 * @return void
 * Modification of wp_link_pages() with an extra element to highlight the current page.
 */
function eve_link_pages($args = array()) {
	$defaults = array(
		'before' => '<p>' . __('Pages:'),
		'after' => '</p>',
		'before_link' => '',
		'after_link' => '',
		'current_before' => '',
		'current_after' => '',
		'link_before' => '',
		'link_after' => '',
		'pagelink' => '%',
		'echo' => 1
	);

//	$r = \wp_parse_args($args, $defaults);
//	$r = \apply_filters('wp_link_pages_args', $r);

	$filtered = \apply_filters('wp_link_pages_args', \wp_parse_args($args, $defaults));

	\extract($filtered, \EXTR_SKIP);

	global $page, $numpages, $multipage, $more, $pagenow;

	if(!$multipage) {
		return;
	} // END if(!$multipage)

	$output = $before;

	for($i = 1; $i < ($numpages + 1); $i++)	{
		$j = \str_replace( '%', $i, $pagelink );
		$output .= ' ';

		if($i != $page || (!$more && 1 == $page)) {
			$output .= $before_link . \_wp_link_page($i) . $link_before . $j . $link_after . '</a>' . $after_link;
		} else {
			$output .= $current_before . $link_before .'<a>' . $j . '</a>' . $link_after . $current_after;
		} // END if($i != $page || (!$more && 1 == $page))
	} // END for($i = 1; $i < ($numpages + 1); $i++)

	echo $output . $after;
} // END function eve_link_pages($args = array())

/**
 * check if a post has content or not
 *
 * @param int $postID ID of the post
 * @return boolean
 */
function eve_post_has_content($postID) {
	$content_post = \get_post($postID);
	$content = $content_post->post_content;

	return \trim(\str_replace('&nbsp;','',  \strip_tags($content))) !== '';
} // END function eve_post_has_content($postID)

function eve_bootstrapDebug() {
	$script = '<script type="text/javascript">'
			. 'jQuery(function($) {'
			. '		(function($, document, window, viewport) {'
			. '			console.log(\'Current breakpoint:\', viewport.current());'
			. '			$("footer").append("<span class=\"viewport-debug\"><span class=\"viewport-debug-inner\"></span></span>");'
			. '			$(".viewport-debug-inner").html(viewport.current().toUpperCase());'
			. '			$(window).resize(viewport.changed(function() {'
			. '				console.log(\'Breakpoint changed to:\', viewport.current());'
			. '				$(".viewport-debug-inner").html(viewport.current().toUpperCase());'
			. '			}));'
			. '		})(jQuery, document, window, ResponsiveBootstrapToolkit);'
			. '});'
			. '</script>';

	echo $script;
}
if(\preg_match('/development/', \APPLICATION_ENV)) {
	\add_action('wp_footer', '\\WordPress\Themes\EveOnline\eve_bootstrapDebug', 99);
} // END if(\preg_match('/development/', \APPLICATION_ENV))

/**
 * Disable Smilies
 *
 * @todo Make it configurable
 */
\add_filter('option_use_smilies', '__return_false');

function eve_check_recommend_plugins() {
	$recommendPlugins = eve_get_recommend_plugins();
}

function eve_get_recommend_plugins() {

}

function eve_get_default_background_images($withThumbnail = false, $baseClass = null) {
	$imagePath = \get_stylesheet_directory() . '/img/background/';
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
				$imageName = ucwords(str_replace('-', ' ', preg_replace("/\\.[^.\\s]{3,4}$/", "", $image)));
				$image = '<figure class="bg-image' . $baseClass . '"><img src="' . \get_template_directory_uri() . '/img/background/' . $image . '" style="width:100px; height:auto;" title="' . $imageName . '"><figcaption>' . $imageName . '</figcaption></figure>';
			} // END foreach($images as &$image)
		} // END if($withThumbnail === true)

		return $images;
	} // END if($handle)
} // END function eve_get_default_background_images($withThumbnail = false, $baseClass = null)

/**
 * Disable Smilies
 *
 * @todo Make it configurable
 */
\add_filter('option_use_smilies', '__return_false');

function eve_get_theme_background_image() {
	$themeSettings = \get_option('eve_theme_options', eve_get_options_default());

	$backgroundImage = \get_template_directory_uri() . '/img/background/' . $themeSettings['background_image'];
	$uploadedBackground = (empty($themeSettings['background_image_upload'])) ? false : true;

	// we have an uploaded image, so overwrite the background
	if($uploadedBackground === true) {
		$backgroundImage = \wp_get_attachment_url($themeSettings['background_image_upload']);
	} // END if($uploadedBackground === true)

	return $backgroundImage;
} // END function eve_get_background_image()

/**
 * Adding the custom style to the theme
 */
function eve_get_theme_custom_style() {
	$themeSettings = \get_option('eve_theme_options', eve_get_options_default());
	$themeCustomStyle = null;

	// background image
	$backgroundImage = eve_get_theme_background_image();
	if(!empty($backgroundImage) && (isset($themeSettings['use_background_image']['yes']) && $themeSettings['use_background_image']['yes'] === 'yes')) {
		$themeCustomStyle .= 'body {background-image:url("' . $backgroundImage . '")}' . "\n";
	} // END if(!empty(eve_get_theme_background_image()))

	if(!empty($themeSettings['background_color'])) {
		$rgbValues = Helper\StringHelper::hextoRgb($themeSettings['background_color'], '0.8');

		$themeCustomStyle .= '.container {background-color:rgba(' . join(',', $rgbValues) . ');}' . "\n";
	}

	// main navigation
	if(isset($themeSettings['navigation_even_cells']['yes']) && $themeSettings['navigation_even_cells']['yes'] === 'yes') {
		$themeCustomStyle .= '@media all and (min-width: 768px) {' . "\n";
		$themeCustomStyle .= '	ul.main-navigation {display:table; width:100%;}' . "\n";
		$themeCustomStyle .= '	ul.main-navigation > li {display:table-cell; text-align:center; float:none;}' . "\n";
		$themeCustomStyle .= '}' . "\n";
	} // END if(isset($themeSettings['navigation_even_cells']['yes']))

	\wp_add_inline_style('eve-online', $themeCustomStyle);
} // END function eve_get_theme_custom_style()
\add_action('wp_enqueue_scripts', '\\WordPress\Themes\EveOnline\eve_get_theme_custom_style');

/* comment form
 * -------------------------------------------------------------------------- */
function eve_comment_form_fields($fields) {
	$commenter = \wp_get_current_commenter();

	$req = \get_option('require_name_email');
	$aria_req = ($req ? " aria-required='true' required" : '');
	$html5 = \current_theme_supports('html5', 'comment-form') ? 1 : 0;

	$fields =  array(
		'author' => '<div class="row"><div class="form-group comment-form-author col-md-4">'
//					. '	<label for="author">' . \__('Name') . ($req ? ' <span class="required">*</span>' : '') . '</label>'
					. '	<input class="form-control" id="author" name="author" type="text" value="' . \esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' placeholder="' . \__('Name') . ($req ? ' *' : '') . '" />'
					. '</div>',
		'email' => '<div class="form-group comment-form-email col-md-4">'
//					. '	<label for="email">' . \__('Email') . ($req ? ' <span class="required">*</span>' : '') . '</label> '
					. '	<input class="form-control" id="email" name="email" ' . ($html5 ? 'type="email"' : 'type="text"') . ' value="' . \esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' placeholder="' . \__('Email') . ($req ? ' *' : '') . '" />'
					. '</div>',
		'url' => '<div class="form-group comment-form-url col-md-4">'
//					. '	<label for="url">' . \__('Website') . '</label> '
					. '	<input class="form-control" id="url" name="url" ' . ($html5 ? 'type="url"' : 'type="text"') . ' value="' . \esc_attr($commenter['comment_author_url']) . '" size="30" placeholder="' . \__('Website') . '" />'
					. '</div></div>'
	);

	return $fields;
} // END function eve_comment_form_fields($fields)
\add_filter('comment_form_default_fields', '\\WordPress\Themes\EveOnline\eve_comment_form_fields');

function eve_comment_form($args) {
	$args['comment_field'] = '<div class="row"><div class="form-group comment-form-comment col-lg-12">'
//							. '	<label for="comment">' . \_x('Comment', 'noun') . '</label>'
							. '	<textarea class="form-control" id="comment" name="comment" cols="45" rows="8" aria-required="true" required placeholder="' . \_x('Comment', 'noun') . '"></textarea>'
							. '</div></div>';
	$args['class_submit'] = 'btn btn-default';

	return $args;
} // END function eve_comment_form($args)
\add_filter('comment_form_defaults', '\\WordPress\Themes\EveOnline\eve_comment_form');

function eve_move_comment_field_to_bottom($fields) {
	$comment_field = $fields['comment'];
	unset($fields['comment']);

	$fields['comment'] = $comment_field;
	return $fields;
} // END function eve_move_comment_field_to_bottom($fields)
\add_filter('comment_form_fields', '\\WordPress\Themes\EveOnline\eve_move_comment_field_to_bottom');