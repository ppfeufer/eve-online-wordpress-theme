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

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Universe\Types\TypeId\Dogma;

class Effects {
    /**
     * effectId
     *
     * @var int
     */
    protected $effectId = null;

    /**
     * isDefault
     *
     * @var bool
     */
    protected $isDefault = null;

    /**
     * getEffectId
     *
     * @return int
     */
    public function getEffectId() {
        return $this->effectId;
    }

    /**
     * setEffectId
     *
     * @param int $effectId
     */
    protected function setEffectId(int $effectId) {
        $this->effectId = $effectId;
    }

    /**
     * getIsDefault
     *
     * @return bool
     */
    public function getIsDefault() {
        return $this->isDefault;
    }

    /**
     * setIsDefault
     *
     * @param bool $isDefault
     */
    protected function setIsDefault(bool $isDefault) {
        $this->isDefault = $isDefault;
    }
}
