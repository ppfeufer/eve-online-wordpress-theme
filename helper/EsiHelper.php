<?php

/**
 * Talking to the ESI API ...
 */

namespace WordPress\Themes\EveOnline\Helper;

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
	 * Returning the instance
	 *
	 * @return \WordPress\Themes\EveOnline\Helper\EsiHelper
	 */
	public static function getInstance() {
		if(null === self::$instance) {
			self::$instance = new self;
		} // END if(null === self::$instance)

		return self::$instance;
	} // END public static function getInstance()

	/**
	 * clone
	 *
	 * no cloning allowed
	 */
	protected function __clone() {
		;
	} // END protected function __clone()

	/**
	 * constructor
	 *
	 * no external instanciation allowed
	 */
	protected function __construct() {
		$this->esiUrl = 'https://esi.tech.ccp.is/latest/';
		$this->imageserverUrl = 'https://image.eveonline.com/';

		/**
		 * Assigning ESI Endpoints
		 *
		 * @see https://esi.tech.ccp.is/latest/
		 */
		$this->esiEndpoints = [
			'alliance-information' => 'alliances/', // getting alliance information by ID - https://esi.tech.ccp.is/latest/alliances/99000102/
			'character-information' => 'characters/', // getting character information by ID - https://esi.tech.ccp.is/latest/characters/90607580/
			'corporation-information' => 'corporations/', // getting corporation information by ID - https://esi.tech.ccp.is/latest/corporations/98000030/
			'search' => 'search/', // Search for entities that match a given sub-string. - https://esi.tech.ccp.is/latest/search/?search=Yulai%20Federation&strict=true&categories=alliance
			'system-information' => 'universe/systems/', // getting system information by ID - https://esi.tech.ccp.is/latest/universe/systems/30000003/
			'type-information' => 'universe/types/', // getting types information by ID - https://esi.tech.ccp.is/latest/universe/types/670/
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
	} // END protected function __construct()

	public function getImageServerUrl() {
		return $this->imageserverUrl;
	} // END public function getImageServerUrl()

	public function getImageServerEndpoint($group) {
		return $this->getImageServerUrl() . $this->imageserverEndpoints[$group];
	} // END public function getImageServerEndpoint($group)

	public function getCharacterData($characterID) {
		$characterData = $this->getEsiData($this->esiEndpoints['character-information'] . $characterID . '/');

		return [
			'data' => $characterData
		];
	} // END public function getCharacterData($characterID)

	public function getCorporationData($corporationID) {
		$corporationData = $this->getEsiData($this->esiEndpoints['corporation-information'] . $corporationID . '/');

		return [
			'data' => $corporationData
		];
	} // END public function getCorporationData($corporationID)

	public function getAllianceData($allianceID) {
		$allianceData = $this->getEsiData($this->esiEndpoints['alliance-information'] . $allianceID . '/', 3600);

		return [
			'data' => $allianceData
		];
	} // END public function getAllianceData($allianceID)

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
			 */
			foreach($data->{$type} as $entityID) {
				switch($type) {
					case 'character':
						$characterSheet = $this->getCharacterData($entityID);

						if(\strtolower($characterSheet['data']->name) === \strtolower($name)) {
							$returnData = $entityID;
							break;
						} // END if($characterSheet['data']->name === $name)
						break;

					case 'corporation':
						$corporationSheet = $this->getCorporationData($entityID);

						if(\strtolower($corporationSheet['data']->corporation_name) === \strtolower($name)) {
							$returnData = $entityID;
							break;
						} // END if($corporationSheet['data']->name === $name)
						break;

					case 'alliance':
						$allianceSheet = $this->getAllianceData($entityID);

						if(\strtolower($allianceSheet['data']->alliance_name) === \strtolower($name)) {
							$returnData = $entityID;
							break;
						} // END if($allianceSheet['data']->name === $name)
						break;
				} // END switch($type)
			} // END foreach($data->{$type} as $entityID)
		} // END if(!isset($data->error) && !empty($data))

		return $returnData;
	} // END public function getEveIdFromName($name, $type)

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
			$imagePath = ImageHelper::getLocalCacheImageUriForRemoteImage('character', $this->imageserverUrl . $this->imageserverEndpoints['character'] . $imageName);

			if($imageOnly === true) {
				return $imagePath;
			} // END if($imageOnly === true)

			if(!\is_null($newWidth)) {
				$newWidth = ' width="' . $newWidth . '"';
			} // END if(!\is_null($newWidth))

			if(!\is_null($newHeight)) {
				$newHeight = ' height="' . $newHeight . '"';
			} // END if(!\is_null($newHeight))

			$returnData = '<img src="' . $imagePath . '" class="eve-character-image eve-character-id-' . $characterID . '" alt="' . $characterName . '">';
		} // END if(!\is_null($characterID))

		return $returnData;
	} // END public function getCharacterImageByName($characterName, $imageOnly = true, $size = 128, $newWidth = null, $newHeight = null)

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
			$imagePath = ImageHelper::getLocalCacheImageUriForRemoteImage($entityType, $this->imageserverUrl . $this->imageserverEndpoints[$entityType] . $imageName);

			if($imageOnly === true) {
				return $imagePath;
			} // END if($imageOnly === true)

			$returnData = '<img src="' . $imagePath . '" class="eve-' . $entityType . '-logo">';
		} // END if(!\is_null($eveID))

		return $returnData;
	} // END public function getEntityLogoByName($entityName, $entityType, $imageOnly = true, $size = 128)

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
		} // END if($data === false)

		if(!empty($data) && !isset($data->error)) {
			$returnValue = \json_decode($data);
		} // END if(!empty($data))

		return $returnValue;
	} // END private function getEsiData($route)
} // END class EsiHelper
