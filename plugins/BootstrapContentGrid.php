<?php

namespace WordPress\Themes\EveOnline\Plugins;

use WordPress\Themes\EveOnline;

\defined('ABSPATH') or die();

class BootstrapContentGrid {
    public function __construct() {
        $this->registerShortcode();
    } // END public function __construct()

    public function registerShortcode() {
        \add_shortcode('contentgrid', [$this, 'shortcodeContentGrid']);
        \add_shortcode('gridelement', [$this, 'shortcodeContentGridElement']);
    } // END public function registerShortcode()

    public function shortcodeContentGrid($atts, $content = null) {
        $args = \shortcode_atts([
            'classes' => EveOnline\Helper\PostHelper::getLoopContentClasses(),
        ],$atts);

        $uniqueID = \uniqid();
        $gridHtml = null;
        $gridHtml .= '<div class="content-grid-row row">';
        $gridHtml .= '<ul class="bootstrap-content-grid bootstrap-content-grid-' . $uniqueID . ' clearfix">';
        $gridHtml .= $this->removeAutopInShortcode($content);
        $gridHtml .= '</ul>';
        $gridHtml .= '</div>';

        $gridHtml .= '<script type="text/javascript">
                        jQuery(document).ready(function() {
                            jQuery("ul.bootstrap-content-grid-' . $uniqueID . '").bootstrapGallery({
                                "classes" : "' . $args['classes'] . '",
                                "hasModal" : false
                            });
                        });
                        </script>';

        return $gridHtml;
    } // END public function shortcodeContentGrid($atts, $content = null)

    public function shortcodeContentGridElement($atts, $content = null) {
        $atts = null; // we don't need it here, but WP provides it anyways

        $gridHtml = '<li>' . $this->removeAutopInShortcode($content) . '</li>';

        return $gridHtml;
    }

    public function removeAutopInShortcode($content) {
        $content = \do_shortcode(\shortcode_unautop($content));
        $content = \preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $content);

        return $content;
    } // END public function removeAutopInShortcode($content)
} // END class BootstrapContentGrid
