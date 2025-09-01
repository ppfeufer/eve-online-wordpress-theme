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
use Ppfeufer\Theme\EVEOnline\Helper\PostHelper;
use Ppfeufer\Theme\EVEOnline\Helper\ThemeHelper;

defined('ABSPATH') or die();

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
    } // END if(!empty($breadcrumbNavigation))
    ?>

    <!--<div class="row main-content">-->
    <div class="main-content clearfix">
        <div class="<?php echo PostHelper::getMainContentColClasses(); ?> content-wrapper">
            <div class="content content-inner single">
                <?php
                if (have_posts()) {
                    while (have_posts()) {
                        the_post();
                        get_template_part('content-single');
                    }
                }
                ?>
            </div> <!-- /.content -->
        </div> <!-- /.col-lg-9 /.col-md-9 /.col-sm-9 /.col-9 -->

        <?php
        if (ThemeHelper::hasSidebar('sidebar-post') || ThemeHelper::hasSidebar('sidebar-general')) {
            ?>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3 sidebar-wrapper">
                <?php
                if (ThemeHelper::hasSidebar('sidebar-general')) {
                    get_sidebar('general');
                }

                if (ThemeHelper::hasSidebar('sidebar-post')) {
                    get_sidebar('post');
                }
                ?>
                </div><!--/.col -->
            <?php
        }
        ?>
    </div> <!-- /.row -->
</div> <!-- /.container -->

<?php get_footer(); ?>
