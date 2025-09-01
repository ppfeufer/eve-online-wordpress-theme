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

use Ppfeufer\Theme\EVEOnline\Helper\PostHelper;

defined('ABSPATH') or die();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
    <?php
    if (has_post_thumbnail()) {
        ?>
        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute('echo=0'); ?>">
            <figure class="post-loop-thumbnail">
                <?php
                the_post_thumbnail('post-loop-thumbnail');
                ?>
            </figure>
        </a>
        <?php
    } // END if(has_post_thumbnail())
    ?>

    <header class="entry-header">
        <h2 class="entry-title">
            <a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'eve-online'), the_title_attribute('echo=0')); ?>" rel="bookmark">
                <?php the_title(); ?>
            </a>
        </h2>
        <aside class="entry-details">
            <p class="meta">
                <?php
                PostHelper::getPostMetaInformation();
                PostHelper::getPostCategoryAndTags();

                edit_post_link(__('Edit', 'eve-online'));
                ?>
            </p>
        </aside><!--end .entry-details -->
    </header><!--end .entry-header -->

    <section class="post-content clearfix">
        <?php
        if (is_search()) { // Only display excerpts without thumbnails for search.
            ?>
            <div class="entry-summary clearfix">
                <?php the_excerpt(); ?>
            </div><!-- end .entry-summary -->
            <?php
        } else {
            ?>
            <div class="entry-content clearfix">
                <?php
                echo wpautop(do_shortcode(Ppfeufer\Theme\EVEOnline\Helper\StringHelper::cutString(get_the_content(), '140')));
                printf('<a href="%1$s"><span class="read-more">' . __('Read more', 'eve-online') . '</span></a>', get_the_permalink());
                ?>
            </div><!-- end .entry-content -->
            <?php
        }
        ?>
    </section>
</article><!-- /.post-->
