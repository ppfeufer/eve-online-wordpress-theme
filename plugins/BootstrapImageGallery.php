<?php
/**
 * Class Name: BootstrapImageGallery
 *
 * based on: https://github.com/twittem/wp-bootstrap-gallery
 */

namespace WordPress\Themes\EveOnline\Plugins;

\defined('ABSPATH') or die();

class BootstrapImageGallery {
	public function __construct() {
		$this->init();
	} // END public function __construct()

	public function init() {
		\add_filter('post_gallery', array($this, 'imageGallery'), 10, 2);
	} // END public function init()

	public function imageGallery($content, $attr) {
		global $instance, $post;

		$instance++;

		if(isset($attr['orderby'])) {
			$attr['orderby'] = \sanitize_sql_orderby($attr['orderby']);

			if(!$attr['orderby']) {
				unset($attr['orderby']);
			} // END if(!$attr['orderby'])
		} // END if(isset($attr['orderby']))

		\extract(\shortcode_atts(array(
			'order' => 'ASC',
			'orderby' => 'menu_order ID',
			'id' => $post->ID,
			'itemtag' => 'figure',
			'icontag' => 'div',
			'captiontag' => 'figcaption',
			'columns' => 3,
			'size' => 'thumbnail',
			'include' => '',
			'exclude' => ''
		), $attr));

		$id = \intval($id);

		if('RAND' == $order) {
			$orderby = 'none';
		} // END if('RAND' == $order)

		if($include) {
			$include = \preg_replace('/[^0-9,]+/', '', $include);

			$_attachments = \get_posts(array(
				'include' => $include,
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $order,
				'orderby' => $orderby
			));

			$attachments = array();

			foreach($_attachments as $key => $val) {
				$attachments[$val->ID] = $_attachments[$key];
			} // END foreach($_attachments as $key => $val)
		} elseif($exclude) {
			$exclude = \preg_replace('/[^0-9,]+/', '', $exclude);

			$attachments = \get_children(array(
				'post_parent' => $id,
				'exclude' => $exclude,
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $order,
				'orderby' => $orderby
			));
		} else {
			$attachments = \get_children(array(
				'post_parent' => $id,
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $order,
				'orderby' => $orderby
			));
		} // END if($include)

		if(empty($attachments)) {
			return;
		} // END if(empty($attachments))

		if(\is_feed()) {
			$output = "\n";

			foreach($attachments as $att_id => $attachment) {
				$output .= \wp_get_attachment_link($att_id, $size, true) . "\n";
			} // END foreach($attachments as $att_id => $attachment)

			return $output;
		} // END if(is_feed())

		$itemtag = \tag_escape($itemtag);
		$captiontag = \tag_escape($captiontag);

		$selector = 'image-gallery-' . $instance;
		$output = '<div class="gallery-row">';
		$output .= '<ul class="bootstrap-gallery bootstrap-image-gallery bootstrap-' . $selector . ' clearfix">';


		foreach($attachments as $id => $attachment) {
			$attachment_image = \wp_get_attachment_image($id, 'full');
			$attachment = \wp_prepare_attachment_for_js($id);
	//		$attachment_link = wp_get_attachment_link($id, 'full', !(isset($attr['link']) AND 'file' == $attr['link']));

			$output .= '<li>';
			$output .= '<' . $itemtag . ' class="bootstrap-gallery-image">';
			$output .= $attachment_image;

			if(!empty($attachment['caption'])) {
				$output .= '<' . $captiontag . '>';
				$output .= $attachment['caption'];
				$output .= '</' . $captiontag . '>';
			}

			$output .= '</' . $itemtag . '>' . "\n";
			$output .= '</li>' . "\n";
		} // END foreach($attachments as $id => $attachment)

		$output .= '</ul></div>' . "\n";
		$output .= '<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery("ul.bootstrap-' . $selector . '").bootstrapGallery({
					"classes" : "col-lg-3 col-md-4 col-sm-6 col-xs-12",
					"hasModal" : true
				});
			});
			</script>';

		return $output;
	} // END public function imageGallery($content, $attr)

	/**
	 * Getting pages and Articles that contain a gallery
	 *
	 * @return array
	 */
	public function getGalleryPages() {
		$args = array(
			'posts_per_page' => -1,
			'category' => 0,
			'post_type' => 'any',
			'post_status' => 'publish',
			'orderby' => 'post_date',
			'order' => 'DESC',
			'suppress_filters' => true
		);

		$result = new \WP_Query($args);

		$pattern = \get_shortcode_regex();

		foreach($result->posts as $post) {
			/**
			 * Check if the gallery shortcode has been used
			 */
			if(\has_shortcode($post->post_content, 'gallery')) {
				/**
				 * Check if it is really a gallery and not a hoax, in other
				 * words, if there are really some images in it.
				 */
				if(\preg_match_all('/'. $pattern .'/s', $post->post_content, $matches) && \array_key_exists(2, $matches) && \in_array('gallery', $matches[2])) {
					$keys = \array_keys( $matches[2], 'gallery' );

					foreach($keys as $key) {
						$atts = \shortcode_parse_atts($matches[3][$key]);

						if(\is_array($atts) && \array_key_exists('ids', $atts)) {
							$items[$post->ID] = $post->post_title;
						} // END if(\is_array($atts) && \array_key_exists('ids', $atts))
					} // END foreach($keys as $key)
				} // END if(\preg_match_all('/'. $pattern .'/s', $post->post_content, $matches) && \array_key_exists(2, $matches) && \in_array('gallery', $matches[2]))
			} // END if(has_shortcode($post->post_content, 'gallery'))
		} // END foreach($result->posts as $post)

		\wp_reset_postdata();

		return $items;
	} // END public function getGalleryPages()

	/**
	 * Get all images from a gallery page in an array
	 *
	 * @param int $postID
	 * @return array
	 */
	public function getGalleryImages($postID) {
		$post = \get_post($postID, \OBJECT);

		if(\has_shortcode($post->post_content, 'gallery')) {
			/**
			 * Check if it is really a gallery and not a hoax, in other
			 * words, if there are really some images in it.
			 */
			$pattern = get_shortcode_regex();
			$galleryImages = null;

			if(\preg_match_all('/'. $pattern .'/s', $post->post_content, $matches) && \array_key_exists(2, $matches) && \in_array('gallery', $matches[2])) {
				$keys = \array_keys( $matches[2], 'gallery' );

				foreach($keys as $key) {
					$atts = \shortcode_parse_atts($matches[3][$key]);

					/**
					 * Only add the page to our array if it has some
					 * images in its gallery
					 */
					if(\is_array($atts) && \array_key_exists('ids', $atts)) {
						$images = new \WP_Query(
							array(
								'post_type' => 'attachment',
								'post_status' => 'inherit',
								'post__in' => \explode( ',', $atts['ids'] ),
								'orderby' => 'post__in'
							)
						);

						if($images->have_posts()) {
							foreach($images->posts as $image) {
								$galleryImages[$image->ID] = $image->guid;
							} // END foreach($images->posts as $image)
						} // END if($images->have_posts())

						\wp_reset_query();
					} // END if(\is_array($atts) && \array_key_exists('ids', $atts))
				} // END foreach($keys as $key)
			} // END if(\preg_match_all('/'. $pattern .'/s', $post->post_content, $matches) && \array_key_exists(2, $matches) && \in_array('gallery', $matches[2]))
		} // END if(has_shortcode($post->post_content, 'gallery'))

		return $galleryImages;
	} // END public function getGalleryImages($postID)
} // END class BootstrapImageGallery

new BootstrapImageGallery();