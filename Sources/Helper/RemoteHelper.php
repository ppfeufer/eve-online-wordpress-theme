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

namespace Ppfeufer\Theme\EVEOnline\Helper;

use Ppfeufer\Theme\EVEOnline\Singletons\GenericSingleton;

class RemoteHelper extends GenericSingleton {
    /**
     * Getting data from a remote source
     *
     * @param string $url
     * @param array $parameter
     * @return mixed
     */
    public function getRemoteData($url, array $parameter = []) {
        $params = '';

        if (\count($parameter) > 0) {
            $params = '?' . \http_build_query($parameter);
        }

        $remoteUrl = $url . $params;
        $get = wp_remote_get($remoteUrl);

        return wp_remote_retrieve_body($get);
    }
}
