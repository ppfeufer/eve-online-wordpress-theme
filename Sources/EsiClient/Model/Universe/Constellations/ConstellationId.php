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

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Constellations;

use \Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Constellations\ConstellationId\Position;

class ConstellationId {
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
     * position
     *
     * @var Position
     */
    protected $position = null;

    /**
     * regionId
     *
     * The region this constellation is in
     *
     * @var int
     */
    protected $regionId = null;

    /**
     * systems
     *
     * @var array
     */
    protected $systems = null;

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
     * getRegionId
     *
     * @return int
     */
    public function getRegionId() {
        return $this->regionId;
    }

    /**
     * setRegionId
     *
     * @param int $regionId
     */
    protected function setRegionId(int $regionId) {
        $this->regionId = $regionId;
    }

    /**
     * getSystems
     *
     * @return array
     */
    public function getSystems() {
        return $this->systems;
    }

    /**
     * setSystems
     *
     * @param array $systems
     */
    protected function setSystems(array $systems) {
        $this->systems = $systems;
    }
}
