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

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Alliance;

class Alliances {
    /**
     * alliances
     *
     * List of Alliance IDs
     *
     * @var array
     */
    protected $alliances = null;

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
        $this->alliances = $alliances;
    }
}
