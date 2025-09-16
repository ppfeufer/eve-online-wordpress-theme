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

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Error;

class EsiError {
    /**
     * error
     *
     * @var string
     */
    protected $error = null;

    /**
     * responseCode
     *
     * @var int
     */
    protected $responseCode = null;

    /**
     * ssoStatus
     *
     * @var int
     */
    protected $ssoStatus = null;

    /**
     * timeout
     *
     * @var int
     */
    protected $timeout = null;

    /**
     * type
     *
     * @var string
     */
    protected $type = null;

    public function __construct($errorCode) {
        switch ($errorCode) {
            case 400:
                $errorType = 'Bad request';
                break;

            case 401:
                $errorType = 'Unauthorized';
                break;

            case 403:
                $errorType = 'Forbidden';
                break;

            case 404:
                $errorType = 'Not found';
                break;

            case 420:
                $errorType = 'Error limited';
                break;

            case 500:
                $errorType = 'Internal server error';
                break;

            case 503:
                $errorType = 'Service unavailable';
                break;

            case 504:
                $errorType = 'Gateway timeout';
                break;

            default:
                $errorType = 'General error';
                break;
        }

        $this->type = $this->setType($errorType);
        $this->responseCode = $this->setResponseCode($errorCode);
    }

    /**
     * getError
     *
     * @return string
     */
    public function getError() {
        return $this->error;
    }

    /**
     * setError
     *
     * @param string $error
     */
    protected function setError(string $error) {
        $this->error = $error;
    }

    /**
     * getSsoStatus
     *
     * @return int
     */
    public function getSsoStatus() {
        return $this->ssoStatus;
    }

    /**
     * setSsoStatus
     *
     * @param int $ssoStatus
     */
    protected function setSsoStatus(int $ssoStatus) {
        $this->ssoStatus = $ssoStatus;
    }

    /**
     * getTimeout
     *
     * @return int
     */
    public function getTimeout() {
        return $this->timeout;
    }

    /**
     * setTimeout
     *
     * @param int $timeout
     */
    protected function setTimeout(int $timeout) {
        $this->timeout = $timeout;
    }

    /**
     * getType
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * setType
     *
     * @param string $type
     */
    protected function setType(string $type) {
        $this->type = $type;
    }

    /**
     * getResponseCode
     *
     * @return int
     */
    public function getResponseCode() {
        return $this->responseCode;
    }

    /**
     * setResponseCode
     *
     * @param int $responsecode
     */
    protected function setResponseCode(int $responsecode) {
        $this->responseCode = $responsecode;
    }
}
