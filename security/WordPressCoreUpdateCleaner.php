<?php
/**
 * Security
 *
 * Removing files that are not needed form website root
 */
namespace WordPress\Themes\EveOnline\Security;

\defined('ABSPATH') or die();

class WordPressCoreUpdateCleaner {
    /**
     * Sets up hooks, actions and filters that the plugin responds to.
     *
     * @since 1.0
     */
    function __construct() {
        \add_action('_core_updated_successfully', [$this, 'updateCleaner'], 0, 1);
        \add_action('core_upgrade_preamble', [$this, 'updateCleaner']);
        \add_action('upgrader_pre_install', [$this, 'updateCleaner']);
        \add_action('upgrader_post_install', [$this, 'updateCleaner']);
    } // END function __construct()

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
     * @since 1.0
     * @param string New version of updated WordPress.
     */
    function updateCleaner($new_version) {
        global $pagenow, $action;

        if('update-core.php' !== $pagenow) {
            return;
        } // END if('update-core.php' !== $pagenow)

        if('do-core-upgrade' !== $action && 'do-core-reinstall' !== $action) {
            return;
        } // END if('do-core-upgrade' !== $action && 'do-core-reinstall' !== $action)

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

        foreach($remove_files as $file) {
            if(\file_exists(\ABSPATH . $file)) {
                if(\unlink(\ABSPATH . $file)) {
                    \show_message(\__('Removing', 'eve-online') . ' ' . $file . '...');
                } // END if(\unlink(\ABSPATH . $file))
            } // END if(\file_exists(\ABSPATH . $file))
        } // END foreach($remove_files as $file)

        // Load the updated default text localization domain for new strings
        \load_default_textdomain();

        // See do_core_upgrade()
        \show_message(\__('WordPress updated successfully', 'eve-online') . '.');
        \show_message('<span>' . \sprintf(\__('Welcome to WordPress %1$s. <a href="%2$s">Learn more</a>.', 'eve-online'), $new_version, \esc_url(\self_admin_url('about.php?updated'))) . '</span>');
        echo '</div>';

        // Include admin-footer.php and exit
        include(\ABSPATH . 'wp-admin/admin-footer.php');

        exit();
    } // END function updateCleaner($new_version)
} // END class WordPressCoreUpdateCleaner
