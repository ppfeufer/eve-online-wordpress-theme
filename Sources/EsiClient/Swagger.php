<?php

/**
 * Copyright (C) 2017 Rounon Dax
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

namespace Ppfeufer\Theme\EVEOnline\EsiClient;

use Ppfeufer\Theme\EVEOnline\EsiClient\Helper\RemoteHelper;
use Ppfeufer\Theme\EVEOnline\EsiClient\Model\Error\EsiError;
use Ppfeufer_Theme_EVEOnline_JsonMapper;

class Swagger {
    /**
     * ESI Version
     *
     * @var string
     */
    protected $esiVersion = 'latest';
    /**
     * ESI method
     *
     * @var string
     */
    protected $esiMethod = 'get';
    /**
     * ESI route
     *
     * @var string
     */
    protected $esiRoute = null;
    /**
     * ESI route parameter
     *
     * @var array
     */
    protected $esiRouteParameter = [];
    /**
     * ESI POST parameter
     *
     * @var array
     */
    protected $esiPostParameter = [];
    /**
     * esiLanguages
     *
     * @var array
     */
    protected $esiLanguages = [
        'de',
        'en-us',
        'fr',
        'ja',
        'ru',
        'zh',
        'ko'
    ];
    /**
     * esiDefaultLanguage
     *
     * @var string
     */
    protected $esiDefaultLanguage = 'en-us';
    /**
     * Remote Helper
     *
     * @var RemoteHelper
     */
    protected $remoteHelper = null;
    /**
     * ESI Compatibility Date
     *
     * @var string
     */
    private $esiCompatibilityDate = '2025-08-26';
    /**
     * ESI URL
     *
     * @var string
     */
    private $esiUrl = 'https://esi.evetech.net/';

    /**
     * getEsiClientVersion
     *
     * @return int
     */
    public function getEsiCompatibilityDate() {
        return $this->esiCompatibilityDate;
    }

    /**
     * getEsiLanguages
     *
     * @return array
     */
    public function getEsiLanguages(): array {
        return $this->esiLanguages;
    }

    /**
     * getEsiDefaultLanguage
     *
     * @return string
     */
    public function getEsiDefaultLanguage(): string {
        return $this->esiDefaultLanguage;
    }

    /**
     * creating the appropriate Error object
     *
     * @param array $esiData
     * @return EsiError|null
     */
    public function createErrorObject(array $esiData): EsiError|null {
        $returnvalue = null;

        if ($esiData['responseCode'] !== 200) {
            $returnvalue = $this->map($esiData['responseBody'], new EsiError($esiData['responseCode']));
        }

        return $returnvalue;
    }

    /**
     * mapping json to class
     *
     * @param string $jSon
     * @param object $object
     * @return object
     */
    public function map(string $jSon, $object) {
        $returnValue = null;

        if (!is_null($jSon)) {
            $jsonMapper = new Ppfeufer_Theme_EVEOnline_JsonMapper;

            $returnValue = $jsonMapper->map(json_decode($jSon), $object);
        }

        return $returnValue;
    }

    /**
     * Call ESI
     *
     * @return object ESI response as object
     */
    public function callEsi() {
        if (!is_a($this->remoteHelper, '\Ppfeufer\Theme\EVEOnline\EsiClient\Helper\Helper\RemoteHelper')) {
            $this->remoteHelper = new RemoteHelper;
        }

        $esiUrl = trailingslashit($this->getEsiUrl() . $this->getEsiVersion());
        $esiRoute = $this->getEsiRoute();

        if (count($this->getEsiRouteParameter()) > 0) {
            $esiRoute = preg_replace(array_keys($this->getEsiRouteParameter()), array_values($this->getEsiRouteParameter()), $this->getEsiRoute());
        }

        $callUrl = $esiUrl . $esiRoute;

        switch ($this->getEsiMethod()) {
            case 'get':
                $remoteData = $this->remoteHelper->getRemoteData($callUrl);
                break;

            case 'post':
                $remoteData = $this->remoteHelper->getRemoteData($callUrl, $this->getEsiMethod(), $this->getEsiPostParameter());
                break;
        }

        $this->resetFieldsToDefaults();

        return $this->getResponseArray($remoteData);
    }

    /**
     * getEsiUrl
     *
     * @return string
     */
    public function getEsiUrl() {
        return $this->esiUrl;
    }

    /**
     * getEsiVersion
     *
     * @return string
     */
    public function getEsiVersion() {
        return $this->esiVersion;
    }

    /**
     * setEsiVersion
     *
     * @param string $esiVersion
     */
    public function setEsiVersion($esiVersion) {
        $this->esiVersion = $esiVersion;
    }

    /**
     * getEsiRoute
     *
     * @return string
     */
    public function getEsiRoute() {
        return $this->esiRoute;
    }

    /**
     * setEsiRoute
     *
     * @param string $esiRoute
     */
    public function setEsiRoute($esiRoute) {
        $this->esiRoute = $esiRoute;
    }

    /**
     * getEsiRouteParameter
     *
     * @return array
     */
    public function getEsiRouteParameter() {
        return $this->esiRouteParameter;
    }

    /**
     * setEsiRouteParameter
     *
     * @param array $esiRouteParameter
     */
    public function setEsiRouteParameter(array $esiRouteParameter) {
        $this->esiRouteParameter = $esiRouteParameter;
    }

    /**
     * getEsiMethod
     *
     * @return string
     */
    public function getEsiMethod() {
        return $this->esiMethod;
    }

    /**
     * setEsiMethod
     *
     * @param string $esiMethod
     */
    public function setEsiMethod($esiMethod) {
        $this->esiMethod = $esiMethod;
    }

    /**
     * getEsiPostParameter
     *
     * @return array
     */
    public function getEsiPostParameter() {
        return $this->esiPostParameter;
    }

    /**
     * setEsiPostParameter
     *
     * @param array $esiPostParameter
     */
    public function setEsiPostParameter(array $esiPostParameter) {
        $this->esiPostParameter = $esiPostParameter;
    }

    /**
     * Resetting field values to defaults
     */
    private function resetFieldsToDefaults() {
        foreach (get_class_vars(get_class($this)) as $field => $defaultValue) {
            $this->$field = $defaultValue;
        }
    }

    /**
     * Get array with response code and body
     *
     * @param array $remoteData
     * @return array
     */
    private function getResponseArray(array $remoteData) {
        return [
            'responseCode' => wp_remote_retrieve_response_code($remoteData),
            'responseHeader' => wp_remote_retrieve_headers($remoteData),
            'responseBody' => wp_remote_retrieve_body($remoteData)
        ];
    }

    /**
     * mapping array to class
     *
     * @param string $jSon
     * @param string $class
     * @param array $array
     * @return object
     */
    public function mapArray(string $jSon, string $class, array $array = []) {
        $returnValue = null;

        if (!is_null($jSon)) {
            $jsonMapper = new Ppfeufer_Theme_EVEOnline_JsonMapper;

            $returnValue = $jsonMapper->mapArray(json_decode($jSon), $array, $class);
        }

        return $returnValue;
    }
}
