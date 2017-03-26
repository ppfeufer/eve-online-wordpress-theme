<?php
/**
 * Class Name: Cron
 */
namespace WordPress\Themes\EveOnline\Addons;

use WordPress\Themes\EveOnline;

class Cron {
	private $themeOptions = null;

	public $cronEvents = array();

	/**
	 * Constructor
	 *
	 * @param bool $init Init the actions and stuff or not
	 */
	public function __construct($init = false) {
		$this->themeOptions = \get_option('eve_theme_options', EveOnline\Helper\ThemeHelper::getThemeDefaultOptions());
		$this->cronEvents = $this->getTemeCronEvents();

		if($init === true) {
			$this->init();
		} // END if($init === true)
	} // END public function __construct()

	/**
	 * Returning all known theme crons as an array
	 *
	 * @return array Themes Cron Events with their respective hooks
	 */
	public function getTemeCronEvents() {
		return array(
			'Cleanup Image Cache' => 'cleanupThemeImageCache'
		);
	} // END public function getTemeCronEvents()

	/**
	 * Initializing all the stuff
	 */
	public function init() {
		// Managing the crons action hooks
		foreach($this->cronEvents as $cronEvent) {
			/**
			 * Only add the cron if the theme settings say so or else remove them
			 */
			if(!empty($this->themeOptions['cron'][$cronEvent])) {
				\add_action($cronEvent, array($this, 'cron' . \ucfirst($cronEvent)));
			} else {
				$this->removeCron($cronEvent);
			} // END if(!empty($this->themeOptions['cron'][$cronEvent]))
		} // END foreach($this->cronEvents as $cronEvent)

		\add_action('switch_theme', array($this, 'removeAllCrons'), 10 , 2);

		$this->scheduleHourlyCron();
		$this->scheduleDailyCron();
		$this->scheduleTwicedailyCron();
	} // END public function init()

	/**
	 * Removing all known theme crons
	 */
	public function removeAllCrons() {
		foreach($this->cronEvents as $cronEvent) {
			// removing $cronEvent
			\wp_clear_scheduled_hook($cronEvent);
		} // END foreach($this->cronEvents as $cronEvent => $cronHook)
	} // END public function removeAllCrons()

	/**
	 * Remove a single cron job
	 *
	 * @param string $cron Hook of the cron to remove
	 */
	public function removeCron($cron = null) {
		\wp_clear_scheduled_hook($cron);
	} // END public function removeCron($cron = null)

	/**
	 * Schedule hourly cron jobs
	 */
	public function scheduleHourlyCron() {
		;
	} // END public function scheduleHourlyCron()

	/**
	 * Schedule daily cron jobs
	 */
	public function scheduleDailyCron() {
		if(!\wp_next_scheduled('cleanupThemeImageCache') && !empty($this->themeOptions['cron']['cronCleanupImageCache'])) {
			\wp_schedule_event(\time(), 'daily', 'cleanupThemeImageCache');
		} // END if(!\wp_next_scheduled('cleanupThemeImageCache'))
	} //END public function scheduleDailyCron()

	/**
	 * Schedule twice daily cron jobs
	 */
	public function scheduleTwicedailyCron() {
		;
	} // END public function scheduleTwicedailyCron()

	/**
	 * Cron Job: cronCleanupImageCache
	 * Schedule: Daily
	 */
	public function cronCleanupImageCache() {
		$imageCacheDirectory = EveOnline\Helper\CacheHelper::getImageCacheDir();

		EveOnline\Helper\FilesystemHelper::deleteDirectoryRecursive($imageCacheDirectory, false);
	} // END public function cronCleanupCacheDirectories()
} // END class Cron
