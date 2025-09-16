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

use Ppfeufer\Theme\EVEOnline\EsiClient\Model\Corporations\CorporationId;
use Ppfeufer\Theme\EVEOnline\EsiClient\Model\Corporations\CorporationId\Icons;
use Ppfeufer\Theme\EVEOnline\EsiClient\Model\Error\EsiError;
use Ppfeufer\Theme\EVEOnline\EsiClient\Swagger;
use function is_null;

class CorporationRepository extends Swagger {
    /**
     * Used ESI enpoints in this class
     *
     * @var array ESI enpoints
     */
    protected $esiEndpoints = [
        'corporations_corporationId' => 'corporations/{corporation_id}/?datasource=tranquility',
        'corporations_corporationId_alliancehistory' => 'corporations/{corporation_id}/alliancehistory/?datasource=tranquility',
        'corporations_corporationId_icons' => 'corporations/{corporation_id}/icons/?datasource=tranquility',
        'corporations_npccorps' => 'corporations/npccorps/?datasource=tranquility',
    ];

    /**
     * Public information about a corporation
     *
     * @param int $corporationID An EVE corporation ID
     * @return CorporationId|EsiError
     */
    public function corporationsCorporationId(int $corporationID) {
        $returnValue = null;

        $this->setEsiMethod('get');
        $this->setEsiRoute($this->esiEndpoints['corporations_corporationId']);
        $this->setEsiRouteParameter([
            '/{corporation_id}/' => $corporationID
        ]);
        $this->setEsiVersion('v4');

        $esiData = $this->callEsi();

        if (!is_null($esiData)) {
            switch ($esiData['responseCode']) {
                case 200:
                    $returnValue = $this->map($esiData['responseBody'], new CorporationId);
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
     * Get corporation logos
     *
     * @param int $corporationID An EVE corporation ID
     * @return Icons|EsiError
     */
    public function corporationsCorporationIdIcons(int $corporationID) {
        $returnValue = null;

        $this->setEsiMethod('get');
        $this->setEsiRoute($this->esiEndpoints['corporations_corporationId_icons']);
        $this->setEsiRouteParameter([
            '/{corporation_id}/' => $corporationID
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
