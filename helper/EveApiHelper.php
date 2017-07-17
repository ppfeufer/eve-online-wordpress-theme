<?php
/**
 * Getting some basic information from the EVE API
 */

namespace WordPress\Themes\EveOnline\Helper;

use WordPress\Themes\EveOnline;

\defined('ABSPATH') or die();

class EveApiHelper {
	private $sUserAgent = 'Mozilla/5.0 (X11; Linux x86_64; rv:5.0) Gecko/20100101 Firefox/5.0 EVE Online WordPress Theme Website API Fetcher https://github.com/ppfeufer/eve-online-wordpress-theme';
	private $apiUrl = 'https://api.eveonline.com/'; // since we are using WordPress' transient cache, there is no need to bother our own proxy for caching.
	private $apiEndpoints = null;
	private $imageserverUrl = 'https://image.eveonline.com/';
	private $imageserverEndpoints = null;
	private $entityGroups = null;
	private $themeOptions = null;

	/**
	 * The Construtor
	 */
	public function __construct($apiType = 'xml') {
		/**
		 * CCP has multiple API types, so we have to differ here
		 *
		 * Valid API type:
		 *		xml
		 *		esi
		 *
		 * Per default we still using the XML API, but that might change at some point
		 */
		switch($apiType) {
			case 'xml':
				/**
				 * Assigning API Endpoints
				 */
				$this->apiEndpoints = array(
					'api.callList' => 'api/CallList.xml.aspx',

					// Account API Endpoints
					'account.status' => 'account/AccountStatus.xml.aspx', // needed access mask: 33554432
					'account.info' => 'account/APIKeyInfo.xml.aspx',
					'account.charcaterList' => 'account/Characters.xml.aspx',

					// Character API Endpoints
					'character.accountBalance' => 'char/AccountBalance.xml.aspx',
					'character.assetList' => 'char/AssetList.xml.aspx',
					'character.calendarEventAttendees' => 'char/CalendarEventAttendees.xml.aspx',
					'character.charcterSheet' => 'char/CharacterSheet.xml.aspx',
					'character.contactList' => 'char/ContactList.xml.aspx',
					'character.contactNotifications' => 'char/ContactNotifications.xml.aspx',
					'character.contracts' => 'char/Contracts.xml.aspx',
					'character.contractItems' => 'char/ContractItems.xml.aspx',
					'character.contractBids' => 'char/ContractBids.xml.aspx',
					'character.factionWarfareStats' => 'char/FacWarStats.xml.aspx',
					'character.industryJobs' => 'char/IndustryJobs.xml.aspx',
					'character.killLog' => 'char/Killlog.xml.aspx',
					'character.locations' => 'char/Locations.xml.aspx',
					'character.mailBodies' => 'char/MailBodies.xml.aspx',
					'character.MailingLists' => 'char/MailingLists.xml.aspx',
					'character.mailHeaders' => 'char/MailMessages.xml.aspx',
					'character.marketOrders' => 'char/MarketOrders.xml.aspx',
					'character.medals' => 'char/Medals.xml.aspx',
					'character.notifications' => 'char/Notifications.xml.aspx',
					'character.notificationTexts' => 'char/NotificationTexts.xml.aspx',
					'character.research' => 'char/Research.xml.aspx',
					'character.skillInTraining' => 'char/SkillInTraining.xml.aspx',
					'character.skillQueue' => 'char/SkillQueue.xml.aspx',
					'character.Standings' => 'char/Standings.xml.aspx',
					'character.upcomingCalendarEvents' => 'char/UpcomingCalendarEvents.xml.aspx',
					'character.walletJournal' => 'char/WalletJournal.xml.aspx',
					'character.walletTransactions' => 'char/WalletTransactions.xml.aspx',

					// Corporation API Endpoints
					'corporation.accountBalance' => 'corp/AccountBalance.xml.aspx',
					'corporation.assetList' => 'corp/AssetList.xml.aspx',
					'corporation.contactList' => 'corp/ContactList.xml.aspx',
					'corporation.contacinerLog' => 'corp/ContainerLog.xml.aspx',
					'corporation.contracts' => 'corp/Contracts.xml.aspx',
					'corporation.contractItems' => 'corp/ContractItems.xml.aspx',
					'corporation.contractBids' => 'corp/ContractBids.xml.aspx ',
					'corporation.corporationSheet' => 'corp/CorporationSheet.xml.aspx',
					'corporation.factionWarStats' => 'corp/FacWarStats.xml.aspx',
					'corporation.indutryJobs' => 'corp/IndustryJobs.xml.aspx',
					'corporation.killLog' => 'corp/Killlog.xml.aspx',
					'corporation.locations' => 'corp/Locations.xml.aspx',
					'corporation.marketOrders' => 'corp/MarketOrders.xml.aspx',
					'corporation.medals' => 'corp/Medals.xml.aspx',
					'corporation.memberMedals' => 'corp/MemberMedals.xml.aspx',
					'corporation.memberSecurity' => 'corp/MemberSecurity.xml.aspx',
					'corporation.memberSecurityLog' => 'corp/MemberSecurityLog.xml.aspx',
					'corporation.memberTracking' => 'corp/MemberTracking.xml.aspx',
					'corporation.outpostList' => 'corp/OutpostList.xml.aspx',
					'corporation.outpostServiceDetail' => 'corp/OutpostServiceDetail.xml.aspx',
					'corporation.shareholders' => 'corp/Shareholders.xml.aspx',
					'corporation.standings' => 'corp/Standings.xml.aspx',
					'corporation.starbaseList' => 'corp/StarbaseList.xml.aspx',
					'corporation.starbaseDetail' => 'corp/StarbaseDetail.xml.aspx',
					'corporation.walletJournal' => 'corp/WalletJournal.xml.aspx',
					'corporation.walletTransactions' => 'corp/WalletTransactions.xml.aspx',

					// EVE API Endpoints
					'eve.allianceList' => 'eve/AllianceList.xml.aspx',
					'eve.certificateTree' => 'eve/CertificateTree.xml.aspx',
					'eve.characterAffiliation' => 'eve/CharacterAffiliation.xml.aspx',
					'eve.characterID' => 'eve/CharacterID.xml.aspx',
					'eve.characterInfo' => 'eve/CharacterInfo.xml.aspx',
					'eve.characterName' => 'eve/CharacterName.xml.aspx',
					'eve.conquerableStationList' => 'eve/ConquerableStationList.xml.aspx',
					'eve.errorList' => 'eve/ErrorList.xml.aspx',
					'eve.factionWarfareStats' => 'eve/FacWarStats.xml.aspx',
					'eve.factionWarfareTopHunderd' => 'eve/FacWarTopStats.xml.aspx',
					'eve.owner' => 'eve/OwnerID.xml.aspx',
					'eve.refTypes' => 'eve/RefTypes.xml.aspx', // Returns a list of transaction types used in the Corporation - WalletJournal & Character - WalletJournal. ( http://eveonline-third-party-documentation.readthedocs.io/en/latest/xmlapi/eve/eve_reftypes.html )
					'eve.skillTree' => 'eve/SkillTree.xml.aspx',
					'eve.typeName' => 'eve/TypeName.xml.aspx', // Returns the names associated with a sequence of typeIDs. ( http://eveonline-third-party-documentation.readthedocs.io/en/latest/xmlapi/eve/eve_typename.html )

					// Server API Endpoints
					'server.status' => 'server/ServerStatus.xml.aspx'
				);
				break;
		} // END switch($apiType)

		/**
		 * Assigning Imagesever Endpoints
		 */
		$this->imageserverEndpoints = array(
			'alliance' => 'Alliance/',
			'corporation' => 'Corporation/',
			'character' => 'Character/',
			'item' => 'Type/',
			'inventory' => 'InventoryType/' // Ships and all the other stuff
		);

		$this->entityGroups = array(
			'1' => 'character',
			'2' => 'corporation',
			'32' => 'alliance'
		);

		$this->themeOptions = \get_option('eve_theme_options', EveOnline\Helper\ThemeHelper::getThemeDefaultOptions());
	} // END public function __construct()

	public function getImageServerUrl() {
		return $this->imageserverUrl;
	} // END public function getImageServerUrl()

	public function getImageServerEndpoint($group) {
		return $this->getImageServerUrl() . $this->imageserverEndpoints[$group];
	} // END public function getImageServerEndpoint($group)

	public function getEveApiUrl() {
		return $this->apiUrl;
	} // END public function getEveApiUrl()

	public function getEveApiEndpoint($section) {
		return $this->getEveApiUrl() . $this->apiEndpoints[$section];
	} // END public function getEveApiEndpoint($section)

	public function getEntityLogoByName($name, $imageOnly = true, $size = 128) {
		$entitieID = $this->getEveIdFromName($name);

		if($entitieID == 0) {
			return false;
		} // END if($entitieID == 0)

		$imageName = $entitieID . '_' . $size . '.png';
		$ownerGroupID = $this->getEveGroupTypeFromName($name);

		$imagePath = ImageHelper::getLocalCacheImageUriForRemoteImage($this->entityGroups[$ownerGroupID], $this->imageserverUrl . $this->imageserverEndpoints[$this->entityGroups[$ownerGroupID]] . $imageName);

		if($imageOnly === true) {
			return $imagePath;
		} // END if($imageOnly === true)

		$html = '<img src="' . $imagePath . '" class="eve-alliance-logo">';

		return $html;
	} // END public function getEntityLogoByName($name, $imageOnly = true, $size = 128)

	public function getCharacterImageByName($name, $imageOnly = true, $size = 128, $newWidth = null, $newHeight = null) {
		$entitieID = $this->getEveIdFromName($name);

		if($entitieID == 0 || $entitieID === false) {
			return false;
		} // END if($entitieID == 0)

		$imageName = $entitieID . '_' . $size. '.jpg';
		$imagePath = ImageHelper::getLocalCacheImageUriForRemoteImage('character', $this->imageserverUrl . $this->imageserverEndpoints['character'] . $imageName);

		if($imageOnly === true) {
			return $imagePath;
		} // END if($imageOnly === true)

		if($newWidth !== null) {
			$newWidth = ' width="' . $newWidth . '"';
		}

		if($newHeight !== null) {
			$newHeight = ' height="' . $newHeight . '"';
		}

		$html = '<img src="' . $imagePath . '" class="eve-character-image eve-character-id-' . $entitieID . '" alt="' . $name . '">';

		return $html;
	} // END public function getCharacterImageByName($name, $imageOnly = true, $size = 128)

	public function getCharacterImageById($id, $imageOnly = true, $size = 128) {
		$imagePath = ImageHelper::getLocalCacheImageUriForRemoteImage($this->entityGroups['3'], $this->imageserverUrl . $this->imageserverEndpoints['character'] . $id . '_' . $size. '.jpg');

		if($imageOnly === true) {
			return $imagePath;
		} // END if($imageOnly === true)

		$html = '<img src="' . $imagePath . '" class="eve-character-image eve-character-id-' . $id . '">';

		return $html;
	} // END public function getCharacterImageByName($name, $imageOnly = true, $size = 128)

	/**
	 * get the EVE ID by it's name
	 * @param type $name
	 * @return type
	 */
	public function getEveIdFromName($name) {
		$transientName = \sanitize_title('get_eve.owner_data_' . $name);
		$data = $this->checkApiCache($transientName);

		if($data === false) {
			$endpoint = 'eve.owner';
			$data = $this->curl($this->apiUrl . $this->apiEndpoints[$endpoint], array('names' => $name));

			if($data === false) {
				return false;
			}

			/**
			 * setting the transient caches
			 */
			$this->setApiCache($transientName, $data);
		} // END if($data === false)

		if($this->isXml($data)) {
			$xml = new \SimpleXMLElement($data);

			if(!empty($xml->result->rowset)) {
				foreach($xml->result->rowset->row as $row) {
					if(\strtolower((string) $row->attributes()->ownerName) === \strtolower($name)) {
						$ownerID = (string) $row->attributes()->ownerID;
					} // END if((string) $row->attributes()->name == $corpName)s
				} // END foreach($xml->result->rowset->row as $row)

				return $ownerID;
			} // END if(!empty($xml->result->rowset))
		} // END if($this->isXml($data))
	} // END public function getCorpIdFromName($name)

	public function getEveGroupTypeFromName($name) {
		$transientName = sanitize_title('get_eve.owner_data_' . $name);
		$data = $this->checkApiCache($transientName);

		if($data === false) {
			$endpoint = 'eve.owner';
			$data = $this->curl($this->apiUrl . $this->apiEndpoints[$endpoint], array('names' => $name));

			/**
			 * setting the transient caches
			 */
			$this->setApiCache($transientName, $data);
		} // END if($data === false)

		if($this->isXml($data)) {
			$xml = new \SimpleXMLElement($data);

			if(!empty($xml->result->rowset)) {
				foreach($xml->result->rowset->row as $row) {
					if(\strtolower((string) $row->attributes()->ownerName) === \strtolower($name)) {
						$ownerGroupID = (string) $row->attributes()->ownerGroupID;
					} // END if((string) $row->attributes()->name == $corpName)s
				} // END foreach($xml->result->rowset->row as $row)

				return $ownerGroupID;
			} // END if(!empty($xml->result->rowset))
		} // END if($this->isXml($data))
	} // END public function getCorpIdFromName($name)

	/**
	 * Getting transient cache information / data
	 *
	 * @param string $transientName
	 * @return mixed
	 */
	private function checkApiCache($transientName) {
		$data = \get_transient($transientName);

		return $data;
	} // END private function checkApiCache($transientName)

	/**
	 * Setting the transient cahe
	 *
	 * @param string $transientName
	 * @param mixed $data
	 */
	private function setApiCache($transientName, $data) {
		\set_transient($transientName, $data, 1 * \HOUR_IN_SECONDS);
	} // END private function setApiCache($transientName, $data)

	/**
	 * Getting stuff from remote systems
	 *
	 * @param type $url
	 * @param type $post
	 * @return type
	 */
	private function curl($url, $post = '') {
		$cUrlChannel = \curl_init();
		$data = false;

		if(!empty($post)) {
			\curl_setopt($cUrlChannel, \CURLOPT_POST, 1);
			\curl_setopt($cUrlChannel, \CURLOPT_POSTFIELDS, \http_build_query($post, \PHP_QUERY_RFC3986));
		} // END if(!empty($post))

		\curl_setopt($cUrlChannel, \CURLOPT_URL, $url);

		if(\ini_get('open_basedir') === '' && \ini_get('safe_mode' === 'Off')) {
			\curl_setopt ($cUrlChannel, \CURLOPT_FOLLOWLOCATION, 1);
		} // END if(ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off'))

		\curl_setopt($cUrlChannel, \CURLOPT_SSL_VERIFYPEER, false);
		\curl_setopt($cUrlChannel, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($cUrlChannel, \CURLOPT_USERAGENT, $this->sUserAgent);

		if($this->ping($url)) {
			$data = \curl_exec($cUrlChannel);

			\curl_close($cUrlChannel);
		} // END if($this->ping($url))

		return $data;
	} // END private function curl($url, $post = '')

	/**
	 * Ping a remote server address
	 *
	 * @param string $host
	 * @param number $port
	 * @param number $timeout
	 * @return boolean
	 */
	private function ping($host, $port = 80, $timeout = 3) {
		$returnValue = false;

		if(\preg_match('/http|https/', $host)) {
			$host = \parse_url($host, \PHP_URL_HOST);
		} // END if(preg_match('/http|https/', $host))

		$errno = null;
		$errstr = null;
		$fsock = \fsockopen($host, $port, $errno, $errstr, $timeout);

		if(!$fsock) {
			$returnValue = false;
		} else {
			$returnValue = true;
		} // END if(!$fsock)

		return $returnValue;
	} // END private function ping($host, $port = 80, $timeout = 3)

	/**
	 * Check if a string is a valid XML
	 *
	 * @param string $string
	 * @return boolean
	 */
	private function isXml($string) {
		if(\substr($string, 0, 5) == "<?xml") {
			return true;
		} else {
			return false;
		} // END if(substr($sovereigntyXml, 0, 5) == "<?xml")
	} // END private function isXml($string)
} // END class EveApi
