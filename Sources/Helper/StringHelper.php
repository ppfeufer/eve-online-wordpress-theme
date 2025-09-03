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

/**
 * Helper for all stuff we may do with strings that is not covered by WordPress
 */

namespace Ppfeufer\Theme\EVEOnline\Helper;

class StringHelper {
    public static function cutString($string, $pos): string {
        $string = strip_tags($string);

        if ($pos < strlen($string)) {
            $text = substr($string, 0, $pos);

            if (($strrpos = strrpos($text, ' ')) !== false) {
                $text = substr($text, 0, $strrpos);
            }

            $string = $text . ' [...]';
        }

        return $string;
    }

    /**
     * Make a string camelCase
     *
     * @param string $string
     * @param boolean $ucFirst
     * @param array $noStrip
     * @return string
     */
    public static function camelCase(string $string, bool $ucFirst = false, array $noStrip = []): string {
        // First we make sure all is lower case
        $string = strtolower($string);

        // non-alpha and non-numeric characters become spaces
        $string = preg_replace('/[^a-z0-9' . implode('', $noStrip) . ']+/i', ' ', $string);
        $string = trim($string);

        // uppercase the first character of each word
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);

        if ($ucFirst === false) {
            $string = lcfirst($string);
        }

        return $string;
    }

    /**
     * converts a hex color string into an array with it's respective rgb(a) values
     *
     * @param string $hex
     * @param string $alpha
     * @return array
     */
    public static function hextoRgb(string $hex, string $alpha = ''): array {
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) === 6) {
            $rgb['r'] = hexdec(substr($hex, 0, 2));
            $rgb['g'] = hexdec(substr($hex, 2, 2));
            $rgb['b'] = hexdec(substr($hex, 4, 2));
        } elseif (strlen($hex) === 3) {
            $rgb['r'] = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $rgb['g'] = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $rgb['b'] = hexdec(str_repeat(substr($hex, 2, 1), 2));
        } else {
            $rgb['r'] = '0';
            $rgb['g'] = '0';
            $rgb['b'] = '0';
        }

        if ($alpha !== '') {
            $rgb['a'] = $alpha;
        }

        return $rgb;
    }

    /**
     * Encoding a string for E-Mail obfuscation
     *
     * @param string $string
     * @return string
     * @throws \Random\RandomException
     */
    public static function encodeMailString(string $string): string {
        $chars = str_split($string);
        $seed = random_int(0, (int)abs(crc32($string) / strlen($string)));

        foreach ($chars as $key => $char) {
            $ord = ord($char);

            if ($ord < 128) { // ignore non-ascii chars
                $r = ($seed * (1 + $key)) % 100; // pseudo "random function"

                if ($r > 60 && $char !== '@') {
                    // plain character (not encoded), if not @-sign
                } elseif ($r < 45) {
                    $chars[$key] = '&#x' . dechex($ord) . ';'; // hexadecimal
                } else {
                    $chars[$key] = '&#' . $ord . ';'; // decimal (ascii)
                }
            }
        }

        return implode('', $chars);
    }
}
