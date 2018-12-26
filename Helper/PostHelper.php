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

class PostHelper {
    public static function getPostMetaInformation() {
        $options = \get_option('eve_theme_options', ThemeHelper::getThemeDefaultOptions());

        if(!empty($options['post_meta']['show'])) {
            \printf(\__('Posted on <time class="entry-date" datetime="%3$s">%4$s</time><span class="byline"> <span class="sep"> by </span> <span class="author vcard">%7$s</span></span>', 'eve-online'),
                \esc_url(\get_permalink()),
                \esc_attr(\get_the_time()),
                \esc_attr(\get_the_date('c')),
                \esc_html(\get_the_date()),
                \esc_url(\get_author_posts_url(\get_the_author_meta('ID'))),
                \esc_attr(\sprintf(\__('View all posts by %s', 'eve-online'),
                    \get_the_author()
                )),
                \esc_html(get_the_author())
            );
        }
    }

    /**
     * Display template for post categories and tags
     */
    public static function getPostCategoryAndTags() {
        $options = \get_option('eve_theme_options', ThemeHelper::getThemeDefaultOptions());

        if(!empty($options['show_post_meta']['yes'])) {
            \printf('<span class="cats_tags"><span class="glyphicon glyphicon-folder-open" title="My tip"></span><span class="cats">');
            \printf(\the_category(', '));
            \printf('</span>');

            if(\has_tag() === true) {
                \printf('<span class="glyphicon glyphicon-tags"></span><span class="tags">');
                \printf(\the_tags(' '));
                \printf('</span>');
            }

            \printf('</span>');
        }
    }

    /**
     * check if a post has content or not
     *
     * @param int $postID ID of the post
     * @return boolean
     */
    public static function hasContent($postID) {
        $content_post = \get_post($postID);
        $content = $content_post->post_content;

        return \trim(\str_replace('&nbsp;','',  \strip_tags($content))) !== '';
    }

    public static function getHeaderColClasses($echo = false) {
        if(ThemeHelper::hasSidebar('header-widget-area')) {
            $contentColClass = 'col-xs-12 col-sm-9 col-md-6 col-lg-6';
        } else {
            $contentColClass = 'col-xs-12 col-sm-9 col-md-9 col-lg-9';
        }

        if($echo === true) {
            echo $contentColClass;
        } else {
            return $contentColClass;
        }
    }

    public static function getMainContentColClasses($echo = false) {
        if(\is_page() || \is_home()) {
            if(ThemeHelper::hasSidebar('sidebar-page') || ThemeHelper::hasSidebar('sidebar-general')) {
                $contentColClass = 'col-lg-9 col-md-9 col-sm-9 col-9';
            } else {
                $contentColClass = 'col-lg-12 col-md-12 col-sm-12 col-12';
            }
        } else {
            if(ThemeHelper::hasSidebar('sidebar-general') || ThemeHelper::hasSidebar('sidebar-post')) {
                $contentColClass = 'col-lg-9 col-md-9 col-sm-9 col-9';
            } else {
                $contentColClass = 'col-lg-12 col-md-12 col-sm-12 col-12';
            }
        }

        if($echo === true) {
            echo $contentColClass;
        } else {
            return $contentColClass;
        }
    }

    public static function getLoopContentClasses($echo = false) {
        if(\is_page() || \is_home()) {
            if(ThemeHelper::hasSidebar('sidebar-page') || ThemeHelper::hasSidebar('sidebar-general')) {
                $contentColClass = 'col-lg-4 col-md-6 col-sm-6 col-xs-12';
            } else {
                $contentColClass = 'col-lg-3 col-md-4 col-sm-6 col-xs-12';
            }
        } else {
            if(ThemeHelper::hasSidebar('sidebar-general') || ThemeHelper::hasSidebar('sidebar-post')) {
                $contentColClass = 'col-lg-4 col-md-6 col-sm-6 col-xs-12';
            } else {
                $contentColClass = 'col-lg-3 col-md-4 col-sm-6 col-xs-12';
            }
        }

        if($echo === true) {
            echo $contentColClass;
        } else {
            return $contentColClass;
        }
    }

    public static function getArticleNavigationPanelClasses($echo = false) {
        if(\is_page() || \is_home()) {
            if(ThemeHelper::hasSidebar('sidebar-page') || ThemeHelper::hasSidebar('sidebar-general')) {
                $contentColClass = 'col-lg-4 col-md-6 col-sm-6 col-xs-6';
            } else {
                $contentColClass = 'col-lg-3 col-md-4 col-sm-6 col-xs-6';
            }
        } else {
            if(ThemeHelper::hasSidebar('sidebar-general') || ThemeHelper::hasSidebar('sidebar-post')) {
                $contentColClass = 'col-lg-4 col-md-6 col-sm-6 col-xs-6';
            } else {
                $contentColClass = 'col-lg-3 col-md-4 col-sm-6 col-xs-6';
            }
        }

        if($echo === true) {
            echo $contentColClass;
        } else {
            return $contentColClass;
        }
    }

    public static function getContentColumnCount($echo = false) {
        if(\is_page() || \is_home()) {
            if(ThemeHelper::hasSidebar('sidebar-page') || ThemeHelper::hasSidebar('sidebar-general')) {
                $columnCount = 3;
            } else {
                $columnCount = 4;
            }
        } else {
            if(ThemeHelper::hasSidebar('sidebar-general') || ThemeHelper::hasSidebar('sidebar-post')) {
                $columnCount = 3;
            } else {
                $columnCount = 4;
            }
        }

        if($echo === true) {
            echo $columnCount;
        } else {
            return $columnCount;
        }
    }

    public static function getExcerptById($postID, $excerptLength = 35) {
        $the_post = \get_post($postID); //Gets post ID
        $the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
        $the_excerpt = \strip_tags(\strip_shortcodes($the_excerpt)); //Strips tags and images
        $words = \explode(' ', $the_excerpt, $excerptLength + 1);

        if(\count($words) > $excerptLength) {
            \array_pop($words);
            \array_push($words, '…');
            $the_excerpt = \implode(' ', $words);
        }

        $the_excerpt = '<p>' . $the_excerpt . '</p>';

        return $the_excerpt;
    }
}
