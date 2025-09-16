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

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe;

use \Ppfeufer\Theme\EVEOnline\EsiClient\Mapper\JsonMapper;

class Ids {
    /**
     * agents
     *
     * @var array
     */
    protected $agents = null;

    /**
     * alliances
     *
     * @var array
     */
    protected $alliances = null;

    /**
     * characters
     *
     * @var array
     */
    protected $characters = null;

    /**
     * constellations
     *
     * @var array
     */
    protected $constellations = null;

    /**
     * corporations
     *
     * @var array
     */
    protected $corporations = null;

    /**
     * factions
     *
     * @var array
     */
    protected $factions = null;

    /**
     * inventoryTypes
     *
     * @var array
     */
    protected $inventoryTypes = null;

    /**
     * regions
     *
     * @var array
     */
    protected $regions = null;

    /**
     * stations
     *
     * @var array
     */
    protected $stations = null;

    /**
     * systems
     *
     * @var array
     */
    protected $systems = null;

    /**
     * getAgents
     *
     * @return array
     */
    public function getAgents() {
        return $this->agents;
    }

    /**
     * setAgents
     *
     * @param array $agents
     */
    protected function setAgents(array $agents) {
        $mapper = new JsonMapper;

        $this->agents = $mapper->mapArray($agents, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Agents');
    }

    /**
     * getAlliances
     *
     * @return array
     */
    public function getAlliances() {
        return $this->alliances;
    }

    /**
     * setAlliances
     *
     * @param array $alliances
     */
    protected function setAlliances(array $alliances) {
        $mapper = new JsonMapper;

        $this->alliances = $mapper->mapArray($alliances, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Alliances');
    }

    /**
     * getCharacters
     *
     * @return array
     */
    public function getCharacters() {
        return $this->characters;
    }

    /**
     * setCharacters
     *
     * @param array $characters
     */
    protected function setCharacters(array $characters) {
        $mapper = new JsonMapper;

        $this->characters = $mapper->mapArray($characters, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Characters');
    }

    /**
     * getConstellations
     *
     * @return array
     */
    public function getConstellations() {
        return $this->constellations;
    }

    /**
     * setConstellations
     *
     * @param array $constellations
     */
    protected function setConstellations(array $constellations) {
        $mapper = new JsonMapper;

        $this->constellations = $mapper->mapArray($constellations, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Constellations');
    }

    /**
     * getCorporations
     *
     * @return array
     */
    public function getCorporations() {
        return $this->corporations;
    }

    /**
     * setCorporations
     *
     * @param array $corporations
     */
    protected function setCorporations(array $corporations) {
        $mapper = new JsonMapper;

        $this->corporations = $mapper->mapArray($corporations, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Corporations');
    }

    /**
     * getFactions
     *
     * @return array
     */
    public function getFactions() {
        return $this->factions;
    }

    /**
     * setFactions
     *
     * @param array $factions
     */
    protected function setFactions(array $factions) {
        $mapper = new JsonMapper;

        $this->factions = $mapper->mapArray($factions, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Factions');
    }

    /**
     * getInventoryTypes
     *
     * @return array
     */
    public function getInventoryTypes() {
        return $this->inventoryTypes;
    }

    /**
     * setInventoryTypes
     *
     * @param array $inventoryTypes
     */
    protected function setInventoryTypes(array $inventoryTypes) {
        $mapper = new JsonMapper;

        $this->inventoryTypes = $mapper->mapArray($inventoryTypes, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\InventoryTypes');
    }

    /**
     * getRegions
     *
     * @return array
     */
    public function getRegions() {
        return $this->regions;
    }

    /**
     * setRegions
     *
     * @param array $regions
     */
    protected function setRegions(array $regions) {
        $mapper = new JsonMapper;

        $this->regions = $mapper->mapArray($regions, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Regions');
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
        $mapper = new JsonMapper;

        $this->stations = $mapper->mapArray($stations, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Stations');
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
        $mapper = new JsonMapper;

        $this->systems = $mapper->mapArray($systems, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Systems');
    }
}
