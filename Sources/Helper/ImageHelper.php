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
use WP_Query;
use WP_Post;

class ImageHelper extends GenericSingleton {
    public function getAttachmentId($url): int|WP_Post {
        $attachment_id = 0;
        $dir = wp_upload_dir();

        if (str_contains($url, $dir['baseurl'] . '/')) { // Is URL in uploads directory?
            $file = basename($url);
            $query_args = [
                'post_type' => 'attachment',
                'post_status' => 'inherit',
                'fields' => 'ids',
                'meta_query' => [
                    [
                        'value' => $file,
                        'compare' => 'LIKE',
                        'key' => '_wp_attachment_metadata',
                    ],
                ]
            ];

            $query = new WP_Query($query_args);

            if ($query->have_posts()) {
                foreach ($query->posts as $post_id) {
                    $meta = wp_get_attachment_metadata($post_id);
                    $original_file = basename($meta['file']);
                    $cropped_image_files = wp_list_pluck($meta['sizes'], 'file');

                    if ($original_file === $file || in_array($file, $cropped_image_files)) {
                        $attachment_id = $post_id;

                        break;
                    }
                }
            }
        }

        return $attachment_id;
    }
}
