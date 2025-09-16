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

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Alliances;

use \DateTime;

class AllianceId {
    /**
     * creatorCorpId
     *
     * ID of the corporation that created the alliance
     *
     * @var int
     */
    protected $creatorCorporationId = null;

    /**
     * creatorId
     *
     * ID of the character that created the alliance
     *
     * @var int
     */
    protected $creatorId = null;

    /**
     * dateFounded
     *
     * @var DateTime
     */
    protected $dateFounded = null;

    /**
     * executorCorpId
     *
     * The executor corporation ID, if this alliance is not closed
     *
     * @var int
     */
    protected $executorCorporationId = null;

    /**
     * factionId
     *
     * Faction ID this alliance is fighting for, if this alliance is enlisted in factional warfare
     *
     * @var int
     */
    protected $factionId = null;

    /**
     * name
     *
     * The full name of the alliance
     *
     * @var string
     */
    protected $name = null;

    /**
     * ticker
     *
     * The short name of the alliance
     *
     * @var string
     */
    protected $ticker = null;

    /**
     * getCreatorCorpId
     *
     * @return int
     */
    public function getCreatorCorporationId() {
        return $this->creatorCorporationId;
    }

    /**
     * setCreatorCorpId
     *
     * @param int $creatorCorpId
     */
    protected function setCreatorCorporationId(int $creatorCorpId) {
        $this->creatorCorporationId = $creatorCorpId;
    }

    /**
     * getCreatorId
     *
     * @return int
     */
    public function getCreatorId() {
        return $this->creatorId;
    }

    /**
     * setCreatorId
     *
     * @param int $creatorId
     */
    protected function setCreatorId(int $creatorId) {
        $this->creatorId = $creatorId;
    }

    /**
     * getDateFounded
     *
     * @return DateTime
     */
    public function getDateFounded() {
        return $this->dateFounded;
    }

    /**
     * setDateFounded
     *
     * @param DateTime $dateFounded
     */
    protected function setDateFounded(DateTime $dateFounded) {
        $this->dateFounded = $dateFounded;
    }

    /**
     * getExecutorCorpId
     *
     * @return int
     */
    public function getExecutorCorporationId() {
        return $this->executorCorporationId;
    }

    /**
     * setExecutorCorpId
     *
     * @param int $executorCorpId
     */
    protected function setExecutorCorporationId(int $executorCorpId) {
        $this->executorCorporationId = $executorCorpId;
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
     * getTicker
     *
     * @return string
     */
    public function getTicker() {
        return $this->ticker;
    }

    /**
     * setTicker
     *
     * @param string $ticker
     */
    protected function setTicker(string $ticker) {
        $this->ticker = $ticker;
    }
}
