<?php
/**
 * Security
 *
 * Hardening our WordPress inatallation by removing non needed
 * stuff from the generated HTML
 *
 * @todo make it configurable
 */
namespace WordPress\Themes\EveOnline\Security;

\defined('ABSPATH') or die();

class WordPressSecurity {
	public function __construct() {
		$this->removeGeneratorName();
		$this->removeBlogClientLink();
		$this->removeLiveWriterManifest();
		$this->removeOembedLink();
		$this->removeRestApiLink();
		$this->removeXPingback();
		$this->removeEmojis();
		$this->removeRelationalLinks();
		$this->removeShortlink();
		$this->removeFeedLinks();
	} // END public function __construct()

	/**
	 * removing <meta name="generator" content="WordPress 4.5.2" />
	 */
	public function removeGeneratorName() {
		\remove_action('wp_head', 'wp_generator');
	} // END public function removeGeneratorName()

	/**
	 * removing <link rel="EditURI" type="application/rsd+xml" title="RSD" href="http://link.net/xmlrpc.php?rsd" />
	 */
	public function removeBlogClientLink() {
		\remove_action('wp_head', 'rsd_link');
	} // END public function removeBlogClientLink()

	/**
	 * removing <link rel="wlwmanifest" type="application/wlwmanifest+xml" href="http://link.net/wp-includes/wlwmanifest.xml" />
	 */
	public function removeLiveWriterManifest() {
		\remove_action('wp_head', 'wlwmanifest_link');
	} // END public function removeLiveWriterManifest()

	/**
	 * removing all oEmbed stuff to embed our own site
	 */
	public function removeOembedLink() {
		\remove_action('wp_head', 'wp_oembed_add_discovery_links');
		\remove_action('rest_api_init', 'wp_oembed_register_route');
		\remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
		\remove_action('wp_head', 'wp_oembed_add_host_js');
	} // END public function removeOembedLink()

	/**
	 * removing <link rel='https://api.w.org/' href='http://link.net/wp-json/' />
	 */
	public function removeRestApiLink() {
		\remove_action('wp_head', 'rest_output_link_wp_head', 10);
		\remove_action('template_redirect', 'rest_output_link_header', 11, 0);
	} // END public function removeRestApiLink()

	/**
	 * removing X-Pingback
	 */
	public function removeXPingback() {
		\add_action('wp', function() {
			\header_remove('X-Pingback');
		}, 1000);
	} // END public function removeXPingback()

	/**
	 * removing Emojis
	 */
	public function removeEmojis() {
		\remove_action('wp_head', 'print_emoji_detection_script', 7);
		\remove_action('wp_print_styles', 'print_emoji_styles');
	} // END public function removeEmojis()

	/**
	 * removing relational next/prev links
	 */
	public function removeRelationalLinks() {
		\remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
	} // END public function removeRelationalLinks()

	/**
	 * removing shortlink
	 */
	public function removeShortlink() {
		\remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	} // END public function removeShortlink()

	/**
	 * removing RSS feeds
	 */
	public function removeFeedLinks() {
		\remove_action('wp_head', 'feed_links', 2);
		\remove_action('wp_head', 'feed_links_extra', 3);
	} // END public function removeFeedLinks()
} // END class Security

new WordPressSecurity();