<?php

/**
 * Copyright (C) 2017 Rounon Dax
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

class DatabaseHelper {
    /**
     * WordPress Database Instance
     *
     * @var \wpdb
     */
    private $wpdb = null;

    /**
     * Returning the instance
     *
     * @return \WordPress\Themes\EveOnline\Helper\EsiHelper
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
        parent::__construct();

        global $wpdb;

        $this->wpdb = $wpdb;
    }

    /**
     * Getting cached Data from DB
     *
     * @param string $route
     * @return Esi Object
     */
    public function getCachedEsiDataFromDb(string $route) {
        $returnValue = null;

        $cacheResult = $this->wpdb->get_results($this->wpdb->prepare(
            'SELECT * FROM ' . $this->wpdb->base_prefix . 'eve_online_esi_cache' . ' WHERE esi_route = %s AND valid_until > %s', [
                $route,
                \time()
            ]
        ));

        if($cacheResult) {
            $returnValue = \maybe_unserialize($cacheResult['0']->value);
        }

        return $returnValue;
    }

    /**
     * Write cache data into the DB
     *
     * @param array $data ([esi_route, value, valid_until])
     */
    public function writeEsiCacheDataToDb(array $data) {
        $this->wpdb->query($this->wpdb->prepare(
            'REPLACE INTO ' . $this->wpdb->base_prefix . 'eve_online_esi_cache' . ' (esi_route, value, valid_until) VALUES (%s, %s, %s)', $data
        ));
    }
}
