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

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Characters\CharacterId;

use \DateTime;

class Corporationhistory {
    /**
     * corporationId
     *
     * @var int
     */
    protected $corporatoinId = null;

    /**
     * isDeleted
     *
     * @var bool
     */
    protected $isDeleted = null;

    /**
     * recordId
     *
     * @var int
     */
    protected $recordId = null;

    /**
     * startDate
     *
     * @var DateTime
     */
    protected $startDate = null;

    /**
     * getCorporationId
     *
     * @return int
     */
    public function getCorporationId() {
        return $this->corporatoinId;
    }

    /**
     * setCorporationId
     *
     * @param int $corporationId
     */
    protected function setCorporationId(int $corporationId) {
        $this->corporatoinId = $corporationId;
    }

    /**
     * getIsDeleted
     *
     * @return bool
     */
    public function getIsDeleted() {
        return $this->isDeleted;
    }

    /**
     * setIsDeleted
     *
     * @param bool $isDeleted
     */
    protected function setIsDeleted(bool $isDeleted) {
        $this->isDeleted = $isDeleted;
    }

    /**
     * getRecordId
     *
     * @return int
     */
    public function getRecordId() {
        return $this->recordId;
    }

    /**
     * setRecordId
     *
     * @param int $recordId
     */
    protected function setRecordId(int $recordId) {
        $this->recordId = $recordId;
    }

    /**
     * getStartDate
     *
     * @return DateTime
     */
    public function getStartDate() {
        return $this->startDate;
    }

    /**
     * setStartDate
     *
     * @param DateTime $startDate
     */
    protected function setStartDate(DateTime $startDate) {
        $this->startDate = $startDate;
    }
}
