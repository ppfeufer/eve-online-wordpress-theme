<?php

namespace Ppfeufer\Theme\EVEOnline\Helper;

use Ppfeufer\Theme\EVEOnline\Singletons\GenericSingleton;

/**
 * ThemeHelper
 *
 * A helper class for various theme-related functionality, such as managing
 * theme assets, settings, and configurations.
 *
 * @package Ppfeufer\Theme\EVEOnline\Helper
 */
class ThemeHelper extends GenericSingleton {
    /**
     * The database version for the theme's settings.
     *
     * @var string
     */
    protected string $themeDbVersion = '20170803';

    /**
     * Get the current database version used for the theme's settings.
     *
     * @return string The database version.
     */
    public function getThemeDbVersion(): string {
        return $this->themeDbVersion;
    }

    /**
     * Get the theme's JavaScript files.
     *
     * @return array An array of JavaScript files with their configurations.
     */
    public function getThemeJavaScripts(): array {
        return [
            // Bootstrap's JavaScript
            'Bootstrap' => [
                'handle' => 'bootstrap-js',
                'src' => get_theme_file_uri('/Assets/libs/bootstrap/3.3.7/js/bootstrap.min.js'),
                'deps' => ['jquery'],
                'ver' => '3.3.7',
                'args' => ['strategy' => 'async', 'in_footer' => true]
            ],
            // Bootstrap Toolkit
            'Bootstrap Toolkit' => [
                'handle' => 'bootstrap-toolkit-js',
                'src' => get_theme_file_uri('/Assets/libs/bootstrap/toolkit/bootstrap-toolkit.min.js'),
                'deps' => ['bootstrap-js'],
                'ver' => '2.6.3',
                'args' => ['strategy' => 'async', 'in_footer' => true]
            ],
            // Bootstrap Gallery
            'Bootstrap Gallery' => [
                'handle' => 'bootstrap-gallery-js',
                'src' => get_theme_file_uri('/Assets/libs/jQuery/bootstrap-gallery/jquery.bootstrap-gallery.min.js'),
                'deps' => ['jquery'],
                'ver' => sanitize_title($this->getThemeVersionForAssets()),
                'args' => ['strategy' => 'async', 'in_footer' => true]
            ],
            // Main JavaScript for the theme
            'EVE Online' => [
                'handle' => 'eve-online-main-js',
                'src' => get_theme_file_uri('/Assets/js/eve-online.min.js'),
                'deps' => ['jquery'],
                'ver' => sanitize_title($this->getThemeVersionForAssets()),
                'args' => ['strategy' => 'async', 'in_footer' => true]
            ]
        ];
    }

    /**
     * Get a string for versioning assets.
     *
     * @return string The version string.
     */
    private function getThemeVersionForAssets(): string {
        return sanitize_title($this->getThemeName()) . '-' . $this->getThemeVersion();
    }

    /**
     * Get the theme's name.
     *
     * @return string The theme name.
     */
    public function getThemeName(): string {
        return $this->getThemeData('Name');
    }

    /**
     * Get theme-related data based on a parameter.
     *
     * @param string $parameter The parameter to retrieve (e.g., 'Name', 'Version').
     * @return string The requested theme data.
     *
     * @link https://developer.wordpress.org/reference/functions/wp_get_theme/
     */
    public function getThemeData(string $parameter): string {
        return wp_get_theme()->get($parameter);
    }

    /**
     * Get the theme's version.
     *
     * @return string The theme version.
     */
    public function getThemeVersion(): string {
        return $this->getThemeData('Version');
    }

    /**
     * Get the theme's stylesheets.
     *
     * @return array An array of stylesheets with their configurations.
     */
    public function getThemeStyleSheets(): array {
        return [
            // Normalize CSS
            'Normalize CSS' => [
                'handle' => 'normalize',
                'src' => get_theme_file_uri('/Assets/css/normalize.min.css'),
                'deps' => [],
                'ver' => '3.0.3',
                'media' => 'all'
            ],
            // Bootstrap CSS
            'Bootstrap' => [
                'handle' => 'bootstrap',
                'src' => get_theme_file_uri('/Assets/libs//bootstrap/3.3.7/css/bootstrap.min.css'),
                'deps' => ['normalize'],
                'ver' => '3.3.7',
                'media' => 'all'
            ],
            // Additional Bootstrap CSS
            'Bootstrap Additional CSS' => [
                'handle' => 'bootstrap-additional',
                'src' => get_theme_file_uri('/Assets/css/bootstrap-additional.min.css'),
                'deps' => ['bootstrap'],
                'ver' => '3.3.7',
                'media' => 'all'
            ],
            // Main theme styles
            'EVE Online Theme Styles' => [
                'handle' => 'eve-online',
                'src' => get_theme_file_uri('/style.min.css'),
                'deps' => ['normalize', 'bootstrap'],
                'ver' => sanitize_title($this->getThemeVersionForAssets()),
                'media' => 'all'
            ],
            // Responsive styles
            'EVE Online Responsive Styles' => [
                'handle' => 'eve-online-responsive-styles',
                'src' => get_theme_file_uri('/Assets/css/responsive.min.css'),
                'deps' => ['eve-online'],
                'ver' => sanitize_title($this->getThemeVersionForAssets()),
                'media' => 'all'
            ],
            // Plugin-specific styles
            'EVE Online Plugin Styles' => [
                'handle' => 'eve-online-plugin-styles',
                'src' => get_theme_file_uri('Assets/css/plugin-tweaks.min.css'),
                'deps' => ['eve-online'],
                'ver' => sanitize_title($this->getThemeVersionForAssets()),
                'media' => 'all'
            ],
        ];
    }

    /**
     * Get the theme's admin stylesheets.
     *
     * @return array An array of admin stylesheets with their configurations.
     */
    public function getThemeAdminStyleSheets(): array {
        return [
            // Admin-specific styles
            'EVE Online Admin Styles' => [
                'handle' => 'eve-online-admin-styles',
                'src' => get_template_directory_uri() . '/Assets/css/eve-online-admin-style.min.css',
                'deps' => [],
                'ver' => sanitize_title($this->getThemeVersionForAssets()),
                'media' => 'all'
            ],
        ];
    }

    /**
     * Add theme support for various WordPress features.
     *
     * @return void
     */
    public function addThemeSupport(): void {
        add_theme_support('automatic-feed-links');
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('post-formats', [
            'aside', 'image', 'gallery', 'link', 'quote', 'status', 'video', 'audio', 'chat'
        ]);
        add_theme_support('html5', [
            'comment-form', 'comment-list', 'gallery', 'caption',
        ]);
    }

    /**
     * Register the theme's navigation menus.
     *
     * @return void
     */
    public function registerNavMenus(): void {
        register_nav_menus([
            'main-menu' => __('Main Menu', 'eve-online'),
            'header-menu' => __('Header Menu', 'eve-online'),
            'footer-menu' => __('Footer Menu', 'eve-online'),
        ]);
    }

    /**
     * Add custom thumbnail sizes for the theme.
     *
     * @return void
     */
    public function addThumbnailSizes(): void {
        set_post_thumbnail_size(1680, 500);
        add_image_size('header-image', 1680, 500, true);
        add_image_size('post-loop-thumbnail', 768, 432, true);
    }

    /**
     * Update the theme's options if the database version has changed.
     *
     * @param string $optionsName The name of the options.
     * @param string $dbVersionFieldName The name of the database version field.
     * @param string $newDbVersion The new database version.
     * @param array $defaultOptions The default options array.
     * @return void
     */
    public function updateOptions(string $optionsName, string $dbVersionFieldName, string $newDbVersion, array $defaultOptions): void {
        $currentDbVersion = get_option($dbVersionFieldName);

        if ($currentDbVersion !== $newDbVersion) {
            $currentOptions = get_option($optionsName);
            $newOptions = is_array($currentOptions) ? array_merge($defaultOptions, $currentOptions) : $defaultOptions;

            update_option($optionsName, $newOptions);
            update_option($dbVersionFieldName, $newDbVersion);
        }
    }

    /**
     * Check if a sidebar is active.
     *
     * @param string $sidebarPosition The sidebar position identifier.
     * @return bool True if the sidebar is active, false otherwise.
     */
    public function hasSidebar(string $sidebarPosition): bool {
        return is_active_sidebar($sidebarPosition);
    }

    /**
     * Get the default background images shipped with the theme.
     *
     * @param bool $withThumbnail Whether to include thumbnails.
     * @param string|null $baseClass An optional base class for thumbnails.
     * @return array|null An array of background images or null if none found.
     */
    public function getDefaultBackgroundImages(bool $withThumbnail = false, string $baseClass = null): ?array {
        $imagePath = get_template_directory() . '/Assets/img/background/';
        $handle = opendir($imagePath);

        if ($baseClass !== null) {
            $baseClass = '-' . $baseClass;
        }

        if ($handle) {
            while (false !== ($entry = readdir($handle))) {
                $files[$entry] = $entry;
            }

            closedir($handle);
            $images = preg_grep('/\.(jpg|jpeg|png|gif)(?:[?#].*)?$/i', $files);

            $currentEveExpansionThemeImage = 'https://web.ccpgamescdn.com/aws/eveonline/sso/background.jpg';

            if ($withThumbnail === true) {
                foreach ($images as &$image) {
                    $imageName = ucwords(str_replace('-', ' ', preg_replace('/\\.[^.\\s]{3,4}$/', '', $image)));
                    $image = '<figure class="bg-image' . $baseClass . '"><img src="' . get_template_directory_uri() . '/Assets/img/background/' . $image . '" style="width:100px; height:auto;" title="' . $imageName . '" alt="' . $imageName . '"><figcaption>' . $imageName . '</figcaption></figure>';
                }

                unset($image);

                $currentEveExpansionThemeImage = '<figure class="bg-image' . $baseClass . '"><img src="' . $currentEveExpansionThemeImage . '" style="width:100px; height:auto;" title="' . __('Current EVE Online Expansion', 'eve-online') . '" alt="' . __('Current EVE Online Expansion', 'eve-online') . '"><figcaption>' . __('Current EVE Expansion', 'eve-online') . '</figcaption></figure>';
            }

            return ['current-eve-expansion' => $currentEveExpansionThemeImage] + $images;
        }

        return null;
    }

    /**
     * Get the theme's background image.
     *
     * @return string The URL of the background image.
     */
    public function getThemeBackgroundImage(): string {
        $themeSettings = get_option('eve_theme_options', $this->getThemeDefaultOptions());
        $backgroundImage = get_template_directory_uri() . '/img/background/' . $themeSettings['background_image'];

        if ($themeSettings['background_image'] === 'current-eve-expansion') {
            $backgroundImage = 'https://web.ccpgamescdn.com/aws/eveonline/sso/background.jpg';
        }

        if (!empty($themeSettings['background_image_upload'])) {
            $backgroundImage = wp_get_attachment_url($themeSettings['background_image_upload']);
        }

        return $backgroundImage;
    }

    /**
     * Get the theme's default options.
     *
     * @return array The default options array.
     */
    public function getThemeDefaultOptions(): array {
        $defaultOptions = [
            'type' => '',
            'name' => '',
            'corp_logos_in_menu' => ['show' => 'show'],
            'navigation' => ['even_cells' => ''],
            'post_meta' => ['show' => ''],
            'cache' => ['remote-image-cache' => 'remote-image-cache'],
            'remote_image_cache_time' => '86400',
            'use_background_image' => ['yes' => 'yes'],
            'background_image' => 'amarr.jpg',
            'background_image_upload' => '',
            'background_color' => '',
            'default_slider' => '',
            'default_slider_on' => ['frontpage_only' => 'frontpage_only'],
            'minify_html_output' => ['yes' => ''],
            'footertext' => '',
        ];

        return apply_filters('eve_theme_options', $defaultOptions);
    }
}
