<?php

/**
 * Talking to the ESI API ...
 */

namespace WordPress\Themes\EveOnline\Helper;

use \Exception;
use \PclZip;
use \WordPress\EsiClient\Repository\AllianceRepository;
use \WordPress\EsiClient\Repository\CharacterRepository;
use \WordPress\EsiClient\Repository\CorporationRepository;
use \WordPress\EsiClient\Repository\UniverseRepository;
use \ZipArchive;

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
     * Option field name for database version
     *
     * @var string
     */
    protected $optionDatabaseFieldName = 'eve-online-theme-database-version';

    /**
     * Database version
     *
     * @var string
     */
    protected $databaseVersion = 20181006;

    /**
     * Database version
     *
     * @var string
     */
    protected $esiClientVersion = 20181202;

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

        $this->checkEsiClient();

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

    /**
     * getNewDatabaseVersion
     *
     * @return int
     */
    protected function getNewDatabaseVersion() {
        return $this->databaseVersion;
    }

    /**
     * getNewEsiClientVersion
     *
     * @return int
     */
    protected function getNewEsiClientVersion() {
        return $this->esiClientVersion;
    }

    /**
     * Returning the database version field name
     *
     * @return string
     */
    protected function getDatabaseFieldName() {
        return $this->optionDatabaseFieldName;
    }

    /**
     * Check if the ESI client needs to be updated
     */
    protected function checkEsiClient() {
        /**
         * Check for current ESI client version
         */
       if(\file_exists(\WP_CONTENT_DIR . '/EsiClient/client_version')) {
           $esiClientCurrentVersion = \trim(\file_get_contents(\WP_CONTENT_DIR . '/EsiClient/client_version'));
       }

       if(\version_compare($esiClientCurrentVersion, $this->getNewEsiClientVersion()) < 0) {
            $this->updateEsiClient($this->getNewEsiClientVersion());
        }
    }

    /**
     * Update the ESI client
     *
     * @throws Exception
     */
    protected function updateEsiClient(string $version = null) {
        // check if ZipArchive is available
        $hasZipArchive = (\class_exists('ZipArchive')) ? true : false;

        $remoteZipFile = 'https://github.com/ppfeufer/wp-esi-client/archive/master.zip';
        $dirInZipFile = '/wp-esi-client-master';

        if(!\is_null($version)) {
            $remoteZipFile = 'https://github.com/ppfeufer/wp-esi-client/archive/v' . $version . '.zip';
            $dirInZipFile = '/wp-esi-client-' . $version;
        }

        $esiClientZipFile = \WP_CONTENT_DIR . '/uploads/EsiClient.zip';

        \wp_remote_get($remoteZipFile, [
            'timeout' => 300,
            'stream' => true,
            'filename' => $esiClientZipFile
        ]);

        if(\is_dir(\WP_CONTENT_DIR . '/EsiClient/')) {
            $this->rrmdir(\WP_CONTENT_DIR . '/EsiClient/');
        }

        // extract using ZipArchive
        if($hasZipArchive === true) {
            $zip = new ZipArchive;
            if(!$zip->open($esiClientZipFile)) {
                throw new Exception('PHP-ZIP: Unable to open the Esi Client zip file');
            }

            if(!$zip->extractTo(\WP_CONTENT_DIR)) {
                throw new Exception('PHP-ZIP: Unable to extract Esi Client zip file');
            }

            $zip->close();
        }

        // extract using PclZip
        if($hasZipArchive === false) {
            require_once(\ABSPATH . 'wp-admin/includes/class-pclzip.php');

            $zip = new PclZip($esiClientZipFile);

            if(!$zip->extract(\PCLZIP_OPT_PATH, \WP_CONTENT_DIR)) {
                throw new Exception('PHP-ZIP: Unable to extract Esi Client zip file');
            }
        }

        \rename(\WP_CONTENT_DIR . $dirInZipFile, \WP_CONTENT_DIR . '/EsiClient/');

        \unlink($esiClientZipFile);
    }

    /**
     * Little helper to recirsively remove a directory
     *
     * @param string $dir
     */
    protected function rrmdir(string $dir) {
        if(\is_dir($dir)) {
            $objects = \scandir($dir);

            foreach($objects as $object) {
                if($object != "." && $object != "..") {
                    if(\is_dir($dir . "/" . $object)) {
                        $this->rrmdir($dir . "/" . $object);
                    } else {
                        \unlink($dir . "/" . $object);
                    }
                }
            }

            \rmdir($dir);
        }
    }

    /**
     * Check if the database needs to be updated
     */
    protected function checkDatabaseForUpdates() {
        $currentVersion = $this->getCurrentDatabaseVersion();

        if(\version_compare($currentVersion, $this->getNewDatabaseVersion()) < 0) {
            $this->updateDatabase($this->getNewDatabaseVersion());
        }

        /**
         * Update database version
         */
        \update_option($this->getDatabaseFieldName(), $this->getNewDatabaseVersion());
    }

    /**
     * Update the plugin database
     */
    public function updateDatabase() {
        $this->createEsiCacheTable();
    }

    private function createEsiCacheTable() {
        $charsetCollate = $this->wpdb->get_charset_collate();
        $tableName = $this->wpdb->base_prefix . 'eve_online_esi_cache';

        $sql = "CREATE TABLE $tableName (
            esi_route varchar(255),
            value longtext,
            valid_until varchar(255),
            PRIMARY KEY esi_route (esi_route)
        ) $charsetCollate;";

        require_once(\ABSPATH . 'wp-admin/includes/upgrade.php');

        \dbDelta($sql);
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
//            $imagePath = ImageHelper::getLocalCacheImageUriForRemoteImage('character', $this->imageserverUrl . $this->imageserverEndpoints['character'] . $imageName);
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
//            $imagePath = ImageHelper::getLocalCacheImageUriForRemoteImage($entityType, $this->imageserverUrl . $this->imageserverEndpoints[$entityType] . $imageName);
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
