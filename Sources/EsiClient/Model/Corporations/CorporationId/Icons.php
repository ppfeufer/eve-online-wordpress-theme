<?php

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Corporation\Corporations\CorporationId;

/**
 * Class Icons
 *
 * Represents the URLs for corporation icons in various resolutions.
 * Provides getter and setter methods for each resolution, ensuring secure HTTPS URLs.
 *
 * @package Ppfeufer\Theme\EVEOnline\EsiClient\Model\Corporation\Corporations\CorporationId
 */
class Icons {
    /**
     * URL for the 256x256 pixel icon.
     *
     * @var string
     */
    protected string $px256x256;

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
     * Get the URL for the 256x256 pixel icon.
     *
     * @return string The URL of the 256x256 pixel icon.
     */
    public function getPx256x256(): string {
        return $this->px256x256;
    }

    /**
     * Set the URL for the 256x256 pixel icon.
     * Ensures the URL uses HTTPS instead of HTTP.
     *
     * @param string $px256x256 The URL of the 256x256 pixel icon.
     */
    protected function setPx256x256(string $px256x256): void {
        $this->px256x256 = preg_replace('/http:\/\//', 'https://', $px256x256);
    }

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
     * This is a workaround until 32px icons are implemented by CCP.
     * Converts the 64x64 icon URL to a 32x32 icon URL by replacing "_64.png" with "_32.png".
     *
     * @return string The URL of the 32x32 pixel icon.
     */
    public function getPx32x32(): string {
        return preg_replace('/_64.png/', '_32.png', $this->px64x64);
    }
}
