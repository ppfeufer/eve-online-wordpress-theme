<?php
/**
 * Class Name: BootstrapMenuWalker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
namespace WordPress\Themes\EveOnline\Addons;

use WordPress\Themes\EveOnline;

\defined('ABSPATH') or die();

class BootstrapMenuWalker extends \Walker_Nav_Menu {
	private $themeOptions = null;
	private $eveApi = null;

	public function __construct() {
		$this->themeOptions = \get_option('eve_theme_options', EveOnline\Helper\ThemeHelper::getThemeDefaultOptions());
		$this->eveApi = new EveOnline\Helper\EveApiHelper;
	} // END public function __construct()

	/**
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	public function start_lvl(&$output, $depth = 0, $args = array()) {
		$indent = \str_repeat("\t", $depth);
		$output .= "\n" . $indent . '<ul role="menu" class="dropdown-menu clearfix">' . "\n";
	} // END public function start_lvl(&$output, $depth = 0, $args = array())

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */
	public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		$indent = ($depth) ? \str_repeat("\t", $depth) : '';

		/**
		 * Dividers, Headers or Disabled
		 * =============================
		 * Determine whether the item is a Divider, Header, Disabled or regular
		 * menu item. To prevent errors we use the strcasecmp() function to so a
		 * comparison that is not case sensitive. The strcasecmp() function returns
		 * a 0 if the strings are equal.
		 */
		if(\strcasecmp($item->attr_title, 'divider') == 0 && $depth === 1) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} else if(\strcasecmp($item->title, 'divider') == 0 && $depth === 1) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} else if(\strcasecmp($item->attr_title, 'dropdown-header') == 0 && $depth === 1) {
			$output .= $indent . '<li role="presentation" class="dropdown-header">' . \esc_attr($item->title);
		} else if(\strcasecmp($item->attr_title, 'disabled') == 0) {
			$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . \esc_attr($item->title) . '</a>';
		} else {
			$class_names = $value = '';
			$classes = empty($item->classes) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;
			$classes[] = 'post-item-' . $item->object_id;
			$class_names = \join(' ', \apply_filters('nav_menu_css_class', \array_filter($classes), $item, $args));

			if($args->has_children) {
				switch($depth) {
					// first level
					case '0':
						$class_names .= ' dropdown';
						break;

					// next levels
					default:
						$class_names .= ' dropdown-submenu';
						break;
				} // END switch($depth)
			} // END if($args->has_children)

			if(in_array('current-menu-item', $classes)) {
				$class_names .= ' active';
			} // END if(in_array('current-menu-item', $classes))

			// let's check if a page actually has content ...
			$hasContent = true;
			if($item->post_parent !== 0 && EveOnline\Helper\PostHelper::hasContent($item->object_id) === false) {
				$hasContent = false;
				$class_names .= ' no-post-content';
			} // END if($item->post_parent !== 0 && EveOnline\eve_post_has_content($item->object_id) === false)

			$class_names = $class_names ? ' class="' . \esc_attr($class_names) . '"' : '';

			$id = \apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
			$id = $id ? ' id="' . \esc_attr($id) . '"' : '';

			$output .= $indent . '<li' . $id . $value . $class_names . '>';

			$atts = array();
			$atts['title'] = !empty($item->title) ? $item->title : '';
			$atts['target'] = !empty($item->target) ? $item->target : '';
			$atts['rel'] = !empty($item->xfn) ? $item->xfn : '';

			// If item has_children add atts to a.
			if($args->has_children && $depth === 0) {
				$atts['href'] = !empty($item->url) ? $item->url : '';
				$atts['data-toggle'] = 'dropdown';
				$atts['class'] = 'dropdown-toggle';
			} else {
				$atts['href'] = !empty($item->url) ? $item->url : '';
			} // END if($args->has_children && $depth === 0)

			$atts = \apply_filters('nav_menu_link_attributes', $atts, $item, $args);

			$attributes = '';
			foreach($atts as $attr => $value) {
				if(!empty($value)) {
					if($attr === 'href') {
						$value = \esc_url($value);

						// remove the link of no description is available
						if($hasContent === false) {
							$value = '#';
						} // END if($hasContent === false)
					} else {
						$value = \esc_attr($value);
					} // END if($attr === 'href')

					if($attr === 'title') {
						// change the title if no description is available
						if($hasContent === false) {
							$value .= ' ' . __('(No description available)', 'eve-online');
						} // END if($hasContent === false)
					} // END if($attr === 'title')

					$attributes .= ' ' . $attr . '="' . $value . '"';
				} // END if(!empty($value))
			} // END foreach($atts as $attr => $value)

			$item_output = $args->before;

			/**
			 * Corp Logos
			 */
			$eve_page_corp_eve_ID = \get_post_meta($item->object_id, 'eve_page_corp_eve_ID', true);
			if($eve_page_corp_eve_ID) {
				if(isset($this->themeOptions['show_corp_logos']['show'])) {
//					$corpLogoPath = $this->eveApi->getImageServerEndpoint('corporation') . $eve_page_corp_eve_ID . '_32.png';
					$corpLogoPath = EveOnline\Helper\ImageHelper::getLocalCacheImageUriForRemoteImage('corporation', $this->eveApi->getImageServerEndpoint('corporation') . $eve_page_corp_eve_ID . '_32.png');

					$item_output .= '<a' . $attributes . '><span class="corp-' . \sanitize_title($item->title) . ' ' . \esc_attr($item->attr_title) . ' corp-eveID-' . $eve_page_corp_eve_ID . '"><img src="' . $corpLogoPath . '" width="24" height="24" alt="' . $item->title . '"></span>&nbsp;';
				} else {
					$item_output .= '<a' . $attributes . '>';
				} // END if(isset($this->themeOptions['show_corp_logos']['show']))
			} else {
				/**
				 * Glyphicons
				 * ==========================
				 * Since the the menu item is NOT a Divider or Header we check the see
				 * if there is a value in the attr_title property. If the attr_title
				 * property is NOT null we apply it as the class name for the glyphicon.
				 */
				if(!empty($item->attr_title)) {
					$item_output .= '<a' . $attributes . '><span class="glyphicon ' . \esc_attr($item->attr_title) . '"></span>&nbsp;';
				} else {
					$item_output .= '<a' . $attributes . '>';
				} // END if(!empty($item->attr_title))
			} // END if($eve_page_corp_eve_ID)

			$item_output .= $args->link_before . \apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
			$item_output .= ( $args->has_children && 0 === $depth ) ? ' <span class="caret"></span></a>' : '</a>';
			$item_output .= $args->after;

			$output .= \apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
		} // END if(strcasecmp($item->attr_title, 'divider') == 0 && $depth === 1)
	} // END public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @see Walker::start_el()
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array $children_elements List of elements to continue traversing.
	 * @param int $max_depth Max depth to traverse.
	 * @param int $depth Depth of current element.
	 * @param array $args
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output) {
		if(!$element) {
			return;
		} // END if(!$element)

		$id_field = $this->db_fields['id'];

		// Display this element.
		if(\is_object($args[0])) {
			$args[0]->has_children = !empty($children_elements[$element->$id_field]);
		} // END if(is_object($args[0]))

		parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	} // END public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output)

	/**
	 * Menu Fallback
	 * =============
	 * If this function is assigned to the wp_nav_menu's fallback_cb variable
	 * and a manu has not been assigned to the theme location in the WordPress
	 * menu manager the function with display nothing to a non-logged in user,
	 * and will add a link to the WordPress menu manager if logged in as an admin.
	 *
	 * @param array $args passed from the wp_nav_menu function.
	 *
	 */
	public static function fallback($args) {
		if(\current_user_can('manage_options')) {
			\extract($args);

			$fb_output = null;

			if($container) {
				$fb_output = '<' . $container;

				if($container_id) {
					$fb_output .= ' id="' . $container_id . '"';
				} // END if($container_id)

				if($container_class) {
					$fb_output .= ' class="' . $container_class . '"';
				} // END if($container_class)

				$fb_output .= '>';
			} // END if($container)

			$fb_output .= '<ul';

			if($menu_id) {
				$fb_output .= ' id="' . $menu_id . '"';
			} // END if($menu_id)

			if($menu_class) {
				$fb_output .= ' class="' . $menu_class . '"';
			} // END if($menu_class)

			$fb_output .= '>';
			$fb_output .= '<li><a href="' . \admin_url('nav-menus.php') . '">' . __('Add a menu', 'eve-online') . '</a></li>';
			$fb_output .= '</ul>';

			if($container) {
				$fb_output .= '</' . $container . '>';
			} // END if($container)

			echo $fb_output;
		} // END if(current_user_can('manage_options'))
	} // END public static function fallback($args)
} // END class BootstrapMenuWalker extends \Walker_Nav_Menu
