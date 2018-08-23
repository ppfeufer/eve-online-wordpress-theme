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
        return \trailingslashit(\WP_CONTENT_DIR) . 'cache/eve-online/';
    }

    /**
     * Getting the URI for the cache directory
     *
     * @return string URI for the cache directory
     */
    public static function getThemeCacheUri() {
        return \trailingslashit(\WP_CONTENT_URL) . 'cache/eve-online/';
    }

    /**
     * Getting the URI for the cache directory
     *
     * @return string URI for the cache directory
     */
    public static function getImageCacheDir() {
        return \trailingslashit(self::getThemeCacheDir() . 'images');
    }

    /**
     * Getting the local image cache URI
     *
     * @return string Local image cache URI
     */
    public static function getImageCacheUri() {
        return \trailingslashit(self::getThemeCacheUri() . 'images');
    }

    /**
     * creating our needed cache directories under:
     *		/wp-content/cache/themes/«theme-name»/
     */
    public static function createCacheDirectory($directory = '') {
        $wpFileSystem =  new \WP_Filesystem_Direct(null);
        $dirToCreate = \trailingslashit(self::getThemeCacheDir() . $directory);

        \wp_mkdir_p($dirToCreate);

        if(!$wpFileSystem->is_file($dirToCreate . '/index.php')) {
            $wpFileSystem->put_contents(
                $dirToCreate . '/index.php',
                '',
                0644
            );
        }
    }

    /**
     * Check if a remote image has been cached locally
     *
     * @param string $cacheType The subdirectory in the image cache filesystem
     * @param string $imageName The image file name
     * @param int $cachetime
     * @return boolean true or false
     */
    public static function checkCachedImage($cacheType, $imageName = null, $cachetime = 86400) {
        $cacheDir = \trailingslashit(self::getImageCacheDir() . $cacheType);

        if(\file_exists($cacheDir . $imageName)) {
            /**
             * Check if the file is older than 24 hrs
             * If so, time to renew it
             *
             * This is just in case our cronjob doesn't run for whetever reason
             */
            if(\time() - \filemtime($cacheDir . $imageName) > $cachetime) {
                \unlink($cacheDir . $imageName);

                $returnValue = false;
            } else {
                $returnValue = true;
            }
        } else {
            $returnValue = false;
        }

        return $returnValue;
    }

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

            return $wpFileSystem->put_contents($cacheDir . $imageFilename, $imageToFetch, 0755);
        }
    }

    /**
     * Getting transient cache information / data
     *
     * @param string $transientName
     * @return mixed
     */
    public static function getTransientCache($transientName) {
        $data = \get_transient($transientName);

        return $data;
    }

    /**
     * Setting the transient cahe
     *
     * @param string $transientName cache name
     * @param mixed $data the data that is needed to be cached
     * @param type $time cache time in hours (default: 2)
     */
    public static function setTransientCache($transientName, $data, $time = 2) {
        \set_transient($transientName, $data, $time * \HOUR_IN_SECONDS);
    }
}
