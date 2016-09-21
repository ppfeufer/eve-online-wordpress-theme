<?php
/**
 * Settings API ( modified from http://www.wp-load.com/register-settings-api/ )
 */

namespace WordPress\Themes\EveOnline\Admin;

use WordPress\Themes\EveOnline;

\defined('ABSPATH') or die();

class SettingsApi {
	/**
	 * Init private variables
	 */
	private $args = null;
	private $settingsArray = null;
	private $settingsFilter = null;
	private $optionsDefault = null;

	/**
	 * Constructor
	 */
	public function __construct($settingsFilter, $defaultOptions = null) {
		$this->settingsFilter = $settingsFilter;
		$this->optionsDefault = $defaultOptions;
	} // END public function __construct()

	/**
	 * Settings API init
	 */
	public function init() {
		\add_action('init', array($this, 'initSettings'));
		\add_action('admin_menu', array($this, 'menuPage'));
		\add_action('admin_init', array($this, 'registerFields'));
		\add_action('admin_init', array($this, 'registerCallback'));
		\add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
		\add_action('admin_enqueue_scripts', array($this, 'enqueueStyles'));
	} // END public function init()

	/**
	 * Init settings runs before admin_init
	 * Put $settingsArray to private variable
	 * Add admin_head for scripts and styles
	 */
	public function initSettings() {
		if(\is_admin()) {
			$this->settingsArray = \apply_filters($this->settingsFilter, array());

			if(!empty($this->isSettingsPage())) {
				\add_action('admin_head', array($this, 'adminStyles'));
				\add_action('admin_head', array($this, 'adminScripts'));
			} // END if(!empty($this->isSettingsPage()))
		} // END if(is_admin())
	} // END public function initSettings()

	/**
	 * Creating pages and menus from the settingsArray
	 */
	public function menuPage() {
		foreach($this->settingsArray as $menu_slug => $options) {
			if(!empty($options['page_title']) && !empty($options['menu_title']) && !empty($options['option_name'])) {
				$options['capability'] = (!empty($options['capability']) ) ? $options['capability'] : 'manage_options';

				if(empty($options['type'])) {
					$options['type'] = 'plugin';
				} // END if(empty($options['type']))

				switch($options['type']) {
					case 'theme':
						\add_theme_page(
							$options['page_title'], $options['menu_title'], $options['capability'], $menu_slug, array($this, 'renderOptions')
						);
						break;

					default:
						\add_options_page(
							$options['page_title'], $options['menu_title'], $options['capability'], $menu_slug, array($this, 'renderOptions')
						);
						break;
				} // END switch($options['type'])
			} // END if(!empty($options['page_title']) && !empty($options['menu_title']) && !empty($options['option_name']))
		} // END foreach($this->settingsArray as $menu_slug => $options)
	} // END public function menuPage()

	/**
	 * Register all fields and settings bound to it from the settingsArray
	 */
	public function registerFields() {
		foreach($this->settingsArray as $page_id => $settings) {
			if(!empty($settings['tabs']) && \is_array($settings['tabs'])) {
				foreach($settings['tabs'] as $tab_id => $item) {
					$sanitized_tab_id = \sanitize_title($tab_id);
					$tab_description = (!empty($item['tab_description']) ) ? $item['tab_description'] : '';
					$this->section_id = $sanitized_tab_id;
					$setting_args = array(
						'option_group' => 'section_page_' . $page_id . '_' . $sanitized_tab_id,
						'option_name' => $settings['option_name']
					);

					\register_setting($setting_args['option_group'], $setting_args['option_name']);

					$section_args = array(
						'id' => 'section_id_' . $sanitized_tab_id,
						'title' => $tab_description,
						'callback' => 'callback',
						'menu_page' => $page_id . '_' . $sanitized_tab_id
					);

					\add_settings_section(
						$section_args['id'], $section_args['title'], array($this, $section_args['callback']), $section_args['menu_page']
					);

					if(!empty($item['fields']) && is_array($item['fields'])) {
						foreach($item['fields'] as $field_id => $field) {
							if(\is_array($field)) {
								$sanitized_field_id = \sanitize_title($field_id);
								$title = (!empty($field['title']) ) ? $field['title'] : '';
								$field['field_id'] = $sanitized_field_id;
								$field['option_name'] = $settings['option_name'];
								$field_args = array(
									'id' => 'field' . $sanitized_field_id,
									'title' => $title,
									'callback' => 'renderFields',
									'menu_page' => $page_id . '_' . $sanitized_tab_id,
									'section' => 'section_id_' . $sanitized_tab_id,
									'args' => $field
								);

								\add_settings_field(
									$field_args['id'], $field_args['title'], array($this, $field_args['callback']), $field_args['menu_page'], $field_args['section'], $field_args['args']
								);
							} // END if(is_array($field))
						} // END foreach($item['fields'] as $field_id => $field)
					} // END if(!empty($item['fields']) && is_array($item['fields']))
				} // END foreach($settings['tabs'] as $tab_id => $item)
			} // END if(!empty($settings['tabs']) && is_array($settings['tabs']))
		} // END foreach($this->settingsArray as $page_id => $settings)
	} // END public function registerFields()

	/**
	 * Register callback is used for the button field type when user
	 * click the button
	 */
	public function registerCallback() {
		$isSettingsPage = $this->isSettingsPage();

		if(!empty($isSettingsPage)) {
			if(!empty($_GET['callback'])) {
				$nonce = \wp_verify_nonce($_GET['_wpnonce']);

				if(!empty($nonce)) {
					if(function_exists($_GET['callback'])) {
						$message = \call_user_func($_GET['callback']);
						\update_option('rsa-message', $message);

						$url = admin_url('options-general.php?page=' . $_GET['page']);
						\wp_redirect($url);

						die;
					} // END if(function_exists($_GET['callback']))
				} // END if(!empty($nonce))
			} // END if(!empty($_GET['callback']))
		} // END if(!empty($isSettingsPage))
	} // END public function registerCallback()

	/**
	 * Check if the current page is a settings page
	 */
	public function isSettingsPage() {
		$menus = array();
		$get_page = (!empty($_GET['page']) ) ? $_GET['page'] : '';

		foreach($this->settingsArray as $menu => $page) {
			$menus[] = $menu;
		} // END foreach($this->settingsArray as $menu => $page)

		if(\in_array($get_page, $menus)) {
			return true;
		} else {
			return false;
		} // END if(in_array($get_page, $menus))
	} // END public function isSettingsPage()

	/**
	 * Return an array for the choices in a select field type
	 */
	public function selectChoices() {
		$items = array();

		if(!empty($this->args['choices']) && \is_array($this->args['choices'])) {
			foreach($this->args['choices'] as $slug => $choice) {
				$items[$slug] = $choice;
			} // END foreach($this->args['choices'] as $slug => $choice)
		} // END if(!empty($this->args['choices']) && is_array($this->args['choices']))

		return $items;
	} // END public function selectChoices()

	/**
	 * Get values from built in WordPress functions
	 */
	public function get() {
		if(!empty($this->args['get'])) {
			$item_array = \call_user_func_array(array($this, 'get_' . EveOnline\Helper\StringHelper::camelCase($this->args['get'], true)), array($this->args));
		} elseif(!empty($this->args['choices'])) {
			$item_array = $this->selectChoices($this->args);
		} else {
			$item_array = array();
		} // END if(!empty($this->args['get']))

		return $item_array;
	} // END public function get()

	/**
	 * Get users from WordPress, used by the select field type
	 */
	public function getUsers() {
		$items = array();
		$args = (!empty($this->args['args'])) ? $this->args['args'] : null;
		$users = \get_users($args);

		foreach($users as $user) {
			$items[$user->ID] = $user->display_name;
		} // END foreach($users as $user)

		return $items;
	} // END public function getUsers()

	/**
	 * Get menus from WordPress, used by the select field type
	 */
	public function getMenus() {
		$items = array();
		$menus = \get_registered_nav_menus();

		if(!empty($menus)) {
			foreach($menus as $location => $description) {
				$items[$location] = $description;
			} // END foreach($menus as $location => $description)
		} // END if(!empty($menus))

		return $items;
	} // END public function getMenus()

	/**
	 * Get posts from WordPress, used by the select field type
	 */
	public function getPosts() {
		$items = null;

		if($this->args['get'] === 'posts' && !empty($this->args['post_type'])) {
			$args = array(
				'category' => 0,
				'post_type' => 'post',
				'post_status' => 'publish',
				'orderby' => 'post_date',
				'order' => 'DESC',
				'suppress_filters' => true
			);

			$the_query = new \WP_Query($args);

			if($the_query->have_posts()) {
				while($the_query->have_posts()) {
					$the_query->the_post();

					global $post;

					$items[$post->ID] = \get_the_title();
				} // END while($the_query->have_posts())
			} // END if($the_query->have_posts())

			\wp_reset_postdata();
		}

		return $items;
	} // END public function getPosts()

	/**
	 * Get terms from WordPress, used by the select field type
	 */
	public function getTerms() {
		$items = array();
		$taxonomies = (!empty($this->args['taxonomies']) ) ? $this->args['taxonomies'] : null;
		$args = (!empty($this->args['args'])) ? $this->args['args'] : null;
		$terms = \get_terms($taxonomies, $args);

		if(!empty($terms)) {
			foreach($terms as $key => $term) {
				$items[$term->term_id] = $term->name;
			} // END foreach($terms as $key => $term)
		} // END if(!empty($terms))

		return $items;
	} // END public function getTerms()

	/**
	 * Get taxonomies from WordPress, used by the select field type
	 */
	public function getTaxonomies() {
		$items = array();
		$args = (!empty($this->args['args'])) ? $this->args['args'] : null;
		$taxonomies = \get_taxonomies($args, 'objects');

		if(!empty($taxonomies)) {
			foreach($taxonomies as $taxonomy) {
				$items[$taxonomy->name] = $taxonomy->label;
			} // END foreach($taxonomies as $taxonomy)
		} // END if(!empty($taxonomies))

		return $items;
	} // END public function getTaxonomies()

	/**
	 * Get sidebars from WordPress, used by the select field type
	 */
	public function getSidebars() {
		$items = array();

		global $wp_registered_sidebars;

		if(!empty($wp_registered_sidebars)) {
			foreach($wp_registered_sidebars as $sidebar) {
				$items[$sidebar['id']] = $sidebar['name'];
			} // END foreach($wp_registered_sidebars as $sidebar)
		} // END if(!empty($wp_registered_sidebars))

		return $items;
	} // END public function getSidebars()

	/**
	 * Get themes from WordPress, used by the select field type
	 */
	public function getThemes() {
		$items = array();
		$args = (!empty($this->args['args'])) ? $this->args['args'] : null;
		$themes = \wp_get_themes($args);

		if(!empty($themes)) {
			foreach($themes as $key => $theme) {
				$items[$key] = $theme->get('Name');
			} // END foreach($themes as $key => $theme)
		} // END if(!empty($themes))

		return $items;
	} // END public function getThemes()

	/**
	 * Get plugins from WordPress, used by the select field type
	 */
	public function getPlugins() {
		$items = array();
		$args = (!empty($this->args['args'])) ? $this->args['args'] : null;
		$plugins = \get_plugins($args);

		if(!empty($plugins)) {
			foreach($plugins as $key => $plugin) {
				$items[$key] = $plugin['Name'];
			} // END foreach($plugins as $key => $plugin)
		} // END if(!empty($plugins))

		return $items;
	} // END public function getPlugins()

	/**
	 * Get post_types from WordPress, used by the select field type
	 */
	public function getPostTypes() {
		$items = array();
		$args = (!empty($this->args['args'])) ? $this->args['args'] : null;
		$post_types = \get_post_types($args, 'objects');

		if(!empty($post_types)) {
			foreach($post_types as $key => $post_type) {
				$items[$key] = $post_type->name;
			} // END foreach($post_types as $key => $post_type)
		} // END if(!empty($post_types))

		return $items;
	} // END public function get_post_types()

	/**
	 * Find a selected value in select or multiselect field type
	 */
	public function selected($key) {
		if($this->valueType() == 'array') {
			return $this->multiselectedValue($key);
		} else {
			return $this->selectedValue($key);
		} // END if($this->valueType() == 'array')
	} // END public function selected($key)

	/**
	 * Return selected html if the value is selected in select field type
	 */
	public function selectedValue($key) {
		$result = '';

		if($this->value($this->options, $this->args) === $key) {
			$result = ' selected="selected"';
		} // END if($this->value($this->options, $this->args) === $key)

		return $result;
	} // END public function selectedValue($key)

	/**
	 * Return selected html if the value is selected in multiselect field type
	 */
	public function multiselectedValue($key) {
		$result = '';
		$value = $this->value($this->options, $this->args, $key);

		if(\is_array($value) && \in_array($key, $value)) {
			$result = ' selected="selected"';
		} // END if(is_array($value) && in_array($key, $value))

		return $result;
	} // END public function multiselectedValue($key)

	/**
	 * Return checked html if the value is checked in radio or checkboxes
	 */
	public function checked($slug) {
		$value = $this->value();

		if($this->valueType() == 'array') {
			$checked = (!empty($value) && \in_array($slug, $this->value())) ? ' checked="checked"' : '';
		} else {
			$checked = (!empty($value) && $slug == $this->value()) ? ' checked="checked"' : '';
		} // END if($this->valueType() == 'array')

		return $checked;
	} // END public function checked($slug)

	/**
	 * Return the value. If the value is not saved the default value is used if
	 * exists in the settingsArray.
	 *
	 * Return as string or array
	 */
	public function value($key = null) {
		$value = '';

		if($this->valueType() == 'array') {
			$default = (!empty($this->args['default']) && \is_array($this->args['default'])) ? $this->args['default'] : array();
		} else {
			$default = (!empty($this->args['default'])) ? $this->args['default'] : '';
		} // END if($this->valueType() == 'array')

		$value = (isset($this->options[$this->args['field_id']])) ? $this->options[$this->args['field_id']] : $default;

		return $value;
	} // END public function value($key = null)

	/**
	 * Check if the current value type is a single value or a multiple
	 * value field type, return string or array
	 */
	public function valueType() {
		$default_single = array(
			'select',
			'radio',
			'text',
			'email',
			'url',
			'color',
			'date',
			'number',
			'password',
			'colorpicker',
			'textarea',
			'datepicker',
			'tinymce',
			'image',
			'file'
		);
		$default_multiple = array('multiselect', 'checkbox');

		if(\in_array($this->args['type'], $default_single)) {
			return 'string';
		} elseif(in_array($this->args['type'], $default_multiple)) {
			return 'array';
		} // END if(in_array($this->args['type'], $default_single))
	} // END public function valueType()

	/**
	 * Check if a checkbox has items
	 */
	public function hasItems() {
		if(!empty($this->args['choices']) && \is_array($this->args['choices'])) {
			return true;
		} // END if(!empty($this->args['choices']) && is_array($this->args['choices']))

		return false;
	} // END public function hasItems()

	/**
	 * Return the html name of the field
	 */
	public function name($slug = '') {
		$option_name = \sanitize_title($this->args['option_name']);

		if($this->valueType() == 'array') {
			return $option_name . '[' . $this->args['field_id'] . '][' . $slug . ']';
		} else {
			return $option_name . '[' . $this->args['field_id'] . ']';
		} // END if($this->valueType() == 'array')
	} // END public function name($slug = '')

	/**
	 * Return the size of a multiselect type. If not set it will calculate it
	 */
	public function size($items) {
		$size = '';

		if($this->args['type'] == 'multiselect') {
			if(!empty($this->args['size'])) {
				$count = $this->args['size'];
			} else {
				$count = count($items);
				$count = (!empty($this->args['empty']) ) ? $count + 1 : $count;
			} // END if(!empty($this->args['size']))

			$size = ' size="' . $count . '"';
		} // END if($this->args['type'] == 'multiselect')

		return $size;
	} // END public function size($items)

	/**
	 * All the field types in html
	 */
	public function renderFields($args) {
		$args['field_id'] = \sanitize_title($args['field_id']);
		$this->args = $args;

		$options = \get_option($args['option_name'], $this->optionsDefault);
		$this->options = $options;

		$screen = \get_current_screen();
		$callback_base = \admin_url() . $screen->parent_file;

		$option_name = \sanitize_title($args['option_name']);
		$out = '';

		if(!empty($args['type'])) {
			switch($args['type']) {
				case 'select':
				case 'multiselect':
					$multiple = ($args['type'] == 'multiselect') ? ' multiple' : '';
					$items = $this->get($args);
					$out .= '<select' . $multiple . ' name="' . $this->name() . '"' . $this->size($items) . '>';

					if(!empty($args['empty'])) {
						$out .= '<option value="" ' . $this->selected('') . '>' . $args['empty'] . '</option>';
					}

					foreach($items as $key => $choice) {
						$key = sanitize_title($key);
						$out .= '<option value="' . $key . '" ' . $this->selected($key) . '>' . $choice . '</option>';
					}

					$out .= '</select>';
					break;

				case 'radio':
				case 'checkbox':
					if($this->hasItems()) {
						$horizontal = (isset($args['align']) && (string) $args['align'] == 'horizontal') ? ' class="horizontal"' : '';

						$out .= '<ul class="settings-group settings-type-' . $args['type'] . '">';

						foreach($args['choices'] as $slug => $choice) {
							$checked = $this->checked($slug);

							$out .= '<li' . $horizontal . '><label>';
							$out .= '<input value="' . $slug . '" type="' . $args['type'] . '" name="' . $this->name($slug) . '"' . $checked . '>';
							$out .= $choice;
							$out .= '</label></li>';
						}

						$out .= '</ul>';
					} // END if($this->hasItems())
					break;

				case 'text':
				case 'email':
				case 'url':
				case 'color':
				case 'date':
				case 'number':
				case 'password':
				case 'colorpicker':
				case 'datepicker':
					$out = '<input type="' . $args['type'] . '" value="' . $this->value() . '" name="' . $this->name() . '" class="' . $args['type'] . '" data-id="' . $args['field_id'] . '">';
					break;

				case 'textarea':
					$rows = (isset($args['rows'])) ? $args['rows'] : 5;
					$out .= '<textarea rows="' . $rows . '" class="large-text" name="' . $this->name() . '">' . $this->value() . '</textarea>';
					break;

				case 'tinymce':
					$rows = (isset($args['rows'])) ? $args['rows'] : 5;
					$tinymce_settings = array(
						'textarea_rows' => $rows,
						'textarea_name' => $option_name . '[' . $args['field_id'] . ']',
					);

					wp_editor($this->value(), $args['field_id'], $tinymce_settings);
					break;

				case 'image':
					$image_obj = (!empty($options[$args['field_id']])) ? \wp_get_attachment_image_src($options[$args['field_id']], 'thumbnail') : '';
					$image = (!empty($image_obj)) ? $image_obj[0] : '';
					$upload_status = (!empty($image_obj)) ? ' style="display: none"' : '';
					$remove_status = (!empty($image_obj)) ? '' : ' style="display: none"';
					$value = (!empty($options[$args['field_id']])) ? $options[$args['field_id']] : '';
					?>
					<div data-id="<?php echo $args['field_id']; ?>">
						<div class="upload" data-field-id="<?php echo $args['field_id']; ?>"<?php echo $upload_status; ?>>
							<span class="button upload-button">
								<a href="#">
									<i class="fa fa-upload"></i>
									<?php echo \__('Upload', 'eve-online'); ?>
								</a>
							</span>
						</div>
						<div class="image">
							<img class="uploaded-image" src="<?php echo $image; ?>" id="<?php echo $args['field_id']; ?>" />
						</div>
						<div class="remove"<?php echo $remove_status; ?>>
							<span class="button upload-button">
								<a href="#">
									<i class="fa fa-trash"></i>
									<?php echo \__('Remove', 'eve-online'); ?>
								</a>
							</span>
						</div>
						<input type="hidden" class="attachment_id" value="<?php echo $value; ?>" name="<?php echo $option_name; ?>[<?php echo $args['field_id']; ?>]">
					</div>
					<?php
					break;

				case 'file':
					$file_url = (!empty($options[$args['field_id']])) ? \wp_get_attachment_url($options[$args['field_id']]) : '';
					$upload_status = (!empty($file_url)) ? ' style="display: none"' : '';
					$remove_status = (!empty($file_url)) ? '' : ' style="display: none"';
					$value = (!empty($options[$args['field_id']])) ? $options[$args['field_id']] : '';
					?>
					<div data-id="<?php echo $args['field_id']; ?>">
						<div class="upload" data-field-id="<?php echo $args['field_id']; ?>"<?php echo $upload_status; ?>>
							<span class="button upload-button">
								<a href="#">
									<i class="fa fa-upload"></i>
									<?php echo \__('Upload', 'eve-online'); ?>
								</a>
							</span>
						</div>
						<div class="url">
							<code class="uploaded-file-url" title="Attachment ID: <?php echo $value; ?>" data-field-id="<?php echo $args['field_id']; ?>">
								<?php echo $file_url; ?>
							</code>
						</div>
						<div class="remove"<?php echo $remove_status; ?>>
							<span class="button upload-button">
								<a href="#">
									<i class="fa fa-trash"></i>
									<?php echo \__('Remove', 'eve-online'); ?>
								</a>
							</span>
						</div>
						<input type="hidden" class="attachment_id" value="<?php echo $value; ?>" name="<?php echo $option_name; ?>[<?php echo $args['field_id']; ?>]">
					</div>
					<?php
					break;

				case 'button':
					$warning_message = (!empty($args['warning-message'])) ? $args['warning-message'] : 'Unsaved settings will be lost. Continue?';
					$warning = (!empty($args['warning'])) ? ' onclick="return confirm(' . "'" . $warning_message . "'" . ')"' : '';
					$label = (!empty($args['label'])) ? $args['label'] : '';
					$complete_url = \wp_nonce_url(\admin_url('options-general.php?page=' . $_GET['page'] . '&callback=' . $args['callback']));
					?>
					<a href="<?php echo $complete_url; ?>" class="button button-secondary"<?php echo $warning; ?>><?php echo $label; ?></a>
					<?php
					break;

				case 'custom':
					$value = (!empty($options[$args['field_id']])) ? $options[$args['field_id']] : null;
					$data = array(
						'value' => $value,
						'name' => $this->name(),
						'args' => $args
					);

					if($args['content'] !== null) {
						echo $args['content'];
					}

					if($args['callback'] !== null) {
						\call_user_func($args['callback'], $data);
					}
					break;
			} // END switch($args['type'])
		} // END if(!empty($args['type']))

		echo $out;

		if(!empty($args['description'])) {
			echo '<p class="description">' . $args['description'] . '</div>';
		} // END if(!empty($args['description']))
	} // END public function renderFields($args)

	/**
	 * Callback for field registration.
	 * It's required by WordPress but not used by this class
	 */
	public function callback() {
	} // END public function callback()

	/**
	 * Final output on the settings page
	 */
	public function renderOptions() {
//		global $wp_settings_sections;

		$page = $_GET['page'];
		$settings = $this->settingsArray[$page];
		$message = \get_option('rsa-message');

		if(!empty($settings['tabs']) && \is_array($settings['tabs'])) {
			$tab_count = \count($settings['tabs']);
			?>
			<div class="wrap">
				<?php
				if(!empty($settings['before_tabs_text'])) {
					echo $settings['before_tabs_text'];
				} // END if(!empty($settings['before_tabs_text']))
				?>
				<form action='options.php' method='post'>
					<?php
					if($tab_count > 1) {
						?>
						<h2 class="nav-tab-wrapper">
						<?php
						$i = 0;
						foreach($settings['tabs'] as $settings_id => $section) {
							$sanitized_id = \sanitize_title($settings_id);
							$tab_title = (!empty($section['tab_title'])) ? $section['tab_title'] : $sanitized_id;
							$active = ($i == 0) ? ' nav-tab-active' : '';

							echo '<a class="nav-tab nav-tab-' . $sanitized_id . $active . '" href="#tab-content-' . $sanitized_id . '">' . $tab_title . '</a>';

							$i++;
						} // END foreach($settings['tabs'] as $settings_id => $section)
						?>
						</h2>

						<?php
						if(!empty($message)) {
							?>
							<div class="updated settings-error">
								<p><strong><?php echo $message; ?></strong></p>
							</div>
							<?php
							\update_option('rsa-message', '');
						} // END if(!empty($message))
					} // END if($tab_count > 1)

					$i = 0;
					foreach($settings['tabs'] as $settings_id => $section) {
						$sanitized_id = \sanitize_title($settings_id);
						$page_id = $_GET['page'] . '_' . $sanitized_id;

						$display = ($i == 0) ? ' style="display: block;"' : ' style="display:none;"';

						echo '<div class="tab-content" id="tab-content-' . $sanitized_id . '"' . $display . '>';
						echo \settings_fields('section_page_' . $_GET['page'] . '_' . $sanitized_id);

						\do_settings_sections($page_id);

						echo '</div>';

						$i++;
					} // END foreach($settings['tabs'] as $settings_id => $section)

					$complete_url = \wp_nonce_url(\admin_url('options-general.php?page=' . $_GET['page'] . '&callback=rsa_delete_settings'));

					\submit_button();
					?>
				</form>
				<?php
				if(!empty($settings['after_tabs_text'])) {
					echo $settings['after_tabs_text'];
				} // END if(!empty($settings['after_tabs_text']))
				?>
			</div>
			<?php
		} // END if(!empty($settings['tabs']) && is_array($settings['tabs']))
	} // END public function renderOptions()

	/**
	 * Register scripts
	 */
	public function enqueueScripts() {
		$isSettingsPage = $this->isSettingsPage();

		if(!empty($isSettingsPage)) {
			\wp_enqueue_media();
			\wp_enqueue_script('wp-color-picker');
			\wp_enqueue_script('jquery-ui-datepicker');
			\wp_enqueue_script(
				'settings-api',
				(\preg_match('/development/', \APPLICATION_ENV)) ? \get_template_directory_uri() . '/admin/js/settings-api.js' : \get_template_directory_uri() . '/admin/js/settings-api.min.js'
			);
		} // END if(!empty($isSettingsPage))
	} // END public function enqueueScripts()

	/**
	 * Register styles
	 */
	public function enqueueStyles() {
		$isSettingsPage = $this->isSettingsPage();

		if(!empty($isSettingsPage)) {
			\wp_enqueue_style('wp-color-picker');
			\wp_enqueue_style('jquery-ui', \get_template_directory_uri() . '/admin/css/jquery-ui.min.css');
			\wp_enqueue_style(
				'font-awesome',
				(\preg_match('/development/', \APPLICATION_ENV)) ? \get_template_directory_uri() . '/font-awesome/css/font-awesome.css' : \get_template_directory_uri() . '/font-awesome/css/font-awesome.min.css'
			);
			\wp_enqueue_style(
				'settings-api',
				(\preg_match('/development/', \APPLICATION_ENV)) ? \get_template_directory_uri() . '/admin/css/settings-api.css' : \get_template_directory_uri() . '/admin/css/settings-api.min.css'
			);
		} // END if(!empty($isSettingsPage))
	} // END public function enqueueStyles()

	/**
	 * Inline scripts and styles
	 */
	public function adminStyles() {
		if(!empty($this->isSettingsPage())) {
			?>
			<style>
			.image img {
				border: 1px solid #ddd;
				vertical-align: bottom;
			}
			.image img:hover {
				cursor: pointer;
			}
			.nav-tab:focus {
				outline: none;
				-webkit-box-shadow: none;
				box-shadow: none;
			}
			.rsa-delete {
				color: #a00;
			}
			.rsa-delete:hover {
				color: red;
			}
			</style>
			<?php
		} // END if(!empty($this->isSettingsPage()))
	} // END public function adminStyes()

	public function adminScripts() {
		if(!empty($this->isSettingsPage())) {
			?>
			<script>
			jQuery(document).ready(function($) {
				<?php
				$settingsArray = $this->settingsArray;

				foreach($settingsArray as $page) {
					foreach($page['tabs'] as $tab) {
						foreach($tab['fields'] as $field_key => $field) {
							if($field['type'] == 'datepicker') {
//								$date_format = (!empty($field['format']) ) ? $field['format'] : 'yy-mm-dd';
								$date_format = (!empty($field['format']) ) ? $field['format'] : \get_option('date_format');
								?>
								$('[data-id="<?php echo $field_key; ?>"]').datepicker({
									dateFormat: '<?php echo $date_format; ?>'
								});
								<?php
							} // END if($field['type'] == 'datepicker')
						} // END foreach($tab['fields'] as $field_key => $field)
					} // END foreach($page['tabs'] as $tab)
				} // END foreach($settingsArray as $page)
				?>
			});
			</script>
			<?php
		} // END if(!empty($this->isSettingsPage()))
	} // END public function adminScripts()
} // END class SettingsApi

/**
 * We fire the API class from within the settings itself ...
 */
//$settingsApi = new SettingsApi();
//$settingsApi->init();