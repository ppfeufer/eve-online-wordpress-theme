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

namespace Ppfeufer\Theme\EVEOnline\Plugins;

use Ppfeufer\Theme\EVEOnline\Helper\ThemeHelper;

class HtmlMinify {
    // Settings
    protected bool $compress_css = true;
    protected bool $compress_js = true;
    protected bool $info_comment = true;
    protected bool $remove_comments = true;

    // Variables
    protected string $html;

    public function __construct($html) {
        if (!empty($html)) {
            $this->parseHTML($html);
        }
    }

    public function __toString() {
        return $this->html;
    }

    protected function bottomComment($raw, $compressed): string {
        $raw = strlen($raw);
        $compressed = strlen($compressed);
        $savings = ($raw - $compressed) / $raw * 100;
        $savings = round($savings, 2);

        return '<!--HTML compressed, size saved ' . $savings . '%. From ' . $raw . ' bytes, now ' . $compressed . ' bytes-->';
    }

    protected function minifyHTML($html): string {
        // Tese have to be removed right away ....
        if ($this->remove_comments) {
            // Remove any HTML comments, except MSIE conditional comments
            $html = preg_replace('/<!--(?!\s*(?:\[if [^]]+]|<!|>))(?:(?!-->).)*-->/s', '', $html);

            // Remove any JS single line comments starting with //
            $html = preg_replace('/\/\/ (.*)\n/', ' ', $html);
        }

        $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);

        $overriding = false;
        $raw_tag = false;
        // Variable reused for output
        $html = '';

        foreach ($matches as $token) {
            $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;

            $content = $token[0];

            if (is_null($tag)) {
                if (!empty($token['script'])) {
                    $strip = $this->compress_js;
                } elseif (!empty($token['style'])) {
                    $strip = $this->compress_css;
                } elseif ($content === '<!--wp-html-compression no compression-->') {
                    $overriding = !$overriding;

                    // Don't print the comment
                    continue;
                } elseif ($this->remove_comments) {
                    if (!$overriding && $raw_tag !== 'textarea') {
                        // Remove any HTML comments, except MSIE conditional comments
                        $content = preg_replace('/<!--(?!\s*(?:\[if [^]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);

                        // Remove any JS single or multiline comments like /* comment */
                        $content = preg_replace('/\/\*(.*)\*\//', ' ', $content);
                    }
                }
            } elseif ($tag === 'pre' || $tag === 'textarea') {
                $raw_tag = $tag;
            } elseif ($tag === '/pre' || $tag === '/textarea') {
                $raw_tag = false;
            } elseif ($raw_tag || $overriding) {
                $strip = false;
            } else {
                $strip = true;

                // Remove any empty attributes, except:
                // action, alt, content, src
                $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);

                // Remove any space before the end of self-closing XHTML tags
                // JavaScript excluded
                $content = str_replace(' />', '/>', $content);
            }

            if ($strip) {
                $content = $this->removeWhiteSpace($content);
            }

            $html .= $content;
        }

        return $html;
    }

    public function parseHTML($html): void {
        $this->html = $this->minifyHTML($html);

        if ($this->info_comment) {
            $this->html .= "\n" . $this->bottomComment($html, $this->html);
        }
    }

    protected function removeWhiteSpace($str): array|string {
        $str = str_replace(['\t', '\n', '\r'], [' ', '', ''], $str);

        while (str_contains($str, '  ')) {
            $str = str_replace('  ', ' ', $str);
        }

        return $str;
    }
}

// phpcs:disable
function eve_html_compression_finish($html): HtmlMinify {
    return new HtmlMinify($html);
}

function eve_html_compression_start(): void {
    ob_start('\\WordPress\\Themes\\EveOnline\\Plugins\\eve_html_compression_finish');
}

$themeOptions = get_option('eve_theme_options', ThemeHelper::getInstance()->getThemeDefaultOptions());

if (!empty($themeOptions['minify_html_output']['yes'])) {
    add_action('get_header', '\\WordPress\\Themes\\EveOnline\\Plugins\\eve_html_compression_start');
}
// phpcs:enable
