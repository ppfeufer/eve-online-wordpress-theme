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
 * Utilizing the ML-Slider Plugin in our theme
 */

namespace Ppfeufer\Theme\EVEOnline\Plugins;

use Ppfeufer\Theme\EVEOnline\Helper\ThemeHelper;
use WP_Post;

class Metaslider {
    public function __construct($init = false) {
        if ($init === true) {
            $this->registerMetaBox();
        }
    }

    public function registerMetaBox(): void {
        add_action('add_meta_boxes', [$this, 'addMetaBox']);
        add_action('save_post', [$this, 'saveMetaBox']);
        add_action('eve_render_header_slider', [$this, 'renderSlider']);
    }

    /**
     * Add Meta Slider Box to page settings
     */
    public function addMetaBox(): bool {
        if ($this->metasliderPluginExists()) {
            add_meta_box('eve-metaslider-page-slider', __('Page Meta Slider', 'eve-online'), [$this, 'renderMetaBox'], 'page', 'side');

            return true;
        }

        return false;
    }

    /**
     * Check if the main plugin actually is installed and is active
     *
     * @return boolean
     */
    public function metasliderPluginExists(): bool {
        return class_exists('\MetaSliderPlugin');
    }

    /**
     * Render the Meta Slider Box
     *
     * @param WP_Post $post
     * @return bool
     */
    public function renderMetaBox(WP_Post $post): bool {
        if ($this->metasliderPluginExists()) {
            $metaslider = get_post_meta($post->ID, 'eve_metaslider_slider', true);

            // Default stretch setting to theme setting.
            $metaslider_stretch = 0;

            $options = $this->metasliderGetOptions();

            if (metadata_exists('post', $post->ID, 'eve_metaslider_slider_stretch')) {
                $metaslider_stretch = get_post_meta($post->ID, 'eve_metaslider_slider_stretch', true);
            }
            ?>
            <label for="eve_page_metaslider"><strong><?php _e('Display Page Meta Slider', 'eve-online'); ?></strong></label>
            <p>
                <select id="eve_page_metaslider" name="eve_page_metaslider">
                    <?php
                    foreach ($options as $id => $name) {
                        ?>
                        <option value="<?php
                        echo esc_attr($id); ?>" <?php
                        selected($metaslider, $id); ?>><?php
                            echo esc_html($name); ?></option>
                        <?php
                    }
                    ?>
                </select>
            </p>
            <p class="checkbox-wrapper">
                <input id="eve_page_metaslider_stretch" name="eve_page_metaslider_stretch" type="checkbox" <?php checked($metaslider_stretch); ?>>
                <label for="eve_page_metaslider_stretch"><?php _e('Stretch Page Meta Slider', 'eve-online'); ?></label>
            </p>
            <?php
            wp_nonce_field('save', '_eve_metaslider_nonce');

            return true;
        }

        return false;
    }

    /**
     * Getting the options
     *
     * @return array
     */
    public function metasliderGetOptions(): array {
        $options = ['' => __('None', 'eve-online')];

        if ($this->metasliderPluginExists()) {
            $sliders = get_posts([
                'post_type' => 'ml-slider',
                'numberposts' => 200,
            ]);

            foreach ($sliders as $slider) {
                $options[sanitize_title('metaSlider_ID_' . $slider->ID)] = __('Slider: ', 'eve-online') . $slider->post_title;
            }
        }

        return $options;
    }

    public function saveMetaBox($post_id): bool|int {
        $postNonce = filter_input(INPUT_POST, '_eve_metaslider_nonce');

        if (empty($postNonce) || !wp_verify_nonce($postNonce, 'save')) {
            return false;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return false;
        }

        if (defined('DOING_AJAX')) {
            return false;
        }

        update_post_meta($post_id, 'eve_metaslider_slider', sanitize_title(filter_input(INPUT_POST, 'eve_page_metaslider')));

        $slider_stretch = filter_input(INPUT_POST, 'eve_page_metaslider_stretch') === 'on';

        update_post_meta($post_id, 'eve_metaslider_slider_stretch', $slider_stretch);

        return true;
    }

    public function renderSlider(): bool {
        if ($this->metasliderPluginExists()) {
            /**
             * Check if a slider is set for this page
             */
            $page_id = get_the_ID();
            $page_slider = get_post_meta($page_id, 'eve_metaslider_slider', true);

            /**
             * No slider set, check for our default slider
             */
            if (empty($page_slider)) {
                $themeOptions = get_option('eve_theme_options', ThemeHelper::getThemeDefaultOptions());

                if (!empty($themeOptions['default_slider'])) {
                    if (isset($themeOptions['default_slider_on']['frontpage_only']) && !is_front_page()) {
                        return false;
                    }

                    $page_slider = $themeOptions['default_slider'];
                } else {
                    /**
                     * No slider set at all, not even a defalt one
                     */
                    return false;
                }
            }

            /**
             * Render it
             */
            if (str_starts_with(sanitize_title($page_slider), 'metaslider_id_')) {
                $slider_id = (int)str_replace('metaslider_id_', '', $page_slider);
                $slider_stretch = get_post_meta($page_id, 'eve_metaslider_slider_stretch', true);

                if ($slider_stretch === '') {
                    /**
                     * We'll default to false, this way it is determined by
                     * the slider's own settings
                     */
                    $slider_stretch = 0;
                }

                if ($slider_stretch === 1) {
                    $sliderHtml = '<div class="meta-slider slider-' . $slider_id . '" data-stretch="true">';
                } else {
                    $sliderHtml = '<div class="meta-slider slider-' . $slider_id . '">';
                }

                $sliderHtml .= do_shortcode('[metaslider id=' . $slider_id . ']');
                $sliderHtml .= '</div>';

                echo $sliderHtml;
            } else {
                /**
                 * Wrong format
                 */
                return false;
            }

            return true;
        }

        return false;
    }
}
