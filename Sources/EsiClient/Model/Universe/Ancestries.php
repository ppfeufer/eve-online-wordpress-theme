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

class Ancestries {
    /**
     * bloodlineId
     *
     * The bloodline associated with this ancestry
     *
     * @var int
     */
    protected $bloodlineId = null;

    /**
     * description
     *
     * @var string
     */
    protected $description = null;

    /**
     * iconId
     *
     * @var int
     */
    protected $iconId = null;

    /**
     * id
     *
     * @var int
     */
    protected $id = null;

    /**
     * name
     *
     * @var string
     */
    protected $name = null;

    /**
     * shortDescription
     *
     * @var string
     */
    protected $shortDescription = null;

    /**
     * getBloodlineId
     *
     * @return int The bloodline associated with this ancestry
     */
    public function getBloodlineId() {
        return $this->bloodlineId;
    }

    /**
     * setBloodlineId
     *
     * @param int $bloodlineId The bloodline associated with this ancestry
     */
    protected function setBloodlineId(int $bloodlineId) {
        $this->bloodlineId = $bloodlineId;
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
     * getId
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * setId
     *
     * @param int $id
     */
    protected function setId(int $id) {
        $this->id = $id;
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
     * getShortDescription
     *
     * @return string
     */
    public function getShortDescription() {
        return $this->shortDescription;
    }

    /**
     * setShortDescription
     *
     * @param string $shortDescription
     */
    protected function setShortDescription(string $shortDescription) {
        $this->shortDescription = $shortDescription;
    }
}
