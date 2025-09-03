<?php

/*
 * Copyright (C) 2018 p.pfeufer
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Our Theme's namespace to keep the global namespace clear
 *
 * Ppfeufer\Theme\EVEOnline
 */

namespace Ppfeufer\Theme\EVEOnline;

// Define a couple of constants we might need.
// phpcs:disable
// Theme name
define(
    constant_name: __NAMESPACE__ . '\THEME_NAME',
    value: wp_get_theme()->get('Name')
);

// Theme version
define(
    constant_name: __NAMESPACE__ . '\THEME_VERSION',
    value: wp_get_theme()->get('Version')
);

// Theme directory (without trailing slash)
define(
    constant_name: __NAMESPACE__ . '\THEME_DIRECTORY',
    value: get_stylesheet_directory()
);

// Theme directory URI (without trailing slash)
define(
    constant_name: __NAMESPACE__ . '\THEME_DIRECTORY_URI',
    value: get_stylesheet_directory_uri()
);

// Theme slug
const THEME_SLUG = 'eve-online';

// Theme Sources directory (without trailing slash)
const THEME_SOURCES_DIRECTORY = THEME_DIRECTORY . '/Sources';

// Theme Library directory (without trailing slash)
const THEME_LIBRARY_DIRECTORY = THEME_SOURCES_DIRECTORY . '/Libs';

// Theme GitHub URI
const THEME_GITHUB_URI = 'https://github.com/ppfeufer/' . THEME_SLUG . '-wordpress-theme/';
// phpcs:enable

// phpcs:disable
// Include the theme autoloader
require_once THEME_SOURCES_DIRECTORY . '/autoload.php';

// Include the library autoloader
require_once THEME_LIBRARY_DIRECTORY . '/autoload.php';

// Load the themes' main class.
(new Main())->init();
// phpcs:enable

######
######
## OLD FUNCTIONS.PHP STARTS HERE
######
######

/**
 * Just to make sure, if this line is not in wp-config, that our environment
 * variable is still set right.
 *
 * This is to determine between "development/staging" and "live/production" environments.
 * If you are testing this theme in your own test environment, make sure you
 * set the following in your webservers vhosts config.
 *      SetEnv APPLICATION_ENV "development"
 */
// phpcs:disable
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (str_contains(getenv('APPLICATION_ENV'), 'development') || str_contains(getenv('APPLICATION_ENV'), 'staging')) ? getenv('APPLICATION_ENV') : 'production');
// phpcs:enable

/**
 * WP Filesystem API
 */
// phpcs:disable
require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');
require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php');
// phpcs:enable

/**
 * Initiate needed general Classes
 */
// phpcs:disable
//new Helper\UpdateHelper;
//new Plugins\Metaslider(true);
//new Plugins\Shortcodes;
//new Plugins\BootstrapImageGallery;
//new Plugins\BootstrapVideoGallery;
//new Plugins\BootstrapContentGrid;
//new Plugins\Corppage;
//new Plugins\Whitelabel;
//new Plugins\ChildpageMenu;
//new Plugins\LatestBlogPosts;
//new Plugins\EveOnlineAvatar;
// phpcs:enable

/**
 * Initiate needed Backend Classes
 */
//if (is_admin()) {
//    // phpcs:disable
//    new Admin\ThemeSettings;
//    new Security\WordPressCoreUpdateCleaner;
//    // phpcs:enable
//}

/**
 * Maximal content width
 */
// phpcs:disable
if (!isset($content_width)) {
    $content_width = 1680;
}
// phpcs:enable

/**
 * Enqueue JavaScripts
 */
if (!function_exists('\Ppfeufer\Theme\EVEOnline\eve_enqueue_scripts')) {
    function eve_enqueue_scripts(): void {
        /**
         * Adds JavaScript to pages with the comment form to support
         * sites with threaded comments (when in use).
         */
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }

        $enqueue_script = eve_get_javascripts();

        /**
         * Loop through the JS array and load the scripts
         */
        foreach ($enqueue_script as $script) {
            wp_enqueue_script($script['handle'], $script['source'], $script['deps'], $script['version'], $script['in_footer']);

            if (!empty($script['condition'])) {
                wp_script_add_data($script['handle'], 'conditional', $script['condition']);
            }
        }
    }
} // END if(!\function_exists('\Ppfeufer\Theme\EVEOnline\eve_enqueue_scripts'))
// phpcs:disable
add_action('wp_enqueue_scripts', '\\Ppfeufer\Theme\EVEOnline\eve_enqueue_scripts');
// phpcs:enable

if (!function_exists('\Ppfeufer\Theme\EVEOnline\eve_get_javascripts')) {
    function eve_get_javascripts(): array {
        return Helper\ThemeHelper::getThemeJavaScripts();
    }
}

/**
 * Enqueue Styles
 */
if (!function_exists('\Ppfeufer\Theme\EVEOnline\eve_enqueue_styles')) {
    function eve_enqueue_styles(): void {
        $enqueue_style = eve_get_stylesheets();

        /**
         * Loop through the CSS array and load the styles
         */
        foreach ($enqueue_style as $style) {
            wp_enqueue_style($style['handle'], $style['source'], $style['deps'], $style['version'], $style['media']);
        }
    }
} // END if(!\function_exists('\Ppfeufer\Theme\EVEOnline\eve_enqueue_styles'))
// phpcs:disable
add_action('wp_enqueue_scripts', '\\Ppfeufer\Theme\EVEOnline\eve_enqueue_styles');
// phpcs:enable

if (!function_exists('\Ppfeufer\Theme\EVEOnline\eve_get_stylesheets')) {
    function eve_get_stylesheets(): array {
        return Helper\ThemeHelper::getThemeStyleSheets();
    }
}

if (!function_exists('\Ppfeufer\Theme\EVEOnline\eve_enqueue_admin_styles')) {
    function eve_enqueue_admin_styles(): void {
        $enqueue_style = Helper\ThemeHelper::getThemeAdminStyleSheets();

        /**
         * Loop through the CSS array and load the styles
         */
        foreach ($enqueue_style as $style) {
            wp_enqueue_style($style['handle'], $style['source'], $style['deps'], $style['version'], $style['media']);
        }
    }
} // END if(!function_exists('\Ppfeufer\Theme\EVEOnline\eve_enqueue_styles'))
// phpcs:disable
add_action('admin_init', '\\Ppfeufer\Theme\EVEOnline\eve_enqueue_admin_styles');
// phpcs:enable

/**
 * Theme Setup
 */
function eve_theme_setup(): void {
    /**
     * Check if options have to be updated
     */
    Helper\ThemeHelper::updateOptions('eve_theme_options', 'eve_theme_db_version', Helper\ThemeHelper::getThemeDbVersion(), Helper\ThemeHelper::getThemeDefaultOptions());

    /**
     * Loading our textdomain
     */
    load_theme_textdomain('eve-online', get_template_directory() . '/l10n');

    eve_add_theme_support();
    eve_register_nav_menus();
    eve_add_thumbnail_sizes();
}
// phpcs:disable
add_action('after_setup_theme', '\\Ppfeufer\Theme\EVEOnline\eve_theme_setup');
// phpcs:enable

/**
 * Adding the theme supprt stuff
 */
function eve_add_theme_support(): void {
    add_theme_support('automatic-feed-links');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('post-formats', [
        'aside',
        'image',
        'gallery',
        'link',
        'quote',
        'status',
        'video',
        'audio',
        'chat'
    ]);

    add_theme_support('html5', [
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ]);
}

/**
 * registering nav menus
 */
function eve_register_nav_menus(): void {
    register_nav_menus([
        'main-menu' => __('Main Menu', 'eve-online'),
        'footer-menu' => __('Footer Menu', 'eve-online'),
        'header-menu' => __('Header Menu', 'eve-online'),
    ]);
}

/**
 * adding the thumbnail sizes
 */
function eve_add_thumbnail_sizes(): void {
    /**
     * Define post thumbnail size.
     * Add two additional image sizes.
     */
    set_post_thumbnail_size(1680, 500);

    /**
     * Thumbnails used for the theme
     */
    add_image_size('header-image', 1680, 500, true);
    add_image_size('post-loop-thumbnail', 768, 432, true);
}

if (!function_exists('\Ppfeufer\Theme\EVEOnline\eve_title_separator')) {
    function eve_title_separator($separator): string {
        $separator = '»';

        return $separator;
    }
}
// phpcs:disable
add_filter('document_title_separator', '\\Ppfeufer\Theme\EVEOnline\eve_title_separator');
// phpcs:enable

/**
 * Remove integrated gallery styles in the content area of standard gallery shortcode.
 * style in css.
 */
function eve_gallery_style_filter($a): string {
    return '<div class="gallery">';
}
// phpcs:disable
add_filter('gallery_style', '\\Ppfeufer\Theme\EVEOnline\eve_gallery_style_filter');
// phpcs:enable

/**
 * Return the Google font stylesheet URL, if available.
 *
 * The use of Source Sans Pro and Bitter by default is localized. For languages
 * that use characters not supported by the font, the font can be disabled.
 *
 * @return string Font stylesheet or empty string if disabled.
 * @since EVE Online Theme 1.0
 *
 */
if (!function_exists('\Ppfeufer\Theme\EVEOnline\eve_fonts_url')) {
    function eve_fonts_url(): string {
        $fonts_url = '';

        /**
         * Translators: If there are characters in your language that are not
         * supported by Source Sans Pro, translate this to 'off'. Do not translate
         * into your own language.
         */
        $source_sans_pro = _x('on', 'Source Sans Pro font: on or off', 'eve-online');

        /**
         * Translators: If there are characters in your language that are not
         * supported by Bitter, translate this to 'off'. Do not translate into your
         * own language.
         */
        $bitter = _x('on', 'Bitter font: on or off', 'eve-online');

        if ('off' !== $source_sans_pro || 'off' !== $bitter) {
            $font_families = [];

            if ('off' !== $source_sans_pro) {
                $font_families[] = 'Source Sans Pro:300,400,700,300italic,400italic,700italic';

                if ('off' !== $bitter) {
                    $font_families[] = 'Bitter:400,700';

                    $query_args = [
                        'family' => urlencode(implode('|', $font_families)),
                        'subset' => urlencode('latin,latin-ext'),
                    ];
                    $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
                }
            }
        }

        return $fonts_url;
    }
} // END if(!\function_exists('\Ppfeufer\Theme\EVEOnline\eve_fonts_url'))

/**
 * Adding the clearfix CSS class to every paragraph in .entry-content
 */
if (!function_exists('\Ppfeufer\Theme\EVEOnline\eve_paragraph_clearfix')) {
    function eve_paragraph_clearfix($content): array|string|null {
        return preg_replace('/<p([^>]+)?>/', '<p$1 class="clearfix">', $content);
    }
}
// phpcs:disable
//\add_filter('the_content', '\\Ppfeufer\Theme\EVEOnline\eve_paragraph_clearfix');
// phpcs:enable

/**
 * Picking up the first paragraph from the_content
 */
if (!function_exists('\Ppfeufer\Theme\EVEOnline\eve_first_paragraph')) {
    function eve_first_paragraph($content): array|string|null {
        return preg_replace('/<p([^>]+)?>/', '<p$1 class="intro">', $content, 1);
    }
}
// phpcs:disable
//\add_filter('the_content', '\\Ppfeufer\Theme\EVEOnline\eve_first_paragraph');
// phpcs:enable

/**
 * Adding a CSS class to the excerpt
 * @param string $excerpt
 * @return string
 */
if (!function_exists('\Ppfeufer\Theme\EVEOnline\eve_add_class_to_excerpt')) {
    function eve_add_class_to_excerpt($excerpt): array|string {
        return str_replace('<p', '<p class="excerpt"', $excerpt);
    }
}
// phpcs:disable
add_filter('the_excerpt', '\\Ppfeufer\Theme\EVEOnline\eve_add_class_to_excerpt');
// phpcs:enable

/**
 * Define theme's widget areas.
 */
function eve_widgets_init(): void {
    $sidebars = [
        [
            'name' => __('Page Sidebar', 'eve-online'),
            'id' => 'sidebar-page',
            'description' => __('This sidebar will be displayed if the current is a page or your blog index.', 'eve-online'),
            'before_widget' => '<aside><div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div></aside>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ],
        [
            'name' => __('Post Sidebar', 'eve-online'),
            'id' => 'sidebar-post',
            'description' => __('This sidebar will always be displayed if the current is a post / blog article.', 'eve-online'),
            'before_widget' => '<aside><div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div></aside>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ],
        [
            'name' => __('General Sidebar', 'eve-online'),
            'id' => 'sidebar-general',
            'description' => __('General sidebar that is always right from the topic, below the side specific sidebars', 'eve-online'),
            'before_widget' => '<aside><div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div></aside>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ],
        [
            'name' => __('Home Column 1', 'eve-online'),
            'id' => 'home-column-1',
            'description' => __('Home Column 1', 'eve-online'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2>',
            'after_title' => '</h2>'
        ],
        [
            'name' => __('Home Column 2', 'eve-online'),
            'id' => 'home-column-2',
            'description' => __('Home Column 2', 'eve-online'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2>',
            'after_title' => '</h2>'
        ],
        [
            'name' => __('Home Column 3', 'eve-online'),
            'id' => 'home-column-3',
            'description' => __('Home Column 3', 'eve-online'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2>',
            'after_title' => '</h2>'
        ],
        [
            'name' => __('Home Column 4', 'eve-online'),
            'id' => 'home-column-4',
            'description' => __('Home Column 4', 'eve-online'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2>',
            'after_title' => '</h2>'
        ],
        [
            'name' => __('Footer Column 1', 'eve-online'),
            'id' => 'footer-column-1',
            'description' => __('Footer Column 1', 'eve-online'),
            'before_widget' => '<aside><div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div></aside>',
            'before_title' => '<h4>',
            'after_title' => '</h4>'
        ],
        [
            'name' => __('Footer Column 2', 'eve-online'),
            'id' => 'footer-column-2',
            'description' => __('Footer Column 2', 'eve-online'),
            'before_widget' => '<aside><div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div></aside>',
            'before_title' => '<h4>',
            'after_title' => '</h4>'
        ],
        [
            'name' => __('Footer Column 3', 'eve-online'),
            'id' => 'footer-column-3',
            'description' => __('Footer Column 3', 'eve-online'),
            'before_widget' => '<aside><div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div></aside>',
            'before_title' => '<h4>',
            'after_title' => '</h4>'
        ],
        [
            'name' => __('Footer Column 4', 'eve-online'),
            'id' => 'footer-column-4',
            'description' => __('Footer Column 4', 'eve-online'),
            'before_widget' => '<aside><div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div></aside>',
            'before_title' => '<h4>',
            'after_title' => '</h4>'
        ],
        [
            'name' => __('Header Widget Area', 'eve-online'),
            'id' => 'header-widget-area',
            'description' => __('Header Widget Area', 'eve-online'),
            'before_widget' => '<aside><div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div></aside>',
            'before_title' => '<h4>',
            'after_title' => '</h4>'
        ],
    ];

    foreach ($sidebars as $sidebar) {
        register_sidebar($sidebar);
    }
}
// phpcs:disable
add_action('init', '\\Ppfeufer\Theme\EVEOnline\eve_widgets_init');
// phpcs:enable

/**
 * Replaces the excerpt "more" text by a link
 */
if (!function_exists('\Ppfeufer\Theme\EVEOnline\eve_excerpt_more')) {
    function eve_excerpt_more($more): string {
        return ' ' . $more . '<br/><a class="read-more" href="' . get_permalink(get_the_ID()) . '">' . __('Read More', 'eve-online') . '</a>';
    }
}
// phpcs:disable
add_filter('excerpt_more', '\\Ppfeufer\Theme\EVEOnline\eve_excerpt_more');
// phpcs:enable

/**
 * prevent scrolling when using more-tag
 */
function eve_remove_more_link_scroll($link): array|string|null {
    return preg_replace('|#more-[0-9]+|', '', $link);
}
// phpcs:disable
add_filter('the_content_more_link', '\\Ppfeufer\Theme\EVEOnline\eve_remove_more_link_scroll');
// phpcs:enable

/**
 * Adds custom classes to the array of body classes.
 */
if (!function_exists('\Ppfeufer\Theme\EVEOnline\eve_body_classes')) {
    function eve_body_classes($classes): array {
        if (!is_multi_author()) {
            $classes[] = 'single-author';
        }

        return $classes;
    }
}
// phpcs:disable
add_filter('body_class', '\\Ppfeufer\Theme\EVEOnline\eve_body_classes');
// phpcs:enable

/**
 * Add post ID attribute to image attachment pages prev/next navigation.
 */
function eve_enhanced_image_navigation($url): string {
    global $post;

    if (wp_attachment_is_image($post->ID)) {
        $url .= '#main';
    } // END if(wp_attachment_is_image($post->ID))

    return $url;
}
// phpcs:disable
add_filter('attachment_link', '\\Ppfeufer\Theme\EVEOnline\eve_enhanced_image_navigation');
// phpcs:enable

/**
 * Define default page titles.
 */
function eve_wp_title($title, $sep): string {
    global $paged, $page;

    if (is_feed()) {
        return $title;
    }

    // Add the site name.
    $title .= get_bloginfo('name');

    // Add the site description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && (is_home() || is_front_page())) {
        $title = "$title $sep $site_description";
    }

    // Add a page number if necessary.
    if ($paged >= 2 || $page >= 2) {
        $title .= ' ' . $sep . ' ' . sprintf(__('Page %s', 'eve-online'), max($paged, $page));
    } // END if($paged >= 2 || $page >= 2)

    return $title;
}
// phpcs:disable
add_filter('wp_title', '\\Ppfeufer\Theme\EVEOnline\eve_wp_title', 10, 2);
// phpcs:enable

/**
 * Link Pages
 * @param array $args
 * @return void
 * Modification of wp_link_pages() with an extra element to highlight the current page.
 * @author toscho
 * @link http://wordpress.stackexchange.com/questions/14406/how-to-style-current-page-number-wp-link-pages
 */
function eve_link_pages(array $args = []): void {
    $arguments = apply_filters('wp_link_pages_args', wp_parse_args($args, [
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
    ]));

    global $page, $numpages, $multipage, $more;

    if (!$multipage) {
        return;
    }

    $output = $arguments['before'];

    for ($i = 1; $i < ($numpages + 1); $i++) {
        $j = str_replace('%', $i, $arguments['pagelink']);
        $output .= ' ';

        if ($i !== $page || (!$more && 1 === $page)) {
            $output .= $arguments['before_link'] . _wp_link_page($i) . $arguments['link_before'] . $j . $arguments['link_after'] . '</a>' . $arguments['after_link'];
        } else {
            $output .= $arguments['current_before'] . $arguments['link_before'] . '<a>' . $j . '</a>' . $arguments['link_after'] . $arguments['current_after'];
        }
    }

    echo $output . $arguments['after'];
}

/**
 * Disable Smilies
 *
 * @todo Make it configurable
 */
// phpcs:disable
add_filter('option_use_smilies', '__return_false');
// phpcs:enable

/**
 * Adding the custom style to the theme
 */
function eve_get_theme_custom_style(): void {
    $themeSettings = get_option('eve_theme_options', Helper\ThemeHelper::getThemeDefaultOptions());
    $themeCustomStyle = null;

    // background image
    $backgroundImage = Helper\ThemeHelper::getThemeBackgroundImage();

    if (!empty($backgroundImage) && (isset($themeSettings['use_background_image']['yes']) && $themeSettings['use_background_image']['yes'] === 'yes')) {
        $themeCustomStyle .= 'body {background-image:url("' . $backgroundImage . '")}' . "\n";
    }

    if (!empty($themeSettings['background_color'])) {
        $rgbValues = Helper\StringHelper::hextoRgb($themeSettings['background_color'], '0.8');

        $themeCustomStyle .= '.container {background-color:rgba(' . implode(',', $rgbValues) . ');}' . "\n";
    }

    // main navigation
    if (!empty($themeSettings['navigation']['even_cells'])) {
        $themeCustomStyle .= '@media all and (min-width: 768px) {' . "\n";
        $themeCustomStyle .= '  ul.main-navigation {display:table; width:100%;}' . "\n";
        $themeCustomStyle .= '  ul.main-navigation > li {display:table-cell; text-align:center; float:none;}' . "\n";
        $themeCustomStyle .= '}' . "\n";
    }

    wp_add_inline_style('eve-online', $themeCustomStyle);
}
// phpcs:disable
add_action('wp_enqueue_scripts', '\\Ppfeufer\Theme\EVEOnline\eve_get_theme_custom_style');
// phpcs:enable

/* comment form
 * -------------------------------------------------------------------------- */

function eve_comment_form_fields($fields): array {
    $commenter = wp_get_current_commenter();

    $req = get_option('require_name_email');
    $aria_req = ($req ? " aria-required='true' required" : '');
    $html5 = current_theme_supports('html5', 'comment-form') ? 1 : 0;
    $consent = empty($commenter['comment_author_email']) ? '' : ' checked="checked"';

    $fields = [
        'author' => '<div class="row"><div class="form-group comment-form-author col-md-4">'
            . '	<input class="form-control" id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' placeholder="' . __('Name', 'eve-online') . ($req ? ' *' : '') . '" />'
            . '</div>',
        'email' => '<div class="form-group comment-form-email col-md-4">'
            . '	<input class="form-control" id="email" name="email" ' . ($html5 ? 'type="email"' : 'type="text"') . ' value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' placeholder="' . __('Email', 'eve-online') . ($req ? ' *' : '') . '" />'
            . '</div>',
        'url' => '<div class="form-group comment-form-url col-md-4">'
            . '	<input class="form-control" id="url" name="url" ' . ($html5 ? 'type="url"' : 'type="text"') . ' value="' . esc_attr($commenter['comment_author_url']) . '" size="30" placeholder="' . __('Website', 'eve-online') . '" />'
            . '</div></div>',
        // GPDR compliance
        'cookies' => '<div class="row"><div class="form-group checkbox comment-form-cookies-consent col-lg-12">'
            . '<label for="wp-comment-cookies-consent">'
            . '<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />'
            . __('Save my name, email, and website in this browser for the next time I comment.', 'eve-online') . '</label>'
            . '</div></div>',
    ];

    return $fields;
}
// phpcs:disable
add_filter('comment_form_default_fields', '\\Ppfeufer\Theme\EVEOnline\eve_comment_form_fields');
// phpcs:enable

function eve_comment_form($args): array {
    $args['comment_field'] = '<div class="row"><div class="form-group comment-form-comment col-lg-12">'
        . ' <textarea class="form-control" id="comment" name="comment" cols="45" rows="8" aria-required="true" required placeholder="' . _x('Comment', 'noun', 'eve-online') . '"></textarea>'
        . '</div></div>';
    $args['class_submit'] = 'btn btn-default';

    return $args;
}
// phpcs:disable
add_filter('comment_form_defaults', '\\Ppfeufer\Theme\EVEOnline\eve_comment_form');
// phpcs:enable

function eve_move_comment_field_to_bottom($fields): array {
    $comment_field = $fields['comment'];
    $gpdr_field = $fields['cookies'];
    unset($fields['comment'], $fields['cookies']);

    $fields['comment'] = $comment_field;
    $fields['cookies'] = $gpdr_field;

    return $fields;
}
// phpcs:disable
add_filter('comment_form_fields', '\\Ppfeufer\Theme\EVEOnline\eve_move_comment_field_to_bottom');
// phpcs:enable

/**
 * Adding some usefull parameters to the Youtube link when using oEmbed
 *
 * @param string $html
 * @return string
 */
function eve_enable_youtube_jsapi(string $html): string {
    if (str_contains($html, 'youtube.com/embed/')) {
        $html = str_replace('?feature=oembed', '?feature=oembed&enablejsapi=1&origin=' . esc_url(home_url()) . '&rel=0', $html);
    }

    return $html;
}
// phpcs:disable
add_filter('oembed_result', '\\Ppfeufer\Theme\EVEOnline\eve_enable_youtube_jsapi');
// phpcs:enable

/**
 * Removing the version string from any enqueued css and js source
 *
 * @param string $src the css or js source
 * @return string
 */
function eve_remove_wp_ver_css_js(string $src): string {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }

    return $src;
}
// phpcs:disable
add_filter('style_loader_src', '\\Ppfeufer\Theme\EVEOnline\eve_remove_wp_ver_css_js', 9999);
add_filter('script_loader_src', '\\Ppfeufer\Theme\EVEOnline\eve_remove_wp_ver_css_js', 9999);
// phpcs:enable

/**
 * Theme credits in footer
 */
function eve_theme_credits(): void {
    echo sprintf(__('(%1$s design and programming by %2$s)', 'eve-online'), '<a href="https://github.com/ppfeufer/eve-online-wordpress-theme">EVE Online theme</a>', 'Rounon Dax');
}
// phpcs:disable
add_action('eve_online_theme_credits', '\\Ppfeufer\Theme\EVEOnline\eve_theme_credits');
// phpcs:enable
