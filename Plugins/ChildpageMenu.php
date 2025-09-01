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

class ChildpageMenu {
    /**
     * constructor
     */
    public function __construct() {
        $this->initPlugin();
    }

    /**
     * initialze the plugin
     */
    private function initPlugin() {
        $this->initWidget();
    }

    /**
     * initialze the widget
     */
    public function initWidget() {
        \add_action('widgets_init', [$this, 'registerWidget']);
    }

    public function registerWidget() {
        return \register_widget("\\Ppfeufer\Theme\EVEOnline\Plugins\Widgets\ChildpageMenuWidget");
    }
}
