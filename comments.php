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

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php
    if (have_comments()) {
        ?>
        <h3><?php printf(__('Comments for »%1$s«', 'eve-online'), get_the_title()); ?></h3>
        <ul class="media-list">
            <?php
            wp_list_comments([
                'callback' => '\Ppfeufer\Theme\EVEOnline\Helper\CommentHelper::getComments'
            ]);
            ?>
        </ul>

        <?php
        if (get_comment_pages_count() > 1 && get_option('page_comments')) {
            ?>
            <nav id="comment-nav-below" class="navigation" role="navigation">
                <div class="nav-previous">
                    <?php previous_comments_link(__('&larr; Older Comments', 'eve-online')); ?>
                </div>
                <div class="nav-next">
                    <?php next_comments_link(__('Newer Comments &rarr;', 'eve-online')); ?>
                </div>
            </nav>
            <?php
        }
    } elseif (!comments_open() && '0' !== get_comments_number() && post_type_supports(get_post_type(), 'comments')) {
        ?>
        <p class="nocomments"><?php _e('Comments are closed.', 'eve-online'); ?></p>
        <?php
    }

    comment_form();
    ?>
</div>
