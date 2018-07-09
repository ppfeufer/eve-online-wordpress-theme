<?php
/**
 * Theme Shortcodes
 */

namespace WordPress\Themes\EveOnline\Plugins;

\defined('ABSPATH') or die();

class Shortcodes {
    /**
     * contructor
     */
    public function __construct() {
//        $this->changeWpAuto();
        $this->addShortcodesToWidgets();
        $this->registerShortcodes();
    }

    /**
     * register all shortcodes
     */
    public function registerShortcodes() {
        \add_shortcode('two_columns_one', [$this, 'shortcodeTwoColumnsOne']);
        \add_shortcode('three_columns_one', [$this, 'shortcodeThreeColumnsOne']);
        \add_shortcode('three_columns_two', [$this, 'shortcodeThreeColumnsTwo']);
        \add_shortcode('four_columns_one', [$this, 'shortcodeFourColumnsOne']);
        \add_shortcode('four_columns_two', [$this, 'shortcodeFourColumnsTwo']);
        \add_shortcode('four_columns_three', [$this, 'shortcodeFourColumnsThree']);
        \add_shortcode('divider', [$this, 'shortcodeDivider']);
        \add_shortcode('credits', [$this, 'shortcodeCredits']);
    }

    public function shortcodeTwoColumnsOne($atts, $content = null) {
        $args = \shortcode_atts([
            'position' => ''
        ], $atts);

        switch($args['position']) {
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

        $html = $prefix . '<div class="col-md-6">' . $this->removeAutopInShortcode($content) . '</div>' . $suffix;

        return $html;
    }

    public function shortcodeThreeColumnsOne($atts, $content = null) {
        $args = \shortcode_atts([
            'position' => ''
        ], $atts);

        switch($args['position']) {
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

    public function shortcodeThreeColumnsTwo($atts, $content = null) {
        $args = \shortcode_atts([
            'position' => ''
        ], $atts);

        switch($args['position']) {
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

    public function shortcodeFourColumnsOne($atts, $content = null) {
        $args = \shortcode_atts([
            'position' => ''
        ], $atts);

        switch($args['position']) {
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

    public function shortcodeFourColumnsTwo($atts, $content = null) {
        $args = \shortcode_atts([
            'position' => ''
        ], $atts);

        switch($args['position']) {
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

    public function shortcodeFourColumnsThree($atts, $content = null) {
        $args = \shortcode_atts([
            'position' => ''
        ], $atts);

        switch($args['position']) {
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

    public function shortcodeDivider($atts) {
        $atts = null; // we don't need it here, but WP provides it anyways

        return '<div class="divider clearfix"></div>';
    }

    public function shortcodeCredits($atts, $content = null) {
        $attributes = \shortcode_atts([
            'headline' => 'h4'
        ], $atts);

        $headlineOpen = '<' . $attributes['headline'] . '>';
        $headlineClose = '</' . $attributes['headline'] . '>';

        $output = '<div class="article-credits clearfix"><header>' . $headlineOpen . \__('Credits:', 'eve-online') . $headlineClose . '</header>' . $this->removeAutopInShortcode($content) . '</div>';

        return $output;
    }

    public function changeWpAuto() {
        \remove_filter('the_content', 'wpautop');
        \add_filter('the_content', 'wpautop', 99);
        \add_filter('the_content', 'shortcode_unautop', 100);
    }

    public function addShortcodesToWidgets() {
        \add_filter('widget_text', 'do_shortcode');
    }

    public function removeAutopInShortcode($content) {
        $content = \do_shortcode(\shortcode_unautop($content));
        $content = \preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $content);

        return $content;
    }
}
