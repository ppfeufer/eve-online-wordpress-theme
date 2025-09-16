<?php

namespace Ppfeufer\Theme\EVEOnline;

use Ppfeufer\Theme\EVEOnline\Helper\StringHelper;
use Ppfeufer\Theme\EVEOnline\Helper\ThemeHelper;

/**
 * Asset Loader
 *
 * This class is responsible for managing the loading of assets (CSS and JavaScript)
 * for the theme, including both frontend and admin-specific assets. It also handles
 * the generation of custom inline styles based on theme settings.
 *
 * @package Ppfeufer\Theme\EVEOnline
 */
class Assets {
    /**
     * Constructor
     *
     * Registers WordPress actions to enqueue styles and scripts for both
     * frontend and admin areas.
     *
     * @return void
     * @access public
     */
    public function __construct() {
        add_action(hook_name: 'wp_enqueue_scripts', callback: [$this, 'loadStyles']);
        add_action(hook_name: 'wp_enqueue_scripts', callback: [$this, 'themeCustomStyle']);
        add_action(hook_name: 'wp_enqueue_scripts', callback: [$this, 'loadScripts']);
        add_action(hook_name: 'admin_enqueue_scripts', callback: [$this, 'loadAdminStyles']);
    }

    /**
     * Load theme CSS
     *
     * Enqueues the theme's frontend stylesheets by iterating over the stylesheets
     * defined in the `ThemeHelper::getThemeStyleSheets()` method.
     *
     * @return void
     */
    public function loadStyles(): void {
        foreach (ThemeHelper::getThemeStyleSheets() as $style) {
            wp_enqueue_style(
                handle: $style['handle'],
                src: $style['src'],
                deps: $style['deps'],
                ver: $style['ver'],
                media: $style['media']
            );
        }
    }

    /**
     * Load admin CSS
     *
     * Enqueues the theme's admin-specific stylesheets by iterating over the stylesheets
     * defined in the `ThemeHelper::getThemeAdminStyleSheets()` method.
     *
     * @return void
     */
    public function loadAdminStyles(): void {
        foreach (ThemeHelper::getThemeAdminStyleSheets() as $style) {
            wp_enqueue_style(
                handle: $style['handle'],
                src: $style['src'],
                deps: $style['deps'],
                ver: $style['ver'],
                media: $style['media']
            );
        }
    }

    /**
     * Load theme JavaScript
     *
     * Enqueues the theme's frontend JavaScript files by iterating over the scripts
     * defined in the `ThemeHelper::getThemeJavaScripts()` method. Also enqueues the
     * `comment-reply` script if threaded comments are enabled.
     *
     * @return void
     */
    public function loadScripts(): void {
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script(handle: 'comment-reply');
        }

        foreach (ThemeHelper::getThemeJavaScripts() as $script) {
            wp_enqueue_script(
                handle: $script['handle'],
                src: $script['src'],
                deps: $script['deps'],
                ver: $script['ver'],
                args: $script['args']
            );

            if (!empty($script['condition'])) {
                wp_script_add_data($script['handle'], 'conditional', $script['condition']);
            }
        }
    }

    /**
     * Load theme custom style
     *
     * Generates and enqueues custom inline styles based on the theme settings.
     * These styles include background images, background colors, and navigation
     * layout adjustments.
     *
     * @return void
     */
    public function themeCustomStyle(): void {
        $themeSettings = get_option('eve_theme_options', ThemeHelper::getThemeDefaultOptions());
        $themeCustomStyle = '';

        // Add background image if enabled in theme settings
        $backgroundImage = ThemeHelper::getThemeBackgroundImage();
        if (!empty($backgroundImage) && ($themeSettings['use_background_image']['yes'] ?? '') === 'yes') {
            $themeCustomStyle .= 'body {background-image:url("' . esc_url($backgroundImage) . '")}' . "\n";
        }

        // Add background color if specified in theme settings
        if (!empty($themeSettings['background_color'])) {
            $rgbValues = StringHelper::hextoRgb($themeSettings['background_color'], '0.8');
            $themeCustomStyle .= '.container {background-color:rgba(' . implode(',', $rgbValues) . ');}' . "\n";
        }

        // Add navigation layout styles if enabled in theme settings
        if (!empty($themeSettings['navigation']['even_cells'])) {
            $themeCustomStyle .= '@media all and (min-width: 768px) {' . "\n";
            $themeCustomStyle .= '  ul.main-navigation {display:table; width:100%;}' . "\n";
            $themeCustomStyle .= '  ul.main-navigation > li {display:table-cell; text-align:center; float:none;}' . "\n";
            $themeCustomStyle .= '}' . "\n";
        }

        // Enqueue the generated inline styles
        wp_add_inline_style('eve-online', $themeCustomStyle);
    }
}
