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

class Portrait {
    /**
     * px512x512
     *
     * @var string
     */
    protected $px512x512 = null;

    /**
     * px256x256
     *
     * @var string
     */
    protected $px256x256 = null;

    /**
     * px128x128
     *
     * @var string
     */
    protected $px128x128 = null;

    /**
     * px64x64
     *
     * @var string
     */
    protected $px64x64 = null;

    /**
     * getPx512x512
     *
     * @return string
     */
    public function getPx512x512() {
        return $this->px512x512;
    }

    /**
     * setPx512x512
     *
     * @param string $px512x512
     */
    protected function setPx512x512(string $px512x512) {
        $this->px512x512 = \preg_replace('/http:\/\//', 'https://', $px512x512);
    }

    /**
     * getPx256x256
     *
     * @return string
     */
    public function getPx256x256() {
        return $this->px256x256;
    }

    /**
     * setPx256x256
     *
     * @param string $px256x256
     */
    protected function setPx256x256(string $px256x256) {
        $this->px256x256 = \preg_replace('/http:\/\//', 'https://', $px256x256);
    }

    /**
     * getPx128x128
     *
     * @return string
     */
    public function getPx128x128() {
        return $this->px128x128;
    }

    /**
     * setPx128x128
     *
     * @param string $px128x128
     */
    protected function setPx128x128(string $px128x128) {
        $this->px128x128 = \preg_replace('/http:\/\//', 'https://', $px128x128);
    }

    /**
     * getPx64x64
     *
     * @return string
     */
    public function getPx64x64() {
        return $this->px64x64;
    }

    /**
     * setPx64x64
     *
     * @param string $px64x64
     */
    protected function setPx64x64(string $px64x64) {
        $this->px64x64 = \preg_replace('/http:\/\//', 'https://', $px64x64);
    }

    /**
     * getPx32x32
     * -=[ Workaround until CCP implements 32px logos ]=-
     *
     * @return string
     */
    public function getPx32x32() {
        return \preg_replace('/_64.jpg/', '_32.jpg', $this->px64x64);
    }
}
