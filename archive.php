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
        <div class="clearfix">
            <div class="col-md-12 breadcrumb-wrapper">
                <?php echo $breadcrumbNavigation; ?>
            </div><!--/.col -->
        </div><!--/.row -->
        <?php
    }
    ?>

    <!--<div class="row main-content">-->
    <div class="main-content clearfix">
        <div class="<?php echo PostHelper::getMainContentColClasses(); ?> content-wrapper">
            <div class="content content-inner content-archive">
                <header class="page-title">
                    <h1>
                        <?php
                        if (is_day()) {
                            printf(__('Daily Archives: %s', 'eve-online'), '<span>' . get_the_date() . '</span>');
                        } elseif (is_month()) {
                            printf(__('Monthly Archives: %s', 'eve-online'), '<span>' . get_the_date(_x('F Y', 'monthly archives date format', 'eve-online')) . '</span>');
                        } elseif (is_year()) {
                            printf(__('Yearly Archives: %s', 'eve-online'), '<span>' . get_the_date(_x('Y', 'yearly archives date format', 'eve-online')) . '</span>');
                        } elseif (is_tag()) {
                            printf(__('Tag Archives: %s', 'eve-online'), '<span>' . single_tag_title('', false) . '</span>');

                            // Show an optional tag description
                            $tag_description = tag_description();
                            if ($tag_description) {
                                echo apply_filters('tag_archive_meta', '<div class="tag-archive-meta">' . $tag_description . '</div>');
                            }
                        } elseif (is_category()) {
                            printf(__('Category Archives: %s', 'eve-online'), '<span>' . single_cat_title('', false) . '</span>');
                        } else {
                            _e('Blog Archives', 'eve-online');
                        }
                        ?>
                    </h1>
                    <?php
                    // Show an optional category description
                    $category_description = category_description();
                    if ($category_description) {
                        echo apply_filters('category_archive_meta', '<div class="category-archive-meta">' . $category_description . '</div>');
                    }
                    ?>
                </header>
                <?php
                if (have_posts()) {
                    $uniqueID = uniqid('', true);

                    if (get_post_type() === 'post') {
                        echo '<div class="gallery-row row">';
                        echo '<ul class="bootstrap-gallery bootstrap-post-loop-gallery bootstrap-post-loop-gallery-' . $uniqueID . ' clearfix">';
                    }

                    while (have_posts()) {
                        the_post();

                        if (get_post_type() === 'post') {
                            echo '<li>';
                        }

                        get_template_part('content', get_post_format());

                        if (get_post_type() === 'post') {
                            echo '</li>';
                        }
                    }

                    if (get_post_type() === 'post') {
                        echo '</ul>';
                        echo '</div>';

                        echo '<script type="text/javascript">
                                jQuery(document).ready(function() {
                                    jQuery("ul.bootstrap-post-loop-gallery-' . $uniqueID . '").bootstrapGallery({
                                        "classes" : "' . PostHelper::getLoopContentClasses() . '",
                                        "hasModal" : false
                                    });
                                });
                                </script>';
                    }
                }

                if (function_exists('wp_pagenavi')) {
                    wp_pagenavi();
                } else {
                    NavigationHelper::getContentNav('nav-below');
                }
                ?>
            </div> <!-- /.content -->
        </div> <!-- /.col -->

        <?php
        if (ThemeHelper::hasSidebar('sidebar-page') || ThemeHelper::hasSidebar('sidebar-general')) {
            ?>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3 sidebar-wrapper">
            <?php
            if (ThemeHelper::hasSidebar('sidebar-general')) {
                get_sidebar('general');
            }

            if (ThemeHelper::hasSidebar('sidebar-page')) {
                get_sidebar('page');
            }
            ?>
            </div><!--/.col -->
            <?php
        }
        ?>
    </div> <!--/.row -->
</div><!-- container -->

<?php get_footer(); ?>
