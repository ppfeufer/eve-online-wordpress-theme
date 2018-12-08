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
?>

<article id="post-<?php the_ID(); ?>" <?php \post_class('clearfix content-single'); ?>>
    <header class="entry-header">
        <h1 class="entry-title">
            <?php \the_title(); ?>
        </h1>
        <aside class="entry-details">
            <p class="meta">
                <?php
                echo \WordPress\Themes\EveOnline\Helper\PostHelper::getPostMetaInformation();
                \WordPress\Themes\EveOnline\Helper\PostHelper::getPostCategoryAndTags();
                \edit_post_link(\__('Edit', 'eve-online'));
                ?>
            </p>
        </aside><!--end .entry-details -->
    </header><!--end .entry-header -->

    <section class="post-content clearfix">
        <div class="entry-content clearfix">
            <?php
            echo \the_content();

            if(\function_exists('\WordPress\Themes\EveOnline\eve_link_pages')) {
                \WordPress\Themes\EveOnline\eve_link_pages([
                    'before' => '<ul class="pagination">',
                    'after' => '</ul>',
                    'before_link' => '<li>',
                    'after_link' => '</li>',
                    'current_before' => '<li class="active">',
                    'current_after' => '</li>',
                    'previouspagelink' => '&laquo;',
                    'nextpagelink' => '&raquo;'
                ]);
            } else {
                \wp_link_pages( [
                    'before' => '<div class="page-links">' . __('Pages:', 'eve-online'),
                    'after'  => '</div>',
                ]);
            }
            ?>
        </div>
    </section>

    <?php
    // AUTHOR INFO
    if(\get_the_author_meta('description')) {
        ?>
        <hr/>
        <div class="author-info clearfix">
            <div class="author-details">
                <h3>
                    <?php
                    echo \__('Written by ', 'eve-online');
                    echo \get_the_author();
                    ?>
                </h3>
                <?php echo \get_avatar(\get_the_author_meta('user_email')); ?>
            </div><!-- end .author-details -->
            <div class="author-description">
                <?php echo \wpautop(\get_the_author_meta('description')); ?>
            </div>
        </div><!-- end .author-info -->
        <?php
    }
    ?>
    <hr/>
    <?php \WordPress\Themes\EveOnline\Helper\NavigationHelper::getArticleNavigation(true); ?>
    <hr/>
    <?php \comments_template(); ?>
</article><!-- /.post-->
