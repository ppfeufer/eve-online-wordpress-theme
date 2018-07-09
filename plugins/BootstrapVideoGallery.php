<?php
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
 *		Videos
 *			Video 1
 *			Video 2
 *			Video 3
 *
 * This way the generated permalink will also be hierarchically,
 * which is nice for Google.
 *		http://yourpage.net/videos/
 *			http://yourpage.net/videos/video-1/
 *			http://yourpage.net/videos/video-2/
 *			http://yourpage.net/videos/video-3/
 *
 * Sneaky, huh?
 *
 * @author H.-Peter Pfeufer <dev@ppfeufer.de>
 */

namespace WordPress\Themes\EveOnline\Plugins;

use WordPress\Themes\EveOnline;

\defined('ABSPATH') or die();

class BootstrapVideoGallery {
    public function __construct() {
        $this->registerShortcode();
        $this->registerMetabox();
    } // END public function __construct()

    public function registerShortcode() {
        \add_shortcode('videogallery', [
            $this,
            'shortcodeVideogallery'
        ]);
    } // END public function registerShortcode()

    public function shortcodeVideogallery($attributes) {
        $args = \shortcode_atts([
            'id' => '',
            'videolist' => '',
            'classes' => '',
            'per_page' => 12
        ], $attributes);

        $id = $args['id'];
        $videoList = $args['videolist'];
        $classes = $args['classes'];
        $perPage = $args['per_page'];
        $idList = null;

        if(!empty($id)) {
            $idList = (\preg_match('/,( )/', $id)) ? \explode(',', $id) : [$id];
        } // END if(!empty($id))

        // loop through the pages and build the gallery code ....
        $uniqueID = \uniqid();
        $videoGalleryHtml = null;
        $videoGalleryHtml .= '<div class="gallery-row row">';
        $videoGalleryHtml .= '<ul class="bootstrap-gallery bootstrap-video-gallery bootstrap-video-gallery-' . $uniqueID . ' clearfix">';

        if(empty($videoList)) {
            // assume we have a list of childpages
            $pageID = \get_queried_object_id();
            $videoPages = $this->getVideoPages($perPage);
            $pageChildren = \get_page_children($pageID, $videoPages->posts);

            if($pageChildren) {
                $childPages = $this->getVideoPagesFromChildren($pageChildren);
            } // END if($children)

            if(!empty($childPages)) {
                foreach($childPages as $child) {
                    $videoGalleryHtml .= '<li>';
                    $videoGalleryHtml .= $child->eve_page_video_oEmbed_code;
                    $videoGalleryHtml .= '<header><h2 class="video-gallery-title"><a href="' . \get_permalink($child->ID) . '">' . $child->post_title . '</a></h2></header>';

                    if($child->post_content) {
                        $videoGalleryHtml .= '<p>' . EveOnline\Helper\StringHelper::cutString($child->post_content, '140') . '</p>';
                    } // END if($child->post_content)

                    $videoGalleryHtml .= '</li>';

                    \wp_reset_query();
                } // END foreach($childPages as $child)
            } else {
                $videoGalleryHtml = false;
            } // END if($childPages)
        } else {
            if(\is_singular()) {
                $videos = \explode(',', $videoList);
                $youtubePattern = '/https?:\/\/((m|www)\.)?youtube\.com\/watch.*/i';
                $vimeoPattern = '/https?:\/\/(.+\.)?vimeo\.com\/.*/i';

                $oEmbed = new \WP_oEmbed();
                foreach($videos as $video) {
                    if(\preg_match($youtubePattern, $video) || \preg_match($vimeoPattern, $video)) {
                        $provider = $oEmbed->get_provider($video);
                        $videoData = $oEmbed->fetch($provider, $video);
                        $videoGalleryHtml .= '<li>';
                        $videoGalleryHtml .= $videoData->html;
                        $videoGalleryHtml .= '<header><h2 class="video-gallery-title"><a href="' . $video . '" rel="external">' . $videoData->title . '</a></h2><span class="bootstrap-video-gallery-video-author small">' . sprintf(\__('&copy %1$s', 'eve-online'), $videoData->author_name) . ' (<a href="' . $videoData->author_url . '" rel=external">' . \__('Channel', 'eve-online') . '</a>)</span></header>';
                        $videoGalleryHtml .= '</li>';
                    } // END if(\preg_match($youtubePattern, $video) || \preg_match($vimeoPattern, $video))
                } // END foreach($videos as $video)
            } else {
                $videoGalleryHtml .= '<li>' . \__('The video gallery can only be displayed on a single site', 'eve-online') . '</li>';
            }
        } // END if(empty($videoList))

        $videoGalleryHtml .= '</ul>';
        $videoGalleryHtml .= '</div>';

        if(empty($classes)) {
            $classes = EveOnline\Helper\PostHelper::getLoopContentClasses();
        } // END if(empty($classes))

        $videoGalleryHtml .= '<script type="text/javascript">
                                jQuery(document).ready(function() {
                                    jQuery("ul.bootstrap-video-gallery-' . $uniqueID . '").bootstrapGallery({
                                        "classes" : "' . $classes . '",
                                        "hasModal" : false
                                    });
                                });
                                </script>';

        if(isset($videoPages) && $videoPages->max_num_pages > 1) {
            $videoGalleryHtml .= '<nav id="nav-videogallery" class="navigation post-navigation clearfix" role="navigation">';
            $videoGalleryHtml .= '<h3 class="assistive-text">' . \__('Video Navigation', 'eve-online') . '</h3>';
            $videoGalleryHtml .= '<div class="nav-previous pull-left">';
            $videoGalleryHtml .= EveOnline\Helper\NavigationHelper::getNextPostsLink(\__('<span class="meta-nav">&larr;</span> Older Videos', 'eve-online'), 0, false, $videoPages);
            $videoGalleryHtml .= '</div>';
            $videoGalleryHtml .= '<div class="nav-next pull-right">';
            $videoGalleryHtml .= EveOnline\Helper\NavigationHelper::getPreviousPostsLink(\__('Newer Videos <span class="meta-nav">&rarr;</span>', 'eve-online'), false);
            $videoGalleryHtml .= '</div>';
            $videoGalleryHtml .= '</nav><!-- #nav-videogallery .navigation -->';
        } // END if($videoPages->max_num_pages > 1)

        return $videoGalleryHtml;
    } // END public function shortcodeVideogallery($attributes)

    public function registerMetabox() {
        \add_action('add_meta_boxes', [
            $this,
            'metaboxVideopage'
        ]);

        \add_action('save_post', [
            $this,
            'saveMetaboxData'
        ]);
    } // END function public function registerMetabox()

    public function metaboxVideopage() {
        \add_meta_box('eve-video-page-box', \__('Video Gallery Page?', 'eve-online'), [$this, 'renderVideopageMetabox'], 'page', 'side');
    } // END public function metaboxVideopage()

    public function renderVideopageMetabox($post) {
        $eve_page_is_video_gallery_page = \get_post_meta($post->ID, 'eve_page_is_video_gallery_page', true);
        $eve_page_is_video_only_list_in_parent = \get_post_meta($post->ID, 'eve_page_video_only_list_in_parent_gallery', true);
        $eve_page_video_url = \get_post_meta($post->ID, 'eve_page_video_url', true);
        ?>
        <label><strong><?php _e('Video Gallery Settings', 'eve-online'); ?></strong></label>
        <p class="checkbox-wrapper">
            <input id="eve_page_is_video_gallery_page" name="eve_page_is_video_gallery_page" type="checkbox" <?php \checked($eve_page_is_video_gallery_page); ?>>
            <label for="eve_page_is_video_gallery_page"><?php \_e('Is Video Gallery Page?', 'eve-online'); ?></label>
        </p>
        <p class="checkbox-wrapper">
            <input id="eve_page_video_only_list_in_parent_gallery" name="eve_page_video_only_list_in_parent_gallery" type="checkbox" <?php \checked($eve_page_is_video_only_list_in_parent); ?>>
            <label for="eve_page_video_only_list_in_parent_gallery"><?php \_e('Only list if it\'s parent gallery', 'eve-online'); ?></label>
        </p>
        <p class="checkbox-wrapper">
            <label for="eve_page_video_url"><?php _e('Video URL:', 'eve-online'); ?></label><br>
            <input id="eve_page_video_url" name="eve_page_video_url" type="text" value="<?php echo $eve_page_video_url; ?>">
        </p>
        <?php
        if(!empty($eve_page_video_url)) {
            ?>
            <p class="checkbox-wrapper">
                <label><strong><?php \_e('Your Video', 'eve-online'); ?></strong></label>
                <br>
                <?php
                $oEmbed = \wp_oembed_get($eve_page_video_url);
                echo $oEmbed;
                ?>
                <script type="text/javascript">
                /**
                 * Making the Video container responsive
                 *
                 * @param {type} $
                 * @returns {undefined}
                 */
                jQuery(function($) {
                    var $oEmbedVideos = $('#eve-video-page-box iframe[src*="youtube"], #eve-video-page-box iframe[src*="vimeo"]');
                    $oEmbedVideos.each(function() {
                        $(this).removeAttr('height').removeAttr('width').wrap('<div class="embed-video-container"></div>');
                    });
                });
                </script>
            </p>
            <?php
        } // END if(!empty($eve_page_corp_eve_ID))

        \wp_nonce_field('save', '_eve_video_page_nonce');
    } // END public function renderVideopageMetabox()

    /**
     * Save the setting
     *
     * @param int $postID
     * @return boolean
     */
    function saveMetaboxData($postID) {
        $postNonce = \filter_input(\INPUT_POST, '_eve_video_page_nonce');

        if(empty($postNonce) || !\wp_verify_nonce($postNonce, 'save')) {
            return false;
        } // END if(empty($postNonce) || !\wp_verify_nonce($postNonce, 'save'))

        if(!\current_user_can('edit_post', $postID)) {
            return false;
        } // END if(!current_user_can('edit_post', $postID))

        if(defined('DOING_AJAX')) {
            return false;
        } // END if(defined('DOING_AJAX'))

        \update_post_meta($postID, 'eve_page_video_url', \filter_input(\INPUT_POST, 'eve_page_video_url'));

        $isVideoPage = \filter_input(\INPUT_POST, 'eve_page_is_video_gallery_page') == "on";
        \update_post_meta($postID, 'eve_page_is_video_gallery_page', $isVideoPage);

        $onlyListForParent = \filter_input(\INPUT_POST, 'eve_page_video_only_list_in_parent_gallery') == "on";
        \update_post_meta($postID, 'eve_page_video_only_list_in_parent_gallery', $onlyListForParent);
    } // END function eve_corp_page_setting_save($postID)

    private function getVideoPages($postPerPage) {
        global $paged;

        $queryArgs = [
            'posts_per_page' => $postPerPage,
            'post_type' => 'page',
            'meta_key' => 'eve_page_is_video_gallery_page',
            'meta_value' => 1,
            'paged' => $paged
        ];
        // Set up the objects needed

        $videoPages = new \WP_Query($queryArgs);

        return $videoPages;
    } // END private function getChildPages()

    private function getVideoPagesFromChildren($children) {
        if(!\is_array($children) || \count($children) === 0) {
            return false;
        } // END if(!is_array($children) || count($children) === 0)

        $videoPages = null;
        foreach($children as $id => $child) {
            $eve_page_is_video_gallery_page = \get_post_meta($child->ID, 'eve_page_is_video_gallery_page', true);
            $eve_page_is_video_only_list_in_parent = \get_post_meta($child->ID, 'eve_page_video_only_list_in_parent_gallery', true);
            $eve_page_video_url = \get_post_meta($child->ID, 'eve_page_video_url', true);

            if(isset($eve_page_is_video_gallery_page)) {
                $videoPages[$id] = $child;
                $videoPages[$id]->eve_page_is_video_gallery_page = $eve_page_is_video_gallery_page;
                $videoPages[$id]->eve_page_video_only_list_in_parent_gallery = $eve_page_is_video_only_list_in_parent;
                $videoPages[$id]->eve_page_video_url = $eve_page_video_url;
                $videoPages[$id]->eve_page_video_oEmbed_code = \wp_oembed_get($eve_page_video_url);
            } // END if(isset($eve_page_is_video_gallery_page))
        } // END foreach($children as $id => $child)

        return $videoPages;
    } // END private function getVideoPagesFromChildren($children)
} // END class BootstrapVideoGallery
