<?php

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Alliances\AllianceId;

/**
 * Class Corporations
 *
 * Represents a collection of corporation IDs associated with an alliance.
 * Provides methods to retrieve and set the list of corporation IDs.
 *
 * @package Ppfeufer\Theme\EVEOnline\EsiClient\Model\Alliances\AllianceId
 */
class Corporations {
    /**
     * @var array $corporations
     * List of corporation IDs.
     */
    protected array $corporations = [];

    /**
     * Retrieve the list of corporation IDs.
     *
     * @return array The list of corporation IDs.
     */
    public function getCorporations(): array {
        return $this->corporations;
    }

    /**
     * Set the list of corporation IDs.
     *
     * @param array $corporations The list of corporation IDs to set.
     * @return void
     */
    protected function setCorporations(array $corporations): void {
        $this->corporations = $corporations;
    }
}
