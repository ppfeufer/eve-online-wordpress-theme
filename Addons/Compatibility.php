<?php

namespace WordPress\Themes\EveOnline\Addons;

/**
 * EVE Online Theme back compat functionality
 *
 * Prevents EVE Online Theme from running on WordPress versions prior to 4.7,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.7.
 *
 * @package WordPress
 * @subpackage Eve_Online
 * @since EVE Online Theme 0.1-r20170324
 */

/**
 * Prevent switching to EVE Online Theme on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since EVE Online Theme 0.1-r20170324
 */
function eve_switch_theme() {
    \switch_theme(\WP_DEFAULT_THEME);

    unset($_GET['activated']);

    \add_action('admin_notices', '\\WordPress\Themes\EveOnline\Addons\eve_upgrade_notice');
}
\add_action('after_switch_theme', '\\WordPress\Themes\EveOnline\Addons\eve_switch_theme');

/**
 * Adds a message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * EVE Online Theme on WordPress versions prior to 4.7.
 *
 * @since EVE Online Theme 0.1-r20170324
 *
 * @global string $wp_version WordPress version.
 */
function eve_upgrade_notice() {
    $message = \sprintf(\__('EVE Online Theme requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'eve-online'), \get_bloginfo('version'));

    \printf('<div class="error"><p>%s</p></div>', $message);
}

/**
 * Prevents the Customizer from being loaded on WordPress versions prior to 4.7.
 *
 * @since EVE Online Theme 0.1-r20170324
 *
 * @global string $wp_version WordPress version.
 */
function eve_customize() {
    \wp_die(\sprintf(\__('EVE Online Theme requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'eve-online'), \get_bloginfo('version')), '', [
        'back_link' => true,
    ]);
}
\add_action('load-customize.php', '\\WordPress\Themes\EveOnline\Addons\eve_customize');

/**
 * Prevents the Theme Preview from being loaded on WordPress versions prior to 4.7.
 *
 * @since EVE Online Theme 0.1-r20170324
 *
 * @global string $wp_version WordPress version.
 */
function eve_preview() {
    $preview = \filter_input('get', 'preview');

    if(!empty($preview)) {
        \wp_die(\sprintf(\__('EVE Online Theme requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'eve-online'), \get_bloginfo('version')));
    }
}
\add_action('template_redirect', '\\WordPress\Themes\EveOnline\Addons\eve_preview');
