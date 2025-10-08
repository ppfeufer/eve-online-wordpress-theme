<?php

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Alliances\AllianceId;

/**
 * Class Icons
 *
 * Represents the icons associated with an alliance, including different resolutions.
 * Provides methods to retrieve and set the URLs for the icons, ensuring secure HTTPS URLs.
 *
 * @package Ppfeufer\Theme\EVEOnline\EsiClient\Model\Alliances\AllianceId
 */
class Icons {
    /**
     * URL for the 128x128 pixel icon.
     *
     * @var string
     */
    protected string $px128x128;

    /**
     * URL for the 64x64 pixel icon.
     *
     * @var string
     */
    protected string $px64x64;

    /**
     * Get the URL for the 128x128 pixel icon.
     *
     * @return string The URL of the 128x128 pixel icon.
     */
    public function getPx128x128(): string {
        return $this->px128x128;
    }

    /**
     * Set the URL for the 128x128 pixel icon.
     * Ensures the URL uses HTTPS instead of HTTP.
     *
     * @param string $px128x128 The URL of the 128x128 pixel icon.
     */
    protected function setPx128x128(string $px128x128): void {
        $this->px128x128 = preg_replace('/http:\/\//', 'https://', $px128x128);
    }

    /**
     * Get the URL for the 64x64 pixel icon.
     *
     * @return string The URL of the 64x64 pixel icon.
     */
    public function getPx64x64(): string {
        return $this->px64x64;
    }

    /**
     * Set the URL for the 64x64 pixel icon.
     * Ensures the URL uses HTTPS instead of HTTP.
     *
     * @param string $px64x64 The URL of the 64x64 pixel icon.
     */
    protected function setPx64x64(string $px64x64): void {
        $this->px64x64 = preg_replace('/http:\/\//', 'https://', $px64x64);
    }

    /**
     * Get the URL for the 32x32 pixel icon.
     * This is a workaround until 32px logos are implemented by CCP.
     * Converts the 64x64 icon URL to a 32x32 icon URL by replacing "_64.png" with "_32.png".
     *
     * @return string The URL of the 32x32 pixel icon.
     */
    public function getPx32x32(): string {
        return preg_replace('/_64.png/', '_32.png', $this->px64x64);
    }
}
