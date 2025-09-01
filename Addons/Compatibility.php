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

namespace Ppfeufer\Theme\EVEOnline\Addons;

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
    switch_theme(WP_DEFAULT_THEME);

    unset($_GET['activated']);

    add_action('admin_notices', '\\Ppfeufer\Theme\EVEOnline\Addons\eve_upgrade_notice');
}
// phpcs:disable
add_action('after_switch_theme', '\\Ppfeufer\Theme\EVEOnline\Addons\eve_switch_theme');
// phpcs:enable

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
    $message = sprintf(__('EVE Online Theme requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'eve-online'), get_bloginfo('version'));

    printf('<div class="error"><p>%s</p></div>', $message);
}

/**
 * Prevents the Customizer from being loaded on WordPress versions prior to 4.7.
 *
 * @since EVE Online Theme 0.1-r20170324
 *
 * @global string $wp_version WordPress version.
 */
function eve_customize() {
    wp_die(sprintf(__('EVE Online Theme requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'eve-online'), get_bloginfo('version')), '', [
        'back_link' => true,
    ]);
}
// phpcs:disable
add_action('load-customize.php', '\\Ppfeufer\Theme\EVEOnline\Addons\eve_customize');
// phpcs:enable

/**
 * Prevents the Theme Preview from being loaded on WordPress versions prior to 4.7.
 *
 * @since EVE Online Theme 0.1-r20170324
 *
 * @global string $wp_version WordPress version.
 */
function eve_preview() {
    $preview = filter_input('get', 'preview');

    if (!empty($preview)) {
        wp_die(sprintf(__('EVE Online Theme requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'eve-online'), get_bloginfo('version')));
    }
}
// phpcs:disable
add_action('template_redirect', '\\Ppfeufer\Theme\EVEOnline\Addons\eve_preview');
// phpcs:enable
