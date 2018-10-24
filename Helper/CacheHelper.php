<?php

namespace WordPress\Themes\EveOnline\Helper;

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
     * @param int $time cache time in hours (default: 2)
     */
    public static function setTransientCache($transientName, $data, $time = 2) {
        \set_transient($transientName, $data, $time * \HOUR_IN_SECONDS);
    }
}
