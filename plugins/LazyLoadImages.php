<?php
namespace WordPress\Themes\EveOnline\Plugins;

\defined('ABSPATH') or die();

class LazyLoadImages {
	protected $enabled = true;

	public function __construct($init = false) {
		if($init === true) {
			\add_action('init', array($this, 'init'));
		} // END if($init === true)
	} // END public function __construct($init = false)

	public function init() {
		if(\is_admin()) {
			return;
		} // END if(is_admin())

		if(!\apply_filters('lazyload_is_enabled', true)) {
			$this->enabled = false;

			return;
		} // END if(!apply_filters('lazyload_is_enabled', true))

		\add_action('wp_head', array($this, 'setupFilter'), 9999); // we don't really want to modify anything in <head> since it's mostly all metadata, e.g. OG tags
	} // END public function init()

	public function setupFilter() {
		\add_filter('the_content', array($this, 'addImagePlaceholder'), 99); // run this later, so other content filters have run, including image_add_wh on WP.com
		\add_filter('post_thumbnail_html', array($this, 'addImagePlaceholder'), 11);
		\add_filter('get_avatar', array($this, 'addImagePlaceholder'), 11);
	} // END public function setupFilter()

	public function addImagePlaceholder($content) {
		if(!$this->isEnabled()) {
			return $content;
		} // END if(!$this->isEnabled())

		// Don't lazyload for feeds, previews, mobile
		if(\is_feed() || \is_preview()) {
			return $content;
		} // END if(is_feed() || is_preview())

		// Don't lazy-load if the content has already been run through previously
		if(false !== \strpos($content, 'data-lazy-src')) {
			return $content;
		} // END if(false !== strpos($content, 'data-lazy-src'))

		// processing images
		$content = preg_replace_callback('#<(img)([^>]+?)(>(.*?)</\\1>|[\/]?>)#si', array(__CLASS__, 'processImage'), $content);

		return $content;
	} // END public function addImagePlaceholder($content)

	public function processImage($matches) {
		// In case you want to change the placeholder image
		$placeholder_image = \apply_filters('lazyload_images_placeholder_image', $this->getUrl('images/1x1.trans.gif'));

		$old_attributes_str = $matches[2];
		$old_attributes = \wp_kses_hair($old_attributes_str, \wp_allowed_protocols());

		if(empty($old_attributes['src'])) {
			return $matches[0];
		} // END if(empty($old_attributes['src']))

		$image_src = $old_attributes['src']['value'];

		// Remove src and lazy-src since we manually add them
		$new_attributes = $old_attributes;
		unset($new_attributes['src'], $new_attributes['data-lazy-src']);

		$new_attributes_str = $this->buildAttributeString($new_attributes);

		return \sprintf('<img src="%1$s" data-lazy-src="%2$s" %3$s><noscript>%4$s</noscript>', \esc_url($placeholder_image), \esc_url($image_src), $new_attributes_str, $matches[0]);
	} // END public function processImage($matches)

	private function buildAttributeString($attributes) {
		$string = array();

		foreach($attributes as $name => $attribute) {
			$value = $attribute['value'];

			if('' === $value) {
				$string[] = \sprintf('%s', $name);
			} else {
				$string[] = \sprintf('%s="%s"', $name, \esc_attr($value));
			} // END if('' === $value)
		} // END foreach($attributes as $name => $attribute)

		return \implode( ' ', $string );
	} // END private function buildAttributeString($attributes)

	public function isEnabled() {
		return $this->enabled;
	} // END public function isEnabled()

	public function getUrl($path = '') {
		return \get_stylesheet_directory_uri() . '/plugins/' . $path;
	} // END public function getUrl($path = '')
} // END class LazyLoadImages

new LazyLoadImages(true);

function lazyload_images_add_placeholders($content) {
	$lazyLoader = new LazyLoadImages();

	return $lazyLoader->addImagePlaceholder($content);
} // END function lazyload_images_add_placeholders($content)