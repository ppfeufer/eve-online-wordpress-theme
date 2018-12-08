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
 * Class Name: BootstrapImageGallery
 *
 * based on: https://github.com/twittem/wp-bootstrap-gallery
 */

namespace WordPress\Themes\EveOnline\Plugins;

use \WordPress\Themes\EveOnline\Helper\PostHelper;

\defined('ABSPATH') or die();

class BootstrapImageGallery {
    public function __construct() {
        $this->init();
    } // END public function __construct()

    public function init() {
        \add_filter('post_gallery', [$this, 'imageGallery'], 10, 2);
    }

    public function imageGallery($content, $attr) {
        global $instance, $post;

        unset($content); // we don't need it here

        $instance++;

        if(isset($attr['orderby'])) {
            $attr['orderby'] = \sanitize_sql_orderby($attr['orderby']);

            if(!$attr['orderby']) {
                unset($attr['orderby']);
            }
        }

        \extract(\shortcode_atts([
            'order' => 'ASC',
            'orderby' => 'menu_order ID',
            'id' => $post->ID,
            'itemtag' => 'figure',
            'icontag' => 'div',
            'captiontag' => 'figcaption',
            'columns' => 3,
            'size' => 'thumbnail',
            'include' => '',
            'exclude' => ''
        ], $attr));

        $id = \intval($id);

        if('RAND' == $order) {
            $orderby = 'none';
        }

        if($include) {
            $include = \preg_replace('/[^0-9,]+/', '', $include);

            $_attachments = \get_posts([
                'include' => $include,
                'post_status' => 'inherit',
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'order' => $order,
                'orderby' => $orderby
            ]);

            $attachments = [];

            foreach($_attachments as $key => $val) {
                $attachments[$val->ID] = $_attachments[$key];
            }
        } elseif($exclude) {
            $exclude = \preg_replace('/[^0-9,]+/', '', $exclude);

            $attachments = \get_children([
                'post_parent' => $id,
                'exclude' => $exclude,
                'post_status' => 'inherit',
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'order' => $order,
                'orderby' => $orderby
            ]);
        } else {
            $attachments = \get_children([
                'post_parent' => $id,
                'post_status' => 'inherit',
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'order' => $order,
                'orderby' => $orderby
            ]);
        }

        if(empty($attachments)) {
            return;
        }

        if(\is_feed()) {
            $output = "\n";

            foreach($attachments as $att_id => $attachment) {
                $output .= \wp_get_attachment_link($att_id, 'post-loop-thumbnail', true) . "\n";
            }

            return $output;
        }

        $itemtag = \tag_escape($itemtag);
        $captiontag = \tag_escape($captiontag);

        $selector = 'image-gallery-' . $instance;
        $output = '<div class="gallery-row row">';
        $output .= '<ul class="bootstrap-gallery bootstrap-image-gallery bootstrap-' . $selector . ' clearfix">';


        foreach($attachments as $id => $attachment) {
            if(\function_exists('\fly_get_attachment_image')) {
                $galleryImage = \fly_get_attachment_image($id, 'post-loop-thumbnail');
            } else {
                $galleryImage = \wp_get_attachment_image($id, 'post-loop-thumbnail');
            }

            $fullImage = \wp_get_attachment_image_src($id, 'full');
            $attachment = \wp_prepare_attachment_for_js($id);

            $output .= '<li>';
            $output .= '<' . $itemtag . ' class="bootstrap-gallery-image" data-fullsizeImage="' . $fullImage['0'] . '">';
            $output .= $galleryImage;

            if(!empty($attachment['caption'])) {
                $output .= '<' . $captiontag . '>';
                $output .= $attachment['caption'];
                $output .= '</' . $captiontag . '>';
            }

            $output .= '</' . $itemtag . '>' . "\n";
            $output .= '</li>' . "\n";
        }

        $output .= '</ul></div>' . "\n";
        $output .= '<script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery("ul.bootstrap-' . $selector . '").bootstrapGallery({
                    "classes" : "' . PostHelper::getLoopContentClasses() . '",
                    "hasModal" : true
                });
            });
            </script>';

        return $output;
    }

    /**
     * Getting pages and Articles that contain a gallery
     *
     * @return array
     */
    public function getGalleryPages() {
        $args = [
            'posts_per_page' => -1,
            'category' => 0,
            'post_type' => 'any',
            'post_status' => 'publish',
            'orderby' => 'post_date',
            'order' => 'DESC',
            'suppress_filters' => true
        ];

        $result = new \WP_Query($args);

        $pattern = \get_shortcode_regex();

        foreach($result->posts as $post) {
            /**
             * Check if the gallery shortcode has been used
             */
            if(\has_shortcode($post->post_content, 'gallery')) {
                /**
                 * Check if it is really a gallery and not a hoax, in other
                 * words, if there are really some images in it.
                 */
                if(\preg_match_all('/'. $pattern .'/s', $post->post_content, $matches) && \array_key_exists(2, $matches) && \in_array('gallery', $matches[2])) {
                    $keys = \array_keys( $matches[2], 'gallery' );

                    foreach($keys as $key) {
                        $atts = \shortcode_parse_atts($matches[3][$key]);

                        if(\is_array($atts) && \array_key_exists('ids', $atts)) {
                            $items[$post->ID] = $post->post_title;
                        }
                    }
                }
            }
        }

        \wp_reset_postdata();

        return $items;
    }

    /**
     * Get all images from a gallery page in an array
     *
     * @param int $postID
     * @return array
     */
    public function getGalleryImages($postID) {
        $post = \get_post($postID, \OBJECT);

        if(\has_shortcode($post->post_content, 'gallery')) {
            /**
             * Check if it is really a gallery and not a hoax, in other
             * words, if there are really some images in it.
             */
            $pattern = get_shortcode_regex();
            $galleryImages = null;

            if(\preg_match_all('/'. $pattern .'/s', $post->post_content, $matches) && \array_key_exists(2, $matches) && \in_array('gallery', $matches[2])) {
                $keys = \array_keys( $matches[2], 'gallery' );

                foreach($keys as $key) {
                    $atts = \shortcode_parse_atts($matches[3][$key]);

                    /**
                     * Only add the page to our array if it has some
                     * images in its gallery
                     */
                    if(\is_array($atts) && \array_key_exists('ids', $atts)) {
                        $images = new \WP_Query([
                            'post_type' => 'attachment',
                            'post_status' => 'inherit',
                            'post__in' => \explode( ',', $atts['ids'] ),
                            'orderby' => 'post__in'
                        ]);

                        if($images->have_posts()) {
                            foreach($images->posts as $image) {
                                $galleryImages[$image->ID] = $image->guid;
                            }
                        }

                        \wp_reset_query();
                    }
                }
            }
        }

        return $galleryImages;
    }
}
