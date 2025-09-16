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

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Repository;

use Ppfeufer\Theme\EVEOnline\EsiClient\Swagger;
use function is_null;

class MetaRepository extends Swagger {
    /**
     * Used ESI enpoints in this class
     *
     * @var array ESI enpoints
     */
    protected $esiEndpoints = [
        'status_json_latest' => 'status.json?version=latest',
        'status_json_dev' => 'status.json?version=dev',
        'status_json_lagecy' => 'status.json?version=lagecy',
        'status_json_meta' => 'status.json?version=meta',
        'esi_versions' => 'versions/',
        'esi_ping' => 'ping',
    ];

    /**
     * Provides a general health indicator per route and method
     *
     * @return array of \Ppfeufer\Theme\EVEOnline\EsiClient\Model\Meta\Status|EsiError
     */
    public function statusJsonLatest() {
        $returnValue = null;

        $this->setEsiMethod('get');
        $this->setEsiRoute($this->esiEndpoints['status_json_latest']);
        $this->setEsiVersion(null);

        $esiData = $this->callEsi();

        if (!is_null($esiData)) {
            switch ($esiData['responseCode']) {
                case 200:
                    $returnValue = $this->mapArray($esiData['responseBody'], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Meta\Status');
                    break;

                // Error ...
                default:
                    $returnValue = $this->createErrorObject($esiData);
                    break;
            }
        }

        return $returnValue;
    }

    /**
     * Provides a general health indicator per route and method
     *
     * @return array of \Ppfeufer\Theme\EVEOnline\EsiClient\Model\Meta\Status|EsiError
     */
    public function statusJsonDev() {
        $returnValue = null;

        $this->setEsiMethod('get');
        $this->setEsiRoute($this->esiEndpoints['status_json_dev']);
        $this->setEsiVersion(null);

        $esiData = $this->callEsi();

        if (!is_null($esiData)) {
            switch ($esiData['responseCode']) {
                case 200:
                    $returnValue = $this->mapArray($esiData['responseBody'], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Meta\Status');
                    break;

                // Error ...
                default:
                    $returnValue = $this->createErrorObject($esiData);
                    break;
            }
        }

        return $returnValue;
    }

    /**
     * Provides a general health indicator per route and method
     *
     * @return array of \Ppfeufer\Theme\EVEOnline\EsiClient\Model\Meta\Status|EsiError
     */
    public function statusJsonLagecy() {
        $returnValue = null;

        $this->setEsiMethod('get');
        $this->setEsiRoute($this->esiEndpoints['status_json_lagecy']);
        $this->setEsiVersion(null);

        $esiData = $this->callEsi();

        if (!is_null($esiData)) {
            switch ($esiData['responseCode']) {
                case 200:
                    $returnValue = $this->mapArray($esiData['responseBody'], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Meta\Status');
                    break;

                // Error ...
                default:
                    $returnValue = $this->createErrorObject($esiData);
                    break;
            }
        }

        return $returnValue;
    }

    /**
     * Provides a general health indicator per route and method
     *
     * @return array of \Ppfeufer\Theme\EVEOnline\EsiClient\Model\Meta\Status|EsiError
     */
    public function statusJsonMeta() {
        $returnValue = null;

        $this->setEsiMethod('get');
        $this->setEsiRoute($this->esiEndpoints['status_json_meta']);
        $this->setEsiVersion(null);

        $esiData = $this->callEsi();

        if (!is_null($esiData)) {
            switch ($esiData['responseCode']) {
                case 200:
                    $returnValue = $this->mapArray($esiData['responseBody'], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Meta\Status');
                    break;

                // Error ...
                default:
                    $returnValue = $this->createErrorObject($esiData);
                    break;
            }
        }

        return $returnValue;
    }
}
