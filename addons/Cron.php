<?php
/**
 * Class Name: Cron
 */
namespace WordPress\Themes\EveOnline\Addons;

use WordPress\Themes\EveOnline;

class Cron {
	public function __construct($init = false) {
		\add_action('cleanupThemeImageCache', array($this, 'cronCleanupImageCacheDirectories'));

		\add_action('switch_theme', array($this, 'removeAllCrons'), 10 , 2);

		if($init === true) {
			$this->init();
		} // END if($init === true)
	} // END public function __construct()

	public function init() {
		$this->scheduleHourlyCron();
		$this->scheduleDailyCron();
		$this->scheduleTwicedailyCron();
	} // END public function init()

	public function removeAllCrons() {
//		wp_die('unscheduling cron');s
		\wp_unschedule_event(\time(), 'cleanupThemeImageCache');
	} // END public function removeAllCrons()

	public function scheduleHourlyCron() {
		;
	} // END public function scheduleHourlyCron()

	public function scheduleDailyCron() {
		if(!\wp_next_scheduled('cleanupThemeImageCache')) {
			\wp_schedule_event(\time(), 'daily', 'cleanupThemeImageCache');
		} // END if(!\wp_next_scheduled('cleanupThemeImageCache'))
	} //END public function scheduleDailyCron()

	public function scheduleTwicedailyCron() {
		;
	} // END public function scheduleTwicedailyCron()

	public function cronCleanupImageCacheDirectories() {
		$imageCacheDirectory = EveOnline\Helper\CacheHelper::getImageCacheDir();

		EveOnline\Helper\FilesystemHelper::deleteDirectoryRecursive($imageCacheDirectory, false);
	} // END public function cronCleanupCacheDirectories()
} // END class Cron
