<?php

namespace WordPress\Themes\EveOnline\Plugins;

class ChildpageMenu {
    /**
     * constructor
     */
    public function __construct() {
        require_once(\get_theme_file_path('/plugins/widgets/ChildpageMenuWidget.php'));

        $this->initPlugin();
    }

    /**
     * initialze the plugin
     */
    private function initPlugin() {
        // frontend actions
        if(!\is_admin()) {
//            $this->addStyle();
        }

        $this->initWidget();
    }

    /**
     * initialze the widget
     */
    public function initWidget() {
        \add_action('widgets_init', \create_function('', 'return register_widget("WordPress\Themes\EveOnline\Plugins\Widgets\ChildpageMenuWidget");'));
    }
}
