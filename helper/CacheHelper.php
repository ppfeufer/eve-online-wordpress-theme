<?php

namespace WordPress\Themes\EveOnline\Helper;

use WordPress\Themes\EveOnline;

class CacheHelper {
	/**
	 * Getting the absolute path for the cache directory
	 *
	 * @return string absolute path for the cache directory
	 */
	public static function getThemeCacheDir() {
		return \trailingslashit(\WP_CONTENT_DIR) . 'cache/themes/' . \sanitize_title(ThemeHelper::getThemeName());
	} // END public static function getThemeCacheDir()

	/**
	 * Getting the URI for the cache directory
	 *
	 * @return string URI for the cache directory
	 */
	public static function getThemeCacheUri() {
		return \trailingslashit(\WP_CONTENT_URL) . 'cache/themes/' . \sanitize_title(ThemeHelper::getThemeName());
	} // END public static function getThemeCacheUri()

	/**
	 * Getting the URI for the cache directory
	 *
	 * @return string URI for the cache directory
	 */
	public static function getImageCacheDir() {
		return \trailingslashit(self::getThemeCacheDir() . '/images');
	} // END public static function getImageCacheDir()

	/**
	 * Getting the local image cache URI
	 *
	 * @return string Local image cache URI
	 */
	public static function getImageCacheUri() {
		return \trailingslashit(self::getThemeCacheUri() . '/images');
	} // END public static function getImageCacheUri()

	/**
	 * creating our needed cache directories under:
	 *		/wp-content/cache/themes/«theme-name»/
	 */
	public static function createCacheDirectory($directory = '') {
		$wpFileSystem =  new \WP_Filesystem_Direct(null);

		if($wpFileSystem->is_writable($wpFileSystem->wp_content_dir())) {
			if(!$wpFileSystem->is_dir(\trailingslashit(self::getThemeCacheDir()) . $directory)) {
				$wpFileSystem->mkdir(\trailingslashit(self::getThemeCacheDir()) . $directory, 0755);
			} // END if(!$wpFileSystem->is_dir(\trailingslashit(self::getThemeCacheDir()) . $directory))
		} // END if($wpFileSystem->is_writable($wpFileSystem->wp_content_dir()))
	} // END public static function createCacheDirectories()

	/**
	 * Check if a remote image has been cached locally
	 *
	 * @param string $cacheType The subdirectory in the image cache filesystem
	 * @param string $imageName The image file name
	 * @return boolean true or false
	 */
	public static function checkCachedImage($cacheType = null, $imageName = null) {
		$cacheDir = \trailingslashit(self::getImageCacheDir() . $cacheType);

		if(\file_exists($cacheDir . $imageName)) {
			/**
			 * Check if the file is older than 24 hrs
			 * If so, time to renew it
			 *
			 * This is just in case our cronjob doesn't run for whetever reason
			 */
			if(\time() - \filemtime($cacheDir . $imageName) > 24 * 3600) {
				\unlink($cacheDir . $imageName);

				$returnValue = false;
			} else {
				$returnValue = true;
			} // END if(\time() - \filemtime($cacheDir . $imageName) > 2 * 3600)
		} else {
			$returnValue = false;
		} // END if(\file_exists($cacheDir . $imageName))

		return $returnValue;
	} // END public static function checkCachedImage($cacheType = null, $imageName = null)

	/**
	 * Cachng a remote image locally
	 *
	 * @param string $cacheType The subdirectory in the image cache filesystem
	 * @param string $remoteImageUrl The URL for the remote image
	 */
	public static function cacheRemoteImageFile($cacheType = null, $remoteImageUrl = null) {
		$cacheDir = \trailingslashit(self::getImageCacheDir() . $cacheType);
		$explodedImageUrl = \explode('/', $remoteImageUrl);
		$imageFilename = \end($explodedImageUrl);
		$explodedImageFilename = \explode('.', $imageFilename);
		$extension = \end($explodedImageFilename);

		// make sure its an image
		if($extension === 'gif' || $extension === 'jpg' || $extension === 'jpeg' || $extension === 'png') {
			// get the remote image
			$get = \wp_remote_get($remoteImageUrl);
			$imageToFetch = \wp_remote_retrieve_body($get);

			$wpFileSystem = new \WP_Filesystem_Direct(null);
			$wpFileSystem->put_contents($cacheDir . $imageFilename, $imageToFetch, 0755);
		} // END if($extension === 'gif' || $extension === 'jpg' || $extension === 'jpeg' || $extension === 'png')
	} // END public static function cacheRemoteImageFile($cacheType = null, $remoteImageUrl = null)
} // END class CacheHelper
