<?php

/*
 * Copyright (C) 2018 p.pfeufer
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

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Characters\Affiliation;

class Characters {
    /**
     * allianceId
     *
     * The character's alliance ID, if their corporation is in an alliance
     *
     * @var int
     */
    protected $allianceId = null;

    /**
     * characterId
     *
     * The character's ID
     *
     * @var int
     */
    protected $characterId = null;

    /**
     * corporationId
     *
     * The character's corporation ID
     *
     * @var int
     */
    protected $corporationId = null;

    /**
     * factionId
     *
     * The character's faction ID, if their corporation is in a faction
     *
     * @var int
     */
    protected $factionId = null;

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
     * getCharacterId
     *
     * @return int
     */
    public function getCharacterId() {
        return $this->characterId;
    }

    /**
     * setCharacterId
     *
     * @param int $characterId
     */
    protected function setCharacterId(int $characterId) {
        $this->characterId = $characterId;
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
}
