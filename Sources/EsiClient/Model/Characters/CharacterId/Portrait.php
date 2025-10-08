<?php

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Characters\CharacterId;

/**
 * Class Portrait
 *
 * Represents the portrait URLs for a character in various resolutions.
 * Provides getter and setter methods for each resolution, ensuring secure HTTPS URLs.
 *
 * @package Ppfeufer\Theme\EVEOnline\EsiClient\Model\Characters\CharacterId
 */
class Portrait {
    /**
     * URL for the 512x512 pixel portrait.
     *
     * @var string
     */
    protected string $px512x512;

    /**
     * URL for the 256x256 pixel portrait.
     *
     * @var string
     */
    protected string $px256x256;

    /**
     * URL for the 128x128 pixel portrait.
     *
     * @var string
     */
    protected string $px128x128;

    /**
     * URL for the 64x64 pixel portrait.
     *
     * @var string
     */
    protected string $px64x64;

    /**
     * Get the URL for the 512x512 pixel portrait.
     *
     * @return string The URL of the 512x512 pixel portrait.
     */
    public function getPx512x512(): string {
        return $this->px512x512;
    }

    /**
     * Set the URL for the 512x512 pixel portrait.
     * Ensures the URL uses HTTPS instead of HTTP.
     *
     * @param string $px512x512 The URL of the 512x512 pixel portrait.
     */
    protected function setPx512x512(string $px512x512): void {
        $this->px512x512 = preg_replace('/http:\/\//', 'https://', $px512x512);
    }

    /**
     * Get the URL for the 256x256 pixel portrait.
     *
     * @return string The URL of the 256x256 pixel portrait.
     */
    public function getPx256x256(): string {
        return $this->px256x256;
    }

    /**
     * Set the URL for the 256x256 pixel portrait.
     * Ensures the URL uses HTTPS instead of HTTP.
     *
     * @param string $px256x256 The URL of the 256x256 pixel portrait.
     */
    protected function setPx256x256(string $px256x256): void {
        $this->px256x256 = preg_replace('/http:\/\//', 'https://', $px256x256);
    }

    /**
     * Get the URL for the 128x128 pixel portrait.
     *
     * @return string The URL of the 128x128 pixel portrait.
     */
    public function getPx128x128(): string {
        return $this->px128x128;
    }

    /**
     * Set the URL for the 128x128 pixel portrait.
     * Ensures the URL uses HTTPS instead of HTTP.
     *
     * @param string $px128x128 The URL of the 128x128 pixel portrait.
     */
    protected function setPx128x128(string $px128x128): void {
        $this->px128x128 = preg_replace('/http:\/\//', 'https://', $px128x128);
    }

    /**
     * Get the URL for the 64x64 pixel portrait.
     *
     * @return string The URL of the 64x64 pixel portrait.
     */
    public function getPx64x64(): string {
        return $this->px64x64;
    }

    /**
     * Set the URL for the 64x64 pixel portrait.
     * Ensures the URL uses HTTPS instead of HTTP.
     *
     * @param string $px64x64 The URL of the 64x64 pixel portrait.
     */
    protected function setPx64x64(string $px64x64): void {
        $this->px64x64 = preg_replace('/http:\/\//', 'https://', $px64x64);
    }

    /**
     * Get the URL for the 32x32 pixel portrait.
     * This is a workaround until 32px portraits are implemented by CCP.
     * Converts the 64x64 portrait URL to a 32x32 portrait URL by replacing "_64.jpg" with "_32.jpg".
     *
     * @return string The URL of the 32x32 pixel portrait.
     */
    public function getPx32x32(): string {
        return preg_replace('/_64.jpg/', '_32.jpg', $this->px64x64);
    }
}
