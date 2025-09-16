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

use Ppfeufer\Theme\EVEOnline\Addons\BootstrapMenuWalker;
use Ppfeufer\Theme\EVEOnline\Helper\ThemeHelper;

defined('ABSPATH') or die();

$options = get_option('eve_theme_options', ThemeHelper::getInstance()->getThemeDefaultOptions());
?>
        </main>

        <footer>
            <div class="footer-wrapper">
                <div class="row">
                    <div class="container">
                        <?php
                        if (ThemeHelper::getInstance()->hasSidebar('footer-column-1') || ThemeHelper::getInstance()->hasSidebar('footer-column-2') || ThemeHelper::getInstance()->hasSidebar('footer-column-3') || ThemeHelper::getInstance()->hasSidebar('footer-column-4')) {
                            get_sidebar('footer');
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="copyright-wrapper">
                <div class="row ">
                    <div class="container">
                        <div class="row copyright">
                            <div class="col-md-12">
                                <div class="pull-left copyright-text">
                                    <?php
                                    if (!empty($options['footertext'])) {
                                        echo '<p>';
                                        echo stripslashes($options['footertext']);
                                        echo '</p>';
                                    }
                                    ?>
                                    <ul class="credit">
                                        <li>
                                            &copy; <?php echo date('Y'); ?> <a href="<?php echo esc_url(home_url()); ?>"><?php bloginfo(); ?></a>
                                        </li>
                                        <li>
                                            <?php
                                            do_action('eve_online_theme_credits');
                                            ?>
                                        </li>
                                    </ul><!-- end .credit -->
                                </div>

                                <div class="footer-menu-wrapper">
                                    <?php
                                    if (has_nav_menu('footer-menu')) {
                                        wp_nav_menu([
                                            'menu' => '',
                                            'theme_location' => 'footer-menu',
                                            'depth' => 1,
                                            'container' => false,
                                            'menu_class' => 'footer-menu',
                                            'fallback_cb' => '\Ppfeufer\Theme\EVEOnline\Addons\BootstrapMenuWalker::fallback',
                                            'walker' => new BootstrapMenuWalker
                                        ]);
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="legal-wrapper">
                <div class="row ">
                    <div class="container">
                        <div class="row copyright">
                            <div class="col-md-12">
                                <h5>CCP Copyright Notice</h5>
                                <p>EVE Online and the EVE logo are the registered trademarks of CCP hf. All rights are reserved worldwide. All other trademarks are the property of their respective owners. EVE Online, the EVE logo, EVE and all associated logos and designs are the intellectual property of CCP hf. All artwork, screenshots, characters, vehicles, storylines, world facts or other recognizable features of the intellectual property relating to these trademarks are likewise the intellectual property of CCP hf.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#pagetop" tabindex="-1" class="totoplink">
                <i class="icon icon-totop"></i>
                <span class="sr-hint">
                    <?php _e('back to top', 'eve-online'); ?>
                </span>
            </a>
        </footer>
        <?php wp_footer(); ?>
    </body>
</html>
