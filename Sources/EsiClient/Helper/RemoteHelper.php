<?php

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Helper;

use WP_Error;

/**
 * Class RemoteHelper
 *
 * A helper class to facilitate remote data retrieval using HTTP GET and POST methods.
 *
 * @package Ppfeufer\Theme\EVEOnline\EsiClient\Helper
 */
class RemoteHelper {
    /**
     * Retrieve data from a remote source using HTTP GET or POST methods.
     *
     * This method allows fetching data from a remote URL using either the GET or POST HTTP method.
     * It supports passing parameters for both methods and handles the response accordingly.
     *
     * @param string $url The URL to fetch data from.
     * @param string $method The HTTP method to use ('get' or 'post'). Defaults to 'get'.
     * @param array $parameter Parameters to include in the request. For GET, they are appended as query parameters.
     *                         For POST, they are included in the request body.
     * @return array|WP_Error|null Returns the response data as an array, a WP_Error on failure, or null if no response.
     */
    public function getRemoteData(string $url, string $method = 'get', array $parameter = []): array|null|WP_Error {
        $returnValue = null;
        $params = '';

        switch ($method) {
            case 'get':
                // Append parameters as a query string for GET requests.
                if (count($parameter) > 0) {
                    $params = '?' . http_build_query($parameter);
                }

                // Perform the GET request.
                $remoteData = wp_remote_get($url . $params);
                break;

            case 'post':
                // Perform the POST request with JSON-encoded parameters.
                $remoteData = wp_remote_post($url, [
                    'headers' => [
                        'Content-Type' => 'application/json; charset=utf-8'
                    ],
                    'body' => json_encode($parameter),
                    'method' => 'POST'
                ]);
                break;
        }

        // Check if the response is an array and assign it to the return value.
        if (is_array($remoteData)) {
            $returnValue = $remoteData;
        }

        return $returnValue;
    }
}
