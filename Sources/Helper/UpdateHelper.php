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

namespace Ppfeufer\Theme\EVEOnline\Helper;

use wpdb;

class UpdateHelper {
    /**
     * Option field name for database version
     *
     * @var string
     */
    protected string $optionDatabaseFieldName = 'eve-online-theme-database-version';

    /**
     * Database version
     *
     * @var int
     */
    protected int $databaseVersion = 20190611;

    /**
     * WordPress Database Instance
     *
     * @var wpdb|null
     */
    private ?wpdb $wpdb;

    /**
     * constructor
     */
    public function __construct() {
        global $wpdb;

        $this->wpdb = $wpdb;

        $this->checkDatabaseForUpdates();
    }

    /**
     * Little helper to recirsively remove a directory
     *
     * @param string $dir
     */
    protected function rrmdir(string $dir): void {
        if (is_dir($dir)) {
            $objects = scandir($dir);

            foreach ($objects as $object) {
                if ($object !== '.' && $object !== '..') {
                    if (is_dir($dir . '/' . $object)) {
                        $this->rrmdir($dir . '/' . $object);
                    } else {
                        unlink($dir . '/' . $object);
                    }
                }
            }

            rmdir($dir);
        }
    }

    /**
     * Check if the database needs to be updated
     */
    protected function checkDatabaseForUpdates(): void {
        $currentVersion = $this->getCurrentDatabaseVersion();

        if (version_compare($currentVersion, $this->getNewDatabaseVersion()) < 0) {
            $this->updateDatabase();
        }

        /**
         * truncate cache table
         */
        if ($currentVersion < 20190611) {
            $this->truncateCacheTable();
        }

        /**
         * Update database version
         */
        update_option($this->getDatabaseFieldName(), $this->getNewDatabaseVersion());
    }

    /**
     * Getting the current database version
     *
     * @return int
     */
    public function getCurrentDatabaseVersion(): int {
        return get_option($this->getDatabaseFieldName());
    }

    /**
     * Returning the database version field name
     *
     * @return string
     */
    protected function getDatabaseFieldName(): string {
        return $this->optionDatabaseFieldName;
    }

    /**
     * getNewDatabaseVersion
     *
     * @return int
     */
    protected function getNewDatabaseVersion(): int {
        return $this->databaseVersion;
    }

    /**
     * Update the plugin database
     */
    public function updateDatabase(): void {
        $this->createEsiCacheTable();
    }

    private function createEsiCacheTable(): void {
        $charsetCollate = $this->wpdb->get_charset_collate();
        $tableName = $this->wpdb->base_prefix . 'eve_online_esi_cache';

        $sql = "CREATE TABLE $tableName (
            esi_route varchar(255),
            value longtext,
            valid_until varchar(255),
            PRIMARY KEY esi_route (esi_route)
        ) $charsetCollate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);
    }

    private function truncateCacheTable(): void {
        $tableName = $this->wpdb->base_prefix . 'eve_online_esi_cache';

        $sql = "TRUNCATE $tableName;";
        $this->wpdb->query($sql);
    }
}
