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
 * Security
 *
 * Removing files that are not needed form website root
 */

namespace Ppfeufer\Theme\EVEOnline\Security;

class WordPressCoreUpdateCleaner {
    /**
     * Sets up hooks, actions and filters that the plugin responds to.
     *
     * @since 1.0
     */
    public function __construct() {
        add_action('_core_updated_successfully', [$this, 'updateCleaner'], 0);
        add_action('core_upgrade_preamble', [$this, 'updateCleaner']);
        add_action('upgrader_pre_install', [$this, 'updateCleaner']);
        add_action('upgrader_post_install', [$this, 'updateCleaner']);
    }

    /**
     * Performs the update cleaning.
     *
     * This function removes the unwanted files when WordPress is updated. The
     * cleaning is performed on core update and core re-install. The function removes
     * wp-config-sample.php, readme.html and localized versions of the readme and
     * license files. If the files are removed successfully, the plugin outputs
     * a response message to the core update screen letting you know which files
     * that were removed.
     *
     * @param string $new_version New version of updated WordPress.
     * @since 1.0
     */
    public function updateCleaner(string $new_version): void {
        global $pagenow, $action;

        if ('update-core.php' !== $pagenow) {
            return;
        } // END if('update-core.php' !== $pagenow)

        if ('do-core-upgrade' !== $action && 'do-core-reinstall' !== $action) {
            return;
        }

        // Remove license, readme files
        $remove_files = [
            'license.txt',
            'licens.html',
            'licenza.html',
            'licencia.txt',
            'licenc.txt',
            'licencia-sk_SK.txt',
            'licens-sv_SE.txt',
            'liesmich.html',
            'LEGGIMI.txt',
            'lisenssi.html',
            'olvasdel.html',
            'readme.html',
            'readme-ja.html',
            'wp-config-sample.php'
        ];

        foreach ($remove_files as $file) {
            if (file_exists(ABSPATH . $file) && unlink(ABSPATH . $file)) {
                show_message(__('Removing', 'eve-online') . ' ' . $file . '...');
            }
        }

        // Load the updated default text localization domain for new strings
        load_default_textdomain();

        // See do_core_upgrade()
        show_message(__('WordPress updated successfully', 'eve-online') . '.');
        show_message('<span>' . sprintf(__('Welcome to WordPress %1$s. <a href="%2$s">Learn more</a>.', 'eve-online'), $new_version, esc_url(self_admin_url('about.php?updated'))) . '</span>');
        echo '</div>';

        // Include admin-footer.php and exit
        include(ABSPATH . 'wp-admin/admin-footer.php');

        exit();
    }
}
