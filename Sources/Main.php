<?php

namespace Ppfeufer\Theme\EVEOnline;

use Ppfeufer\Theme\EVEOnline\Libs\YahnisElsts\PluginUpdateChecker\v5p6\PucFactory;

/**
 * Main Theme Class
 *
 * This class is responsible for the main functionality of the theme, including
 * update checks, hook initialization, and loading necessary classes.
 *
 * @package Ppfeufer\Theme\Ppfeufer
 */
class Main {
    /**
     * Constructor
     *
     * Initializes the theme by performing an update check and setting up hooks.
     *
     * @return void
     * @access public
     */
    public function __construct() {
        $this->doUpdateCheck();
        $this->initializeHooks();
    }

    /**
     * Check GitHub for updates
     *
     * Uses the Plugin Update Checker library to check for updates from the
     * theme's GitHub repository and enables the use of release assets.
     *
     * @return void
     * @access public
     */
    public function doUpdateCheck(): void {
        PucFactory::buildUpdateChecker(
            metadataUrl: THEME_GITHUB_URI, // GitHub repository URL for update metadata
            fullPath: THEME_DIRECTORY, // Full path to the theme directory
            slug: THEME_SLUG // Theme slug
        )->getVcsApi()->enableReleaseAssets();
    }

    /**
     * Initialize hooks
     *
     * Registers WordPress hooks required for the theme's functionality.
     *
     * @return void
     * @access private
     */
    private function initializeHooks(): void {
        add_action(
            hook_name: 'after_setup_theme', // Hook to set up theme defaults
            callback: [$this, 'loadTextDomain'] // Callback to load the theme's text domain
        );
    }

    /**
     * Load text domain
     *
     * Loads the theme's text domain for localization, enabling translation of
     * theme strings.
     *
     * @return void
     * @access public
     */
    public function loadTextDomain(): void {
        load_child_theme_textdomain(
            domain: THEME_SLUG, // Text domain for the theme
            path: THEME_DIRECTORY . '/l10n' // Path to the localization files
        );
    }

    /**
     * Initialize the theme
     *
     * Instantiates and initializes all classes required for the theme's
     * functionality.
     *
     * @return void
     * @access public
     */
    public function init(): void {
        array_map(static fn($class) => new $class(), $this->getClassesToLoad());
    }

    /**
     * Get classes to load
     *
     * Returns an array of classes that need to be loaded for the theme's
     * functionality. Includes both frontend and admin-specific classes.
     *
     * @return array List of class names to be instantiated
     * @access private
     */
    private function getClassesToLoad(): array {
        return [
            Assets::class, // Asset Loader
            Helper\UpdateHelper::class, // Update helper
            Plugins\Metaslider::class, // Meta Slider integration
            Plugins\BootstrapImageGallery::class, // Bootstrap Image Gallery integration
            Plugins\BootstrapVideoGallery::class, // Bootstrap Video Gallery integration
            Plugins\BootstrapContentGrid::class, // Bootstrap Content Grid integration
            Plugins\Corppage::class, // Corppage plugin integration
            Plugins\Whitelabel::class, // Whitelabel plugin integration
            Plugins\ChildpageMenu::class, // Childpage Menu plugin integration
            Plugins\LatestBlogPosts::class, // Latest Blog Posts plugin integration
            Plugins\EveOnlineAvatar::class, // EVE Online Avatar plugin integration
            Plugins\Shortcodes::class, // Theme shortcodes
            ...(is_admin() ? [
                Admin\ThemeSettings::class, // Theme info page
                Security\WordPressCoreUpdateCleaner::class, // Security: Remove unwanted files after WP core update
            ] : [])
        ];
    }
}
