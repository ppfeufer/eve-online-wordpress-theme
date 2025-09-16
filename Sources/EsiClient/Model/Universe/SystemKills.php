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

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe;

class SystemKills {
    /**
     * npcKills
     *
     * Number of NPC ships killed in this system
     *
     * @var int
     */
    protected $npcKills = null;

    /**
     * podKills
     *
     * Number of pods killed in this system
     *
     * @var int
     */
    protected $podKills = null;

    /**
     * shipKills
     *
     * Number of player ships killed in this system
     *
     * @var int
     */
    protected $shipKills = null;

    /**
     * systemId
     *
     * @var int
     */
    protected $systemId = null;

    /**
     * getNpcKills
     *
     * @return int
     */
    public function getNpcKills() {
        return $this->npcKills;
    }

    /**
     * setNpcKills
     *
     * @param int $npcKills
     */
    protected function setNpcKills(int $npcKills) {
        $this->npcKills = $npcKills;
    }

    /**
     * getPodKills
     *
     * @return int
     */
    public function getPodKills() {
        return $this->podKills;
    }

    /**
     * setPodKills
     *
     * @param int $podKills
     */
    protected function setPodKills(int $podKills) {
        $this->podKills = $podKills;
    }

    /**
     * getShipKills
     *
     * @return int
     */
    public function getShipKills() {
        return $this->shipKills;
    }

    /**
     * setShipKills
     *
     * @param int $shipKills
     */
    protected function setShipKills(int $shipKills) {
        $this->shipKills = $shipKills;
    }

    /**
     * getSystemId
     *
     * @return int
     */
    public function getSystemId() {
        return $this->systemId;
    }

    /**
     * setSystemId
     *
     * @param int $systemId
     */
    protected function setSystemId(int $systemId) {
        $this->systemId = $systemId;
    }
}
