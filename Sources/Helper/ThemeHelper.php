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

namespace Ppfeufer\Theme\EVEOnline\Helper;

class ThemeHelper {
    /**
     * themeDbVersion
     *
     * @var string
     */
    protected static string $themeDbVersion = '20170803';

    /**
     * Return the current DB version used for the themes settings
     *
     * @return string
     */
    public static function getThemeDbVersion(): string {
        return self::$themeDbVersion;
    }

    /**
     * Returning the theme's default options
     *
     * @return array
     */
    public static function getThemeDefaultOptions(): array {
        $defaultOptions = [
            // generel settings tab
            'type' => '',
            'name' => '',
            'corp_logos_in_menu' => [
                'show' => 'show'
            ],
            'navigation' => [
                'even_cells' => ''
            ],
            'post_meta' => [
                'show' => ''
            ],
            'cache' => [
                'remote-image-cache' => 'remote-image-cache'
            ],
            'remote_image_cache_time' => '86400',

            // background settings tab
            'use_background_image' => [
                'yes' => 'yes'
            ],
            'background_image' => 'amarr.jpg',
            'background_image_upload' => '',
            'background_color' => '',

            // slider settings tab
            'default_slider' => '',
            'default_slider_on' => [
                'frontpage_only' => 'frontpage_only'
            ],

            // performance tab
            'minify_html_output' => [
                'yes' => ''
            ],

            // footer settings tab
            'footertext' => '',
        ];

        return apply_filters('eve_theme_options', $defaultOptions);
    }

    /**
     * Returning some theme related data
     *
     * @param string $parameter
     * @return string
     *
     * @link https://developer.wordpress.org/reference/functions/wp_get_theme/
     */
    public static function getThemeData(string $parameter): string {
        return wp_get_theme()->get($parameter);
    }

    /**
     * Return the theme's javascripts
     *
     * @return array
     */
    public static function getThemeJavaScripts(): array {
        return [
            /* Bootstrap's JS */
            'Bootstrap' => [
                'handle' => 'bootstrap-js',
                'source' => get_theme_file_uri('/Assets/libs/bootstrap/3.3.7/js/bootstrap.min.js'),
                'deps' => [
                    'jquery'
                ],
                'version' => '3.3.7',
                'in_footer' => true
            ],
            /* Bootstrap Toolkit */
            'Bootstrap Toolkit' => [
                'handle' => 'bootstrap-toolkit-js',
                'source' => get_theme_file_uri('/Assets/libs/bootstrap/toolkit/bootstrap-toolkit.min.js'),
                'deps' => [
                    'bootstrap-js'
                ],
                'version' => '2.6.3',
                'in_footer' => true
            ],
            /* Bootstrap Gallery */
            'Bootstrap Gallery' => [
                'handle' => 'bootstrap-gallery-js',
                'source' => get_theme_file_uri('/Assets/libs/jQuery/bootstrap-gallery/jquery.bootstrap-gallery.min.js'),
                'deps' => [
                    'jquery'
                ],
                'version' => sanitize_title(self::getThemeData('Name')) . '-' . self::getThemeData('Version'),
                'in_footer' => true
            ],
            /* The main JS */
            'EVE Online' => [
                'handle' => 'eve-online-main-js',
                'source' => get_theme_file_uri('/Assets/js/eve-online.min.js'),
                'deps' => [
                    'jquery'
                ],
                'version' => sanitize_title(self::getThemeData('Name')) . '-' . self::getThemeData('Version'),
                'in_footer' => true
            ]
        ];
    }

    /**
     * Return the theme's stylesheets
     *
     * @return array
     */
    public static function getThemeStyleSheets(): array {
        return [
            /* Normalize CSS */
            'Normalize CSS' => [
                'handle' => 'normalize',
                'source' => get_theme_file_uri('/Assets/css/normalize.min.css'),
                'deps' => [],
                'version' => '3.0.3',
                'media' => 'all'
            ],
            /* Bootstrap */
            'Bootstrap' => [
                'handle' => 'bootstrap',
                'source' => get_theme_file_uri('/Assets/libs//bootstrap/3.3.7/css/bootstrap.min.css'),
                'deps' => [
                    'normalize'
                ],
                'version' => '3.3.7',
                'media' => 'all'
            ],
            /* Bootstrap Additional CSS */
            'Bootstrap Additional CSS' => [
                'handle' => 'bootstrap-additional',
                'source' => get_theme_file_uri('/Assets/css/bootstrap-additional.min.css'),
                'deps' => [
                    'bootstrap'
                ],
                'version' => '3.3.7',
                'media' => 'all'
            ],
            /* Theme Main CSS */
            'EVE Online Theme Styles' => [
                'handle' => 'eve-online',
                'source' => get_theme_file_uri('/style.min.css'),
                'deps' => [
                    'normalize',
                    'bootstrap'
                ],
                'version' => sanitize_title(self::getThemeData('Name')) . '-' . self::getThemeData('Version'),
                'media' => 'all'
            ],
            /* Theme Responsive CSS */
            'EVE Online Responsive Styles' => [
                'handle' => 'eve-online-responsive-styles',
                'source' => get_theme_file_uri('/Assets/css/responsive.min.css'),
                'deps' => [
                    'eve-online'
                ],
                'version' => sanitize_title(self::getThemeData('Name')) . '-' . self::getThemeData('Version'),
                'media' => 'all'
            ],
            /* Adjustment to Plugins */
            'EVE Online Plugin Styles' => [
                'handle' => 'eve-online-plugin-styles',
                'source' => get_theme_file_uri('Assets/css/plugin-tweaks.min.css'),
                'deps' => [
                    'eve-online'
                ],
                'version' => sanitize_title(self::getThemeData('Name')) . '-' . self::getThemeData('Version'),
                'media' => 'all'
            ],
        ];
    }

    /**
     * Return the theme's admin stylesheets
     *
     * @return array
     */
    public static function getThemeAdminStyleSheets(): array {
        return [
            /* Adjustment to Plugins */
            'EVE Online Admin Styles' => [
                'handle' => 'eve-online-admin-styles',
                'source' => get_template_directory_uri() . '/Assets/css/eve-online-admin-style.min.css',
                'deps' => [],
                'version' => sanitize_title(self::getThemeData('Name')) . '-' . self::getThemeData('Version'),
                'media' => 'all'
            ],
        ];
    }

    /**
     * Update the options array for our theme, if needed
     *
     * @param string $optionsName
     * @param string $dbVersionFieldName
     * @param string $newDbVersion
     * @param array $defaultOptions
     */
    public static function updateOptions(string $optionsName, string $dbVersionFieldName, string $newDbVersion, array $defaultOptions): void {
        $currentDbVersion = get_option($dbVersionFieldName);

        // Check if the DB needs to be updated
        if ($currentDbVersion !== $newDbVersion) {
            $currentOptions = get_option($optionsName);

            if (is_array($currentOptions)) {
                $newOptions = array_merge($defaultOptions, $currentOptions);
            } else {
                $newOptions = $defaultOptions;
            }

            // Update the options
            update_option($optionsName, $newOptions);

            // Update the DB Version
            update_option($dbVersionFieldName, $newDbVersion);
        }
    }

    /**
     * Alias for is_active_sidebar()
     *
     * @param string $sidebarPosition
     * @return boolean
     * @uses is_active_sidebar() Whether a sidebar is in use.
     */
    public static function hasSidebar(string $sidebarPosition): bool {
        return is_active_sidebar($sidebarPosition);
    }

    /**
     * Getting the default background mages that are shipped with the theme
     *
     * @param boolean $withThumbnail
     * @param string|null $baseClass
     * @return array|null
     */
    public static function getDefaultBackgroundImages(bool $withThumbnail = false, string $baseClass = null): ?array {
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

            // we are only looking for images
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

            /**
             * Special case:
             *  Current EVE Expansion theme Image
             */
            return ['current-eve-expansion' => $currentEveExpansionThemeImage] + $images;
        }

        return null;
    }

    /**
     * Getting the themes background image
     *
     * @return string
     */
    public static function getThemeBackgroundImage(): string {
        $themeSettings = get_option('eve_theme_options', self::getThemeDefaultOptions());

        $backgroundImage = get_template_directory_uri() . '/img/background/' . $themeSettings['background_image'];

        if ($themeSettings['background_image'] === 'current-eve-expansion') {
            $backgroundImage = 'https://web.ccpgamescdn.com/aws/eveonline/sso/background.jpg';
        }

        $uploadedBackground = !empty($themeSettings['background_image_upload']);

        // we have an uploaded image, so overwrite the background
        if ($uploadedBackground === true) {
            $backgroundImage = wp_get_attachment_url($themeSettings['background_image_upload']);
        }

        return $backgroundImage;
    }

    /**
     * Getting the theme's name
     *
     * @return string Theme Name
     */
    public static function getThemeName(): string {
        return 'EVE Online';
    }
}
