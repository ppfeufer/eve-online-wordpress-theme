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
use Ppfeufer\Theme\EVEOnline\Helper\EsiHelper;
use Ppfeufer\Theme\EVEOnline\Helper\PostHelper;
use Ppfeufer\Theme\EVEOnline\Helper\ThemeHelper;

defined('ABSPATH') or die();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

        <?php wp_head(); ?>
    </head>

    <body <?php body_class('no-js'); ?> id="pagetop">
        <header>
            <!-- Blog Name & Logo -->
            <div class="top-main-menu">
                <div class="container">
                    <div class="row">
                        <!-- Logo -->
                        <div class="<?php echo PostHelper::getHeaderColClasses(); ?> brand clearfix">
                            <?php
                            $options = get_option('eve_theme_options', ThemeHelper::getThemeDefaultOptions());

                            if (!empty($options['name'])) {
                                $eveApi = EsiHelper::getInstance();
                                $siteLogo = $eveApi->getEntityLogoByName($options['name'], $options['type']);

                                if ($siteLogo !== false) {
                                    ?>
                                    <div class="site-logo float-left">
                                        <a href="<?php echo esc_url(home_url()); ?>"><img src="<?php echo $siteLogo; ?>" class="img-responsive" alt="<?php echo get_bloginfo('name'); ?>"></a>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <div class="site-title">
                                <span class="site-name"><?php echo get_bloginfo('name'); ?></span>
                                <span class="site-description"><?php echo get_bloginfo('description'); ?></span>
                            </div>
                        </div>

                        <!-- Navigation Header -->
                        <div class="col-sm-3 col-md-3 col-sm-12 visible-sm visible-md visible-lg">
                            <div class="top-head-menu">
                                <nav class="navbar navbar-default navbar-headermenu" role="navigation">
                                    <?php
                                    if (has_nav_menu('header-menu')) {
                                        wp_nav_menu([
                                            'menu' => '',
                                            'theme_location' => 'header-menu',
                                            'depth' => 1,
                                            'container' => false,
                                            'menu_class' => 'header-menu nav navbar-nav',
                                            'fallback_cb' => '\Ppfeufer\Theme\EVEOnline\Addons\BootstrapMenuWalker::fallback',
                                            'walker' => new BootstrapMenuWalker
                                        ]);
                                    }
                                    ?>
                                </nav>
                            </div>
                        </div>

                        <!-- Header Widget from Theme options -->
                        <?php
                        if (ThemeHelper::hasSidebar('header-widget-area')) {
                            ?>
                            <div class="col-md-3 col-sm-12">
                                <div class="row">
                                    <div class="col-sm-12 header-widget">
                                        <?php
                                        if (function_exists('\dynamic_sidebar')) {
                                            dynamic_sidebar('header-widget-area');
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <!-- Navigation Main -->
                    <?php
                    if (has_nav_menu('main-menu') || has_nav_menu('header-menu')) {
                        ?>
                        <!-- Menu -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="top-main-menu">
                                    <nav class="<?php if (!has_nav_menu('main-menu')) { echo 'visible-xs ';} // phpcs:ignore ?>navbar navbar-default" role="navigation">
                                        <!-- Brand and toggle get grouped for better mobile display -->
                                        <div class="navbar-header">
                                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                                                <span class="sr-only"><?php __('Toggle navigation', 'eve-online'); ?></span>
                                                <span class="icon-bar"></span>
                                                <span class="icon-bar"></span>
                                                <span class="icon-bar"></span>
                                            </button>
                                            <span class="navbar-toggled-title visible-xs float-right"><?php printf(__('Menu', 'eve-online')) ?></span>
                                        </div>

                                        <!-- Collect the nav links, forms, and other content for toggling -->
                                        <div class="collapse navbar-collapse navbar-ex1-collapse">
                                            <?php
                                            if (has_nav_menu('main-menu')) {
                                                wp_nav_menu([
                                                    'menu' => '',
                                                    'theme_location' => 'main-menu',
                                                    'depth' => 3,
                                                    'container' => false,
                                                    'menu_class' => 'nav navbar-nav main-navigation',
                                                    'fallback_cb' => '\Ppfeufer\Theme\EVEOnline\Addons\BootstrapMenuWalker::fallback',
                                                    'walker' => new BootstrapMenuWalker
                                                ]);
                                            }

                                            if (has_nav_menu('header-menu')) {
                                                $additionalMenuClass = null;
                                                if (has_nav_menu('main-menu')) {
                                                    $additionalMenuClass = ' secondary-mobile-menu';
                                                }

                                                wp_nav_menu([
                                                    'menu' => '',
                                                    'theme_location' => 'header-menu',
                                                    'depth' => 1,
                                                    'container' => false,
                                                    'menu_class' => 'visible-xs header-menu nav navbar-nav' . $additionalMenuClass,
                                                    'fallback_cb' => '\Ppfeufer\Theme\EVEOnline\Addons\BootstrapMenuWalker::fallback',
                                                    'walker' => new BootstrapMenuWalker
                                                ]);
                                            }
                                            ?>
                                        </div><!-- /.navbar-collapse -->
                                    </nav>
                                </div><!-- /.top-main-menu -->
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div><!-- /.container -->
            </div><!-- /.top-main-menu -->

            <div class="stage">
                <div class="container">
                    <?php
                    if (is_single() && has_post_thumbnail()) {
                        ?>
                        <figure class="post-header-image">
                            <?php
                            the_post_thumbnail('header-image');
                            ?>
                        </figure>
                        <?php
                    } elseif (is_category() || is_tax()) {
                        if (function_exists('\z_taxonomy_image')) {
                            $headerImage = z_taxonomy_image(null, 'header-image', null, false);

                            if ($headerImage !== '') {
                                ?>
                                <figure class="post-header-image foobar">
                                    <?php echo $headerImage; ?>
                                </figure>
                                <?php
                            }
                        }
                    } else {
                        /**
                         * Render our Slider, if we have one
                         */
                        do_action('eve_render_header_slider');
                    }
                    ?>
                </div>
            </div>
        </header>
        <!-- End Header. Begin Template Content -->

        <?php
        if ((is_front_page()) && !is_paged()) {
            if (ThemeHelper::hasSidebar('home-column-1') || ThemeHelper::hasSidebar('home-column-2') || ThemeHelper::hasSidebar('home-column-3') || ThemeHelper::hasSidebar('home-column-4')) {
                ?>
                <!--
                // Marketing Stuff / Home Widgets
                -->
                <div class="home-widget-area">
                    <div class="home-widget-wrapper">
                        <div class="row">
                            <div class="container home-widgets">
                                <?php get_sidebar('home'); ?>
                            </div>
                        </div>
                    </div>
                </div><!--/.row -->
                <?php
            }
        }
        ?>

        <main>
