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

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Meta;

class Status {
    /**
     * endpoint
     *
     * @var string
     */
    protected $endpoint = null;

    /**
     * method
     *
     * @var string
     */
    protected $method = null;

    /**
     * route
     *
     * @var string
     */
    protected $route = null;

    /**
     * status
     *
     * @var string
     */
    protected $status = null;

    /**
     * tags
     *
     * @var array
     */
    protected $tags = null;

    /**
     * getEndpoint
     *
     * ESI Endpoint cluster advertising this route
     *
     * @return string
     */
    public function getEndpoint() {
        return $this->endpoint;
    }

    /**
     * setEndpoint
     *
     * @param string $endpoint
     */
    protected function setEndpoint(string $endpoint) {
        $this->endpoint = $endpoint;
    }

    /**
     * getMethod
     *
     * Swagger defined method
     *
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * setMethod
     *
     * @param string $method
     */
    protected function setMethod(string $method) {
        $this->method = $method;
    }

    /**
     * getRoute
     *
     * Swagger defined route, not including version prefix
     *
     * @return string
     */
    public function getRoute() {
        return $this->route;
    }

    /**
     * setRoute
     *
     * @param string $route
     */
    protected function setRoute(string $route) {
        $this->route = $route;
    }

    /**
     * getStatus
     *
     * Vague route status.
     * Green is good
     * Yellow is degraded, meaning slow or potentially dropping requests
     * Red means most requests are not succeeding and/or are very slow (5s+) on average.
     *
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * setStatus
     *
     * @param string $status
     */
    public function setStatus(string $status) {
        $this->status = $status;
    }

    /**
     * getTags
     *
     * Swagger tags applicable to this route
     *
     * @return array
     */
    public function getTags() {
        return $this->tags;
    }

    /**
     * setTags
     *
     * @param array $tags
     */
    public function setTags(array $tags) {
        $this->tags = $tags;
    }
}
