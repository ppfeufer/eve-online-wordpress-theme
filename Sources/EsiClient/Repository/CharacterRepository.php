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

use Ppfeufer\Theme\EVEOnline\EsiClient\Model\Characters\CharacterId;
use Ppfeufer\Theme\EVEOnline\EsiClient\Model\Characters\CharacterId\CorporationHistory;
use Ppfeufer\Theme\EVEOnline\EsiClient\Model\Characters\CharacterId\Portrait;
use Ppfeufer\Theme\EVEOnline\EsiClient\Model\Error\EsiError;
use Ppfeufer\Theme\EVEOnline\EsiClient\Swagger;
use function is_null;

class CharacterRepository extends Swagger {
    /**
     * Used ESI enpoints in this class
     *
     * @var array ESI enpoints
     */
    protected $esiEndpoints = [
        'characters_characterId' => 'characters/{character_id}/?datasource=tranquility',
        'characters_characterId_corporationhistory' => 'characters/{character_id}/corporationhistory/?datasource=tranquility',
        'characters_characterId_portrait' => 'characters/{character_id}/portrait/?datasource=tranquility',
        'characters_affiliation' => 'characters/affiliation/?datasource=tranquility'
    ];

    /**
     * Public information about a character
     *
     * @param int $characterID An EVE character ID
     * @return CharacterId|EsiError
     */
    public function charactersCharacterId(int $characterID) {
        $returnValue = null;

        $this->setEsiMethod('get');
        $this->setEsiRoute($this->esiEndpoints['characters_characterId']);
        $this->setEsiRouteParameter([
            '/{character_id}/' => $characterID
        ]);
        $this->setEsiVersion('v4');

        $esiData = $this->callEsi();

        if (!is_null($esiData)) {
            switch ($esiData['responseCode']) {
                case 200:
                    $returnValue = $this->map($esiData['responseBody'], new CharacterId);
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
     * Get a list of all the corporations a character has been a member of
     *
     * @param int $characterID An EVE character ID
     * @return CorporationHistory|EsiError
     */
    public function charactersCharacterIdCorporationhistory(int $characterID) {
        $returnValue = null;

        $this->setEsiMethod('get');
        $this->setEsiRoute($this->esiEndpoints['characters_characterId_corporationhistory']);
        $this->setEsiRouteParameter([
            '/{character_id}/' => $characterID
        ]);
        $this->setEsiVersion('v4');

        $esiData = $this->callEsi();

        if (!is_null($esiData)) {
            switch ($esiData['responseCode']) {
                case 200:
                    $returnValue = $this->map($esiData['responseBody'], new CorporationHistory);
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
     * Bulk lookup of character IDs to corporation, alliance and faction
     *
     * @param array $characterIds The character IDs to fetch affiliations for. All characters must exist, or none will be returned
     * @return array of Ppfeufer\Theme\EVEOnline\EsiClient\Model\Characters\Affiliation\Characters|EsiError
     */
    public function charactersAffiliation(array $characterIds = []) {
        $returnValue = null;

        $this->setEsiMethod('post');
        $this->setEsiPostParameter($characterIds);
        $this->setEsiRoute($this->esiEndpoints['characters_affiliation']);
        $this->setEsiVersion('v1');

        $esiData = $this->callEsi();

        if (!is_null($esiData)) {
            switch ($esiData['responseCode']) {
                case 200:
                    $returnValue = $this->mapArray($esiData['responseBody'], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Characters\Affiliation\Characters');
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
     * Get character portraits
     *
     * @param int $characterID An EVE character ID
     * @return Portrait|EsiError
     */
    public function charactersCharacterIdPortrait(int $characterID) {
        $returnValue = null;

        $this->setEsiMethod('get');
        $this->setEsiRoute($this->esiEndpoints['characters_characterId_portrait']);
        $this->setEsiRouteParameter([
            '/{character_id}/' => $characterID
        ]);
        $this->setEsiVersion('v2');

        $esiData = $this->callEsi();

        if (!is_null($esiData)) {
            switch ($esiData['responseCode']) {
                case 200:
                    $returnValue = $this->map($esiData['responseBody'], new Portrait);
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
