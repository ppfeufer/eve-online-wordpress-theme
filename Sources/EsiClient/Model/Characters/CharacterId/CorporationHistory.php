<?php

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Characters\CharacterId;

use DateTime;

/**
 * Class CorporationHistory
 *
 * Represents the history of a character's corporation membership.
 * Provides methods to retrieve and set details such as corporation ID, deletion status, record ID, and start date.
 *
 * @package Ppfeufer\Theme\EVEOnline\EsiClient\Model\Characters\CharacterId
 */
class CorporationHistory {
    /**
     * @var int $corporationId
     * The ID of the corporation associated with this history record.
     */
    protected int $corporationId;

    /**
     * @var bool $isDeleted
     * Indicates whether the corporation has been deleted.
     */
    protected bool $isDeleted;

    /**
     * @var int $recordId
     * The unique ID of this history record.
     */
    protected int $recordId;

    /**
     * @var DateTime $startDate
     * The date when the character joined the corporation.
     */
    protected DateTime $startDate;

    /**
     * Get the ID of the corporation associated with this history record.
     *
     * @return int The corporation ID.
     */
    public function getCorporationId(): int {
        return $this->corporationId;
    }

    /**
     * Get the deletion status of the corporation.
     *
     * @return bool True if the corporation is deleted, false otherwise.
     */
    public function getIsDeleted(): bool {
        return $this->isDeleted;
    }

    /**
     * Set the deletion status of the corporation.
     *
     * @param bool $isDeleted The deletion status to set.
     * @return void
     */
    protected function setIsDeleted(bool $isDeleted): void {
        $this->isDeleted = $isDeleted;
    }

    /**
     * Get the unique ID of this history record.
     *
     * @return int The record ID.
     */
    public function getRecordId(): int {
        return $this->recordId;
    }

    /**
     * Set the unique ID of this history record.
     *
     * @param int $recordId The record ID to set.
     * @return void
     */
    protected function setRecordId(int $recordId): void {
        $this->recordId = $recordId;
    }

    /**
     * Get the date when the character joined the corporation.
     *
     * @return DateTime The start date.
     */
    public function getStartDate(): DateTime {
        return $this->startDate;
    }

    /**
     * Set the date when the character joined the corporation.
     *
     * @param DateTime $startDate The start date to set.
     * @return void
     */
    protected function setStartDate(DateTime $startDate): void {
        $this->startDate = $startDate;
    }

    /**
     * Set the ID of the corporation associated with this history record.
     *
     * @param int $corporationId The corporation ID to set.
     * @return void
     */
    protected function setCorporationId(int $corporationId): void {
        $this->corporationId = $corporationId;
    }
}
