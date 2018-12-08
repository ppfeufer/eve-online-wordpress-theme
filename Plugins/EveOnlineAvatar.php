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

namespace WordPress\Themes\EveOnline\Plugins;

use \WordPress\Themes\EveOnline\Helper\EsiHelper;

class EveOnlineAvatar {
    private $eveApi = null;

    public function __construct() {
        $this->eveApi = EsiHelper::getInstance();

        $this->init();
    }

    public function init() {
        \add_filter('get_avatar', [$this, 'eveCharacterAvatar'], 10, 5);
        \add_filter('bp_core_fetch_avatar', [$this, 'fetchEveCharacterAvatar'], 1, 2);
        \add_filter('bp_core_fetch_avatar_url', [$this, 'fetchEveCharacterAvatar'], 1, 2);
        \add_filter('user_profile_picture_description', \create_function('$desc', 'return "' . \__('If you set your nickname to your pilot\'s name, you EVE avatar will be used here.', 'eve-online') . '";'));
    }

    public function eveCharacterAvatar($content, $id_or_email) {
        $returnValue = $content;

        if(\preg_match("/gravatar.com\/avatar/", $content)) {
            // get user login
            if(\is_numeric($id_or_email)) {
                $id = (int) $id_or_email;
                $user = \get_userdata($id);
            } elseif(\is_object($id_or_email)) {
                if(!empty($id_or_email->user_id)) {
                    $id = (int) $id_or_email->user_id;
                    $user = \get_userdata($id);
                } elseif(!empty($id_or_email->comment_author_email)) {
                    // Let's see if we can find an EVE Online Avatar
                    if(!empty($id_or_email->comment_author)) {
                        $eveImage = $this->eveApi->getCharacterImageByName($id_or_email->comment_author, false);

                        if($eveImage !== false) {
                            return $eveImage;
                        }
                    }

                    // Nope, no EVE Online Avatar available
                    return $content;
                }
            } else {
                $user = \get_user_by('email', $id_or_email);
            }

            if(!empty($user->nickname)) {
                $eveImage = $this->eveApi->getCharacterImageByName($user->nickname, false);
            }

            if(!empty($eveImage)) {
                $returnValue = $eveImage;
            }
        }

        return $returnValue;
    }

    public function fetchEveCharacterAvatar($content, $params) {
        $returnValue = $content;

        if(\is_array($params) && $params['object'] == 'user' ) {
            $returnValue = $this->eveCharacterAvatar($content, $params['item_id']);
        }

        return $returnValue;
    }
}
