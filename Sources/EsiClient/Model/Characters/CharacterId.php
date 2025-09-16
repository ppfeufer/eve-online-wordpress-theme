<?php

/*
 * Copyright (C) 2018 ppfeufer
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Characters;

use \DateTime;

class CharacterId {
    /**
     * allianceId
     *
     * The character's alliance ID
     *
     * @var int
     */
    protected $allianceId = null;

    /**
     * ancestryId
     *
     * @var int
     */
    protected $ancestryId = null;

    /**
     * birthday
     *
     * Creation date of the character
     *
     * @var DateTime
     */
    protected $birhday = null;

    /**
     * bloodlineId
     *
     * @var int
     */
    protected $bloodlineId = null;

    /**
     * corporationId
     *
     * The character's corporation ID
     *
     * @var int
     */
    protected $corporationId = null;

    /**
     * description
     *
     * @var string
     */
    protected $description = null;

    /**
     * factionId
     *
     * ID of the faction the character is fighting for, if the character is enlisted in Factional Warfare
     *
     * @var int
     */
    protected $factionId = null;

    /**
     * gender
     *
     * @var string
     */
    protected $gender = null;

    /**
     * name
     *
     * @var string
     */
    protected $name = null;

    /**
     * raceId
     *
     * @var int
     */
    protected $raceId = null;

    /**
     * securityStatus
     *
     * @var float
     */
    protected $securityStatus = null;

    /**
     * title
     *
     * @var string
     */
    protected $title = null;

    /**
     * getAllianceId
     *
     * @return int
     */
    public function getAllianceId() {
        return $this->allianceId;
    }

    /**
     * setAllianceId
     *
     * @param int $allianceId
     */
    protected function setAllianceId(int $allianceId) {
        $this->allianceId = $allianceId;
    }

    /**
     * getAncestryId
     *
     * @return int
     */
    public function getAncestryId() {
        return $this->ancestryId;
    }

    /**
     * setAncestryId
     *
     * @param int $ancestryId
     */
    protected function setAncestryId(int $ancestryId) {
        $this->ancestryId = $ancestryId;
    }

    /**
     * getBirthday
     *
     * @return DateTime
     */
    public function getBirthday() {
        return $this->birhday;
    }

    /**
     * setBirthday
     *
     * @param DateTime $birthday
     */
    protected function setBirthday(DateTime $birthday) {
        $this->birhday = $birthday;
    }

    /**
     * getBloodlineId
     *
     * @return int
     */
    public function getBloodlineId() {
        return $this->bloodlineId;
    }

    /**
     * setBloodlineId
     *
     * @param int $bloodlineId
     */
    protected function setBloodlineId(int $bloodlineId) {
        $this->bloodlineId = $bloodlineId;
    }

    /**
     * getCorporationId
     *
     * @return int
     */
    public function getCorporationId() {
        return $this->corporationId;
    }

    /**
     * setCorporationId
     *
     * @param int $corporationId
     */
    protected function setCorporationId(int $corporationId) {
        $this->corporationId = $corporationId;
    }

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * setDescription
     *
     * @param string $description
     */
    protected function setDescription(string $description) {
        $this->description = \strip_tags($description);
    }

    /**
     * getFactionId
     *
     * @return int
     */
    public function getFactionId() {
        return $this->factionId;
    }

    /**
     * setFactionId
     *
     * @param int $factionId
     */
    protected function setFactionId(int $factionId) {
        $this->factionId = $factionId;
    }

    /**
     * getGender
     *
     * @return string
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * setGender
     *
     * @param string $gender
     */
    protected function setGender(string $gender) {
        $this->gender = $gender;
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * setName
     *
     * @param string $name
     */
    protected function setName(string $name) {
        $this->name = $name;
    }

    /**
     * getRaceId
     *
     * @return int
     */
    public function getRaceId() {
        return $this->raceId;
    }

    /**
     * setRaceId
     *
     * @param int $raceId
     */
    protected function setRaceId(int $raceId) {
        $this->raceId = $raceId;
    }

    /**
     * getSecurityStatus
     *
     * @return float
     */
    public function getSecurityStatus() {
        return $this->securityStatus;
    }

    /**
     * setSecurityStatus
     *
     * @param float $securityStatus
     */
    protected function setSecurityStatus(float $securityStatus) {
        $this->securityStatus = $securityStatus;
    }

    /**
     * getTitle
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * setTitle
     *
     * @param string $title
     */
    protected function setTitle(string $title) {
        $this->title = $title;
    }
}
