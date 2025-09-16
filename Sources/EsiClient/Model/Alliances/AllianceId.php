<?php

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Alliances;

use DateTime;

/**
 * Class AllianceId
 *
 * Represents an alliance with various attributes such as creator, founding date, executor corporation, and faction.
 * Provides getter and setter methods for each attribute.
 *
 * @package Ppfeufer\Theme\EVEOnline\EsiClient\Model\Alliances
 */
class AllianceId {
    /**
     * @var int $creatorCorporationId
     * ID of the corporation that created the alliance.
     */
    protected int $creatorCorporationId;

    /**
     * @var int $creatorId
     * ID of the character that created the alliance.
     */
    protected int $creatorId;

    /**
     * @var DateTime $dateFounded
     * The date when the alliance was founded.
     */
    protected DateTime $dateFounded;

    /**
     * @var int $executorCorporationId
     * The executor corporation ID, if this alliance is not closed.
     */
    protected int $executorCorporationId;

    /**
     * @var int $factionId
     * Faction ID this alliance is fighting for, if enlisted in factional warfare.
     */
    protected int $factionId;

    /**
     * @var string $name
     * The full name of the alliance.
     */
    protected string $name;

    /**
     * @var string $ticker
     * The short name (ticker) of the alliance.
     */
    protected string $ticker;

    /**
     * Get the ID of the corporation that created the alliance.
     *
     * @return int The creator corporation ID.
     */
    public function getCreatorCorporationId(): int {
        return $this->creatorCorporationId;
    }

    /**
     * Set the ID of the corporation that created the alliance.
     *
     * @param int $creatorCorpId The creator corporation ID.
     */
    protected function setCreatorCorporationId(int $creatorCorpId): void {
        $this->creatorCorporationId = $creatorCorpId;
    }

    /**
     * Get the ID of the character that created the alliance.
     *
     * @return int The creator ID.
     */
    public function getCreatorId(): int {
        return $this->creatorId;
    }

    /**
     * Set the ID of the character that created the alliance.
     *
     * @param int $creatorId The creator ID.
     */
    protected function setCreatorId(int $creatorId): void {
        $this->creatorId = $creatorId;
    }

    /**
     * Get the date when the alliance was founded.
     *
     * @return DateTime The founding date.
     */
    public function getDateFounded(): DateTime {
        return $this->dateFounded;
    }

    /**
     * Set the date when the alliance was founded.
     *
     * @param DateTime $dateFounded The founding date.
     */
    protected function setDateFounded(DateTime $dateFounded): void {
        $this->dateFounded = $dateFounded;
    }

    /**
     * Get the executor corporation ID.
     *
     * @return int The executor corporation ID.
     */
    public function getExecutorCorporationId(): int {
        return $this->executorCorporationId;
    }

    /**
     * Set the executor corporation ID.
     *
     * @param int $executorCorpId The executor corporation ID.
     */
    protected function setExecutorCorporationId(int $executorCorpId): void {
        $this->executorCorporationId = $executorCorpId;
    }

    /**
     * Get the faction ID this alliance is fighting for.
     *
     * @return int The faction ID.
     */
    public function getFactionId(): int {
        return $this->factionId;
    }

    /**
     * Set the faction ID this alliance is fighting for.
     *
     * @param int $factionId The faction ID.
     */
    protected function setFactionId(int $factionId): void {
        $this->factionId = $factionId;
    }

    /**
     * Get the full name of the alliance.
     *
     * @return string The full name of the alliance.
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Set the full name of the alliance.
     *
     * @param string $name The full name of the alliance.
     */
    protected function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * Get the short name (ticker) of the alliance.
     *
     * @return string The ticker of the alliance.
     */
    public function getTicker(): string {
        return $this->ticker;
    }

    /**
     * Set the short name (ticker) of the alliance.
     *
     * @param string $ticker The ticker of the alliance.
     */
    protected function setTicker(string $ticker): void {
        $this->ticker = $ticker;
    }
}
