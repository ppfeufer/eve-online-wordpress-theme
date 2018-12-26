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

namespace WordPress\Themes\EveOnline\Plugins\Widgets;

use WP_Widget;

class ChildpageMenuWidget extends WP_Widget {
    public $idBase = null;
    public $widgetName = null;

    public function __construct() {
        $this->idBase = 'eve_childpage_widget';
        $this->widgetName = \__('Childpage Menu Widget', 'eve-online');


        $widget_options = [
            'classname' => 'eve-childpage-menu-widget',
            'description' => \__('Displaying the childpages as a menu in your sidebar.', 'eve-online')
        ];

        $control_options = [];

        parent::__construct($this->idBase, $this->widgetName, $widget_options, $control_options);
    }

    /**
     * Widget Output
     *
     * @param type $args
     * @param type $instance
     */
    /**
     *
     * @param type $args
     * @param type $instance
     */
    public function widget($args, $instance) {
        if(\is_page()) {
            $widgetData = $this->getWidgetData();

            if(!empty($widgetData)) {
                echo $args['before_widget'];
                echo '<ul class="childpages-list">' . $widgetData . '</ul>';
                echo $args['after_widget'];
            }
        }
    }

    private function getWidgetData() {
        global $post;

        $parent = $post->ID;

        if($post->post_parent) {
            $ancestors = \get_post_ancestors($post->ID);

            $root = \count($ancestors) - 1;
            $parent = $ancestors[$root];
        }

        return \wp_list_pages([
            'title_li' => '',
            'child_of' => $parent,
            'echo' => false
        ]);
    }
}
