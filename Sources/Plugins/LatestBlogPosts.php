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

/**
 * Latest Blog Posts plugin
 * Displays the latest Blog Posts via Shortcode [latestblogposts number=""]
 */

namespace Ppfeufer\Theme\EVEOnline\Plugins;

use Ppfeufer\Theme\EVEOnline\Helper\PostHelper;
use WP_Query;

class LatestBlogPosts {
    /**
     * Post Helper Instance
     *
     * An instance of the PostHelper class to assist with post-related tasks.
     *
     * @var PostHelper
     * @access private
     */
    private PostHelper $postHelper;


    public function __construct() {
        $this->postHelper = PostHelper::getInstance();

        $this->registerShortcodes();
    }

    public function registerShortcodes(): void {
        add_shortcode('latestblogposts', [$this, 'shortcodeLatestBlogPosts']);
    }

    public function shortcodeLatestBlogPosts($attributes): false|string {
        $args = shortcode_atts([
            'number' => $this->postHelper->getContentColumnCount(),
            'classes' => $this->postHelper->getLoopContentClasses(),
            'headline_type' => 'h2',
            'headline_text' => ''
        ], $attributes);

        $number = $args['number'];
        $classes = $args['classes'];

        $queryArgs = [
            'posts_per_page' => $number,
            'post_type' => 'post',
            'post_status' => 'publish',
            'orderby' => 'post_date',
            'order' => 'DESC',
            'suppress_filters' => true,
            'ignore_sticky_posts' => true
        ];

        $latestPosts = new WP_Query($queryArgs);

        if ($latestPosts->have_posts() && is_page()) {
            ob_start();

            if (!empty($args['headline_text'])) {
                echo '<' . $args['headline_type'] . ' class="latest-blogposts-headline">' . $args['headline_text'] . '</' . $args['headline_type'] . '>';
                echo '<div class="latest-blogposts-headline-decoration"><div class="latest-blogposts-headline-decoration-inside"></div></div>';
            }

            $blogPage = get_option('page_for_posts');
            $uniqueID = uniqid('', true);

            echo '<div class="gallery-row row">';
            echo '<ul class="bootstrap-gallery bootstrap-latest-post-loop bootstrap-latest-post-loop-' . $uniqueID . ' clearfix">';

            while ($latestPosts->have_posts()) {
                $latestPosts->the_post();
                echo '<li class="latest-post-article">';

                get_template_part('content', get_post_format($latestPosts->post_id));
                echo '</li>';
            }

            echo '</ul>';
            echo '</div>';
            echo '<div>'
                . ' <a class="news-more-link" href="' . esc_url(get_permalink($blogPage)) . '">'
                . '     <span class="news-show-all read-more">' . __('Show all article', 'eve-online') . '</span>'
                . ' </a>'
                . '</div>';

            echo '<script type="text/javascript">
                    jQuery(document).ready(function() {
                        jQuery("ul.bootstrap-latest-post-loop-' . $uniqueID . '").bootstrapGallery({
                            "classes" : "' . $classes . '",
                            "hasModal" : false
                        });
                    });
                    </script>';

            $articleLoop = ob_get_clean();
        }

        wp_reset_postdata();

        return $articleLoop;
    }
}
