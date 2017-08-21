<?php

namespace WordPress\Themes\EveOnline\Helper;

\defined('ABSPATH') or die();

class RemoteHelper {
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
	 * @return \WordPress\Themes\EveOnline\Helper\RemoteHelper
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
	}

	/**
	 * constructor
	 *
	 * no external instanciation allowed
	 */
	protected function __construct() {
		;
	}

	/**
	 * Getting data from a remote source
	 *
	 * @param string $url
	 * @param array $parameter
	 * @return mixed
	 */
	public function getRemoteData($url, array $parameter = []) {
		$params = '';

		if(\count($parameter) > 0) {
			$params = '?' . \http_build_query($parameter);
		} // END if(\count($parameter > 0))

		$remoteUrl = $url . $params;

		$get = \wp_remote_get($remoteUrl);
		$data = \wp_remote_retrieve_body($get);

		return $data;
	} // END private function getRemoteData($url, array $parameter)
} // END class RemoteHelper extends \WordPress\Plugin\EveOnlineTranquilityStatus\Singletons\AbstractSingleton
