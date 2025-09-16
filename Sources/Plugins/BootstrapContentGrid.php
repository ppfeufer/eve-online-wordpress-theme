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

namespace Ppfeufer\Theme\EVEOnline\Plugins;

use Ppfeufer\Theme\EVEOnline\Helper\PostHelper;

class BootstrapContentGrid {
    public function __construct() {
        $this->registerShortcode();
    }

    public function registerShortcode(): void {
        add_shortcode('contentgrid', [$this, 'shortcodeContentGrid']);
        add_shortcode('gridelement', [$this, 'shortcodeContentGridElement']);
    }

    public function shortcodeContentGrid($atts, $content = null): string {
        $args = shortcode_atts([
            'classes' => PostHelper::getInstance()->getLoopContentClasses(),
        ], $atts);

        $uniqueID = uniqid('', true);
        $gridHtml = '<div class="content-grid-row row">';
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
    }

    public function removeAutopInShortcode($content): array|string|null {
        $content = do_shortcode(shortcode_unautop($content));

        return preg_replace('#^</p>|^<br />|<p>$#', '', $content);
    }

    public function shortcodeContentGridElement($atts, $content = null): string {
        $atts = null; // we don't need it here, but WP provides it anyways

        return '<li>' . $this->removeAutopInShortcode($content) . '</li>';
    }
}
