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
        <div class="row">
            <div class="col-md-12 breadcrumb-wrapper">
                <?php echo $breadcrumbNavigation; ?>
            </div><!--/.col -->
        </div><!--/.row -->
        <?php
    }
    ?>

    <div class="row main-content">
        <div class="<?php echo \WordPress\Themes\EveOnline\Helper\PostHelper::getMainContentColClasses(); ?> content-wrapper">
            <div class="content content-inner content-search">
                <?php
                if(\have_posts()) {
                    ?>
                    <header class="post-title">
                        <h1><?php \printf(\__('Search Results for: %s', 'eve-online'), '<span>' . \get_search_query() . '</span>'); ?></h1>
                    </header>
                    <?php
                } else {
                    ?>
                    <header class="post-title">
                        <h1><?php \_e('No Results Found', 'eve-online'); ?></h1>
                    </header>
                    <?php
                }

                if(\have_posts()) {
                    while(\have_posts()) {
                        \the_post();
                        \get_template_part('content', \get_post_format());
                    }
                } else {
                    ?>
                    <p class="lead">
                        <?php \_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps you should try again with a different search term.', 'eve-online'); ?>
                    </p>

                    <div class="well">
                        <?php \get_search_form(); ?>
                    </div><!--/.well -->
                    <?php
                }
                ?>
            </div> <!-- /.content -->
        </div> <!-- /.col -->

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
