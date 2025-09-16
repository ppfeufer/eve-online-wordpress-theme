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

class SystemJumps {
    /**
     * shipJumps
     *
     * @var int
     */
    protected $shipJumps = null;

    /**
     * systemId
     *
     * @var int
     */
    protected $systemId = null;

    /**
     * getShipJumps
     *
     * @return int
     */
    public function getShipJumps() {
        return $this->shipJumps;
    }

    /**
     * setShipJumps
     *
     * @param int $shipJumps
     */
    protected function setShipJumps(int $shipJumps) {
        $this->shipJumps = $shipJumps;
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
