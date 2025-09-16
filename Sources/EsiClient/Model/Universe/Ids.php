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

use Ppfeufer_Theme_EVEOnline_JsonMapper;
use Ppfeufer_Theme_EVEOnline_JsonMapper_Exception;

class Ids {
    /**
     * agents
     *
     * @var array
     */
    protected array $agents = [];

    /**
     * alliances
     *
     * @var array
     */
    protected array $alliances = [];

    /**
     * characters
     *
     * @var array
     */
    protected array $characters = [];

    /**
     * constellations
     *
     * @var array
     */
    protected array $constellations = [];

    /**
     * corporations
     *
     * @var array
     */
    protected array $corporations = [];

    /**
     * factions
     *
     * @var array
     */
    protected array $factions = [];

    /**
     * inventoryTypes
     *
     * @var array
     */
    protected array $inventoryTypes = [];

    /**
     * regions
     *
     * @var array
     */
    protected array $regions = [];

    /**
     * stations
     *
     * @var array
     */
    protected array $stations = [];

    /**
     * systems
     *
     * @var array
     */
    protected array $systems = [];

    /**
     * getAgents
     *
     * @return array
     */
    public function getAgents(): array {
        return $this->agents;
    }

    /**
     * setAgents
     *
     * @param array $agents
     * @throws Ppfeufer_Theme_EVEOnline_JsonMapper_Exception
     */
    protected function setAgents(array $agents): void {
        $mapper = new Ppfeufer_Theme_EVEOnline_JsonMapper;

        $this->agents = $mapper->mapArray($agents, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Agents');
    }

    /**
     * getAlliances
     *
     * @return array
     */
    public function getAlliances(): array {
        return $this->alliances;
    }

    /**
     * setAlliances
     *
     * @param array $alliances
     * @throws Ppfeufer_Theme_EVEOnline_JsonMapper_Exception
     */
    protected function setAlliances(array $alliances): void {
        $mapper = new Ppfeufer_Theme_EVEOnline_JsonMapper;

        $this->alliances = $mapper->mapArray($alliances, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Alliances');
    }

    /**
     * getCharacters
     *
     * @return array
     */
    public function getCharacters(): array {
        return $this->characters;
    }

    /**
     * setCharacters
     *
     * @param array $characters
     * @throws Ppfeufer_Theme_EVEOnline_JsonMapper_Exception
     */
    protected function setCharacters(array $characters): void {
        $mapper = new Ppfeufer_Theme_EVEOnline_JsonMapper;

        $this->characters = $mapper->mapArray($characters, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Characters');
    }

    /**
     * getConstellations
     *
     * @return array
     */
    public function getConstellations(): array {
        return $this->constellations;
    }

    /**
     * setConstellations
     *
     * @param array $constellations
     * @throws Ppfeufer_Theme_EVEOnline_JsonMapper_Exception
     */
    protected function setConstellations(array $constellations): void {
        $mapper = new Ppfeufer_Theme_EVEOnline_JsonMapper;

        $this->constellations = $mapper->mapArray($constellations, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Constellations');
    }

    /**
     * getCorporations
     *
     * @return array
     */
    public function getCorporations(): array {
        return $this->corporations;
    }

    /**
     * setCorporations
     *
     * @param array $corporations
     * @throws Ppfeufer_Theme_EVEOnline_JsonMapper_Exception
     */
    protected function setCorporations(array $corporations): void {
        $mapper = new Ppfeufer_Theme_EVEOnline_JsonMapper;

        $this->corporations = $mapper->mapArray($corporations, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Corporations');
    }

    /**
     * getFactions
     *
     * @return array
     */
    public function getFactions(): array {
        return $this->factions;
    }

    /**
     * setFactions
     *
     * @param array $factions
     * @throws Ppfeufer_Theme_EVEOnline_JsonMapper_Exception
     */
    protected function setFactions(array $factions): void {
        $mapper = new Ppfeufer_Theme_EVEOnline_JsonMapper;

        $this->factions = $mapper->mapArray($factions, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Factions');
    }

    /**
     * getInventoryTypes
     *
     * @return array
     */
    public function getInventoryTypes(): array {
        return $this->inventoryTypes;
    }

    /**
     * setInventoryTypes
     *
     * @param array $inventoryTypes
     * @throws Ppfeufer_Theme_EVEOnline_JsonMapper_Exception
     */
    protected function setInventoryTypes(array $inventoryTypes): void {
        $mapper = new Ppfeufer_Theme_EVEOnline_JsonMapper;

        $this->inventoryTypes = $mapper->mapArray($inventoryTypes, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\InventoryTypes');
    }

    /**
     * getRegions
     *
     * @return array
     */
    public function getRegions(): array {
        return $this->regions;
    }

    /**
     * setRegions
     *
     * @param array $regions
     * @throws Ppfeufer_Theme_EVEOnline_JsonMapper_Exception
     */
    protected function setRegions(array $regions): void {
        $mapper = new Ppfeufer_Theme_EVEOnline_JsonMapper;

        $this->regions = $mapper->mapArray($regions, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Regions');
    }

    /**
     * getStations
     *
     * @return array|null
     */
    public function getStations(): ?array {
        return $this->stations;
    }

    /**
     * setStations
     *
     * @param array $stations
     * @throws Ppfeufer_Theme_EVEOnline_JsonMapper_Exception
     */
    protected function setStations(array $stations): void {
        $mapper = new Ppfeufer_Theme_EVEOnline_JsonMapper;

        $this->stations = $mapper->mapArray($stations, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Stations');
    }

    /**
     * getSystems
     *
     * @return array|null
     */
    public function getSystems(): ?array {
        return $this->systems;
    }

    /**
     * setSystems
     *
     * @param array $systems
     * @throws Ppfeufer_Theme_EVEOnline_JsonMapper_Exception
     */
    protected function setSystems(array $systems): void {
        $mapper = new Ppfeufer_Theme_EVEOnline_JsonMapper;

        $this->systems = $mapper->mapArray($systems, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Ids\Systems');
    }
}
