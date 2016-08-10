<?php
/**
 * EVE Killboard Theme Plugin for fetching Killmails from EDK killboard software
 */
namespace WordPress\Themes\EveOnline\Plugins;

use WordPress\Themes\EveOnline;

\defined('ABSPATH') or die();

class Killboard {
	private $themeSettings = null;
	private $kbDB = null;

	/**
	 * constructor
	 */
	public function __construct() {
		$this->themeSettings = \get_option('eve_theme_options', EveOnline\eve_get_options_default());

		$this->kbDB = $this->initiateKillboardDatabase();
	} // END public function __construct()

	private function initiateKillboardDatabase() {
		$returnValue = false;

		if(!empty($this->themeSettings['killboard_db_user']) && !empty($this->themeSettings['killboard_db_password']) && !empty($this->themeSettings['killboard_db_name']) && !empty($this->themeSettings['killboard_db_host'])) {
			$returnValue = new \wpdb($this->themeSettings['killboard_db_user'], $this->themeSettings['killboard_db_password'], $this->themeSettings['killboard_db_name'], $this->themeSettings['killboard_db_host']);
		} // END if(!empty($this->themeSettings['killboard_db_user']) && !empty($this->themeSettings['killboard_db_password']) && !empty($this->themeSettings['killboard_db_name']) && !empty($this->themeSettings['killboard_db_host']))

		return $returnValue;
	} // END private function initiateKillboardDatabase()
} // END class Killboard