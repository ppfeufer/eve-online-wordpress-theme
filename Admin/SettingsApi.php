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
 * Settings API ( modified from http://www.wp-load.com/register-settings-api/ )
 */

namespace WordPress\Themes\EveOnline\Admin;

use \WordPress\Themes\EveOnline\Helper\StringHelper;
use \WP_Query;

\defined('ABSPATH') or die();

class SettingsApi {
    /**
     * Init private variables
     */
    private $args = null;
    private $settingsArray = null;
    private $settingsFilter = null;
    private $optionsDefault = null;

    /**
     * Constructor
     */
    public function __construct($settingsFilter, $defaultOptions = null) {
        $this->settingsFilter = $settingsFilter;
        $this->optionsDefault = $defaultOptions;
    }

    /**
     * Settings API init
     */
    public function init() {
        \add_action('init', [$this, 'initSettings']);
        \add_action('admin_menu', [$this, 'menuPage']);
        \add_action('admin_init', [$this, 'registerFields']);
        \add_action('admin_init', [$this, 'registerCallback']);
        \add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
        \add_action('admin_enqueue_scripts', [$this, 'enqueueStyles']);
    }

    /**
     * Init settings runs before admin_init
     * Put $settingsArray to private variable
     * Add admin_head for scripts and styles
     */
    public function initSettings() {
        if(\is_admin()) {
            $this->settingsArray = \apply_filters($this->settingsFilter, []);

            if($this->isSettingsPage() === true) {
                \add_action('admin_head', [$this, 'adminStyles']);
                \add_action('admin_head', [$this, 'adminScripts']);
            }
        }
    }

    /**
     * Creating pages and menus from the settingsArray
     */
    public function menuPage() {
        foreach($this->settingsArray as $menu_slug => $options) {
            if(!empty($options['page_title']) && !empty($options['menu_title']) && !empty($options['option_name'])) {
                $options['capability'] = (!empty($options['capability']) ) ? $options['capability'] : 'manage_options';

                if(empty($options['type'])) {
                    $options['type'] = 'plugin';
                }

                switch($options['type']) {
                    case 'theme':
                        \add_theme_page(
                            $options['page_title'],
                            $options['menu_title'],
                            $options['capability'],
                            $menu_slug,
                            [
                                $this,
                                'renderOptions'
                            ]
                        );
                        break;

                    default:
                        \add_options_page(
                            $options['page_title'],
                            $options['menu_title'],
                            $options['capability'],
                            $menu_slug,
                            [
                                $this,
                                'renderOptions'
                            ]
                        );
                        break;
                }
            }
        }
    }

    /**
     * Register all fields and settings bound to it from the settingsArray
     */
    public function registerFields() {
        foreach($this->settingsArray as $page_id => $settings) {
            if(!empty($settings['tabs']) && \is_array($settings['tabs'])) {
                foreach($settings['tabs'] as $tab_id => $item) {
                    $sanitized_tab_id = \sanitize_title($tab_id);
                    $tab_description = (!empty($item['tab_description']) ) ? $item['tab_description'] : '';
                    $this->section_id = $sanitized_tab_id;
                    $setting_args = [
                        'option_group' => 'section_page_' . $page_id . '_' . $sanitized_tab_id,
                        'option_name' => $settings['option_name']
                    ];

                    \register_setting($setting_args['option_group'], $setting_args['option_name']);

                    $section_args = [
                        'id' => 'section_id_' . $sanitized_tab_id,
                        'title' => $tab_description,
                        'callback' => 'callback',
                        'menu_page' => $page_id . '_' . $sanitized_tab_id
                    ];

                    \add_settings_section(
                        $section_args['id'],
                        $section_args['title'],
                        [
                            $this,
                            $section_args['callback']
                        ],
                        $section_args['menu_page']
                    );

                    if(!empty($item['fields']) && \is_array($item['fields'])) {
                        foreach($item['fields'] as $field_id => $field) {
                            if(\is_array($field)) {
                                $sanitized_field_id = \sanitize_title($field_id);
                                $title = (!empty($field['title']) ) ? $field['title'] : '';
                                $field['field_id'] = $sanitized_field_id;
                                $field['option_name'] = $settings['option_name'];
                                $field_args = [
                                    'id' => 'field' . $sanitized_field_id,
                                    'title' => $title,
                                    'callback' => 'renderFields',
                                    'menu_page' => $page_id . '_' . $sanitized_tab_id,
                                    'section' => 'section_id_' . $sanitized_tab_id,
                                    'args' => $field
                                ];

                                \add_settings_field(
                                    $field_args['id'],
                                    $field_args['title'],
                                    [
                                        $this,
                                        $field_args['callback']
                                    ],
                                    $field_args['menu_page'],
                                    $field_args['section'],
                                    $field_args['args']
                                );
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Register callback is used for the button field type when user
     * click the button
     */
    public function registerCallback() {
        if($this->isSettingsPage() === true) {
            $getCallback = \filter_input(\INPUT_GET, 'callback');
            $getWpNonce = \filter_input(\INPUT_GET, '_wpnonce');
            $getPage = \filter_input(\INPUT_GET, 'page');

            if(!empty($getCallback)) {
                $nonce = \wp_verify_nonce($getWpNonce);

                if(!empty($nonce)) {
                    if(\function_exists($getCallback)) {
                        $message = \call_user_func($getCallback);
                        \update_option('rsa-message', $message);

                        $url = \admin_url('options-general.php?page=' . $getPage);
                        \wp_redirect($url);

                        die;
                    }
                }
            }
        }
    }

    /**
     * Check if the current page is a settings page
     */
    public function isSettingsPage() {
        $menus = [];
        $returnValue = false;

        $getPage = \filter_input(\INPUT_GET, 'page');
        $get_page = (!empty($getPage)) ? $getPage : '';
        foreach($this->settingsArray as $menu => $page) {
            $page = $page; // we need to do this shit here
            $menus[] = $menu;
        }

        if(\in_array($get_page, $menus)) {
            $returnValue = true;
        } else {
            $returnValue = false;
        }

        return $returnValue;
    }

    /**
     * Return an array for the choices in a select field type
     */
    public function selectChoices() {
        $items = [];

        if(!empty($this->args['choices']) && \is_array($this->args['choices'])) {
            foreach($this->args['choices'] as $slug => $choice) {
                $items[$slug] = $choice;
            }
        }

        return $items;
    }

    /**
     * Get values from built in WordPress functions
     */
    public function get() {
        if(!empty($this->args['get'])) {
            $item_array = \call_user_func_array([$this, 'get_' . StringHelper::camelCase($this->args['get'], true)], [$this->args]);
        } elseif(!empty($this->args['choices'])) {
            $item_array = $this->selectChoices($this->args);
        } else {
            $item_array = [];
        }

        return $item_array;
    }

    /**
     * Get users from WordPress, used by the select field type
     */
    public function getUsers() {
        $items = [];
        $args = (!empty($this->args['args'])) ? $this->args['args'] : null;
        $users = \get_users($args);

        foreach($users as $user) {
            $items[$user->ID] = $user->display_name;
        }

        return $items;
    }

    /**
     * Get menus from WordPress, used by the select field type
     */
    public function getMenus() {
        $items = [];
        $menus = \get_registered_nav_menus();

        if(!empty($menus)) {
            foreach($menus as $location => $description) {
                $items[$location] = $description;
            }
        }

        return $items;
    }

    /**
     * Get posts from WordPress, used by the select field type
     */
    public function getPosts() {
        $items = null;

        if($this->args['get'] === 'posts' && !empty($this->args['post_type'])) {
            $args = [
                'category' => 0,
                'post_type' => 'post',
                'post_status' => 'publish',
                'orderby' => 'post_date',
                'order' => 'DESC',
                'suppress_filters' => true
            ];

            $the_query = new WP_Query($args);

            if($the_query->have_posts()) {
                while($the_query->have_posts()) {
                    $the_query->the_post();

                    global $post;

                    $items[$post->ID] = \get_the_title();
                }
            }

            \wp_reset_postdata();
        }

        return $items;
    }

    /**
     * Get terms from WordPress, used by the select field type
     */
    public function getTerms() {
        $items = [];
        $taxonomies = (!empty($this->args['taxonomies']) ) ? $this->args['taxonomies'] : null;
        $args = (!empty($this->args['args'])) ? $this->args['args'] : null;
        $terms = \get_terms($taxonomies, $args);

        if(!empty($terms)) {
            foreach($terms as $key => $term) {
                unset($key); // we don't need that variable here

                $items[$term->term_id] = $term->name;
            }
        }

        return $items;
    }

    /**
     * Get taxonomies from WordPress, used by the select field type
     */
    public function getTaxonomies() {
        $items = [];
        $args = (!empty($this->args['args'])) ? $this->args['args'] : null;
        $taxonomies = \get_taxonomies($args, 'objects');

        if(!empty($taxonomies)) {
            foreach($taxonomies as $taxonomy) {
                $items[$taxonomy->name] = $taxonomy->label;
            }
        }

        return $items;
    }

    /**
     * Get sidebars from WordPress, used by the select field type
     */
    public function getSidebars() {
        $items = [];

        global $wp_registered_sidebars;

        if(!empty($wp_registered_sidebars)) {
            foreach($wp_registered_sidebars as $sidebar) {
                $items[$sidebar['id']] = $sidebar['name'];
            }
        }

        return $items;
    }

    /**
     * Get themes from WordPress, used by the select field type
     */
    public function getThemes() {
        $items = [];
        $args = (!empty($this->args['args'])) ? $this->args['args'] : null;
        $themes = \wp_get_themes($args);

        if(!empty($themes)) {
            foreach($themes as $key => $theme) {
                $items[$key] = $theme->get('Name');
            }
        }

        return $items;
    }

    /**
     * Get plugins from WordPress, used by the select field type
     */
    public function getPlugins() {
        $items = [];
        $args = (!empty($this->args['args'])) ? $this->args['args'] : null;
        $plugins = \get_plugins($args);

        if(!empty($plugins)) {
            foreach($plugins as $key => $plugin) {
                $items[$key] = $plugin['Name'];
            }
        }

        return $items;
    }

    /**
     * Get post_types from WordPress, used by the select field type
     */
    public function getPostTypes() {
        $items = [];
        $args = (!empty($this->args['args'])) ? $this->args['args'] : null;
        $post_types = \get_post_types($args, 'objects');

        if(!empty($post_types)) {
            foreach($post_types as $key => $post_type) {
                $items[$key] = $post_type->name;
            }
        }

        return $items;
    }

    /**
     * Find a selected value in select or multiselect field type
     */
    public function selected($key) {
        $returnValue = null;

        if($this->valueType() == 'array') {
            $returnValue = $this->multiselectedValue($key);
        } else {
            $returnValue = $this->selectedValue($key);
        }

        return $returnValue;
    }

    /**
     * Return selected html if the value is selected in select field type
     */
    public function selectedValue($key) {
        $result = '';

        if($this->value($this->options, $this->args) === $key) {
            $result = ' selected="selected"';
        }

        return $result;
    }

    /**
     * Return selected html if the value is selected in multiselect field type
     */
    public function multiselectedValue($key) {
        $result = '';
        $value = $this->value($this->options, $this->args, $key);

        if(\is_array($value) && \in_array($key, $value)) {
            $result = ' selected="selected"';
        }

        return $result;
    }

    /**
     * Return checked html if the value is checked in radio or checkboxes
     */
    public function checked($slug) {
        $value = $this->value();

        if($this->valueType() == 'array') {
            $checked = (!empty($value) && \in_array($slug, $this->value())) ? ' checked="checked"' : '';
        } else {
            $checked = (!empty($value) && $slug == $this->value()) ? ' checked="checked"' : '';
        }

        return $checked;
    }

    /**
     * Return the value. If the value is not saved the default value is used if
     * exists in the settingsArray.
     *
     * Return as string or array
     */
    public function value() {
        $value = '';

        if($this->valueType() == 'array') {
            $default = (!empty($this->args['default']) && \is_array($this->args['default'])) ? $this->args['default'] : [];
        } else {
            $default = (!empty($this->args['default'])) ? $this->args['default'] : '';
        }

        $value = (isset($this->options[$this->args['field_id']])) ? $this->options[$this->args['field_id']] : $default;

        return $value;
    }

    /**
     * Check if the current value type is a single value or a multiple
     * value field type, return string or array
     */
    public function valueType() {
        $returnValue = null;
        $defaultSingle = [
            'select',
            'radio',
            'text',
            'email',
            'url',
            'color',
            'date',
            'number',
            'password',
            'colorpicker',
            'textarea',
            'datepicker',
            'tinymce',
            'image',
            'file'
        ];

        $defaultMultiple = [
            'multiselect',
            'checkbox'
        ];

        if(\in_array($this->args['type'], $defaultSingle)) {
            $returnValue = 'string';
        } elseif(\in_array($this->args['type'], $defaultMultiple)) {
            $returnValue = 'array';
        }

        return $returnValue;
    }

    /**
     * Check if a checkbox has items
     */
    public function hasItems() {
        $returnValue = false;

        if(!empty($this->args['choices']) && \is_array($this->args['choices'])) {
            $returnValue = true;
        }

        return $returnValue;
    }

    /**
     * Return the html name of the field
     */
    public function name($slug = '') {
        $returnValue = null;
        $option_name = \sanitize_title($this->args['option_name']);

        if($this->valueType() == 'array') {
            $returnValue = $option_name . '[' . $this->args['field_id'] . '][' . $slug . ']';
        } else {
            $returnValue = $option_name . '[' . $this->args['field_id'] . ']';
        }

        return $returnValue;
    }

    /**
     * Return the size of a multiselect type. If not set it will calculate it
     */
    public function size($items) {
        $size = '';

        if($this->args['type'] == 'multiselect') {
            if(!empty($this->args['size'])) {
                $count = $this->args['size'];
            } else {
                $count = \count($items);
                $count = (!empty($this->args['empty']) ) ? $count + 1 : $count;
            }

            $size = ' size="' . $count . '"';
        }

        return $size;
    }

    /**
     * All the field types in html
     */
    public function renderFields($args) {
        $args['field_id'] = \sanitize_title($args['field_id']);
        $this->args = $args;

        $options = \get_option($args['option_name'], $this->optionsDefault);
        $this->options = $options;

        $option_name = \sanitize_title($args['option_name']);
        $out = '';

        if(!empty($args['type'])) {
            switch($args['type']) {
                case 'select':
                case 'multiselect':
                    $multiple = ($args['type'] == 'multiselect') ? ' multiple' : '';
                    $items = $this->get($args);
                    $out .= '<select' . $multiple . ' name="' . $this->name() . '"' . $this->size($items) . '>';

                    if(!empty($args['empty'])) {
                        $out .= '<option value="" ' . $this->selected('') . '>' . $args['empty'] . '</option>';
                    }

                    foreach($items as $key => $choice) {
                        $key = \sanitize_title($key);
                        $out .= '<option value="' . $key . '" ' . $this->selected($key) . '>' . $choice . '</option>';
                    }

                    $out .= '</select>';
                    break;

                case 'radio':
                case 'checkbox':
                    if($this->hasItems()) {
                        $horizontal = (isset($args['align']) && (string) $args['align'] == 'horizontal') ? ' class="horizontal"' : '';

                        $out .= '<ul class="settings-group settings-type-' . $args['type'] . '">';

                        foreach($args['choices'] as $slug => $choice) {
                            $checked = $this->checked($slug);

                            $out .= '<li' . $horizontal . '><label>';
                            $out .= '<input value="' . $slug . '" type="' . $args['type'] . '" name="' . $this->name($slug) . '"' . $checked . '>';
                            $out .= $choice;
                            $out .= '</label></li>';
                        }

                        $out .= '</ul>';
                    }
                    break;

                case 'text':
                case 'email':
                case 'url':
                case 'color':
                case 'date':
                case 'number':
                case 'password':
                case 'colorpicker':
                case 'datepicker':
                    $out = '<input type="' . $args['type'] . '" value="' . $this->value() . '" name="' . $this->name() . '" class="' . $args['type'] . '" data-id="' . $args['field_id'] . '">';
                    break;

                case 'textarea':
                    $rows = (isset($args['rows'])) ? $args['rows'] : 5;
                    $out .= '<textarea rows="' . $rows . '" class="large-text" name="' . $this->name() . '">' . $this->value() . '</textarea>';
                    break;

                case 'tinymce':
                    $rows = (isset($args['rows'])) ? $args['rows'] : 5;
                    $tinymce_settings = [
                        'textarea_rows' => $rows,
                        'textarea_name' => $option_name . '[' . $args['field_id'] . ']',
                    ];

                    \wp_editor($this->value(), $args['field_id'], $tinymce_settings);
                    break;

                case 'image':
                    $image_obj = (!empty($options[$args['field_id']])) ? \wp_get_attachment_image_src($options[$args['field_id']], 'thumbnail') : '';
                    $image = (!empty($image_obj)) ? $image_obj[0] : '';
                    $upload_status = (!empty($image_obj)) ? ' style="display: none"' : '';
                    $remove_status = (!empty($image_obj)) ? '' : ' style="display: none"';
                    $value = (!empty($options[$args['field_id']])) ? $options[$args['field_id']] : '';
                    ?>
                    <div data-id="<?php echo $args['field_id']; ?>">
                        <div class="upload" data-field-id="<?php echo $args['field_id']; ?>"<?php echo $upload_status; ?>>
                            <span class="button upload-button">
                                <a href="#">
                                    <i class="fa fa-upload"></i>
                                    <?php echo \__('Upload', 'eve-online'); ?>
                                </a>
                            </span>
                        </div>
                        <div class="image">
                            <img class="uploaded-image" src="<?php echo $image; ?>" id="<?php echo $args['field_id']; ?>" />
                        </div>
                        <div class="remove"<?php echo $remove_status; ?>>
                            <span class="button upload-button">
                                <a href="#">
                                    <i class="fa fa-trash"></i>
                                    <?php echo \__('Remove', 'eve-online'); ?>
                                </a>
                            </span>
                        </div>
                        <input type="hidden" class="attachment_id" value="<?php echo $value; ?>" name="<?php echo $option_name; ?>[<?php echo $args['field_id']; ?>]">
                    </div>
                    <?php
                    break;

                case 'file':
                    $file_url = (!empty($options[$args['field_id']])) ? \wp_get_attachment_url($options[$args['field_id']]) : '';
                    $upload_status = (!empty($file_url)) ? ' style="display: none"' : '';
                    $remove_status = (!empty($file_url)) ? '' : ' style="display: none"';
                    $value = (!empty($options[$args['field_id']])) ? $options[$args['field_id']] : '';
                    ?>
                    <div data-id="<?php echo $args['field_id']; ?>">
                        <div class="upload" data-field-id="<?php echo $args['field_id']; ?>"<?php echo $upload_status; ?>>
                            <span class="button upload-button">
                                <a href="#">
                                    <i class="fa fa-upload"></i>
                                    <?php echo \__('Upload', 'eve-online'); ?>
                                </a>
                            </span>
                        </div>
                        <div class="url">
                            <code class="uploaded-file-url" title="Attachment ID: <?php echo $value; ?>" data-field-id="<?php echo $args['field_id']; ?>">
                                <?php echo $file_url; ?>
                            </code>
                        </div>
                        <div class="remove"<?php echo $remove_status; ?>>
                            <span class="button upload-button">
                                <a href="#">
                                    <i class="fa fa-trash"></i>
                                    <?php echo \__('Remove', 'eve-online'); ?>
                                </a>
                            </span>
                        </div>
                        <input type="hidden" class="attachment_id" value="<?php echo $value; ?>" name="<?php echo $option_name; ?>[<?php echo $args['field_id']; ?>]">
                    </div>
                    <?php
                    break;

                case 'button':
                    $getPage = \filter_input(\INPUT_GET, 'page');
                    $warning_message = (!empty($args['warning-message'])) ? $args['warning-message'] : 'Unsaved settings will be lost. Continue?';
                    $warning = (!empty($args['warning'])) ? ' onclick="return confirm(' . "'" . $warning_message . "'" . ')"' : '';
                    $label = (!empty($args['label'])) ? $args['label'] : '';
                    $complete_url = \wp_nonce_url(\admin_url('options-general.php?page=' . $getPage . '&callback=' . $args['callback']));
                    ?>
                    <a href="<?php echo $complete_url; ?>" class="button button-secondary"<?php echo $warning; ?>><?php echo $label; ?></a>
                    <?php
                    break;

                case 'custom':
                    $value = (!empty($options[$args['field_id']])) ? $options[$args['field_id']] : null;
                    $data = [
                        'value' => $value,
                        'name' => $this->name(),
                        'args' => $args
                    ];

                    if($args['content'] !== null) {
                        echo $args['content'];
                    }

                    if($args['callback'] !== null) {
                        \call_user_func($args['callback'], $data);
                    }
                    break;
            }
        }

        echo $out;

        if(!empty($args['description'])) {
            echo '<p class="description">' . $args['description'] . '</div>';
        }
    }

    /**
     * Callback for field registration.
     * It's required by WordPress but not used by this class
     */
    public function callback() {
    }

    /**
     * Final output on the settings page
     */
    public function renderOptions() {
        $page = \filter_input(\INPUT_GET, 'page');
        $settings = $this->settingsArray[$page];
        $message = \get_option('rsa-message');

        if(!empty($settings['tabs']) && \is_array($settings['tabs'])) {
            $tab_count = \count($settings['tabs']);
            ?>
            <div class="wrap">
                <?php
                if(!empty($settings['before_tabs_text'])) {
                    echo $settings['before_tabs_text'];
                }
                ?>
                <form action='options.php' method='post'>
                    <?php
                    if($tab_count > 1) {
                        ?>
                        <h2 class="nav-tab-wrapper">
                        <?php
                        $i = 0;
                        foreach($settings['tabs'] as $settings_id => $section) {
                            $sanitized_id = \sanitize_title($settings_id);
                            $tab_title = (!empty($section['tab_title'])) ? $section['tab_title'] : $sanitized_id;
                            $active = ($i == 0) ? ' nav-tab-active' : '';

                            echo '<a class="nav-tab nav-tab-' . $sanitized_id . $active . '" href="#tab-content-' . $sanitized_id . '">' . $tab_title . '</a>';

                            $i++;
                        }
                        ?>
                        </h2>

                        <?php
                        if(!empty($message)) {
                            ?>
                            <div class="updated settings-error">
                                <p><strong><?php echo $message; ?></strong></p>
                            </div>
                            <?php
                            \update_option('rsa-message', '');
                        }
                    }

                    $i = 0;
                    foreach($settings['tabs'] as $settings_id => $section) {
                        $sanitized_id = \sanitize_title($settings_id);
                        $page_id = $page . '_' . $sanitized_id;

                        $display = ($i == 0) ? ' style="display: block;"' : ' style="display:none;"';

                        echo '<div class="tab-content" id="tab-content-' . $sanitized_id . '"' . $display . '>';
                        echo \settings_fields('section_page_' . $page . '_' . $sanitized_id);

                        \do_settings_sections($page_id);

                        echo '</div>';

                        $i++;
                    }

                    \submit_button();
                    ?>
                </form>
                <?php
                if(!empty($settings['after_tabs_text'])) {
                    echo $settings['after_tabs_text'];
                }
                ?>
            </div>
            <?php
        }
    }

    /**
     * Register scripts
     */
    public function enqueueScripts() {
        if($this->isSettingsPage() === true) {
            \wp_enqueue_media();
            \wp_enqueue_script('wp-color-picker');
            \wp_enqueue_script('jquery-ui-datepicker');
            \wp_enqueue_script(
                'settings-api',
                (\preg_match('/development/', \APPLICATION_ENV)) ? \get_template_directory_uri() . '/Admin/js/settings-api.js' : \get_template_directory_uri() . '/Admin/js/settings-api.min.js'
            );
        }
    }

    /**
     * Register styles
     */
    public function enqueueStyles() {
        if($this->isSettingsPage() === true) {
            \wp_enqueue_style('wp-color-picker');
            \wp_enqueue_style('jquery-ui', \get_template_directory_uri() . '/Admin/css/jquery-ui.min.css');
            \wp_enqueue_style(
                'font-awesome',
                (\preg_match('/development/', \APPLICATION_ENV)) ? \get_template_directory_uri() . '/font-awesome/css/font-awesome.css' : \get_template_directory_uri() . '/font-awesome/css/font-awesome.min.css'
            );
            \wp_enqueue_style(
                'settings-api',
                (\preg_match('/development/', \APPLICATION_ENV)) ? \get_template_directory_uri() . '/Admin/css/settings-api.css' : \get_template_directory_uri() . '/Admin/css/settings-api.min.css'
            );
        }
    }

    /**
     * Inline scripts and styles
     */
    public function adminStyles() {
        if($this->isSettingsPage() === true) {
            ?>
            <style type="text/css">
            .image img {
                border: 1px solid #ddd;
                vertical-align: bottom;
            }
            .image img:hover {
                cursor: pointer;
            }
            .nav-tab:focus {
                outline: none;
                -webkit-box-shadow: none;
                box-shadow: none;
            }
            .rsa-delete {
                color: #a00;
            }
            .rsa-delete:hover {
                color: red;
            }
            </style>
            <?php
        }
    }

    public function adminScripts() {
        if($this->isSettingsPage() === true) {
            ?>
            <script type="text/javascript">
            jQuery(document).ready(function($) {
                <?php
                $settingsArray = $this->settingsArray;

                foreach($settingsArray as $page) {
                    foreach($page['tabs'] as $tab) {
                        foreach($tab['fields'] as $field_key => $field) {
                            if($field['type'] == 'datepicker') {
                                $date_format = (!empty($field['format']) ) ? $field['format'] : \get_option('date_format');
                                ?>
                                $('[data-id="<?php echo $field_key; ?>"]').datepicker({
                                    dateFormat: '<?php echo $date_format; ?>'
                                });
                                <?php
                            }
                        }
                    }
                }
                ?>
            });
            </script>
            <?php
        }
    }
}
