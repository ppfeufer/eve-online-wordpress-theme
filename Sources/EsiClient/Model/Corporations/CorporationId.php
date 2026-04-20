<?php

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Corporations;

use DateTime;

/**
 * Class CorporationId
 *
 * Represents a corporation in the EVE Online universe with various attributes such as alliance, CEO, creator, and more.
 * Provides getter and setter methods for each attribute.
 *
 * @package Ppfeufer\Theme\EVEOnline\EsiClient\Model\Corporations
 */
class CorporationId {
    /**
     * @var int $allianceId
     * ID of the alliance that the corporation is a member of, if any.
     */
    protected int $allianceId;

    /**
     * @var int $ceoId
     * ID of the CEO of the corporation.
     */
    protected int $ceoId;

    /**
     * @var int $creatorId
     * ID of the creator of the corporation.
     */
    protected int $creatorId;

    /**
     * @var DateTime $dateFounded
     * The date the corporation was founded.
     */
    protected DateTime $dateFounded;

    /**
     * @var string $description
     * A description of the corporation.
     */
    protected string $description;

    /**
     * @var int $factionId
     * ID of the faction the corporation is associated with, if any.
     */
    protected int $factionId;

    /**
     * @var int $homeStationId
     * ID of the corporation's home station.
     */
    protected int $homeStationId;

    /**
     * @var int $memberCount
     * The number of members in the corporation.
     */
    protected int $memberCount;

    /**
     * @var string $name
     * The full name of the corporation.
     */
    protected string $name;

    /**
     * @var int $shares
     * The number of shares owned by the corporation.
     */
    protected int $shares;

    /**
     * @var float $taxRate
     * The tax rate of the corporation.
     */
    protected float $taxRate;

    /**
     * @var string $ticker
     * The short name (ticker) of the corporation.
     */
    protected string $ticker;

    /**
     * @var string $url
     * The URL of the corporation's website.
     */
    protected string $url;

    /**
     * Get the ID of the alliance the corporation is a member of.
     *
     * @return int The alliance ID.
     */
    public function getAllianceId(): int {
        return $this->allianceId;
    }

    /**
     * Set the ID of the alliance the corporation is a member of.
     *
     * @param int $allianceId The alliance ID to set.
     */
    protected function setAllianceId(int $allianceId): void {
        $this->allianceId = $allianceId;
    }

    /**
     * Get the ID of the CEO of the corporation.
     *
     * @return int The CEO ID.
     */
    public function getCeoId(): int {
        return $this->ceoId;
    }

    /**
     * Set the ID of the CEO of the corporation.
     *
     * @param int $ceoId The CEO ID to set.
     */
    protected function setCeoId(int $ceoId): void {
        $this->ceoId = $ceoId;
    }

    /**
     * Get the ID of the creator of the corporation.
     *
     * @return int The creator ID.
     */
    public function getCreatorId(): int {
        return $this->creatorId;
    }

    /**
     * Set the ID of the creator of the corporation.
     *
     * @param int $creatorId The creator ID to set.
     */
    protected function setCreatorId(int $creatorId): void {
        $this->creatorId = $creatorId;
    }

    /**
     * Get the date the corporation was founded.
     *
     * @return DateTime The founding date.
     */
    public function getDateFounded(): DateTime {
        return $this->dateFounded;
    }

    /**
     * Set the date the corporation was founded.
     *
     * @param DateTime $dateFounded The founding date to set.
     */
    protected function setDateFounded(DateTime $dateFounded): void {
        $this->dateFounded = $dateFounded;
    }

    /**
     * Get the description of the corporation.
     *
     * @return string The description.
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * Set the description of the corporation.
     * Strips HTML tags from the input.
     *
     * @param string $description The description to set.
     */
    protected function setDescription(string $description): void {
        $this->description = strip_tags($description);
    }

    /**
     * Get the ID of the faction the corporation is associated with.
     *
     * @return int The faction ID.
     */
    public function getFactionId(): int {
        return $this->factionId;
    }

    /**
     * Set the ID of the faction the corporation is associated with.
     *
     * @param int $factionId The faction ID to set.
     */
    protected function setFactionId(int $factionId): void {
        $this->factionId = $factionId;
    }

    /**
     * Get the ID of the corporation's home station.
     *
     * @return int The home station ID.
     */
    public function getHomeStationId(): int {
        return $this->homeStationId;
    }

    /**
     * Set the ID of the corporation's home station.
     *
     * @param int $homeStationId The home station ID to set.
     */
    protected function setHomeStationId(int $homeStationId): void {
        $this->homeStationId = $homeStationId;
    }

    /**
     * Get the number of members in the corporation.
     *
     * @return int The member count.
     */
    public function getMembercount(): int {
        return $this->memberCount;
    }

    /**
     * Set the number of members in the corporation.
     *
     * @param int $memberCount The member count to set.
     */
    protected function setMemberCount(int $memberCount): void {
        $this->memberCount = $memberCount;
    }

    /**
     * Get the full name of the corporation.
     *
     * @return string The name.
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Set the full name of the corporation.
     *
     * @param string $name The name to set.
     */
    protected function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * Get the number of shares owned by the corporation.
     *
     * @return int The number of shares.
     */
    public function getShares(): int {
        return $this->shares;
    }

    /**
     * Set the number of shares owned by the corporation.
     *
     * @param int $shares The number of shares to set.
     */
    protected function setShares(int $shares): void {
        $this->shares = $shares;
    }

    /**
     * Get the tax rate of the corporation.
     *
     * @return float The tax rate.
     */
    public function getTaxRate(): float {
        return $this->taxRate;
    }

    /**
     * Set the tax rate of the corporation.
     *
     * @param float $taxRate The tax rate to set.
     */
    protected function setTaxRate(float $taxRate): void {
        $this->taxRate = $taxRate;
    }

    /**
     * Get the short name (ticker) of the corporation.
     *
     * @return string The ticker.
     */
    public function getTicker(): string {
        return $this->ticker;
    }

    /**
     * Set the short name (ticker) of the corporation.
     *
     * @param string $ticker The ticker to set.
     */
    protected function setTicker(string $ticker): void {
        $this->ticker = $ticker;
    }

    /**
     * Get the URL of the corporation's website.
     *
     * @return string The URL.
     */
    public function getUrl(): string {
        return $this->url;
    }

    /**
     * Set the URL of the corporation's website.
     *
     * @param string $url The URL to set.
     */
    protected function setUrl(string $url): void {
        $this->url = $url;
    }
}
