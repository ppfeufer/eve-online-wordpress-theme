<?php

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Characters\Affiliation;

/**
 * Class Characters
 *
 * Represents a character's affiliation details, including alliance, corporation, and faction IDs.
 * Provides getter and setter methods for each property.
 *
 * @package Ppfeufer\Theme\EVEOnline\EsiClient\Model\Characters\Affiliation
 */
class Characters {
    /**
     * @var int $allianceId
     * The character's alliance ID, if their corporation is in an alliance.
     */
    protected int $allianceId;

    /**
     * @var int $characterId
     * The character's unique ID.
     */
    protected int $characterId;

    /**
     * @var int $corporationId
     * The character's corporation ID.
     */
    protected int $corporationId;

    /**
     * @var int $factionId
     * The character's faction ID, if their corporation is in a faction.
     */
    protected int $factionId;

    /**
     * Get the character's alliance ID.
     *
     * @return int The alliance ID.
     */
    public function getAllianceId(): int {
        return $this->allianceId;
    }

    /**
     * Set the character's alliance ID.
     *
     * @param int $allianceId The alliance ID to set.
     * @return void
     */
    protected function setAllianceId(int $allianceId): void {
        $this->allianceId = $allianceId;
    }

    /**
     * Get the character's unique ID.
     *
     * @return int The character ID.
     */
    public function getCharacterId(): int {
        return $this->characterId;
    }

    /**
     * Set the character's unique ID.
     *
     * @param int $characterId The character ID to set.
     * @return void
     */
    protected function setCharacterId(int $characterId): void {
        $this->characterId = $characterId;
    }

    /**
     * Get the character's corporation ID.
     *
     * @return int The corporation ID.
     */
    public function getCorporationId(): int {
        return $this->corporationId;
    }

    /**
     * Set the character's corporation ID.
     *
     * @param int $corporationId The corporation ID to set.
     * @return void
     */
    protected function setCorporationId(int $corporationId): void {
        $this->corporationId = $corporationId;
    }

    /**
     * Get the character's faction ID.
     *
     * @return int The faction ID.
     */
    public function getFactionId(): int {
        return $this->factionId;
    }

    /**
     * Set the character's faction ID.
     *
     * @param int $factionId The faction ID to set.
     * @return void
     */
    protected function setFactionId(int $factionId): void {
        $this->factionId = $factionId;
    }
}
