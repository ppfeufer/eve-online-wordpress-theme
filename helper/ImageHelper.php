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
					} // END if($original_file === $file || \in_array($file, $cropped_image_files))
				} // END foreach($query->posts as $post_id)
			} // END if($query->have_posts())
		} // END if(\strpos($url, $dir['baseurl'] . '/') !== false)

		return $attachment_id;
	} // END public static function getAttachmentId($url)

	/**
	 * Getting the cached URL for a remote image
	 *
	 * @param string $cacheType The subdirectory in the image cache filesystem
	 * @param string $remoteImageUrl The URL for the remote image
	 * @return string The cached Image URL
	 */
	public static function getLocalCacheImageUriForRemoteImage($cacheType, $remoteImageUrl = null) {
		$themeOptions = \get_option('eve_theme_options', ThemeHelper::getThemeDefaultOptions());
		$returnValue = $remoteImageUrl;

		// Check if we should use image cache
		if(!empty($themeOptions['cache']['remote-image-cache'])) {
			$explodedImageUrl = \explode('/', $remoteImageUrl);
			$imageFilename = \end($explodedImageUrl);
			$cachedImage = CacheHelper::getImageCacheUri() . $cacheType . '/' . $imageFilename;

			// if we don't have the image cached already
			if(CacheHelper::checkCachedImage($cacheType, $imageFilename, $themeOptions['remote_image_cache_time']) === false) {
				/**
				 * Check if the content dir is writable and cache the image.
				 * Otherwise set the remote image as return value.
				 */
				if(\is_dir(CacheHelper::getImageCacheDir() . $cacheType) && \is_writable(CacheHelper::getImageCacheDir() . $cacheType)) {
					if(CacheHelper::cacheRemoteImageFile($cacheType, $remoteImageUrl) === true) {
						$returnValue = $cachedImage;
					} // END if(CacheHelper::cacheRemoteImageFile($cacheType, $remoteImageUrl) === true)
				} // END if(\is_dir(CacheHelper::getImageCacheDir() . $cacheType) && \is_writable(CacheHelper::getImageCacheDir() . $cacheType))
			} else {
				$returnValue = $cachedImage;
			} // END if(CacheHelper::checkCachedImage($cacheType, $imageName) === false)
		} // END if(!empty($themeOptions['cache']['remote-image-cache']))

		return $returnValue;
	} // END public static function getLocalCacheImageUri($cacheType = null, $remoteImageUrl = null)
} // END class ImageHelper
