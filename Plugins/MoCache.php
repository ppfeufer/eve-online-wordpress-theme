<?php
/**
 * Caches the language-files.
 *
 * @package WordPress
 */

namespace WordPress\Themes\EveOnline\Plugins;

use \MO;

class MoCache {
    private $cacheGroup = 'eve-mo';
    private $moCacheArray = null;
    private $cacheExpire = 21600;

    private $hit = [];
    private $miss = [];

    public function __construct() {
        \add_filter('override_load_textdomain', [$this, 'load'], 10, 3);
    }

    /**
     * Load MO object from cache
     *
     * @author ppfeufer
     */
    public function load($override, $domain, $mofile) {
        if(isset($this->hit[$domain])) {
            return true;
        }

        if(!\preg_match('/\w+(?=\.mo$)/', $mofile, $matches)) {
            return;
        }

        $key = $domain . ':' . $matches[0];

        if(($cache = \get_transient($this->cacheGroup . '_' . $domain)) !== false) {
            if(\is_array($cache)) {
                global $l10n;

                $mo = new MO();
                $mo->entries = $cache['entries'];
                $mo->set_headers($cache['headers']);
                $l10n[$domain] = $mo;
            }

            $this->hit[$domain] = true;

            return true;
        } else {
            \add_action('shutdown', [$this, 'store']);

            $this->miss[$domain] = $key;

            return false;
        }
    }

    /**
     * Store MO object in cache
     *
     * @author ppfeufer
     */
    public function store() {
        global $l10n;

        $this->moCacheArray = \get_option('eve-online-theme-mo-cache');

        foreach($this->miss as $domain => $key) {
            $key = null; // We don't need it here

            if(isset($l10n[$domain])) {
                $mo = $l10n[$domain];
                $cache = [
                    'entries' => $mo->entries,
                    'headers' => $mo->headers
                ];
            } else {
                continue;
            }

            $this->moCacheArray[$domain] = $this->cacheGroup . '_' . $domain;

            \set_transient($this->cacheGroup . '_' . $domain, $cache, $this->cacheExpire);
        }

        \update_option('eve-online-theme-mo-cache', $this->moCacheArray);

        return;
    }
}
