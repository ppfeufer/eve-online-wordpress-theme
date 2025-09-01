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

use Ppfeufer\Theme\EVEOnline\Helper\NavigationHelper;

defined('ABSPATH') or die();

/**
 * Template Name: Default Page (Full Width)
 */

get_header();
?>

<div class="container main">
    <?php
    $breadcrumbNavigation = NavigationHelper::getBreadcrumbNavigation();

    if (!empty($breadcrumbNavigation)) {
        ?>
        <!--
        // Breadcrumb Navigation
        -->
        <!--<div class="row">-->
        <div class="clearfix">
            <div class="col-md-12 breadcrumb-wrapper">
                <?php echo $breadcrumbNavigation; ?>
            </div><!--/.col -->
        </div><!--/.row -->
        <?php
    }
    ?>

    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            ?>
            <!--<div class="row main-top">-->
            <div class="main-top clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <header>
                        <h1>
                            <?php
                            if (is_front_page()) {
                                ?>
                                <h1><?php echo get_bloginfo('name'); ?></h1>
                                <?php
                            } else {
                                ?>
                                <h1><?php the_title(); ?></h1>
                                <?php
                            }
                            ?>
                        </h1>
                    </header>
                </div><!--/.col -->
            </div><!--/.row -->

            <!--<div class="row main-content">-->
            <div class="main-content clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 content-wrapper">
                    <div class="content content-inner content-full-width content-page">
                        <article class="post clearfix" id="post-<?php the_ID(); ?>">
                            <?php the_content(); ?>
                        </article>
                    </div> <!-- /.content -->
                </div> <!-- /.col -->
            </div> <!--/.row -->
            <?php
        }
    }
    ?>
</div><!-- /.container -->

<?php get_footer(); ?>
