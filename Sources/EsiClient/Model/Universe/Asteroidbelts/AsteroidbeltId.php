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

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Asteroidbelts;

use \Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Asteroidbelts\AsteroidbeltId\Position;

class AsteroidbeltId {
    /**
     * name
     *
     * @var string
     */
    protected $name = null;

    /**
     * position
     *
     * @var Position
     */
    protected $position = null;

    /**
     * systemId
     *
     * The solar system this asteroid belt is in
     *
     * @var int
     */
    protected $systemId = null;

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
     * getPosition
     *
     * @return Position
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * setPosition
     *
     * @param Position $position
     */
    protected function setPosition(Position $position) {
        $this->position = $position;
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
