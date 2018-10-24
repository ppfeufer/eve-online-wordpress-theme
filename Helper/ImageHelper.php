<?php

namespace WordPress\Themes\EveOnline\Helper;

\defined('ABSPATH') or die();

class ImageHelper {
    public static function getAttachmentId($url) {
        $attachment_id = 0;
        $dir = \wp_upload_dir();

        if(\strpos($url, $dir['baseurl'] . '/') !== false) { // Is URL in uploads directory?
            $file = \basename($url);
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

            $query = new \WP_Query($query_args);

            if($query->have_posts()) {
                foreach($query->posts as $post_id) {
                    $meta = \wp_get_attachment_metadata($post_id);
                    $original_file = \basename($meta['file']);
                    $cropped_image_files = \wp_list_pluck($meta['sizes'], 'file');

                    if($original_file === $file || \in_array($file, $cropped_image_files)) {
                        $attachment_id = $post_id;

                        break;
                    }
                }
            }
        }

        return $attachment_id;
    }
}
