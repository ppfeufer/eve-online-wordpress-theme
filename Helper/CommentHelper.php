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

namespace WordPress\Themes\EveOnline\Helper;

\defined('ABSPATH') or die();

class CommentHelper {
    public static function getComments($comment, $args, $depth) {
        switch($comment->comment_type) {
            case 'pingback':
            case 'trackback':
                echo self::getTrackbackTemplate();
                break;
            default :
                // Proceed with normal comments.
                global $post;
                ?>
                <li class="comment media" id="comment-<?php \comment_ID(); ?>">
                    <?php echo self::getCommenterAvatar($comment); ?>
                    <div class="media-body">
                        <h4 class="media-heading comment-author vcard">
                            <?php echo self::getCommentAuthor($post, $comment); ?>
                        </h4>
                        <?php
                        echo self::getCommentModerated($comment);

                        \comment_text();
                        ?>
                        <p class="meta">
                            <?php echo self::getCommentMeta($comment); ?>
                        </p>
                        <p class="reply">
                            <?php echo self::getCommentReply($args, $depth); ?>
                        </p>
                    </div> <!--/.media-body -->
                    <?php
                break;
        }
    }

    /**
     * The template for Trackbacks and Pingbacks
     *
     * @return string
     */
    public static function getTrackbackTemplate() {
        $returnValue = '<li class="comment media" id="comment-' . \get_comment_ID() . '">';
        $returnValue .= '<div class="media-body">';
        $returnValue .= '<p>' . \__('Pingback:', 'eve-online') . ' ' . \get_comment_author_link() . '</p>';
        $returnValue .= '</div><!--/.media-body -->';

        return $returnValue;
    }

    /**
     * The commenters avatar
     *
     * @param object $comment
     * @return string
     */
    public static function getCommenterAvatar($comment) {
        $returnValue = null;

        if(!empty($comment->comment_author_url)) {
            $returnValue = '<a href="' . $comment->comment_author_url . '" class="pull-left comment-avatar">' . \get_avatar($comment, 64) . '</a>';
        } else {
            $returnValue = '<span class="pull-left comment-avatar">' . \get_avatar($comment, 64) . '</span>';
        }

        return $returnValue;
    }

    /**
     * Getting the comment author
     *
     * @param object $post
     * @param object $comment
     * @return string
     */
    public static function getCommentAuthor($post, $comment) {
        return \sprintf('<cite class="fn">%1$s %2$s</cite>',
            \get_comment_author_link(),
            // If current post author is also comment author, make it known visually.
            ($comment->user_id === $post->post_author) ? '<span class="label"> ' . \__('Post author', 'eve-online') . '</span> ' : ''
        );
    }

    /**
     * The "Comment awaits moderation" message ...
     *
     * @param object $comment
     * @return string
     */
    public static function getCommentModerated($comment) {
        $returnValue = '';

        if('0' === $comment->comment_approved) {
            $returnValue = '<p class="comment-awaiting-moderation">' . \__('Your comment is awaiting moderation.', 'eve-online') . '</p>';
        }

        return $returnValue;
    }

    /**
     * Getting the comment meta
     *
     * @param object $comment
     * @return string
     */
    public static function getCommentMeta($comment) {
        return \sprintf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
            \esc_url(\get_comment_link($comment->comment_ID)),
            \get_comment_time('c'),
            \sprintf(\__('%1$s at %2$s', 'eve-online'), \get_comment_date(), \get_comment_time())
        );
    }

    /**
     * Getting the comment reply
     *
     * @param array $args
     * @param int $depth
     * @return string
     */
    public static function getCommentReply($args, $depth) {
        return \get_comment_reply_link(\array_merge($args, [
            'reply_text' => \__('Reply <span>&darr;</span>', 'eve-online'),
            'depth' => $depth,
            'max_depth' => $args['max_depth']
        ]));
    }
}
