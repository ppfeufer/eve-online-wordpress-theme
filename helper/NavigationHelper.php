<?php

namespace WordPress\Themes\EveOnline\Helper;

\defined('ABSPATH') or die();

class NavigationHelper {
	/**
	 * Display page next/previous navigation links.
	 *
	 * @global object $wp_query
	 * @param int $nav_id
	 */
	public static function getContentNav($nav_id) {
		global $wp_query;

		if($wp_query->max_num_pages > 1) {
			?>
			<nav id="<?php echo $nav_id; ?>" class="navigation post-navigation clearfix" role="navigation">
				<h3 class="assistive-text"><?php \_e('Post navigation', 'eve-online'); ?></h3>
				<div class="nav-previous pull-left">
					<?php self::getNextPostsLink(\__('<span class="meta-nav">&larr;</span> Older posts', 'eve-online'), 0, true); ?>
				</div>
				<div class="nav-next pull-right">
					<?php self::getPreviousPostsLink(\__('Newer posts <span class="meta-nav">&rarr;</span>', 'eve-online'), true); ?>
				</div>
			</nav><!-- #<?php echo $nav_id; ?> .navigation -->
			<?php
		} // END if($wp_query->max_num_pages > 1)
	} // END public static function getContentNav($nav_id)

	/**
	 * Retrieves the next posts page link.
	 *
	 * @global int      $paged
	 * @global WP_Query $wp_query
	 *
	 * @param string $label Content for link text.
	 * @param int $max_page Optional. Max pages. Default 0.
	 * @param boolean $echo Return or echo
	 * @param object $wp_query default null, but if another query object should be used here, set it
	 *
	 * @return string|void HTML-formatted next posts page link.
	 */
	public static function getNextPostsLink($label = null, $max_page = 0, $echo = false, $wp_query = null) {
		global $paged;

		if($wp_query === null) {
			global $wp_query;
		}

		if(!$max_page) {
			$max_page = $wp_query->max_num_pages;
		} // END if(!$max_page)

		if(!$paged) {
			$paged = 1;
		} // END if(!$paged)

		$nextpage = \intval($paged) + 1;

		if(null === $label) {
			$label = __('&laquo; Previous Page');
		} // END if(null === $label)

		if(!\is_single() && ($nextpage <= $max_page)) {
			/**
			 * Filters the anchor tag attributes for the next posts page link.
			 *
			 * @since 2.7.0
			 *
			 * @param string $attributes Attributes for the anchor tag.
			 */
			$attr = \apply_filters('previous_posts_link_attributes', '');

			if($echo === true) {
				echo '<a class="btn btn-default" href="' . \next_posts( $max_page, false ) . "\" $attr>" . \preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label) . '</a>';
			} else {
				return '<a class="btn btn-default" href="' . \next_posts( $max_page, false ) . "\" $attr>" . \preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label) . '</a>';
			}
		} // END if(!\is_single() && ($nextpage <= $max_page))
	} // END public static function getNextPostsLink($label = null, $max_page = 0, $echo = false)

	/**
	 * Retrieves the previous posts page link.
	 *
	 * @global int $paged
	 *
	 * @param string $label Optional. Previous page link text.
	 * @param bool $echo Optional. echoing or returning the link.
	 * @return string|void HTML-formatted previous page link.
	 */
	public static function getPreviousPostsLink($label = null, $echo = false) {
		global $paged;

		if(null === $label) {
			$label = __('Next Page &raquo;');
		} // END if(null === $label)

		if(!\is_single() && $paged > 1) {
			/**
			 * Filters the anchor tag attributes for the previous posts page link.
			 *
			 * @since 2.7.0
			 *
			 * @param string $attributes Attributes for the anchor tag.
			 */
			$attr = \apply_filters('next_posts_link_attributes', '');

			if($echo === true) {
				echo '<a class="btn btn-default" href="' . \previous_posts(false) . "\" $attr>". \preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label) .'</a>';
			} else {
				return '<a class="btn btn-default" href="' . \previous_posts(false) . "\" $attr>". \preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label) .'</a>';
			}
		} // END if(!\is_single() && $paged > 1)
	} // END public static function getPreviousPostsLink($label = null, $echo = false)

	public static function getBreadcrumbs($addTexts = true) {
		$home = __('Home', 'eve-online'); // text for the 'Home' link
		$before = '<li class="active">'; // tag before the current crumb
		$sep = '';
		$after = '</li>'; // tag after the current crumb

		if(!\is_home() && !\is_front_page() || \is_paged()) {
			echo '<ul class="breadcrumb">';

			global $post;

			$homeLink = \home_url();

			echo '<li><a href="' . $homeLink . '">' . $home . '</a> ' . $sep . '</li> ';

			if(\is_category()) {
				global $wp_query;

				$cat_obj = $wp_query->get_queried_object();
				$thisCat = $cat_obj->term_id;
				$thisCat = \get_category($thisCat);
				$parentCat = \get_category($thisCat->parent);

				if($thisCat->parent != 0) {
					echo '<li>' . \get_category_parents($parentCat, true, $sep . '</li><li>') . '</li>';
				} // END if($thisCat->parent != 0)

				$format = $before . ($addTexts ? (__('Archive by category ', 'eve-online') . '"%s"') : '%s') . $after;

				echo \sprintf($format, \single_cat_title('', false));
			} elseif(\is_day()) {
				echo '<li><a href="' . \get_year_link(\get_the_time('Y')) . '">' . \get_the_time('Y') . '</a></li> ';
				echo '<li><a href="' . \get_month_link(\get_the_time('Y'), \get_the_time('m')) . '">' . \get_the_time('F') . '</a></li> ';
				echo $before . \get_the_time('d') . $after;
			} elseif(\is_month()) {
				echo '<li><a href="' . \get_year_link(\get_the_time('Y')) . '">' . \get_the_time('Y') . '</a></li> ';
				echo $before . \get_the_time('F') . $after;
			} elseif(\is_year()) {
				echo $before . \get_the_time('Y') . $after;
			} elseif(\is_single() && !\is_attachment()) {
				if(\get_post_type() != 'post') {
					$post_type = \get_post_type_object(\get_post_type());
					$slug = $post_type->rewrite;

					echo '<li><a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a></li> ';
					echo $before . \get_the_title() . $after;
				} else {
					$cat = \get_the_category();
					$cat = $cat[0];

					echo '<li>' . \get_category_parents($cat, true, $sep) . '</li>';
					echo $before . \get_the_title() . $after;
				} // END if(get_post_type() != 'post')
			} elseif(!\is_single() && !\is_page() && \get_post_type() != 'post' && !\is_404()) {
				$post_type = \get_post_type_object(\get_post_type());

				echo $before . $post_type->labels->singular_name . $after;
			} elseif(\is_attachment()) {
				$parent = \get_post($post->post_parent);
				$cat = \get_the_category($parent->ID);
				$cat = (isset($cat['0'])) ? $cat['0'] : '';

				echo (isset($cat['0'])) ? \get_category_parents($cat, true, $sep) : '';
				echo '<li><a href="' . \get_permalink($parent) . '">' . $parent->post_title . '</a></li> ';
				echo $before . \get_the_title() . $after;
			} elseif(\is_page() && !$post->post_parent) {
				echo $before . get_the_title() . $after;
			} elseif(\is_page() && $post->post_parent) {
				$parent_id = $post->post_parent;
				$breadcrumbs = array();

				while($parent_id) {
					$page = \get_page($parent_id);
					$breadcrumbs[] = '<li><a href="' . \get_permalink($page->ID) . '">' . \get_the_title($page->ID) . '</a>' . $sep . '</li>';
					$parent_id = $page->post_parent;
				} // END while($parent_id)

				$breadcrumbs = \array_reverse($breadcrumbs);

				foreach($breadcrumbs as $crumb) {
					echo $crumb;
				} // END foreach($breadcrumbs as $crumb)

				echo $before . \get_the_title() . $after;
			} elseif(\is_search()) {
				$format = $before . ($addTexts ? (\__('Search results for "', 'eve-online') . '"%s"') : '%s') . $after;

				echo \sprintf($format, \get_search_query());
			} elseif(\is_tag()) {
				$format = $before . ($addTexts ? (\__('Posts tagged "', 'eve-online') . '"%s"') : '%s') . $after;

				echo \sprintf($format, \single_tag_title('', false));
			} elseif(\is_author()) {
				global $author;

				$userdata = \get_userdata($author);
				$format = $before . ($addTexts ? (\__('Articles posted by ', 'eve-online') . '"%s"') : '%s') . $after;

				echo \sprintf($format, $userdata->display_name);
			} elseif(\is_404()) {
				echo $before . \__('Error 404', 'eve-online') . $after;
			} // END if(is_category())

			echo '</ul>';
		} // END if(!is_home() && !is_front_page() || is_paged())
	} // END public static function getBreadcrumbs($addTexts = true)
} // END class NavigationHelper
