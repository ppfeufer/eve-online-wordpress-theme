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

use Ppfeufer\Theme\EVEOnline\EsiClient\Model\Alliance\Alliances;
use Ppfeufer\Theme\EVEOnline\EsiClient\Model\Alliances\AllianceId;
use Ppfeufer\Theme\EVEOnline\EsiClient\Model\Alliances\AllianceId\Corporations;
use Ppfeufer\Theme\EVEOnline\EsiClient\Model\Alliances\AllianceId\Icons;
use Ppfeufer\Theme\EVEOnline\EsiClient\Model\Error\EsiError;
use Ppfeufer\Theme\EVEOnline\EsiClient\Swagger;
use function is_null;
use function json_encode;

class AllianceRepository extends Swagger {
    /**
     * Used ESI enpoints in this class
     *
     * @var array ESI enpoints
     */
    protected $esiEndpoints = [
        'alliances' => 'alliances/?datasource=tranquility',
        'alliances_allianceId' => 'alliances/{alliance_id}/?datasource=tranquility',
        'alliances_allianceId_corporations' => 'alliances/{alliance_id}/corporations/?datasource=tranquility',
        'alliances_allianceId_icons' => 'alliances/{alliance_id}/icons/?datasource=tranquility'
    ];

    /**
     * List all active player alliances
     *
     * @return Alliances|EsiError
     */
    public function alliances() {
        $returnValue = null;

        $this->setEsiMethod('get');
        $this->setEsiRoute($this->esiEndpoints['alliances']);
        $this->setEsiVersion('v1');

        $esiData = $this->callEsi();

        if (!is_null($esiData)) {
            switch ($esiData['responseCode']) {
                case 200:
                    $returnValue = $this->mapArray(json_encode(['alliances' => $esiData['responseBody']]), '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Alliance\Alliances');
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
     * Public information about an alliance
     *
     * @param int $allianceID An EVE alliance ID
     * @return AllianceId|EsiError
     */
    public function alliancesAllianceId(int $allianceID) {
        $returnValue = null;

        $this->setEsiMethod('get');
        $this->setEsiRoute($this->esiEndpoints['alliances_allianceId']);
        $this->setEsiRouteParameter([
            '/{alliance_id}/' => $allianceID
        ]);
        $this->setEsiVersion('v3');

        $esiData = $this->callEsi();

        if (!is_null($esiData)) {
            switch ($esiData['responseCode']) {
                case 200:
                    $returnValue = $this->map($esiData['responseBody'], new AllianceId);
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
     * List all current member corporations of an alliance
     *
     * @return Corporations|EsiError
     */
    public function alliancesAllianceIdCorporations() {
        $returnValue = null;

        $this->setEsiMethod('get');
        $this->setEsiRoute($this->esiEndpoints['alliances_allianceId_corporations']);
        $this->setEsiVersion('v1');

        $esiData = $this->callEsi();

        if (!is_null($esiData)) {
            switch ($esiData['responseCode']) {
                case 200:
                    $returnValue = $this->mapArray(json_encode(['corporations' => $esiData['responseBody']]), '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Alliance\Alliances\AllianceId\Corporations');
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
     * Get alliance logos
     *
     * @param int $allianceID An EVE alliance ID
     * @return Icons|EsiError
     */
    public function alliancesAllianceIdIcons(int $allianceID) {
        $returnValue = null;

        $this->setEsiMethod('get');
        $this->setEsiRoute($this->esiEndpoints['alliances_allianceId_icons']);
        $this->setEsiRouteParameter([
            '/{alliance_id}/' => $allianceID
        ]);
        $this->setEsiVersion('v1');

        $esiData = $this->callEsi();

        if (!is_null($esiData)) {
            switch ($esiData['responseCode']) {
                case 200:
                    $returnValue = $this->map($esiData['responseBody'], new Icons);
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
