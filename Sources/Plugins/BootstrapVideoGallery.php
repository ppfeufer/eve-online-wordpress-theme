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
 * Bootstrap Video Gallery
 *
 * Pretty similar to the Bootstrap Image Gallery, but with videos
 * from Youtube and Vimeo. Since WordPress doesn't have an own video gallery
 * we can hijack as we do it with the Bootstrap Image Gallery plugin,
 * so we have to improvise here a bit.
 *
 * First we are going to set up a shortcode [videogallery id="1,2,3,4,5"]
 *
 * As you can see, this shortcode will be the overview of the videos.
 * the "id"-parameter is optinal and will be a comma separated list of pages
 * that are marked as video-page (will explain later). If no id's are given
 * it will show all video-pages as gallery.
 *
 * To mark a page as video-page we will simply add a metabox to the page
 * edit section in which you can tick a check box saying "Is video page" and
 * supply the link to the video in an input field. This plugin will take care
 * of the rest.
 *
 * So basically all you have to do is to create a page for the gallery overview
 * in which you put the shortcode, and a page for every video you want to have
 * in this gallery. Pretty simple, isn't it?
 *
 * I recommend to order the paged hierarchically.
 *        Videos
 *            Video 1
 *            Video 2
 *            Video 3
 *
 * This way the generated permalink will also be hierarchically,
 * which is nice for Google.
 *        http://yourpage.net/videos/
 *            http://yourpage.net/videos/video-1/
 *            http://yourpage.net/videos/video-2/
 *            http://yourpage.net/videos/video-3/
 *
 * Sneaky, huh?
 *
 * @author H.-Peter Pfeufer <develop@ppfeufer.de>
 */

namespace Ppfeufer\Theme\EVEOnline\Plugins;

use Ppfeufer\Theme\EVEOnline\Helper\NavigationHelper;
use Ppfeufer\Theme\EVEOnline\Helper\PostHelper;
use Ppfeufer\Theme\EVEOnline\Helper\StringHelper;
use WP_oEmbed;
use WP_Query;

class BootstrapVideoGallery {
    public function __construct() {
        $this->registerShortcode();
        $this->registerMetabox();
    }

    public function registerShortcode(): void {
        add_shortcode('videogallery', [
            $this,
            'shortcodeVideogallery'
        ]);
    }

    public function registerMetabox(): void {
        add_action('add_meta_boxes', [
            $this,
            'metaboxVideopage'
        ]);

        add_action('save_post', [
            $this,
            'saveMetaboxData'
        ]);
    }

    public function shortcodeVideogallery($attributes): string {
        $args = shortcode_atts([
            'id' => '',
            'videolist' => '',
            'classes' => '',
            'per_page' => 12
        ], $attributes);

        $videoList = $args['videolist'];
        $classes = $args['classes'];
        $perPage = $args['per_page'];

        // loop through the pages and build the gallery code ....
        $uniqueID = uniqid('', true);
        $videoGalleryHtml = null;
        $videoGalleryHtml .= '<div class="gallery-row row">';
        $videoGalleryHtml .= '<ul class="bootstrap-gallery bootstrap-video-gallery bootstrap-video-gallery-' . $uniqueID . ' clearfix">';

        if (empty($videoList)) {
            // assume we have a list of childpages
            $pageID = get_queried_object_id();
            $videoPages = $this->getVideoPages($perPage);
            $pageChildren = get_page_children($pageID, $videoPages->posts);

            if ($pageChildren) {
                $childPages = $this->getVideoPagesFromChildren($pageChildren);
            }

            if ($childPages) {
                foreach ($childPages as $child) {
                    $videoGalleryHtml .= '<li>';
                    $videoGalleryHtml .= $child->eve_page_video_oEmbed_code;
                    $videoGalleryHtml .= '<header><h2 class="video-gallery-title"><a href="' . get_permalink($child->ID) . '">' . $child->post_title . '</a></h2></header>';

                    if ($child->post_content) {
                        $videoGalleryHtml .= '<p>' . StringHelper::cutString($child->post_content, '140') . '</p>';
                    }

                    $videoGalleryHtml .= '</li>';

                    wp_reset_query();
                }
            } else {
                $videoGalleryHtml = false;
            }
        } else {
            if (is_singular()) {
                $videos = explode(',', $videoList);
                $youtubePattern = '/https?:\/\/((m|www)\.)?youtube\.com\/watch.*/i';
                $vimeoPattern = '/https?:\/\/(.+\.)?vimeo\.com\/.*/i';

                $oEmbed = new WP_oEmbed();
                foreach ($videos as $video) {
                    if (preg_match($youtubePattern, $video) || preg_match($vimeoPattern, $video)) {
                        $provider = $oEmbed->get_provider($video);
                        $videoData = $oEmbed->fetch($provider, $video);
                        $videoGalleryHtml .= '<li>';
                        $videoGalleryHtml .= $videoData->html;
                        $videoGalleryHtml .= '<header><h2 class="video-gallery-title"><a href="' . $video . '" rel="external">' . $videoData->title . '</a></h2><span class="bootstrap-video-gallery-video-author small">' . sprintf(__('&copy %1$s', 'eve-online'), $videoData->author_name) . ' (<a href="' . $videoData->author_url . '" rel=external">' . __('Channel', 'eve-online') . '</a>)</span></header>';
                        $videoGalleryHtml .= '</li>';
                    }
                }
            } else {
                $videoGalleryHtml .= '<li>' . __('The video gallery can only be displayed on a single site', 'eve-online') . '</li>';
            }
        }

        $videoGalleryHtml .= '</ul>';
        $videoGalleryHtml .= '</div>';

        if (empty($classes)) {
            $classes = PostHelper::getLoopContentClasses();
        }

        $videoGalleryHtml .= '<script type="text/javascript">
                                jQuery(document).ready(function() {
                                    jQuery("ul.bootstrap-video-gallery-' . $uniqueID . '").bootstrapGallery({
                                        "classes" : "' . $classes . '",
                                        "hasModal" : false
                                    });
                                });
                                </script>';

        if (isset($videoPages) && $videoPages->max_num_pages > 1) {
            $videoGalleryHtml .= '<nav id="nav-videogallery" class="navigation post-navigation clearfix" role="navigation">';
            $videoGalleryHtml .= '<h3 class="assistive-text">' . __('Video Navigation', 'eve-online') . '</h3>';
            $videoGalleryHtml .= '<div class="nav-previous pull-left">';
            $videoGalleryHtml .= NavigationHelper::getNextPostsLink(__('<span class="meta-nav">&larr;</span> Older Videos', 'eve-online'), 0, false, $videoPages);
            $videoGalleryHtml .= '</div>';
            $videoGalleryHtml .= '<div class="nav-next pull-right">';
            $videoGalleryHtml .= NavigationHelper::getPreviousPostsLink(__('Newer Videos <span class="meta-nav">&rarr;</span>', 'eve-online'), false);
            $videoGalleryHtml .= '</div>';
            $videoGalleryHtml .= '</nav><!-- #nav-videogallery .navigation -->';
        }

        return $videoGalleryHtml;
    }

    private function getVideoPages($postPerPage): WP_Query {
        global $paged;

        // Set up the objects needed
        $queryArgs = [
            'posts_per_page' => $postPerPage,
            'post_type' => 'page',
            'meta_key' => 'eve_page_is_video_gallery_page',
            'meta_value' => 1,
            'paged' => $paged
        ];

        return new WP_Query($queryArgs);
    }

    private function getVideoPagesFromChildren($children): ?bool {
        if (!is_array($children) || count($children) === 0) {
            return false;
        }

        $videoPages = null;
        foreach ($children as $id => $child) {
            $eve_page_is_video_gallery_page = get_post_meta($child->ID, 'eve_page_is_video_gallery_page', true);
            $eve_page_is_video_only_list_in_parent = get_post_meta($child->ID, 'eve_page_video_only_list_in_parent_gallery', true);
            $eve_page_video_url = get_post_meta($child->ID, 'eve_page_video_url', true);

            if (isset($eve_page_is_video_gallery_page)) {
                $videoPages[$id] = $child;
                $videoPages[$id]->eve_page_is_video_gallery_page = $eve_page_is_video_gallery_page;
                $videoPages[$id]->eve_page_video_only_list_in_parent_gallery = $eve_page_is_video_only_list_in_parent;
                $videoPages[$id]->eve_page_video_url = $eve_page_video_url;
                $videoPages[$id]->eve_page_video_oEmbed_code = wp_oembed_get($eve_page_video_url);
            }
        }

        return $videoPages;
    }

    public function metaboxVideopage(): void {
        add_meta_box('eve-video-page-box', __('Video Gallery Page?', 'eve-online'), [$this, 'renderVideopageMetabox'], 'page', 'side');
    }

    public function renderVideopageMetabox($post): void {
        $eve_page_is_video_gallery_page = get_post_meta($post->ID, 'eve_page_is_video_gallery_page', true);
        $eve_page_is_video_only_list_in_parent = get_post_meta($post->ID, 'eve_page_video_only_list_in_parent_gallery', true);
        $eve_page_video_url = get_post_meta($post->ID, 'eve_page_video_url', true);
        ?>
        <label><strong><?php
                _e('Video Gallery Settings', 'eve-online'); ?></strong></label>
        <p class="checkbox-wrapper">
            <input id="eve_page_is_video_gallery_page" name="eve_page_is_video_gallery_page" type="checkbox" <?php checked($eve_page_is_video_gallery_page); ?>>
            <label for="eve_page_is_video_gallery_page"><?php _e('Is Video Gallery Page?', 'eve-online'); ?></label>
        </p>
        <p class="checkbox-wrapper">
            <input id="eve_page_video_only_list_in_parent_gallery" name="eve_page_video_only_list_in_parent_gallery" type="checkbox" <?php checked($eve_page_is_video_only_list_in_parent); ?>>
            <label for="eve_page_video_only_list_in_parent_gallery"><?php _e('Only list if it\'s parent gallery', 'eve-online'); ?></label>
        </p>
        <p class="checkbox-wrapper">
            <label for="eve_page_video_url"><?php _e('Video URL:', 'eve-online'); ?></label><br>
            <input id="eve_page_video_url" name="eve_page_video_url" type="text" value="<?php echo $eve_page_video_url; ?>">
        </p>
        <?php
        if (!empty($eve_page_video_url)) {
            ?>
            <p class="checkbox-wrapper">
                <label><strong><?php
                        _e('Your Video', 'eve-online'); ?></strong></label>
                <br>
                <?php
                $oEmbed = wp_oembed_get($eve_page_video_url);
                echo $oEmbed;
                ?>
                <script type="text/javascript">
                    /**
                     * Making the Video container responsive
                     *
                     * @param {type} $
                     * @returns {undefined}
                     */
                    jQuery(function ($) {
                        const $oEmbedVideos = $('#eve-video-page-box iframe[src*="youtube"], #eve-video-page-box iframe[src*="vimeo"]');
                        $oEmbedVideos.each(function () {
                            $(this).removeAttr('height').removeAttr('width').wrap('<div class="embed-video-container"></div>');
                        });
                    });
                </script>
            </p>
            <?php
        }

        wp_nonce_field('save', '_eve_video_page_nonce');
    }

    /**
     * Save the setting
     *
     * @param int $postID
     * @return boolean
     */
    public function saveMetaboxData(int $postID): bool {
        $postNonce = filter_input(INPUT_POST, '_eve_video_page_nonce');

        if (empty($postNonce) || !wp_verify_nonce($postNonce, 'save')) {
            return false;
        }

        if (!current_user_can('edit_post', $postID)) {
            return false;
        }

        if (defined('DOING_AJAX')) {
            return false;
        }

        update_post_meta($postID, 'eve_page_video_url', filter_input(INPUT_POST, 'eve_page_video_url'));

        $isVideoPage = filter_input(INPUT_POST, 'eve_page_is_video_gallery_page') === 'on';
        update_post_meta($postID, 'eve_page_is_video_gallery_page', $isVideoPage);

        $onlyListForParent = filter_input(INPUT_POST, 'eve_page_video_only_list_in_parent_gallery') === 'on';
        update_post_meta($postID, 'eve_page_video_only_list_in_parent_gallery', $onlyListForParent);

        return true;
    }
}
