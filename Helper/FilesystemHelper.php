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

namespace WordPress\Themes\EveOnline\Helper;

\defined('ABSPATH') or die();

class FilesystemHelper {
    /**
     * Removing either the content of a directory or the directory recursively
     *
     * @param string $directory
     * @param boolean $removeDirectory Remove the given Directoy as well? (true or false)
     */
    public static function deleteDirectoryRecursive($directory, $removeDirectory = false) {
        // open dir and save it in a handle
        $entry = \opendir($directory);

        // read content of $dir and save it in $file
        while($file = \readdir($entry)) {
            $path = $directory . '/' . $file;

            if ($file !== '.' && $file !== '..') {
                // check if handle is a dir or a file
                if(\is_dir($path)) {
                    self::deleteDirectoryRecursive($path);
                } else {
                    \unlink($path);
                }
            }
        }

        // close dir handle
        \closedir($entry);

        if($removeDirectory === true) {
            //remove dir
            rmdir($directory);
        }
    }
}
