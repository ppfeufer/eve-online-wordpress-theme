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

namespace Ppfeufer\Theme\EVEOnline\Helper;

use Ppfeufer\Theme\EVEOnline\Singletons\GenericSingleton;
use WP_Query;

class NavigationHelper extends GenericSingleton {
    /**
     * Display page next/previous navigation links.
     *
     * @param string $nav_id
     * @global object $wp_query
     */
    public function getContentNav(string $nav_id): void {
        global $wp_query;

        if ($wp_query->max_num_pages > 1) {
            ?>
            <nav id="<?php echo $nav_id; ?>" class="navigation post-navigation clearfix" role="navigation">
                <h3 class="assistive-text"><?php _e('Post navigation', 'eve-online'); ?></h3>
                <div class="nav-previous pull-left">
                    <?php $this->getNextPostsLink(__('<span class="meta-nav">&larr;</span> Older posts', 'eve-online'), 0, true); ?>
                </div>
                <div class="nav-next pull-right">
                    <?php $this->getPreviousPostsLink(__('Newer posts <span class="meta-nav">&rarr;</span>', 'eve-online'), true); ?>
                </div>
            </nav><!-- #<?php echo $nav_id; ?> .navigation -->
            <?php
        }
    }

    /**
     * Retrieves the next posts page link.
     *
     * @param string|null $label Content for link text.
     * @param int $max_page Optional. Max pages. Default 0.
     * @param boolean $echo Return or echo
     * @param object|null $wp_query default null, but if another query object should be used here, set it
     *
     * @return string|null HTML-formatted next posts page link.
     * @global WP_Query $wp_query
     *
     * @global int $paged
     */
    public function getNextPostsLink(string $label = null, int $max_page = 0, bool $echo = false, object $wp_query = null): ?string {
        global $paged;

        if ($wp_query === null) {
            global $wp_query;
        }

        if (!$max_page) {
            $max_page = $wp_query->max_num_pages;
        }

        if (!$paged) {
            $paged = 1;
        }

        $nextpage = $paged + 1;

        if (null === $label) {
            $label = __('&laquo; Previous Page', 'eve-online');
        }

        if (!is_single() && ($nextpage <= $max_page)) {
            /**
             * Filters the anchor tag attributes for the next posts page link.
             *
             * @param string $attributes Attributes for the anchor tag.
             * @since 2.7.0
             *
             */
            $attr = apply_filters('previous_posts_link_attributes', '');

            if ($echo === true) {
                echo '<a class="btn btn-default" href="' . next_posts($max_page, false) . "\" $attr>" . preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label) . '</a>';
            } else {
                return '<a class="btn btn-default" href="' . next_posts($max_page, false) . "\" $attr>" . preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label) . '</a>';
            }
        }

        return null;
    }

    /**
     * Retrieves the previous posts page link.
     *
     * @param string|null $label Optional. Previous page link text.
     * @param bool $echo Optional. echoing or returning the link.
     * @return string|null HTML-formatted previous page link.
     * @global int $paged
     */
    public function getPreviousPostsLink(string $label = null, bool $echo = false): ?string {
        global $paged;

        if (null === $label) {
            $label = __('Next Page &raquo;', 'eve-online');
        }

        if ($paged > 1 && !is_single()) {
            /**
             * Filters the anchor tag attributes for the previous posts page link.
             *
             * @param string $attributes Attributes for the anchor tag.
             * @since 2.7.0
             *
             */
            $attr = apply_filters('next_posts_link_attributes', '');

            if ($echo === true) {
                echo '<a class="btn btn-default" href="' . previous_posts(false) . "\" $attr>" . preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label) . '</a>';
            } else {
                return '<a class="btn btn-default" href="' . previous_posts(false) . "\" $attr>" . preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label) . '</a>';
            }
        }

        return null;
    }

    /**
     * Get the Breadcrumb Navigation
     *
     * @param boolean $addTexts
     * @param boolean $echo
     * @return string|null
     */
    public function getBreadcrumbNavigation(bool $addTexts = true, bool $echo = false): ?string {
        $home = __('Home', 'eve-online'); // text for the 'Home' link
        $before = '<li class="active">'; // tag before the current crumb
        $sep = '';
        $after = '</li>'; // tag after the current crumb

        $breadcrumb = '';

        if ((!is_home() && !is_front_page()) || is_paged()) {
            $breadcrumb .= '<ul class="breadcrumb">';

            global $post;

            $homeLink = home_url();

            $breadcrumb .= '<li><a href="' . $homeLink . '">' . $home . '</a> ' . $sep . '</li> ';

            if (is_category()) {
                global $wp_query;

                $cat_obj = $wp_query->get_queried_object();
                $thisCat = $cat_obj->term_id;
                $thisCat = get_category($thisCat);
                $parentCat = get_category($thisCat->parent);

                if ($thisCat->parent !== 0) {
                    $breadcrumb .= '<li>' . get_category_parents($parentCat, true, $sep . '</li><li>') . '</li>';
                }

                $format = $before . ($addTexts ? (__('Archive by category ', 'eve-online') . '"%s"') : '%s') . $after;

                $breadcrumb .= sprintf($format, single_cat_title('', false));
            } elseif (is_day()) {
                $breadcrumb .= '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li> ';
                $breadcrumb .= '<li><a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a></li> ';
                $breadcrumb .= $before . get_the_time('d') . $after;
            } elseif (is_month()) {
                $breadcrumb .= '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li> ';
                $breadcrumb .= $before . get_the_time('F') . $after;
            } elseif (is_year()) {
                $breadcrumb .= $before . get_the_time('Y') . $after;
            } elseif (is_single() && !is_attachment()) {
                if (get_post_type() !== 'post') {
                    $post_type = get_post_type_object(get_post_type());
                    $slug = $post_type->rewrite;

                    $breadcrumb .= '<li><a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a></li> ';
                } else {
                    $cat = get_the_category();
                    $cat = $cat[0];

                    $breadcrumb .= '<li>' . get_category_parents($cat, true, $sep) . '</li>';
                }

                $breadcrumb .= $before . get_the_title() . $after;
            } elseif (!is_single() && !is_page() && get_post_type() !== 'post' && !is_404()) {
                $post_type = get_post_type_object(get_post_type());

                $breadcrumb .= $before . $post_type->labels->singular_name . $after;
            } elseif (is_attachment()) {
                $parent = get_post($post->post_parent);
                $cat = get_the_category($parent->ID);
                $cat = $cat['0'] ?? '';

                $breadcrumb .= (isset($cat['0'])) ? get_category_parents($cat, true, $sep) : '';
                $breadcrumb .= '<li><a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a></li> ';
                $breadcrumb .= $before . get_the_title() . $after;
            } elseif (is_page() && !$post->post_parent) {
                $breadcrumb .= $before . get_the_title() . $after;
            } elseif (is_page() && $post->post_parent) {
                $parent_id = $post->post_parent;
                $breadcrumbs = [];

                while ($parent_id) {
                    $page = get_post($parent_id);
                    $breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>' . $sep . '</li>';
                    $parent_id = $page->post_parent;
                } // END while($parent_id)

                $breadcrumbs = array_reverse($breadcrumbs);

                foreach ($breadcrumbs as $crumb) {
                    $breadcrumb .= $crumb;
                } // END foreach($breadcrumbs as $crumb)

                $breadcrumb .= $before . get_the_title() . $after;
            } elseif (is_search()) {
                $format = $before . ($addTexts ? (__('Search results for ', 'eve-online') . '"%s"') : '%s') . $after;

                $breadcrumb .= sprintf($format, get_search_query());
            } elseif (is_tag()) {
                $format = $before . ($addTexts ? (__('Posts tagged ', 'eve-online') . '"%s"') : '%s') . $after;

                $breadcrumb .= sprintf($format, single_tag_title('', false));
            } elseif (is_author()) {
                global $author;

                $userdata = get_userdata($author);
                $format = $before . ($addTexts ? (__('Articles posted by ', 'eve-online') . '"%s"') : '%s') . $after;

                $breadcrumb .= sprintf($format, $userdata->display_name);
            } elseif (is_404()) {
                $breadcrumb .= $before . __('Error 404', 'eve-online') . $after;
            }

            $breadcrumb .= '</ul>';
        }

        if ($echo === false) {
            return $breadcrumb;
        }

        echo $breadcrumb;

        return null;
    }

    /**
     * Articlenavigation.
     * Displaying the next/previous links in single article.
     *
     * @since 0.1-r20170329
     *
     * @package WordPress
     * @subpackage EVE Online Theme
     */
    public function getArticleNavigation($echo = false): ?string {
        $htmlOutput = null;
        $previousPostObject = get_previous_post();
        $nextPostObject = get_next_post();

        $htmlOutput .= '<nav class="article-navigation clearfix">';
        $htmlOutput .= '<h3 class="assistive-text">' . __('Post Navigation', 'eve-online') . '</h3>';

        $htmlOutput .= '<div class="row clearfix">';
        if ($previousPostObject) {
            $htmlOutput .= '<div class="nav-previous ' . PostHelper::getInstance()->getArticleNavigationPanelClasses() . ' pull-left clearfix">';
            $htmlOutput .= '<div class="nav-previous-link">' . get_previous_post_link('%link', __('<span class="meta-nav">&larr;</span> Previous', 'eve-online')) . '</div>';

            if (has_post_thumbnail($previousPostObject->ID)) {
                $htmlOutput .= '<div class="nav-previous-thumbnail">';
                $htmlOutput .= '<a href="' . get_the_permalink($previousPostObject->ID) . '" title="' . esc_html($previousPostObject->post_title) . '">';
                $htmlOutput .= '<figure class="post-loop-thumbnail">';
                $htmlOutput .= get_the_post_thumbnail($previousPostObject->ID, 'post-loop-thumbnail');

//                $htmlOutput .= '<figcaption>' . $previousPostObject->post_title . '</figcaption>';
                $htmlOutput .= '</figure>';
                $htmlOutput .= '</a>';
                $htmlOutput .= '</div>';
            } else {
                // Article Image Plaveholder. We don't have it yet ....
//                $htmlOutput .= '<a class="related-article-header" href="' . \get_permalink($previousPostObject->ID) . '" rel="bookmark" title="' . \__('Permanent link to: ', 'eve-online') . \esc_html($previousPostObject->post_title) . '"><img width="251" height="115" title="' . \__('Placeholder Postthumbnail Related Article', 'eve-online') . '" alt="' . \__('Placeholder Postthumbnail Related Article', 'eve-online') . '" class="attachment-related-article wp-post-image" src="' . get_theme_file_uri('/images/placeholder/postthumbnail-related-article.jpg') . '" /></a>';
            }

            $htmlOutput .= '<div><em>' . esc_html($previousPostObject->post_title) . '</em></div>';
            $htmlOutput .= '</div>';
        }

        if ($nextPostObject) {
            $htmlOutput .= '<div class="nav-next ' . PostHelper::getInstance()->getArticleNavigationPanelClasses() . ' pull-right text-align-right clearfix">';
            $htmlOutput .= '<div class="nav-next-link">' . get_next_post_link('%link', __('Next <span class="meta-nav">&rarr;</span>', 'eve-online')) . '</div>';

            if (has_post_thumbnail($nextPostObject->ID)) {
                $htmlOutput .= '<div class="nav-next-thumbnail">';
                $htmlOutput .= '<a href="' . get_the_permalink($nextPostObject->ID) . '" title="' . esc_html($nextPostObject->post_title) . '">';
                $htmlOutput .= '<figure class="post-loop-thumbnail">';
                $htmlOutput .= get_the_post_thumbnail($nextPostObject->ID, 'post-loop-thumbnail');

//                $htmlOutput .= '<figcaption>' . $nextPostObject->post_title . '</figcaption>';
                $htmlOutput .= '</figure>';
                $htmlOutput .= '</a>';
                $htmlOutput .= '</div>';
            } else {
                // Article Image Plaveholder. We don't have it yet ....
//                $htmlOutput .= '<a class="related-article-header" href="' . \get_permalink($nextPostObject->ID) . '" rel="bookmark" title="' . \__('Permanent link to: ', 'eve-online') . \esc_html($nextPostObject->post_title) . '"><img width="251" height="115" title="' . \__('Placeholder Postthumbnail Related Article', 'eve-online') . '" alt="' . \__('Placeholder Postthumbnail Related Article', 'eve-online') . '" class="attachment-related-article wp-post-image" src="' . get_theme_file_uri('/images/placeholder/postthumbnail-related-article.jpg') . '" /></a>';
            }

            $htmlOutput .= '<div><em>' . esc_html($nextPostObject->post_title) . '</em></div>';
            $htmlOutput .= '</div>';
        }

        $htmlOutput .= '</div>';
        $htmlOutput .= '</nav>';

        if ($echo === false) {
            return $htmlOutput;
        }

        echo $htmlOutput;

        return null;
    }
}
