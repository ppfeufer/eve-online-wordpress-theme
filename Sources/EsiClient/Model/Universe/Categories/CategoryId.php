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

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Categories;

class CategoryId {
    /**
     * categoryId
     *
     * @var int
     */
    protected $categoryId = null;

    /**
     * groups
     *
     * @var array
     */
    protected $groups = null;

    /**
     * name
     *
     * @var string
     */
    protected $name = null;

    /**
     * published
     *
     * @var bool
     */
    protected $published = null;

    /**
     * getCategoryId
     *
     * @return int
     */
    public function getCategoryId() {
        return $this->categoryId;
    }

    /**
     * setCategoryId
     *
     * @param int $categoryId
     */
    protected function setCategoryId(int $categoryId) {
        $this->categoryId = $categoryId;
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
     * getGroups
     *
     * @return array
     */
    public function getGroups() {
        return $this->groups;
    }

    /**
     * setGroups
     *
     * @param array $groups
     */
    protected function setGroups(array $groups) {
        $this->groups = $groups;
    }
}
