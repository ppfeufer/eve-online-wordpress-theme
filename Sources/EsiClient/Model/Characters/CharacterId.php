<?php

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Characters;

use DateTime;

/**
 * Class CharacterId
 *
 * Represents a character in the EVE Online universe with various attributes such as alliance, ancestry, birthday, and more.
 * Provides getter and setter methods for each attribute.
 *
 * @package Ppfeufer\Theme\EVEOnline\EsiClient\Model\Characters
 */
class CharacterId {
    /**
     * @var int $allianceId
     * The character's alliance ID.
     */
    protected int $allianceId;

    /**
     * @var int $ancestryId
     * The character's ancestry ID.
     */
    protected int $ancestryId;

    /**
     * @var DateTime $birthday
     * The creation date of the character.
     */
    protected DateTime $birthday;

    /**
     * @var int $bloodlineId
     * The character's bloodline ID.
     */
    protected int $bloodlineId;

    /**
     * @var int $corporationId
     * The character's corporation ID.
     */
    protected int $corporationId;

    /**
     * @var string $description
     * A description of the character.
     */
    protected string $description;

    /**
     * @var int $factionId
     * The ID of the faction the character is fighting for, if enlisted in Factional Warfare.
     */
    protected int $factionId;

    /**
     * @var string $gender
     * The gender of the character.
     */
    protected string $gender;

    /**
     * @var string $name
     * The name of the character.
     */
    protected string $name;

    /**
     * @var int $raceId
     * The character's race ID.
     */
    protected int $raceId;

    /**
     * @var float $securityStatus
     * The security status of the character.
     */
    protected float $securityStatus;

    /**
     * @var string $title
     * The title of the character.
     */
    protected string $title;

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
     */
    protected function setAllianceId(int $allianceId): void {
        $this->allianceId = $allianceId;
    }

    /**
     * Get the character's ancestry ID.
     *
     * @return int The ancestry ID.
     */
    public function getAncestryId(): int {
        return $this->ancestryId;
    }

    /**
     * Set the character's ancestry ID.
     *
     * @param int $ancestryId The ancestry ID to set.
     */
    protected function setAncestryId(int $ancestryId): void {
        $this->ancestryId = $ancestryId;
    }

    /**
     * Get the character's birthday.
     *
     * @return DateTime The birthday.
     */
    public function getBirthday(): DateTime {
        return $this->birthday;
    }

    /**
     * Set the character's birthday.
     *
     * @param DateTime $birthday The birthday to set.
     */
    protected function setBirthday(DateTime $birthday): void {
        $this->birthday = $birthday;
    }

    /**
     * Get the character's bloodline ID.
     *
     * @return int The bloodline ID.
     */
    public function getBloodlineId(): int {
        return $this->bloodlineId;
    }

    /**
     * Set the character's bloodline ID.
     *
     * @param int $bloodlineId The bloodline ID to set.
     */
    protected function setBloodlineId(int $bloodlineId): void {
        $this->bloodlineId = $bloodlineId;
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
     */
    protected function setCorporationId(int $corporationId): void {
        $this->corporationId = $corporationId;
    }

    /**
     * Get the character's description.
     *
     * @return string The description.
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * Set the character's description.
     * Strips HTML tags from the input.
     *
     * @param string $description The description to set.
     */
    protected function setDescription(string $description): void {
        $this->description = strip_tags($description);
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
     */
    protected function setFactionId(int $factionId): void {
        $this->factionId = $factionId;
    }

    /**
     * Get the character's gender.
     *
     * @return string The gender.
     */
    public function getGender(): string {
        return $this->gender;
    }

    /**
     * Set the character's gender.
     *
     * @param string $gender The gender to set.
     */
    protected function setGender(string $gender): void {
        $this->gender = $gender;
    }

    /**
     * Get the character's name.
     *
     * @return string The name.
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Set the character's name.
     *
     * @param string $name The name to set.
     */
    protected function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * Get the character's race ID.
     *
     * @return int The race ID.
     */
    public function getRaceId(): int {
        return $this->raceId;
    }

    /**
     * Set the character's race ID.
     *
     * @param int $raceId The race ID to set.
     */
    protected function setRaceId(int $raceId): void {
        $this->raceId = $raceId;
    }

    /**
     * Get the character's security status.
     *
     * @return float The security status.
     */
    public function getSecurityStatus(): float {
        return $this->securityStatus;
    }

    /**
     * Set the character's security status.
     *
     * @param float $securityStatus The security status to set.
     */
    protected function setSecurityStatus(float $securityStatus): void {
        $this->securityStatus = $securityStatus;
    }

    /**
     * Get the character's title.
     *
     * @return string The title.
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * Set the character's title.
     *
     * @param string $title The title to set.
     */
    protected function setTitle(string $title): void {
        $this->title = $title;
    }
}
