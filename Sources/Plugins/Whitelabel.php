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
 * Whitelabeling WordPress
 */

namespace Ppfeufer\Theme\EVEOnline\Plugins;

use Ppfeufer\Theme\EVEOnline\Helper\EsiHelper;
use Ppfeufer\Theme\EVEOnline\Helper\ThemeHelper;

class Whitelabel {
    private ?string $developerName;
    private ?string $developerWebsite;
    private ?string $developerDiscord;

    private ?string $themeName;
    private ?string $themeGithubUri;
    private ?string $themeGithubIssueUri;

    private ?string $themeBackgroundUrl;

    private mixed $themeSettings;

    private EsiHelper|null $eveApi;

    /**
     * Theme Helper Instance
     *
     * An instance of the ThemeHelper class to assist with theme-related tasks.
     *
     * @var ThemeHelper
     * @access private
     */
    private ThemeHelper $themeHelper;

    /**
     * Fire the actions to whitelabel WordPress
     *
     * Maybe edit the .htaccess file aswell
     *      RewriteRule ^login$ http://www.website.de/wp-login.php [NC,L]
     */
    public function __construct() {
        $this->themeHelper = ThemeHelper::getInstance();

        /**
         * Setting Theme Informations
         */
        $this->themeName = 'EVE Online';
        $this->themeGithubUri = 'https://github.com/ppfeufer/eve-online-wordpress-theme';
        $this->themeGithubIssueUri = 'https://github.com/ppfeufer/eve-online-wordpress-theme/issues';

        /**
         * Setting Developer Information
         */
        $this->developerName = '[TN-NT] Rounon Dax';
        $this->developerWebsite = 'https://terra-nanotech.de';
        $this->developerDiscord = 'https://discord.gg/YymuCZa';

        $this->themeBackgroundUrl = $this->getBackgroundImage();

        /**
         * Getting theme settings
         */
        $this->themeSettings = get_option('eve_theme_options', $this->themeHelper->getThemeDefaultOptions());

        /**
         * Starting the helper classes
         */
        $this->eveApi = EsiHelper::getInstance();

        $this->initActions();
        $this->initFilters();
    }

    private function getBackgroundImage(): string {
        return $this->themeHelper->getThemeBackgroundImage();
    }

    public function initActions(): void {
        /**
         * Actions
         */
        add_action('login_head', [$this, 'customLoginLogoStyle']);
        add_action('wp_dashboard_setup', [$this, 'addDashboardWidget']);
    }

    public function initFilters(): void {
        /**
         * Filters
         */
        add_filter('admin_footer_text', [$this, 'modifyAdminFooter']);
        add_filter('login_headerurl', [$this, 'loginLogoUrl']);

        if (version_compare((float)get_bloginfo('version'), '5.2', '<')) {
            add_filter('login_headertitle', [$this, 'loginLogoTitle']);
        } else {
            add_filter('login_headertext', [$this, 'loginLogoTitle']);
        }
    }

    /**
     * Custom URL Title
     *
     * @return string
     */
    public function loginLogoTitle(): string {
        return get_bloginfo('name') . ' - ' . get_bloginfo('description');
    }

    /**
     * Custom URL linked by the Logo on Login page
     *
     * @return string
     */
    public function loginLogoUrl(): string {
        return site_url();
    }

    /**
     * Developer Info in Admin Footer
     */
    public function modifyAdminFooter(string $content): string {
        $content .= sprintf(
            ' | %1$s %2$s',
            __('Customized by:', 'eve-online'),
            ' <a href="' . $this->getDeveloperWebsite() . '" target="_blank">' . $this->getDeveloperName() . '</a>'
        );

        return $content;
    }

    private function getDeveloperWebsite(): ?string {
        return $this->developerWebsite;
    }

    private function getDeveloperName(): ?string {
        return $this->developerName;
    }

    /**
     * Dashboard Widget with Developer Contact Info
     */
    public function themeInfo(): void {
        echo '<ul>
            <li>
                <strong>' . __('Theme:', 'eve-online') . '</strong> ' . $this->getThemeName() .
            sprintf(
                __(' (%1$s | %2$s)', 'eve-online'),
                '<a href="' . $this->getThemeGithubUri() . '" target="_blank">Github</a>',
                '<a href="' . $this->getThemeGithubIssueUri() . '" target="_blank">Issue Tracker</a>'
            ) . '
            </li>
            <li><strong>' . __('Customized by:', 'eve-online') . '</strong> ' . $this->getDeveloperName() . '</li>
            <li><strong>' . __('Discord:', 'eve-online') . '</strong> <a href="' . $this->getDeveloperDiscord() . '" target="_blank">' . $this->getDeveloperDiscord() . '</a></li>
        </ul>';
    }

    private function getThemeName(): ?string {
        return $this->themeName;
    }

    private function getThemeGithubUri(): ?string {
        return $this->themeGithubUri;
    }

    private function getThemeGithubIssueUri(): ?string {
        return $this->themeGithubIssueUri;
    }

    private function getDeveloperDiscord(): ?string {
        return $this->developerDiscord;
    }

    public function addDashboardWidget(): void {
        wp_add_dashboard_widget('wp_dashboard_widget', __('Developer Contact', 'eve-online'), [$this, 'themeInfo']);
    }

    /**
     * Custom Logo on Login Page
     */
    public function customLoginLogoStyle(): void {
        $type = (!empty($this->themeSettings['type'])) ? $this->themeSettings['type'] : null;
        $hasCustomLoginLogo = !empty($this->themeSettings['custom_login_logo']);

        $size = 320;

        if ($this->getLoginLogo() === null) {
            $size = 1;
        }

        if ($type !== null && $hasCustomLoginLogo === false) {
            $size = ($type === 'alliance') ? 128 : 256;
        }

        echo $this->getStyleSheet($size);
    }

    /**
     * Getting the Login Screen Logo
     *
     * @return string|null The Login Logo URL
     */
    private function getLoginLogo(): ?string {
        $logo = null;

        /**
         * Check if we have a custom login logo or not and use
         * what ever we might have as logo here.
         */
        if (empty($this->themeSettings['custom_login_logo'])) {
            $type = (!empty($this->themeSettings['type'])) ? $this->themeSettings['type'] : null;
            $name = (!empty($this->themeSettings['name'])) ? $this->themeSettings['name'] : null;

            if ($type !== null && $name !== null) {
                $size = ($type === 'alliance') ? 128 : 256;

                // getting the logo
                $logo = $this->getEveApi()->getEntityLogoByName($name, $type, true, $size);
            }
        }

        return $logo;
    }

    public function getEveApi(): EsiHelper|null {
        return $this->eveApi;
    }

    /**
     * Getting the Login Page CSS
     *
     * @param int $logoSize
     * @return string The Login Page CSS
     */
    private function getStyleSheet(int $logoSize = 320): string {
        return '<style>
        body {
            background-image: url("' . $this->getThemeBackgroundUrl() . '");
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
        }
        h1 a {
            background-image:url(' . $this->getLoginLogo() . ') !important;
            background-size: cover !important;
            height: ' . 1 / 16 * $logoSize . 'rem !important;
            width: ' . 1 / 16 * $logoSize . 'rem !important;
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
        .login form {
            margin-top: 0.625rem !important;
            background-color: rgba(255,255,255,0.7);
        }
        .login input[type="text"], .login input[type="password"] {
            background-color: rgba(251,251,251,0.5);
        }
        </style>';
    }

    private function getThemeBackgroundUrl(): ?string {
        return $this->themeBackgroundUrl;
    }
}
