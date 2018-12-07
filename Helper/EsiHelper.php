<?php

/**
 * Talking to the ESI API ...
 */

namespace WordPress\Themes\EveOnline\Helper;

use \WordPress\EsiClient\Repository\AllianceRepository;
use \WordPress\EsiClient\Repository\CharacterRepository;
use \WordPress\EsiClient\Repository\CorporationRepository;
use \WordPress\EsiClient\Repository\UniverseRepository;

\defined('ABSPATH') or die();

class EsiHelper {
    /**
     * ESI URL
     *
     * @var string
     */
    private $esiUrl = null;

    /**
     * ESI Endpoints
     *
     * @var array
     */
    private $esiEndpoints = null;

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
        $this->esiUrl = 'https://esi.evetech.net/latest/';
        $this->imageserverUrl = 'https://imageserver.eveonline.com/';

        /**
         * ESI API Client
         */
        $this->allianceApi = new AllianceRepository;
        $this->corporationApi = new CorporationRepository;
        $this->characterApi = new CharacterRepository;
        $this->universeApi = new UniverseRepository;

        /**
         * Assigning ESI Endpoints
         *
         * @see https://esi.evetech.net/latest/
         */
        $this->esiEndpoints = [
            'alliance-information' => 'alliances/', // getting alliance information by ID - https://esi.evetech.net/latest/alliances/99000102/
            'character-information' => 'characters/', // getting character information by ID - https://esi.evetech.net/latest/characters/90607580/
            'corporation-information' => 'corporations/', // getting corporation information by ID - https://esi.evetech.net/latest/corporations/98000030/
            'search' => 'search/', // Search for entities that match a given sub-string. - https://esi.evetech.net/latest/search/?search=Yulai%20Federation&strict=true&categories=alliance
            'system-information' => 'universe/systems/', // getting system information by ID - https://esi.evetech.net/latest/universe/systems/30000003/
            'type-information' => 'universe/types/', // getting types information by ID - https://esi.evetech.net/latest/universe/types/670/
        ];

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
     * Get character data by ID
     *
     * @param int $characterId
     * @return \WordPress\EsiClient\Model\Character\CharactersCharacterId
     */
//    public function getCharacterData(int $characterId) {
//        $characterData = $this->databaseHelper->getCachedEsiDataFromDb('characters/' . $characterId);
//
//        if(\is_null($characterData)) {
//            $characterData = $this->characterApi->charactersCharacterId($characterId);
//
//            if(!\is_null($characterData)) {
//                $this->databaseHelper->writeEsiCacheDataToDb([
//                    'characters/' . $characterId,
//                    \maybe_serialize($characterData),
//                    \strtotime('+1 week')
//                ]);
//            }
//        }
//
//        return [
//            'data' => $characterData
//        ];
//    }

    /**
     * Get corporation data by ID
     *
     * @param int $corporationId
     * @return \WordPress\EsiClient\Model\Corporation\CorporationsCorporationId
     */
//    public function getCorporationData(int $corporationId) {
//        $corporationData = $this->databaseHelper->getCachedEsiDataFromDb('corporations/' . $corporationId);
//
//        if(\is_null($corporationData)) {
//            $corporationData = $this->corporationApi->corporationsCorporationId($corporationId);
//
//            if(!\is_null($corporationData)) {
//                $this->databaseHelper->writeEsiCacheDataToDb([
//                    'corporations/' . $corporationId,
//                    \maybe_serialize($corporationData),
//                    \strtotime('+1 week')
//                ]);
//            }
//        }
//
//        return [
//            'data' => $corporationData
//        ];
//    }

    /**
     * Get alliance data by ID
     *
     * @param int $allianceId
     * @return \WordPress\EsiClient\Model\Alliance\AlliancesAllianceId
     */
//    public function getAllianceData(int $allianceId) {
//        $allianceData = $this->databaseHelper->getCachedEsiDataFromDb('alliances/' . $allianceId);
//
//        if(\is_null($allianceData)) {
//            $allianceData = $this->allianceApi->alliancesAllianceId($allianceId);
//
//            if(!\is_null($allianceData)) {
//                $this->databaseHelper->writeEsiCacheDataToDb([
//                    'alliances/' . $allianceId,
//                    \maybe_serialize($allianceData),
//                    \strtotime('+1 week')
//                ]);
//            }
//        }
//
//        return [
//            'data' => $allianceData
//        ];
//    }

    /**
     * Get the IDs to an array of names
     *
     * @param array $names
     * @param string $type
     * @return type
     */
    public function getIdFromName(array $names, string $type) {
        $returnData = null;

        /* @var $esiData \WordPress\EsiClient\Model\Universe\UniverseIds */
        $esiData = $this->universeApi->universeIds(\array_values($names));

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

    /**
     * Getting data from the ESI
     *
     * @param string $route
     * @param int $cacheTime Caching time in hours (Default: 120)
     * @return object
     */
//    private function getEsiData($route, $cacheTime = 120) {
//        $returnValue = null;
//
//        $transientName = \sanitize_title('eve-esi-data_' . $route);
//        $data = CacheHelper::getTransientCache($transientName);
//
//        if($data === false || empty($data)) {
//            $data = RemoteHelper::getInstance()->getRemoteData($this->esiUrl . $route);
//
//            /**
//             * setting the transient caches
//             */
//            if(!isset($data->error) && !empty($data)) {
//                CacheHelper::setTransientCache($transientName, $data, $cacheTime);
//            }
//        }
//
//        if(!empty($data) && !isset($data->error)) {
//            $returnValue = \json_decode($data);
//        }
//
//        return $returnValue;
//    }

    /**
     * Check if we have valid ESI data or not
     *
     * @param array $esiData
     * @return boolean
     */
//    public function isValidEsiData($esiData) {
//        $returnValue = false;
//
//        if(!\is_null($esiData) && isset($esiData['data']) && !\is_null($esiData['data']) && !isset($esiData['data']->error)) {
//            $returnValue = true;
//        }
//
//        return $returnValue;
//    }
}
