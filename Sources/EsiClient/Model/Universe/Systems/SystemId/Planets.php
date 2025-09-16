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

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Systems\SystemId;

class Planets {
    /**
     * asteroidBelts
     *
     * @var array
     */
    protected $asteroidBelts = null;

    /**
     * moons
     *
     * @var array
     */
    protected $moons = null;

    /**
     * planetId
     *
     * @var int
     */
    protected $planetId = null;

    /**
     * getAsteroidBelts
     *
     * @return array
     */
    public function getAsteroidBelts() {
        return $this->asteroidBelts;
    }

    /**
     * setAsteroidBelts
     *
     * @param array $asteroidBelts
     */
    protected function setAsteroidBelts(array $asteroidBelts) {
        $this->asteroidBelts = $asteroidBelts;
    }

    /**
     * getMoons
     *
     * @return array
     */
    public function getMoons() {
        return $this->moons;
    }

    /**
     * setMoons
     *
     * @param array $moons
     */
    protected function setMoons(array $moons) {
        $this->moons = $moons;
    }

    /**
     * getPlanetId
     *
     * @return int
     */
    public function getPlanetId() {
        return $this->planetId;
    }

    /**
     * setPlanetId
     *
     * @param int $planetId
     */
    protected function setPlanetId(int $planetId) {
        $this->planetId = $planetId;
    }
}
