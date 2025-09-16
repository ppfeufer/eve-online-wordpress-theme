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

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Types;

use \Ppfeufer\Theme\EVEOnline\EsiClient\Mapper\JsonMapper;

class TypeId {
    /**
     * capacity
     *
     * @var float
     */
    protected $capacity = null;

    /**
     * description
     *
     * @var string
     */
    protected $description = null;

    /**
     * dogmaAttributes
     *
     * @var array
     */
    protected $dogmaAttributes = null;

    /**
     * dogmaEffects
     *
     * @var array
     */
    protected $dogmaEffects = null;

    /**
     * graphicId
     *
     * @var int
     */
    protected $graphicId = null;

    /**
     * groupId
     *
     * @var int
     */
    protected $groupId = null;

    /**
     * iconId
     *
     * @var int
     */
    protected $iconId = null;

    /**
     * marketGroupId
     *
     * This only exists for types that can be put on the market
     *
     * @var int
     */
    protected $marketGroupId = null;

    /**
     * mass
     *
     * @var float
     */
    protected $mass = null;

    /**
     * name
     *
     * @var string
     */
    protected $name = null;

    /**
     * packagedVolume
     *
     * @var float
     */
    protected $packagedVolume = null;

    /**
     * portionSize
     *
     * @var int
     */
    protected $portionSize = null;

    /**
     * published
     *
     * @var bool
     */
    protected $published = false;

    /**
     * radius
     *
     * @var float
     */
    protected $radius = null;

    /**
     * typeId
     *
     * @var int
     */
    protected $typeId = null;

    /**
     * volume
     *
     * @var float
     */
    protected $volume = null;

    /**
     * getCapacity
     *
     * @return float
     */
    public function getCapacity() {
        return $this->capacity;
    }

    /**
     * setCapacity
     *
     * @param float $capacity
     */
    protected function setCapacity(float $capacity) {
        $this->capacity = $capacity;
    }

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * setDescription
     *
     * @param string $description
     */
    protected function setDescription(string $description) {
        $this->description = $description;
    }

    /**
     * getDogmaAttributes
     *
     * @return array
     */
    public function getDogmaAttributes() {
        return $this->dogmaAttributes;
    }

    /**
     * setDogmaAttributes
     *
     * @param array $dogmaAttributes
     */
    protected function setDogmaAttributes(array $dogmaAttributes) {
        $mapper = new JsonMapper;

        $this->dogmaAttributes = $mapper->mapArray($dogmaAttributes, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Types\TypeId\Dogma\Attributes');
    }

    /**
     * getDogmaEffects
     *
     * @return array
     */
    public function getDogmaEffects() {
        return $this->dogmaEffects;
    }

    /**
     * setDogmaEffects
     *
     * @param array $dogmaEffects
     */
    protected function setDogmaEffects(array $dogmaEffects) {
        $mapper = new JsonMapper;

        $this->dogmaEffects = $mapper->mapArray($dogmaEffects, [], '\\Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Types\TypeId\Dogma\Effects');
    }

    /**
     * getGraphicId
     *
     * @return int
     */
    public function getGraphicId() {
        return $this->graphicId;
    }

    /**
     * setGraphicId
     *
     * @param int $graphicId
     */
    protected function setGraphicId(int $graphicId) {
        $this->graphicId = $graphicId;
    }

    /**
     * getGroupId
     *
     * @return int
     */
    public function getGroupId() {
        return $this->groupId;
    }

    /**
     * setGroupId
     *
     * @param int $groupId
     */
    protected function setGroupId(int $groupId) {
        $this->groupId = $groupId;
    }

    /**
     * getIconId
     *
     * @return int
     */
    public function getIconId() {
        return $this->iconId;
    }

    /**
     * setIconId
     *
     * @param int $iconId
     */
    protected function setIconId(int $iconId) {
        $this->iconId = $iconId;
    }

    /**
     * getMarketGroupId
     *
     * @return int
     */
    public function getMarketGroupId() {
        return $this->marketGroupId;
    }

    /**
     * setMarketGroupId
     *
     * @param int $marketGroupId
     */
    protected function setMarketGroupId(int $marketGroupId) {
        $this->marketGroupId = $marketGroupId;
    }

    /**
     * getMass
     *
     * @return float
     */
    public function getMass() {
        return $this->mass;
    }

    /**
     * setMass
     *
     * @param float $mass
     */
    protected function setMass(float $mass) {
        $this->mass = $mass;
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
     * getPackagedVolume
     *
     * @return float
     */
    public function getPackagedVolume() {
        return $this->packagedVolume;
    }

    /**
     * setPackedVolume
     *
     * @param float $packagedVolume
     */
    protected function setPackedVolume(float $packagedVolume) {
        $this->packagedVolume = $packagedVolume;
    }

    /**
     * getPortionSize
     *
     * @return int
     */
    public function getPortionSize() {
        return $this->portionSize;
    }

    /**
     * setPortionSize
     *
     * @param int $portionSize
     */
    protected function setPortionSize(int $portionSize) {
        $this->portionSize = $portionSize;
    }

    /**
     * getPublished
     *
     * @return bool
     */
    public function getPublished() {
        return $this->published;
    }

    /**
     * setPublished
     *
     * @param bool $published
     */
    protected function setPublished(bool $published) {
        $this->published = $published;
    }

    /**
     * getRadius
     *
     * @return float
     */
    public function getRadius() {
        return $this->radius;
    }

    /**
     * setRadius
     *
     * @param float $radius
     */
    protected function setRadius(float $radius) {
        $this->radius = $radius;
    }

    /**
     * getTypeId
     *
     * @return int
     */
    public function getTypeId() {
        return $this->typeId;
    }

    /**
     * setTypeId
     *
     * @param int $typeId
     */
    protected function setTypeId(int $typeId) {
        $this->typeId = $typeId;
    }

    /**
     * getVolume
     *
     * @return float
     */
    public function getVolume() {
        return $this->volume;
    }

    /**
     * setVolume
     *
     * @param float $volume
     */
    protected function setVolume(float $volume) {
        $this->volume = $volume;
    }
}
