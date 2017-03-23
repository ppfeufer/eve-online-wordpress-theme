<?php
/**
 * Latest Blog Posts plugin
 * Displays the latest Blog Posts via Shortcode [latestblogposts number=""]
 */

namespace WordPress\Themes\EveOnline\Plugins;

use WordPress\Themes\EveOnline;

\defined('ABSPATH') or die();

class LatestBlogPosts {
	public function __construct() {
		$this->registerShortcodes();
	} // END public function __construct()

	public function registerShortcodes() {
		\add_shortcode('latestblogposts', array($this, 'shortcodeLatestBlogPosts'));
	} // END public function registerShortcodes()

	public function shortcodeLatestBlogPosts($attributes) {
		$args = \shortcode_atts(
			array(
				'number' => EveOnline\Helper\PostHelper::getContentColumnCount(),
				'classes' => EveOnline\Helper\PostHelper::geLoopContentClasses(),
				'headline_type' => 'h2',
				'headline_text' => ''
			),
			$attributes
		);

		$number = $args['number'];
		$classes = $args['classes'];

		$queryArgs = array(
			'posts_per_page' => $number,
			'post_type' => 'post',
			'post_status' => 'publish',
			'orderby' => 'post_date',
			'order' => 'DESC',
			'suppress_filters' => true,
			'ignore_sticky_posts' => true
		);
		/* @var $latestPosts object */
		$latestPosts = new \WP_Query($queryArgs);

		if($latestPosts->have_posts() && is_page()) {
			\ob_start();

			if(!empty($args['headline_text'])) {
				echo '<' . $args['headline_type'] . ' class="latest-blogposts-headline">' . $args['headline_text'] . '</' . $args['headline_type'] . '>';
				echo '<div class="latest-blogposts-headline-decoration"><div class="latest-blogposts-headline-decoration-inside"></div></div>';
			} // END if(!empty($args['headline_text']))

			$blogPage = \get_option('page_for_posts');
			$uniqueID = \uniqid();

			echo '<div class="gallery-row row">';
			echo '<ul class="bootstrap-gallery bootstrap-latest-post-loop bootstrap-latest-post-loop-' . $uniqueID . ' clearfix">';

			while($latestPosts->have_posts()) {
				$latestPosts->the_post();
				echo '<li class="latest-post-article">';

				\get_template_part('content', \get_post_format($latestPosts->post_id));
				echo '</li>';
			} // END while($the_query->have_posts())

			echo '</ul>';
			echo '</div>';
			echo '<div>'
				. '	<a class="news-more-link" href="' . \esc_url(\get_permalink($blogPage)) . '">'
				. '		<span class="news-show-all read-more">' . \__('Show all article', 'eve-online') . '</span>'
				. '	</a>'
				. '</div>';

			echo '<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery("ul.bootstrap-latest-post-loop-' . $uniqueID . '").bootstrapGallery({
							"classes" : "' . $classes . '",
							"hasModal" : false
						});
					});
					</script>';

			$articleLoop = \ob_get_contents();

			\ob_end_clean();
		} // END if($the_query->have_posts())

		\wp_reset_postdata();

		return $articleLoop;
	} // END public function shortcodeLatestBlogPosts($attributes, $content = null)
} // END class LatestBlogPosts
