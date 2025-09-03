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
 * Theme Shortcodes
 */

namespace Ppfeufer\Theme\EVEOnline\Plugins;

class Shortcodes {
    /**
     * contructor
     */
    public function __construct() {
        $this->addShortcodesToWidgets();
        $this->registerShortcodes();
    }

    public function addShortcodesToWidgets(): void {
        add_filter('widget_text', 'do_shortcode');
    }

    /**
     * register all shortcodes
     */
    public function registerShortcodes(): void {
        add_shortcode('two_columns_one', [$this, 'shortcodeTwoColumnsOne']);
        add_shortcode('three_columns_one', [$this, 'shortcodeThreeColumnsOne']);
        add_shortcode('three_columns_two', [$this, 'shortcodeThreeColumnsTwo']);
        add_shortcode('four_columns_one', [$this, 'shortcodeFourColumnsOne']);
        add_shortcode('four_columns_two', [$this, 'shortcodeFourColumnsTwo']);
        add_shortcode('four_columns_three', [$this, 'shortcodeFourColumnsThree']);
        add_shortcode('divider', [$this, 'shortcodeDivider']);
        add_shortcode('credits', [$this, 'shortcodeCredits']);
    }

    public function shortcodeTwoColumnsOne($atts, $content = null): string {
        $args = shortcode_atts([
            'position' => ''
        ], $atts);

        switch ($args['position']) {
            case 'first':
                $prefix = '<div class="row">';
                $suffix = '';
                break;

            case 'last':
                $prefix = '';
                $suffix = '</div>';
                break;

            default:
                $prefix = '';
                $suffix = '';
                break;
        }

        return $prefix . '<div class="col-md-6">' . $this->removeAutopInShortcode($content) . '</div>' . $suffix;
    }

    public function removeAutopInShortcode($content): array|string|null {
        $content = do_shortcode(shortcode_unautop($content));

        return preg_replace('#^</p>|^<br />|<p>$#', '', $content);
    }

    public function shortcodeThreeColumnsOne($atts, $content = null): string {
        $args = shortcode_atts([
            'position' => ''
        ], $atts);

        switch ($args['position']) {
            case 'first':
                $prefix = '<div class="row">';
                $suffix = '';
                break;

            case 'last':
                $prefix = '';
                $suffix = '</div>';
                break;

            default:
                $prefix = '';
                $suffix = '';
                break;
        }

        return $prefix . '<div class="col-md-4">' . $this->removeAutopInShortcode($content) . '</div>' . $suffix;
    }

    public function shortcodeThreeColumnsTwo($atts, $content = null): string {
        $args = shortcode_atts([
            'position' => ''
        ], $atts);

        switch ($args['position']) {
            case 'first':
                $prefix = '<div class="row">';
                $suffix = '';
                break;

            case 'last':
                $prefix = '';
                $suffix = '</div>';
                break;

            default:
                $prefix = '';
                $suffix = '';
                break;
        }

        return $prefix . '<div class="col-md-8">' . $this->removeAutopInShortcode($content) . '</div>' . $suffix;
    }

    public function shortcodeFourColumnsOne($atts, $content = null): string {
        $args = shortcode_atts([
            'position' => ''
        ], $atts);

        switch ($args['position']) {
            case 'first':
                $prefix = '<div class="row">';
                $suffix = '';
                break;

            case 'last':
                $prefix = '';
                $suffix = '</div>';
                break;

            default:
                $prefix = '';
                $suffix = '';
                break;
        }

        return $prefix . '<div class="col-md-3">' . $this->removeAutopInShortcode($content) . '</div>' . $suffix;
    }

    public function shortcodeFourColumnsTwo($atts, $content = null): string {
        $args = shortcode_atts([
            'position' => ''
        ], $atts);

        switch ($args['position']) {
            case 'first':
                $prefix = '<div class="row">';
                $suffix = '';
                break;

            case 'last':
                $prefix = '';
                $suffix = '</div>';
                break;

            default:
                $prefix = '';
                $suffix = '';
                break;
        }

        return $prefix . '<div class="col-md-6">' . $this->removeAutopInShortcode($content) . '</div>' . $suffix;
    }

    public function shortcodeFourColumnsThree($atts, $content = null): string {
        $args = shortcode_atts([
            'position' => ''
        ], $atts);

        switch ($args['position']) {
            case 'first':
                $prefix = '<div class="row">';
                $suffix = '';
                break;

            case 'last':
                $prefix = '';
                $suffix = '</div>';
                break;

            default:
                $prefix = '';
                $suffix = '';
                break;
        }

        return $prefix . '<div class="col-md-9">' . $this->removeAutopInShortcode($content) . '</div>' . $suffix;
    }

    public function shortcodeDivider($atts): string {
        $atts = null; // we don't need it here, but WP provides it anyways

        return '<div class="divider clearfix"></div>';
    }

    public function shortcodeCredits($atts, $content = null): string {
        $attributes = shortcode_atts([
            'headline' => 'h4'
        ], $atts);

        $headlineOpen = '<' . $attributes['headline'] . '>';
        $headlineClose = '</' . $attributes['headline'] . '>';

        return '<div class="article-credits clearfix"><header>' . $headlineOpen . __('Credits:', 'eve-online') . $headlineClose . '</header>' . $this->removeAutopInShortcode($content) . '</div>';
    }
}
