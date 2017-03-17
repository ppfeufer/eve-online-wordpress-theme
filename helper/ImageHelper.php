<?php

namespace WordPress\Themes\EveOnline\Helper;

\defined('ABSPATH') or die();

class ImageHelper {
	public static function getAttachmentId($url) {
		$attachment_id = 0;
		$dir = \wp_upload_dir();

		if(\strpos($url, $dir['baseurl'] . '/') !== false) { // Is URL in uploads directory?
			$file = \basename($url);
			$query_args = array(
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'fields' => 'ids',
				'meta_query' => array(
					array(
						'value' => $file,
						'compare' => 'LIKE',
						'key' => '_wp_attachment_metadata',
					),
				)
			);

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

	public static function getImageCacheDir() {
		return \trailingslashit(ThemeHelper::getThemeCacheDir() . '/images');
	} // END public static function getImageCacheDir()

	public static function getImageCacheUri() {
		return \trailingslashit(ThemeHelper::getThemeCacheUri() . '/images');
	} // END public static function getImageCacheUri()

	/**
	 * Chek if a remote image has been cached locally
	 *
	 * @param string $cacheType The subdirectory in the image cache filesystem
	 * @param string $imageName The image file name
	 * @return boolean true or false
	 */
	public static function checkCachedImage($cacheType = null, $imageName = null) {
		$cacheDir = \trailingslashit(self::getImageCacheDir() . $cacheType);

		if(\file_exists($cacheDir . $imageName)) {
			// check if the file is older than 2 hrs
			if(\time() - \filemtime($cacheDir . $imageName) > 2 * 3600) {
				\unlink($cacheDir . $imageName);

				$returnValue = false;
			} else {
				$returnValue = true;
			} // END if(\time() - \filemtime($cacheDir . $imageName) > 2 * 3600)
		} else {
			$returnValue = false;
		} // END if(\file_exists($cacheDir . $imageName))

		return $returnValue;
	} // END public static function checkCachedImage($cacheType = null, $imageName = null)

	/**
	 * Cachng a remote image locally
	 *
	 * @param string $cacheType The subdirectory in the image cache filesystem
	 * @param string $remoteImageUrl The URL for the remote image
	 */
	public static function cacheRemoteImageFile($cacheType = null, $remoteImageUrl = null) {
		$cacheDir = \trailingslashit(self::getImageCacheDir() . $cacheType);
		$explodedImageUrl = \explode('/', $remoteImageUrl);
		$imageFilename = \end($explodedImageUrl);
		$explodedImageFilename = \explode('.', $imageFilename);
		$extension = \end($explodedImageFilename);

		// make sure its an image
		if($extension === 'gif' || $extension === 'jpg' || $extension === 'jpeg' || $extension === 'png') {
			// get the remote image
			$get = \wp_remote_get($remoteImageUrl);
			$imageToFetch = \wp_remote_retrieve_body($get);

//			$wpFileSystem = new \WP_Filesystem_Direct(null);
//			$wpFileSystem->put_contents($cacheDir . $imageFilename, $imageToFetch, 0755);

			$localImageFile = \fopen($cacheDir . $imageFilename, 'w+');

			\chmod($cacheDir . $imageFilename,0755);
			\fwrite($localImageFile, $imageToFetch);
			\fclose($localImageFile);
		} // END if($extension === 'gif' || $extension === 'jpg' || $extension === 'jpeg' || $extension === 'png')
	} // END public static function cacheRemoteImageFile($cacheType = null, $remoteImageUrl = null)

	/**
	 * Getting the cached URL for a remote image
	 *
	 * @param string $cacheType The subdirectory in the image cache filesystem
	 * @param string $remoteImageUrl The URL for the remote image
	 * @return string The cached Image URL
	 */
	public static function getLocalCacheImageUriForRemoteImage($cacheType = null, $remoteImageUrl = null) {
		$explodedImageUrl = \explode('/', $remoteImageUrl);
		$imageFilename = \end($explodedImageUrl);
		$returnValue = self::getImageCacheUri() . $cacheType . '/' . $imageFilename;

		// if we don't have the image cached already
		if(self::checkCachedImage($cacheType, $imageFilename) === false) {
			/**
			 * Check if the content dir is writable and cache the image.
			 * Otherwise set the remote image as return value.
			 */
			if(\is_dir(self::getImageCacheDir() . $cacheType) && \is_writable(self::getImageCacheDir() . $cacheType)) {
				self::cacheRemoteImageFile($cacheType, $remoteImageUrl);
			} else {
				$returnValue = $remoteImageUrl;
			} // END if(\is_writable(\WP_CONTENT_DIR))
		} // END if(ImageHelper::checkCachedImage($cacheType, $imageName) === false)

		return $returnValue;
	} // END public static function getLocalCacheImageUri($cacheType = null, $remoteImageUrl = null)
} // END class ImageHelper
