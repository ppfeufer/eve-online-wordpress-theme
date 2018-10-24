<?php
/**
 * Whitelabeling WordPress
 */

namespace WordPress\Themes\EveOnline\Plugins;

use WordPress\Themes\EveOnline;

\defined('ABSPATH') or die();

class Whitelabel {
    private $developerName = null;
    private $developerWebsite = null;
    private $developerDiscord = null;

    private $themeName = null;
    private $themeGithubUri = null;
    private $themeGithubIssueUri = null;

    private $themeBackgroundUrl = null;
    private $customLoginLogo = null;

    private $themeSettings = null;

    private $eveApi = null;

    /**
     * Fire the actions to whitelabel WordPress
     *
     * Maybe edit the .htaccess file aswell
     *      RewriteRule ^login$ http://www.website.de/wp-login.php [NC,L]
     */
    public function __construct() {
        /**
         * Setting Theme Informations
         */
        $this->themeName = 'EVE Online';
        $this->themeGithubUri = 'https://github.com/ppfeufer/eve-online-wordpress-theme';
        $this->themeGithubIssueUri = 'https://github.com/ppfeufer/eve-online-wordpress-theme/issues';

        /**
         * Setting Developer Information
         */
        $this->developerName = 'YF [TN-NT] Rounon Dax';
        $this->developerWebsite = 'https://yulaifederation.net';
        $this->developerDiscord = 'https://discord.gg/YymuCZa';

        $this->themeBackgroundUrl = $this->getBackgroundImage();
        $this->customLoginLogo = $this->getLoginLogo();

        /**
         * Getting theme settings
         */
        $this->themeSettings = \get_option('eve_theme_options', EveOnline\Helper\ThemeHelper::getThemeDefaultOptions());

        /**
         * Starting the helper classes
         */
        $this->eveApi = EveOnline\Helper\EsiHelper::getInstance();

        $this->initActions();
        $this->initFilters();
    }

    public function initActions() {
        /**
         * Actions
         */
        \add_action('login_head', [$this, 'customLoginLogoStyle']);
        \add_action('wp_dashboard_setup', [$this, 'addDashboardWidget']);
    }

    public function initFilters() {
        /**
         * Filters
         */
        \add_filter('admin_footer_text', [$this, 'modifyAdminFooter']);
        \add_filter('login_headerurl', [$this, 'loginLogoUrl']);
        \add_filter('login_headertitle', [$this, 'loginLogoTitle']);
    }

    private function getBackgroundImage() {
        return EveOnline\Helper\ThemeHelper::getThemeBackgroundImage();
    }

    /**
     * Custom URL Title
     *
     * @return Ambigous <string, mixed>
     */
    public function loginLogoTitle() {
        return \get_bloginfo('name') . ' - ' . \get_bloginfo('description');
    }

    /**
     * Custom URL linked by the Logo on Login page
     *
     * @return Ambigous <string, mixed, boolean>
     */
    public function loginLogoUrl() {
        return \site_url();
    }

    /**
     * Developer Info in Admin Footer
     */
    public function modifyAdminFooter() {
        echo \sprintf('<span id="footer-thankyou">%1$s</span> %2$s',
            \__('Customized by:', 'eve-online'),
            ' <a href="' . $this->developerWebsite . '" target="_blank">' . $this->developerName . '</a>'
        );
    }

    /**
     * Dashboard Widget with Developer Contact Info
     */
    public function themeInfo() {
        echo '<ul>
            <li>
                <strong>' . \__('Theme:', 'eve-online') . '</strong> ' . $this->themeName .
                \sprintf(\__(' (%1$s | %2$s)', 'eve-online'),
                    '<a href="' . $this->themeGithubUri . '" target="_blank">Github</a>',
                    '<a href="' . $this->themeGithubIssueUri . '" target="_blank">Issue Tracker</a>'
                ) . '
            </li>
            <li><strong>' . \__('Customized by:', 'eve-online') . '</strong> ' . $this->developerName . '</li>
            <li><strong>' . \__('Discord:', 'eve-online') . '</strong> <a href="' . $this->developerDiscord . '" target="_blank">' . $this->developerDiscord . '</a></li>
        </ul>';
    }

    public function addDashboardWidget() {
        \wp_add_dashboard_widget('wp_dashboard_widget', \__('Developer Contact', 'eve-online'), [$this, 'themeInfo']);
    }

    /**
     * Custom Logo on Login Page
     */
    public function customLoginLogoStyle() {
        $type = (!empty($this->themeSettings['type'])) ? $this->themeSettings['type'] : null;
        $hasCustomLoginLogo = (!empty($this->themeSettings['custom_login_logo'])) ? true : false;

        $size = 320;

        if($this->getLoginLogo() === null) {
            $size = 1;
        }

        if($type !== null && $hasCustomLoginLogo === false) {
                $size = ($type === 'alliance') ? 128 : 256;
        }

        echo $this->getStyleSheet($size);
    }

    /**
     * Getting the Login Page CSS
     *
     * @param int $logoSize
     * @return string The Login Page CSS
     */
    private function getStyleSheet($logoSize = 320) {
        return '<style type="text/css">
        body {
            background-image: url("' . $this->themeBackgroundUrl . '");
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
        }
        h1 a {
            background-image:url(' . $this->getLoginLogo() . ') !important;
            background-size: cover !important;
            height: ' . $logoSize . 'px !important;
            width: ' . $logoSize . 'px !important;
            height: ' . 1 / 16 * $logoSize . 'rem !important;
            width: ' . 1 / 16 * $logoSize . 'rem !important;
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
        .login form {
            margin-top: 10px !important;
            margin-top: 0.625rem !important;
            background-color: rgba(255,255,255,0.7);
        }
        .login input[type="text"], .login input[type="password"] {
            background-color: rgba(251,251,251,0.5);
        }
        </style>';
    }

    /**
     * Getting the Login Screen Logo
     *
     * @return type
     */
    private function getLoginLogo() {
        $logo = null;

        /**
         * Check if we have a custom login logo or not and use
         * what ever we might have as logo here.
         */
        if(!empty($this->themeSettings['custom_login_logo'])) {
            // Do nothing here
        } else {
            $type = (!empty($this->themeSettings['type'])) ? $this->themeSettings['type'] : null;
            $name = (!empty($this->themeSettings['name'])) ? $this->themeSettings['name'] : null;

            if($type !== null && $name !== null) {
                $size = ($type === 'alliance') ? 128 : 256;

                // getting the logo
                $logo = $this->eveApi->getEntityLogoByName($name, $type, true, $size);
            }
        }

        return $logo;
    }
}
