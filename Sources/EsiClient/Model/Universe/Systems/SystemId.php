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

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Systems;

use \Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Systems\SystemId\Position;
use Ppfeufer_Theme_EVEOnline_JsonMapper;

class SystemId {
    /**
     * constellationId
     *
     * The constellation this solar system is in
     *
     * @var int
     */
    protected $constellationId = null;

    /**
     * name
     *
     * @var string
     */
    protected $name = null;

    /**
     * planets
     *
     * @var array
     */
    protected $planets = null;

    /**
     * position
     *
     * @var Position
     */
    protected $position = null;

    /**
     * securityClass
     *
     * @var string
     */
    protected $securityClass = null;

    /**
     * securityStatus
     *
     * @var float
     */
    protected $securityStatus = null;

    /**
     * starId
     *
     * @var int
     */
    protected $starId = null;

    /**
     * stargates
     *
     * @var array
     */
    protected $stargates = null;

    /**
     * stations
     *
     * @var array
     */
    protected $stations = null;

    /**
     * systemId
     *
     * @var int
     */
    protected $systemId = null;

    /**
     * getConstellationId
     *
     * @return int
     */
    public function getConstellationId() {
        return $this->constellationId;
    }

    /**
     * setConstellationId
     *
     * @param int $constellationId
     */
    protected function setConstellationId(int $constellationId) {
        $this->constellationId = $constellationId;
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
     * getPlanets
     *
     * @return array
     */
    public function getPlanets() {
        return $this->planets;
    }

    /**
     * setPlanets
     *
     * @param array $planets
     */
    protected function setPlanets(array $planets) {
        $mapper = new Ppfeufer_Theme_EVEOnline_JsonMapper;

        $this->planets = $mapper->mapArray($planets, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Systems\SystemId\Planets');
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
     * getSecurityClass
     *
     * @return string
     */
    public function getSecurityClass() {
        return $this->securityClass;
    }

    /**
     * setSecurityClass
     *
     * @param string $securityClass
     */
    protected function setSecurityClass(string $securityClass) {
        $this->securityClass = $securityClass;
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
     * getStarId
     *
     * @return int
     */
    public function getStarId() {
        return $this->starId;
    }

    /**
     * setStarId
     *
     * @param int $starId
     */
    protected function setStarId(int $starId) {
        $this->starId = $starId;
    }

    /**
     * getStargates
     *
     * @return array
     */
    public function getStargates() {
        return $this->stargates;
    }

    /**
     * setStargates
     *
     * @param array $stargates
     */
    protected function setStargates(array $stargates) {
        $this->stargates = $stargates;
    }

    /**
     * getStations
     *
     * @return array
     */
    public function getStations() {
        return $this->stations;
    }

    /**
     * setStations
     *
     * @param array $stations
     */
    protected function setStations(array $stations) {
        $this->stations = $stations;
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
