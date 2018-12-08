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

namespace WordPress\Themes\EveOnline\Helper;

\defined('ABSPATH') or die();

class RemoteHelper {
    /**
     * instance
     *
     * static variable to keep the current (and only!) instance of this class
     *
     * @var Singleton
     */
    protected static $instance = null;

    /**
     * Returning the instance
     *
     * @return \WordPress\Themes\EveOnline\Helper\RemoteHelper
     */
    public static function getInstance() {
        if(null === self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * clone
     *
     * no cloning allowed
     */
    protected function __clone() {
        ;
    }

    /**
     * constructor
     *
     * no external instanciation allowed
     */
    protected function __construct() {
        ;
    }

    /**
     * Getting data from a remote source
     *
     * @param string $url
     * @param array $parameter
     * @return mixed
     */
    public function getRemoteData($url, array $parameter = []) {
        $params = '';

        if(\count($parameter) > 0) {
            $params = '?' . \http_build_query($parameter);
        }

        $remoteUrl = $url . $params;

        $get = \wp_remote_get($remoteUrl);
        $data = \wp_remote_retrieve_body($get);

        return $data;
    }
}
