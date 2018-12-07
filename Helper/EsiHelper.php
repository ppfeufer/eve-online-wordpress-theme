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

    public function getCharacterData($characterID) {
        $characterData = $this->getEsiData($this->esiEndpoints['character-information'] . $characterID . '/');

        return [
            'data' => $characterData
        ];
    }

    public function getCorporationData($corporationID) {
        $corporationData = $this->getEsiData($this->esiEndpoints['corporation-information'] . $corporationID . '/');

        return [
            'data' => $corporationData
        ];
    }

    public function getAllianceData($allianceID) {
        $allianceData = $this->getEsiData($this->esiEndpoints['alliance-information'] . $allianceID . '/', 3600);

        return [
            'data' => $allianceData
        ];
    }

    /**
     * Get the EVE ID by it's name
     *
     * @param type $name
     * @param type $type
     * @return type
     */
    public function getEveIdFromName($name, $type) {
        $returnData = null;

        $data = $this->getEsiData($this->esiEndpoints['search'] . '?search=' . \urlencode(\wp_specialchars_decode($name, \ENT_QUOTES)) . '&strict=true&categories=' . $type, 3600);

        if(!isset($data->error) && !empty((array) $data) && isset($data->{$type})) {
            /**
             * -= FIX =-
             * CCPs strict mode is not really strict, so we have to check manually ....
             * Please CCP, get your shit sorted ...
             *
             * @todo Replace this with /universe/ids/ logic
             */
            foreach($data->{$type} as $entityID) {
                switch($type) {
                    case 'character':
                        $characterSheet = $this->getCharacterData($entityID);

                        if($this->isValidEsiData($characterSheet) === true && \strtolower($characterSheet['data']->name) === \strtolower($name)) {
                            $returnData = $entityID;
                            break;
                        }
                        break;

                    case 'corporation':
                        $corporationSheet = $this->getCorporationData($entityID);

                        if($this->isValidEsiData($corporationSheet) === true && \strtolower($corporationSheet['data']->name) === \strtolower($name)) {
                            $returnData = $entityID;
                            break;
                        }
                        break;

                    case 'alliance':
                        $allianceSheet = $this->getAllianceData($entityID);

                        if($this->isValidEsiData($allianceSheet) === true && \strtolower($allianceSheet['data']->name) === \strtolower($name)) {
                            $returnData = $entityID;
                            break;
                        }
                        break;
                }
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

        $characterID = $this->getEveIdFromName($characterName, 'character');

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

        $eveID = $this->getEveIdFromName($entityName, $entityType);

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
    private function getEsiData($route, $cacheTime = 120) {
        $returnValue = null;

        $transientName = \sanitize_title('eve-esi-data_' . $route);
        $data = CacheHelper::getTransientCache($transientName);

        if($data === false || empty($data)) {
            $data = RemoteHelper::getInstance()->getRemoteData($this->esiUrl . $route);

            /**
             * setting the transient caches
             */
            if(!isset($data->error) && !empty($data)) {
                CacheHelper::setTransientCache($transientName, $data, $cacheTime);
            }
        }

        if(!empty($data) && !isset($data->error)) {
            $returnValue = \json_decode($data);
        }

        return $returnValue;
    }

    /**
     * Check if we have valid ESI data or not
     *
     * @param array $esiData
     * @return boolean
     */
    public function isValidEsiData($esiData) {
        $returnValue = false;

        if(!\is_null($esiData) && isset($esiData['data']) && !\is_null($esiData['data']) && !isset($esiData['data']->error)) {
            $returnValue = true;
        }

        return $returnValue;
    }
}
