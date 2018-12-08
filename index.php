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

defined('ABSPATH') or die();

\get_header();
?>

<div class="container main">
    <?php
    $breadcrumbNavigation = \WordPress\Themes\EveOnline\Helper\NavigationHelper::getBreadcrumbNavigation();
    if(!empty($breadcrumbNavigation)) {
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

    <!--<div class="row main-content">-->
    <div class="main-conten clearfixt">
        <div class="<?php echo \WordPress\Themes\EveOnline\Helper\PostHelper::getMainContentColClasses(); ?> content-wrapper">
            <div class="content content-inner content-index content-loop">
                <?php
                if(\have_posts()) {
                    if(\get_post_type() === 'post') {
                        $uniqueID = \uniqid();

                        echo '<div class="gallery-row row">';
                        echo '<ul class="bootstrap-gallery bootstrap-post-loop-gallery bootstrap-post-loop-gallery-' . $uniqueID . ' clearfix">';
                    }

                    while(\have_posts()) {
                        \the_post();

                        if(\get_post_type() === 'post') {
                            echo '<li>';
                        }

                        \get_template_part('content', \get_post_format());

                        if(\get_post_type() === 'post') {
                            echo '</li>';
                        }
                    } // END while (have_posts())

                    if(\get_post_type() === 'post') {
                        echo '</ul>';
                        echo '</div>';

                        echo '<script type="text/javascript">
                                jQuery(document).ready(function() {
                                    jQuery("ul.bootstrap-post-loop-gallery-' . $uniqueID . '").bootstrapGallery({
                                        "classes" : "' . \WordPress\Themes\EveOnline\Helper\PostHelper::getLoopContentClasses() . '",
                                        "hasModal" : false
                                    });
                                });
                                </script>';
                    }
                } else {

                }

                if(\function_exists('\wp_pagenavi')) {
                    \wp_pagenavi();
                } else {
                    \WordPress\Themes\EveOnline\Helper\NavigationHelper::getContentNav('nav-below');
                }
                ?>
            </div>
        </div><!--/.col -->

        <?php
        if(\WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-page') || \WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-general')) {
            ?>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3 sidebar-wrapper">
                <?php
                if(\WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-page')) {
                    \get_sidebar('page');
                }

                if(\WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-general')) {
                    \get_sidebar('general');
                }
                ?>
            </div><!--/.col -->
            <?php
        }
        ?>
    </div> <!--/.row -->
</div><!-- container -->

<?php \get_footer(); ?>
