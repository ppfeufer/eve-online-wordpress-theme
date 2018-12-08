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

namespace WordPress\Themes\EveOnline\Helper;

use \WordPress\EsiClient\Model\Universe\UniverseIds;
use \WordPress\EsiClient\Repository\AllianceRepository;
use \WordPress\EsiClient\Repository\CharacterRepository;
use \WordPress\EsiClient\Repository\CorporationRepository;
use \WordPress\EsiClient\Repository\UniverseRepository;

\defined('ABSPATH') or die();

class EsiHelper {
    /**
     * Image Server URL
     *
     * @var string
     */
    private $imageserverUrl = null;

    /**
     * Image Server Endpoints
     *
     * @var array
     */
    private $imageserverEndpoints = null;

    /**
     * instance
     *
     * static variable to keep the current (and only!) instance of this class
     *
     * @var Singleton
     */
    protected static $instance = null;

    /**
     * allianceApi
     *
     * @var AllianceRepository
     */
    private $allianceApi = null;

    /**
     * corporationApi
     *
     * @var CorporationRepository
     */
    private $corporationApi = null;

    /**
     * characterApi
     *
     * @var CharacterRepository
     */
    private $characterApi = null;

    /**
     * universeApi
     *
     * @var UniverseRepository
     */
    private $universeApi = null;

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
        $this->imageserverUrl = 'https://imageserver.eveonline.com/';

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
            'alliance' => 'Alliance/',
            'corporation' => 'Corporation/',
            'character' => 'Character/',
            'item' => 'Type/',
            'inventory' => 'InventoryType/' // Ships and all the other stuff
        ];
    }

    public function getImageServerUrl() {
        return $this->imageserverUrl;
    }

    public function getImageServerEndpoint($group) {
        return $this->getImageServerUrl() . $this->imageserverEndpoints[$group];
    }

    /**
     * Get the IDs to an array of names
     *
     * @param array $names
     * @param string $type
     * @return type
     */
    public function getIdFromName(array $names, string $type) {
        $returnData = null;

        /* @var $esiData UniverseIds */
        $esiData = $this->universeApi->universeIds(\array_values($names));

        if(!\is_null($esiData)) {
            switch($type) {
                case 'agents':
                    $returnData = $esiData->getAgents();
                    break;

                case 'alliances':
                    $returnData = $esiData->getAlliances();
                    break;

                case 'constellations':
                    $returnData = $esiData->getConstellations();
                    break;

                case 'characters':
                    $returnData = $esiData->getCharacters();
                    break;

                case 'corporations':
                    $returnData = $esiData->getCorporations();
                    break;

                case 'factions':
                    $returnData = $esiData->getFactions();
                    break;

                case 'inventoryTypes':
                    $returnData = $esiData->getInventoryTypes();
                    break;

                case 'regions':
                    $returnData = $esiData->getRegions();
                    break;

                case 'stations':
                    $returnData = $esiData->getStations();
                    break;

                case 'systems':
                    $returnData = $esiData->getSystems();
                    break;
            }
        }

        return $returnData;
    }

    /**
     * Get a pilots avatar by his name
     *
     * @param string $characterName
     * @param boolean $imageOnly
     * @param int $size
     * @param string $newWidth
     * @param string $newHeight
     * @return string
     */
    public function getCharacterImageByName($characterName, $imageOnly = true, $size = 128, $newWidth = null, $newHeight = null) {
        $returnData = null;

        $characterData = $this->getIdFromName([\trim($characterName)], 'characters');
        $characterID = (!\is_null($characterData)) ? $characterData['0']->getId() : null;

        // If we actually have a characterID
        if(!\is_null($characterID)) {
            $imageName = $characterID . '_' . $size. '.jpg';
            $imagePath = $this->imageserverUrl . $this->imageserverEndpoints['character'] . $imageName;

            if($imageOnly === true) {
                return $imagePath;
            }

            if(!\is_null($newWidth)) {
                $newWidth = ' width="' . $newWidth . '"';
            }

            if(!\is_null($newHeight)) {
                $newHeight = ' height="' . $newHeight . '"';
            }

            $returnData = '<img src="' . $imagePath . '" class="eve-character-image eve-character-id-' . $characterID . '" alt="' . $characterName . '">';
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
     * @return string
     */
    public function getEntityLogoByName($entityName, $entityType, $imageOnly = true, $size = 128) {
        $returnData = null;

        $eveEntityData = $this->getIdFromName([\trim($entityName)], $entityType . 's');
        $eveID = (!\is_null($eveEntityData)) ? $eveEntityData['0']->getId() : null;

        if(!\is_null($eveID)) {
            $imageName = $eveID . '_' . $size . '.png';
            $imagePath = $this->imageserverUrl . $this->imageserverEndpoints[$entityType] . $imageName;

            if($imageOnly === true) {
                return $imagePath;
            }

            $returnData = '<img src="' . $imagePath . '" class="eve-' . $entityType . '-logo">';
        }

        return $returnData;
    }
}
