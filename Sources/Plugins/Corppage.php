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
 * Corppage Plugin
 */

namespace Ppfeufer\Theme\EVEOnline\Plugins;

use Ppfeufer\Theme\EVEOnline\Helper\EsiHelper;
use Ppfeufer\Theme\EVEOnline\Helper\StringHelper;
use WP_Query;

class Corppage {
    private ?EsiHelper $esiHelper;

    public function __construct() {
        $this->esiHelper = EsiHelper::getInstance();

        if (is_admin()) {
            $this->registerMetaBoxes();
        }

        $this->registerShortcodes();
    }

    public function registerMetaBoxes(): void {
        add_action('add_meta_boxes', [$this, 'addMetaBox']);
        add_action('save_post', [$this, 'savePageSettings']);
    }

    public function registerShortcodes(): void {
        add_shortcode('corplist', [
            $this,
            'shortcodeCorplist'
        ]);
    }

    public static function getCorprationLogo($corpPageID): false|string {
        $corpName = get_post_meta($corpPageID, 'eve_page_corp_name', true);
        $corpID = get_post_meta($corpPageID, 'eve_page_corp_eve_ID', true);

        $imagePath = sprintf(
            EsiHelper::getInstance()->getImageServerEndpoint('corporation') . '?size=256.png',
            $corpID
        );

        if ($imagePath !== false) {
            $html = '<div class="eve-corp-page-corp-logo eve-image eve-corporation-logo-container"><figure><img src="' . $imagePath . '" class="eve-corporation-logo" alt="' . esc_html($corpName) . '" width="256">';
            $html .= '<figcaption>' . esc_html($corpName) . '</figcaption>';
            $html .= '</figure></div>';

            return $html;
        }

        return false;
    }

    public function shortcodeCorplist($attributes): ?string {
        $corpPages = $this->getCorporationPages();
        $corplistHTML = null;

        if ($corpPages !== false) {
            $corplistHTML = $this->getCorporationPagesLoop($corpPages);
        }

        return $corplistHTML;
    }

    private function getCorporationPages(): array|false {
        $returnValue = false;

        $result = new WP_Query([
            'post_type' => 'page',
            'meta_key' => 'eve_page_is_corp_page',
            'meta_value' => 1,
            'posts_per_page' => -1,
            'orderby' => 'post_title',
            'order' => 'ASC'
        ]);

        if ($result) {
            $returnValue = $result->posts;
        }

        return $returnValue;
    }

    /**
     * Rendering the loop for the corporation pages
     *
     * @param object $corpPages
     * @return string
     */
    private function getCorporationPagesLoop(object $corpPages): string {
        $uniqueID = uniqid('', false);
        $corplistHTML = '<div class="gallery-row row">';
        $corplistHTML .= '<ul class="bootstrap-gallery bootstrap-corporationlist bootstrap-corporationlist-' . $uniqueID . ' clearfix">';

        foreach ($corpPages as $page) {
            if (!empty($page->post_content)) {
                $corplistHTML .= $this->getCorporationPageLoopItem($page);
            }
        }

        $corplistHTML .= '</ul>';
        $corplistHTML .= '</div>';

        $corplistHTML .= '<script type="text/javascript">
                            jQuery(document).ready(function() {
                                jQuery("ul.bootstrap-corporationlist-' . $uniqueID . '").bootstrapGallery({
                                    "classes" : "col-lg-3 col-md-4 col-sm-6 col-xs-12",
                                    "hasModal" : false
                                });
                            });
                            </script>';

        return $corplistHTML;
    }

    /**
     * Rendering the single loop item for the corporation pages
     *
     * @param object $page
     * @return string
     */
    private function getCorporationPageLoopItem(object $page): string {
        $corpID = get_post_meta($page->ID, 'eve_page_corp_eve_ID', true);

        $corpLogo = sprintf(
            $this->esiHelper->getImageServerEndpoint('corporation') . '?size=256.png',
            $corpID
        );

        $corplistHTML = '<li>';
        $corplistHTML .= '<figure><a href="' . get_permalink($page->ID) . '"><img src="' . $corpLogo . '" alt="' . $page->post_title . '"></a></figure>';
        $corplistHTML .= '<header><h2 class="corporationlist-title"><a href="' . get_permalink($page->ID) . '">' . $page->post_title . '</a></h2></header>';

        $corplistHTML .= '<p>' . StringHelper::getInstance()->cutString(strip_shortcodes($page->post_content), '200') . '</p>';

        $corplistHTML .= '</li>';

        return $corplistHTML;
    }

    public function addMetaBox(): void {
        add_meta_box('eve-corp-page-box', __('Corp Page?', 'eve-online'), [$this, 'renderMetaBox'], 'page', 'side');
    }

    public function renderMetaBox($post): void {
        $isCorpPage = get_post_meta($post->ID, 'eve_page_is_corp_page', true);
        $showCorpLogo = get_post_meta($post->ID, 'eve_page_show_corp_logo', true);
        $corpName = get_post_meta($post->ID, 'eve_page_corp_name', true);
        $corpID = get_post_meta($post->ID, 'eve_page_corp_eve_ID', true);
        ?>
        <label><strong><?php
                _e('Corp Page Settings', 'eve-online'); ?></strong></label>
        <p class="checkbox-wrapper">
            <input id="eve_page_is_corp_page" name="eve_page_is_corp_page" type="checkbox" <?php checked($isCorpPage); ?>>
            <label for="eve_page_is_corp_page"><?php _e('Is Corp Page?', 'eve-online'); ?></label>
        </p>
        <p class="checkbox-wrapper">
            <input id="eve_page_show_corp_logo" name="eve_page_show_corp_logo" type="checkbox" <?php checked($showCorpLogo); ?>>
            <label for="eve_page_show_corp_logo"><?php _e('Show Corp Logo at the beginning of your page\'s content?', 'eve-online'); ?></label>
        </p>
        <p class="checkbox-wrapper">
            <label for="eve_page_corp_name"><?php _e('Corporation Name:', 'eve-online'); ?></label><br>
            <input id="eve_page_corp_name" name="eve_page_corp_name" type="text" value="<?php echo $corpName; ?>">
        </p>
        <?php
        if (!empty($corpID)) {
            ?>
            <p class="checkbox-wrapper">
                <label for="eve_page_corp_ID"><?php _e('Corporation ID', 'eve-online'); ?></label>
                <input id="eve_page_corp_ID" name="eve_page_corp_ID" type="text" value="<?php echo esc_html($corpID); ?>" disabled>
            </p>
            <p class="checkbox-wrapper">
                <label><strong><?php _e('Corporation Logo', 'eve-online'); ?></strong></label>
                <br>
                <?php
                $corpLogoPath = sprintf(
                    $this->esiHelper->getImageServerEndpoint('corporation') . '?size=256.png',
                    $corpID
                );
                ?>
                <img src="<?php echo $corpLogoPath; ?>" alt="<?php echo $corpName; ?>">
            </p>
            <?php
        }

        wp_nonce_field('save', '_eve_corp_page_nonce');
    }

    /**
     * @throws \Exception
     */
    public function savePageSettings(int $postID): ?bool {
        $postNonce = filter_input(INPUT_POST, '_eve_corp_page_nonce');

        if (empty($postNonce) || !wp_verify_nonce($postNonce, 'save')) {
            return false;
        }

        if (!current_user_can('edit_post', $postID)) {
            return false;
        }

        if (defined('DOING_AJAX')) {
            return false;
        }

        $isCorpPage = filter_input(INPUT_POST, 'eve_page_is_corp_page') === 'on';
        $showCorpLogo = '';
        $corpName = '';
        $corpID = '';

        /**
         * only if we really have a corp page ....
         */
        if (!empty($isCorpPage)) {
            $showCorpLogo = filter_input(INPUT_POST, 'eve_page_show_corp_logo') === 'on';
            $corpName = filter_input(INPUT_POST, 'eve_page_corp_name');

            $corpData = $this->esiHelper->getIdFromName([trim(stripslashes(filter_input(INPUT_POST, 'eve_page_corp_name')))], 'corporations');

            if (!is_null($corpData)) {
                $corpID = $corpData['0']->getId();
            }
        }

        update_post_meta($postID, 'eve_page_corp_name', $corpName);
        update_post_meta($postID, 'eve_page_is_corp_page', $isCorpPage);
        update_post_meta($postID, 'eve_page_show_corp_logo', $showCorpLogo);
        update_post_meta($postID, 'eve_page_corp_eve_ID', $corpID);

        return true;
    }
}
