<?php

namespace Ppfeufer\Theme\EVEOnline;

use Ppfeufer\Theme\EVEOnline\Libs\YahnisElsts\PluginUpdateChecker\v5p6\PucFactory;

/**
 * Main Theme Class
 *
 * This class is responsible for the main functionality of the theme.
 *
 * @package Ppfeufer\Theme\Ppfeufer
 */
class Main {
    /**
     * Constructor
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
     * @return void
     * @access public
     */
    public function doUpdateCheck(): void {
        PucFactory::buildUpdateChecker(
            metadataUrl: THEME_GITHUB_URI,
            fullPath: THEME_DIRECTORY,
            slug: THEME_SLUG
        )->getVcsApi()->enableReleaseAssets();
    }

    /**
     * Initialize hooks
     *
     * @return void
     * @access private
     */
    private function initializeHooks(): void {
        add_action(
            hook_name: 'after_setup_theme',
            callback: [$this, 'loadTextDomain']
        );
    }

    /**
     * Load text domain
     *
     * @return void
     * @access public
     */
    public function loadTextDomain(): void {
        load_child_theme_textdomain(
            domain: THEME_SLUG,
            path: THEME_DIRECTORY . '/l10n'
        );
    }

    /**
     * Initialize the theme
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
     * @return array
     * @access private
     */
    private function getClassesToLoad(): array {
        return [
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
