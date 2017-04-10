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
 * EVE Online only works in WordPress 4.7 or later.
 */
if(\version_compare($GLOBALS['wp_version'], '4.7-alpha', '<')) {
	require_one(\get_template_directory() . '/addons/Compatibility.php');

	return false;
} // END if(\version_compare($GLOBALS['wp_version'], '4.7-alpha', '<'))

/**
 * Loading Theme Addon Classes
 */
require_once(\get_template_directory() .'/addons/BootstrapMenuWalker.php');
require_once(\get_template_directory() .'/addons/Cron.php');

/**
 * Loading Helper Classes
 */
require_once(\get_template_directory() . '/helper/ThemeHelper.php');
require_once(\get_template_directory() . '/helper/NavigationHelper.php');
require_once(\get_template_directory() . '/helper/PostHelper.php');
require_once(\get_template_directory() . '/helper/EveApiHelper.php');
require_once(\get_template_directory() . '/helper/StringHelper.php');
require_once(\get_template_directory() . '/helper/ImageHelper.php');
require_once(\get_template_directory() . '/helper/FilesystemHelper.php');
require_once(\get_template_directory() . '/helper/CacheHelper.php');
require_once(\get_template_directory() . '/helper/CommentHelper.php');

/**
 * Loading Plugins
 */
require_once(\get_template_directory() . '/plugins/Metaslider.php');
require_once(\get_template_directory() . '/plugins/Shortcodes.php');
require_once(\get_template_directory() . '/plugins/BootstrapImageGallery.php');
require_once(\get_template_directory() . '/plugins/BootstrapVideoGallery.php');
require_once(\get_template_directory() . '/plugins/Corppage.php');
require_once(\get_template_directory() . '/plugins/Whitelabel.php');
require_once(\get_template_directory() . '/plugins/MoCache.php');
require_once(\get_template_directory() . '/plugins/EncodeEmailAddresses.php');
require_once(\get_template_directory() . '/plugins/helper/EdkKillboardHelper.php');
require_once(\get_template_directory() . '/plugins/helper/ZkbKillboardHelper.php');
require_once(\get_template_directory() . '/plugins/widgets/KillboardWidget.php');
require_once(\get_template_directory() . '/plugins/Killboard.php');
require_once(\get_template_directory() . '/plugins/LatestBlogPosts.php');
require_once(\get_template_directory() . '/plugins/EveOnlineAvatar.php');
require_once(\get_template_directory() . '/plugins/HtmlMinify.php');

/**
 * Loading Security Classes
 */
require_once(\get_template_directory() . '/security/WordPressSecurity.php');
require_once(\get_template_directory() . '/security/WordPressCoreUpdateCleaner.php');

/**
 * Theme Options
 */
require_once(\get_template_directory() . '/admin/SettingsApi.php');
require_once(\get_template_directory() . '/admin/ThemeSettings.php');

/**
 * WP Filesystem API
 */
require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');
require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php');

/**
 * Initiate needed general Classes
 */
new Addons\Cron(true);
new Security\WordPressSecurity;
new Plugins\MoCache;
new Plugins\Metaslider(true);
new Plugins\Shortcodes;
new Plugins\BootstrapImageGallery;
new Plugins\BootstrapVideoGallery;
new Plugins\Corppage;
new Plugins\EncodeEmailAddresses;
new Plugins\Whitelabel;
new Plugins\Killboard(true);
new Plugins\LatestBlogPosts;
new Plugins\EveOnlineAvatar;

/**
 * Initiate needed Backend Classes
 */
if(\is_admin()) {
	new Admin\ThemeSettings;
	new Security\WordPressCoreUpdateCleaner;
} // END if(\is_admin())

/**
 * Maximal content width
 */
if(!isset($content_width)) {
	$content_width = 1680;
} // END if(!isset($content_width))

/**
 * Enqueue JavaScripts
 */
if(!\function_exists('eve_enqueue_scripts')) {
	function eve_enqueue_scripts() {
		/**
		 * Adds JavaScript to pages with the comment form to support
		 * sites with threaded comments (when in use).
		 */
		if(\is_singular() && \comments_open() && \get_option('thread_comments')) {
			\wp_enqueue_script('comment-reply');
		} // END if(\is_singular() && \comments_open() && \get_option('thread_comments'))

		$enqueue_script = Helper\ThemeHelper::getThemeJavaScripts();

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

			if(!empty($script['condition'])) {
				\wp_script_add_data($script['handle'], 'conditional', $script['condition']);
			}
		} // END foreach($enqueue_script as $script)
	} // END function eve_enqueue_styles()

	\add_action('wp_enqueue_scripts', '\\WordPress\Themes\EveOnline\eve_enqueue_scripts');
} // END if(!\function_exists('eve_enqueue_scripts'))

/**
 * Enqueue Styles
 */
if(!\function_exists('eve_enqueue_styles')) {
	function eve_enqueue_styles() {
		$enqueue_style = Helper\ThemeHelper::getThemeStyleSheets();

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

if(!\function_exists('eve_enqueue_admin_styles')) {
	function eve_enqueue_admin_styles() {
		$enqueue_style = Helper\ThemeHelper::getThemeAdminStyleSheets();

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
	Helper\ThemeHelper::updateOptions('eve_theme_options', 'eve_theme_db_version', Helper\ThemeHelper::getThemeDbVersion(), Helper\ThemeHelper::getThemeDefaultOptions());

	/**
	 * Loading out textdomain
	 */
	\load_theme_textdomain('eve-online', \get_template_directory() . '/l10n');

	eve_add_theme_support();

	eve_register_nav_menus();

	eve_add_thumbnail_sizes();

	/**
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	\add_editor_style(array(
		'css/editor-style.css',
		'genericons/genericons.css',
		eve_fonts_url()
	));

	// Setting up the image cache directories
	Helper\CacheHelper::createCacheDirectory();
	Helper\CacheHelper::createCacheDirectory('images');
	Helper\CacheHelper::createCacheDirectory('images/corporation');
	Helper\CacheHelper::createCacheDirectory('images/alliance');
	Helper\CacheHelper::createCacheDirectory('images/character');
	Helper\CacheHelper::createCacheDirectory('images/render');
} // END function eve_theme_setup()
\add_action('after_setup_theme', '\\WordPress\Themes\EveOnline\eve_theme_setup');

/**
 * Adding the theme supprt stuff
 */
function eve_add_theme_support() {
	\add_theme_support('automatic-feed-links');
	\add_theme_support('post-thumbnails');
	\add_theme_support('title-tag');
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

	\add_theme_support('html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	));
} // END function eve_add_theme_support()

/**
 * registering nav menus
 */
function eve_register_nav_menus() {
	\register_nav_menus(array(
		'main-menu' => __('Main Menu', 'eve-online'),
		'footer-menu' => __('Footer Menu', 'eve-online'),
		'header-menu' => __('Header Menu', 'eve-online'),
	));
} // END function eve_register_nav_menus()

/**
 * adding the thumbnail sizes
 */
function eve_add_thumbnail_sizes() {
	/**
	 * Define post thumbnail size.
	 * Add two additional image sizes.
	 */
	\set_post_thumbnail_size(1680, 500);

	/**
	 * Thumbnails used for the theme
	 * Compatibilty with Fly Dynamic Image Resizer plugin
	 */
	if(\function_exists('\fly_add_image_size')) {
		\fly_add_image_size('header-image', 1680, 500, true);
		\fly_add_image_size('post-loop-thumbnail', 705, 395, true);
	} else {
		\add_image_size('header-image', 1680, 500, true);
		\add_image_size('post-loop-thumbnail', 705, 395, true);
	} // END if(\function_exists('\fly_add_image_size'))
} // END function eve_add_thumbnail_sizes()

function eve_title_separator($separator) {
	$separator = '»';

	return $separator;
}
\add_filter('document_title_separator', '\\WordPress\Themes\EveOnline\eve_title_separator');

/**
 * Remove integrated gallery styles in the content area of standard gallery shortcode.
 * style in css.
 */
\add_filter('gallery_style', \create_function('$a', 'return "<div class=\'gallery\'>";'));

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
	return ' ' . $more . '<br/><a class="read-more" href="'. \get_permalink(\get_the_ID()) . '">'.__('Read More', 'eve-online').'</a>';
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
 * Link Pages
 * @author toscho
 * @link http://wordpress.stackexchange.com/questions/14406/how-to-style-current-page-number-wp-link-pages
 * @param  array $args
 * @return void
 * Modification of wp_link_pages() with an extra element to highlight the current page.
 */
function eve_link_pages($args = array()) {
	$arguments = \apply_filters('wp_link_pages_args', \wp_parse_args($args, array(
		'before' => '<p>' . __('Pages:', 'eve-online'),
		'after' => '</p>',
		'before_link' => '',
		'after_link' => '',
		'current_before' => '',
		'current_after' => '',
		'link_before' => '',
		'link_after' => '',
		'pagelink' => '%',
		'echo' => 1
	)));

	global $page, $numpages, $multipage, $more;

	if(!$multipage) {
		return;
	} // END if(!$multipage)

	$output = $arguments['before'];

	for($i = 1; $i < ($numpages + 1); $i++)	{
		$j = \str_replace( '%', $i, $arguments['pagelink']);
		$output .= ' ';

		if($i != $page || (!$more && 1 == $page)) {
			$output .= $arguments['before_link'] . \_wp_link_page($i) . $arguments['link_before'] . $j . $arguments['link_after'] . '</a>' . $arguments['after_link'];
		} else {
			$output .= $arguments['current_before'] . $arguments['link_before'] .'<a>' . $j . '</a>' . $arguments['link_after'] . $arguments['current_after'];
		} // END if($i != $page || (!$more && 1 == $page))
	} // END for($i = 1; $i < ($numpages + 1); $i++)

	echo $output . $arguments['after'];
} // END function eve_link_pages($args = array())

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

/**
 * Adding the custom style to the theme
 */
function eve_get_theme_custom_style() {
	$themeSettings = \get_option('eve_theme_options', Helper\ThemeHelper::getThemeDefaultOptions());
	$themeCustomStyle = null;

	// background image
	$backgroundImage = Helper\ThemeHelper::getThemeBackgroundImage();

	if(!empty($backgroundImage) && (isset($themeSettings['use_background_image']['yes']) && $themeSettings['use_background_image']['yes'] === 'yes')) {
		$themeCustomStyle .= 'body {background-image:url("' . $backgroundImage . '")}' . "\n";
	} // END if(!empty(eve_get_theme_background_image()))

	if(!empty($themeSettings['background_color'])) {
		$rgbValues = Helper\StringHelper::hextoRgb($themeSettings['background_color'], '0.8');

		$themeCustomStyle .= '.container {background-color:rgba(' . join(',', $rgbValues) . ');}' . "\n";
	} // END if(!empty($themeSettings['background_color']))

	// main navigation
	if(!empty($themeSettings['navigation']['even_cells'])) {
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
					. '	<input class="form-control" id="author" name="author" type="text" value="' . \esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' placeholder="' . \__('Name', 'eve-online') . ($req ? ' *' : '') . '" />'
					. '</div>',
		'email' => '<div class="form-group comment-form-email col-md-4">'
					. '	<input class="form-control" id="email" name="email" ' . ($html5 ? 'type="email"' : 'type="text"') . ' value="' . \esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' placeholder="' . \__('Email', 'eve-online') . ($req ? ' *' : '') . '" />'
					. '</div>',
		'url' => '<div class="form-group comment-form-url col-md-4">'
					. '	<input class="form-control" id="url" name="url" ' . ($html5 ? 'type="url"' : 'type="text"') . ' value="' . \esc_attr($commenter['comment_author_url']) . '" size="30" placeholder="' . \__('Website', 'eve-online') . '" />'
					. '</div></div>'
	);

	return $fields;
} // END function eve_comment_form_fields($fields)
\add_filter('comment_form_default_fields', '\\WordPress\Themes\EveOnline\eve_comment_form_fields');

function eve_comment_form($args) {
	$args['comment_field'] = '<div class="row"><div class="form-group comment-form-comment col-lg-12">'
							. '	<textarea class="form-control" id="comment" name="comment" cols="45" rows="8" aria-required="true" required placeholder="' . \_x('Comment', 'noun', 'eve-online') . '"></textarea>'
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

/**
 * Getting the "on the fly" image URLs for Meta Slider
 *
 * @global object $fly_images
 * @param string $cropped_url
 * @param string $orig_url
 * @return string
 */
function eve_metaslider_fly_image_urls($cropped_url, $orig_url) {
	unset($cropped_url); // we don't need it here

	$attachmentImage = \fly_get_attachment_image_src(Helper\ImageHelper::getAttachmentId($orig_url), 'header-image');

	return \str_replace('http://', '//', $attachmentImage['src']);
} // END function eve_metaslider_fly_image_urls($cropped_url, $orig_url)
if(\function_exists('\fly_get_attachment_image')) {
	\add_filter('metaslider_resized_image_url', '\\WordPress\Themes\EveOnline\eve_metaslider_fly_image_urls', 10, 2);
} // END if(\function_exists('\fly_get_attachment_image'))

/**
 * Adding some usefull parameters to the Youtube link when using oEmbed
 *
 * @param string $html
 * @param string $url
 * @param array $args
 * @return string
 */
function eve_enable_youtube_jsapi($html) {
	if(\strstr($html, 'youtube.com/embed/')) {
		$html = \str_replace('?feature=oembed', '?feature=oembed&enablejsapi=1&origin=' . \get_bloginfo('url') . '&rel=0', $html);
	} // END if(\strstr($html, 'youtube.com/embed/'))

	return $html;
} // END function eve_enable_youtube_jsapi($html, $url, $args)
\add_filter('oembed_result', '\\WordPress\Themes\EveOnline\eve_enable_youtube_jsapi');

/**
 * Removing the version string from any enqueued css and js source
 *
 * @param string $src the css or js source
 * @return string
 */
function eve_remove_wp_ver_css_js($src) {
	if(strpos($src, 'ver=')) {
		$src = \remove_query_arg('ver', $src);
	} // END if(strpos($src, 'ver='))

	return $src;
} // END function eve_remove_wp_ver_css_js($src)
\add_filter('style_loader_src', '\\WordPress\Themes\EveOnline\eve_remove_wp_ver_css_js', 9999);
\add_filter('script_loader_src', '\\WordPress\Themes\EveOnline\eve_remove_wp_ver_css_js', 9999);
