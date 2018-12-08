<?php

namespace WordPress\Themes\EveOnline\Plugins;

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
        \add_action('widgets_init', \create_function('', 'return register_widget("\\WordPress\Themes\EveOnline\Plugins\Widgets\ChildpageMenuWidget");'));
    }
}
