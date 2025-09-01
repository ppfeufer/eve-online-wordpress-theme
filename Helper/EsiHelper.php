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
 * Talking to the ESI API ...
 */

namespace Ppfeufer\Theme\EVEOnline\Helper;

use WordPress\EsiClient\Repository\AllianceRepository;
use WordPress\EsiClient\Repository\CharacterRepository;
use WordPress\EsiClient\Repository\CorporationRepository;
use WordPress\EsiClient\Repository\UniverseRepository;

class EsiHelper {
    /**
     * instance
     *
     * static variable to keep the current (and only!) instance of this class
     */
    protected static ?EsiHelper $instance = null;
    /**
     * Image Server URL
     *
     * @var string|null
     */
    private ?string $imageserverUrl;
    /**
     * Image Server Endpoints
     *
     * @var array|null
     */
    private ?array $imageserverEndpoints;
    /**
     * allianceApi
     *
     * @var AllianceRepository|null
     */
    private ?AllianceRepository $allianceApi;

    /**
     * corporationApi
     *
     * @var CorporationRepository|null
     */
    private ?CorporationRepository $corporationApi;

    /**
     * characterApi
     *
     * @var CharacterRepository|null
     */
    private ?CharacterRepository $characterApi;

    /**
     * universeApi
     *
     * @var UniverseRepository|null
     */
    private ?UniverseRepository $universeApi;

    /**
     * constructor
     *
     * no external instanciation allowed
     */
    protected function __construct() {
        $this->imageserverUrl = 'https://images.evetech.net/';

        /**
         * ESI API Client
         */
        $this->allianceApi = new AllianceRepository;
        $this->corporationApi = new CorporationRepository;
        $this->characterApi = new CharacterRepository;
        $this->universeApi = new UniverseRepository;

        /**
         * Assigning Imagesever Endpoints
         */
        $this->imageserverEndpoints = [
            'alliance' => 'alliances/%d/logo',
            'corporation' => 'corporations/%d/logo',
            'character' => 'characters/%d/portrait',
            'typeIcon' => 'types/%d/icon',
            'typeRender' => 'types/%d/render'
        ];
    }

    /**
     * Returning the instance
     *
     * @return \Ppfeufer\Theme\EVEOnline\Helper\EsiHelper|null
     */
    public static function getInstance(): ?EsiHelper {
        if (null === self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function getImageServerEndpoint($group): string {
        return $this->getImageServerUrl() . $this->imageserverEndpoints[$group];
    }

    public function getImageServerUrl(): ?string {
        return $this->imageserverUrl;
    }

    /**
     * Get a pilots avatar by his name
     *
     * @param string $characterName
     * @param boolean $imageOnly
     * @param int $size
     * @param string|null $newWidth
     * @param string|null $newHeight
     * @return string
     */
    public function getCharacterImageByName(string $characterName, bool $imageOnly = true, int $size = 128, string $newWidth = null, string $newHeight = null): ?string {
        $returnData = null;

        $characterData = $this->getIdFromName([trim($characterName)], 'characters');
        $characterID = (!is_null($characterData)) ? $characterData['0']->getId() : null;

        // If we actually have a characterID
        if (!is_null($characterID)) {
            $imagePath = sprintf(
                $this->imageserverUrl . $this->imageserverEndpoints['character'] . '?size=' . $size,
                $characterID
            );

            if ($imageOnly === true) {
                return $imagePath;
            }

            if (!is_null($newWidth)) {
                $newWidth = ' width="' . $newWidth . '"';
            }

            if (!is_null($newHeight)) {
                $newHeight = ' height="' . $newHeight . '"';
            }

            $returnData = '<img src="' . $imagePath . '" class="eve-character-image eve-character-id-' . $characterID . '" alt="' . $characterName . '">';
        }

        return $returnData;
    }

    /**
     * Get the IDs to an array of names
     *
     * @param array $names
     * @param string $type
     * @return array|null
     * @throws \Exception
     */
    public function getIdFromName(array $names, string $type): ?array {
        $returnData = null;

        /* @var $esiData \WordPress\EsiClient\Model\Universe\Ids */
        $esiData = $this->universeApi->universeIds(array_values($names));

        if (is_a($esiData, '\WordPress\EsiClient\Model\Universe\Ids')) {
            $returnData = match ($type) {
                'agents' => $esiData->getAgents(),
                'alliances' => $esiData->getAlliances(),
                'constellations' => $esiData->getConstellations(),
                'characters' => $esiData->getCharacters(),
                'corporations' => $esiData->getCorporations(),
                'factions' => $esiData->getFactions(),
                'inventoryTypes' => $esiData->getInventoryTypes(),
                'regions' => $esiData->getRegions(),
                'stations' => $esiData->getStations(),
                'systems' => $esiData->getSystems(),
                default => throw new \Exception('Unexpected value'),
            };
        }

        return $returnData;
    }

    /**
     * Get a corp or alliance logo by it's entity name
     *
     * @param string $entityName
     * @param string $entityType corporation/alliance
     * @param boolean $imageOnly
     * @param int $size
     * @return string|null
     */
    public function getEntityLogoByName(string $entityName, string $entityType, bool $imageOnly = true, int $size = 128): ?string {
        $returnData = null;

        $eveEntityData = $this->getIdFromName([trim($entityName)], $entityType . 's');
        $eveID = (!is_null($eveEntityData)) ? $eveEntityData['0']->getId() : null;

        if (!is_null($eveID)) {
            $imagePath = sprintf(
                $this->imageserverUrl . $this->imageserverEndpoints[$entityType] . '?size=' . $size,
                $eveID
            );

            if ($imageOnly === true) {
                return $imagePath;
            }

            $returnData = '<img src="' . $imagePath . '" class="eve-' . $entityType . '-logo">';
        }

        return $returnData;
    }

    /**
     * clone
     *
     * no cloning allowed
     */
    protected function __clone() {
    }
}
