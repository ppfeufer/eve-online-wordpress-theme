<?php
namespace WordPress\Themes\EveOnline\Plugins;

use WordPress\Themes\EveOnline;

\defined('ABSPATH') or die();

class Corppage {
	private $eveApi = null;
	private $string = null;

	public function __construct() {
		$this->eveApi = new EveOnline\Helper\EveApi;
		$this->string = new EveOnline\Helper\String;

		$this->registerMetaBoxes();
		$this->registerShortcodes();
	} // END public function __construct()

	public function registerShortcodes() {
		\add_shortcode('corplist', array(
			$this,
			'shortcodeCorplist'
		));
	} // END public function registerShortcodes()

	public function shortcodeCorplist($attributes) {
		$args = \shortcode_atts(
			array(
				'type' => 'boxes'
			),
			$attributes
		);
		$type = $args['type'];

		$corpPages = $this->getCorporationPages();
		$corplistHTML = null;

		if($corpPages !== false) {
			$uniqueID = \uniqid();
			$corplistHTML .= '<div class="gallery-row">';
			$corplistHTML .= '<ul class="bootstrap-gallery bootstrap-corporationlist bootstrap-corporationlist-' . $uniqueID . ' clearfix">';

			foreach($corpPages as $page) {
				if(!empty($page->post_content)) {
					$corpID = \get_post_meta($page->ID, 'eve_page_corp_eve_ID', true);
					$corpLogo = $this->eveApi->getImageServerEndpoint('corporation') . $corpID . '_256.png';

					$corplistHTML .= '<li>';
					$corplistHTML .= '<figure><a href="' . \get_permalink($page->ID) . '"><img src="' . $corpLogo . '" alt="' . $page->post_title . '"></a></figure>';
					$corplistHTML .= '<header><h4><a href="' . \get_permalink($page->ID) . '">' . $page->post_title . '</a></h4></header>';

					$corplistHTML .= '<p>' . $this->string->cutString(strip_shortcodes($page->post_content), '200') . '</p>';

					$corplistHTML .= '</li>';
				} // END if(!empty($page->post_content))
			} // END foreach($corpPages as $page)

			$corplistHTML .= '</ul>';
			$corplistHTML .= '</div>';

			$corplistHTML .= '<script type="text/javascript">
									jQuery(document).ready(function() {
										jQuery("ul.bootstrap-corporationlist-' . $uniqueID . '").bootstrapGallery({
											"classes" : "col-lg-3 col-md-4 col-sm-6 col-xs-12",
											"hasModal" : false
										});
									});
									</script>';
		} // END if($corpPages !== false)

		return $corplistHTML;
	} // END public function shortcodeCorplist($attributes)

	public function getCorporationPages() {
		$result = new \WP_Query(array(
			'post_type' => 'page',
			'meta_key' => 'eve_page_is_corp_page',
			'meta_value' => 1,
			'posts_per_page' => -1,
			'orderby' => 'post_title',
			'order' => 'ASC'
		));

		if($result) {
			return $result->posts;
		} // END if($result)

		return false;
	} // END public function getCorporationPages()

	public function registerMetaBoxes() {
		\add_action('add_meta_boxes', array($this, 'addMetaBox'));
		\add_action('save_post', array($this, 'savePageSettings'));
	} // END public function registerMetaBoxes()

	public function addMetaBox() {
		\add_meta_box('eve-corp-page-box', __('Corp Page?', 'eve-online'), array($this, 'renderMetaBox'), 'page', 'side');
	} // END public function addMetaBox()

	public function renderMetaBox($post) {
		$eve_page_is_corp_page = \get_post_meta($post->ID, 'eve_page_is_corp_page', true);
		$eve_page_corp_name = \get_post_meta($post->ID, 'eve_page_corp_name', true);
		$eve_page_corp_eve_ID = \get_post_meta($post->ID, 'eve_page_corp_eve_ID', true);
		?>
		<label><strong><?php _e('Corp Page Settings', 'eve-online'); ?></strong></label>
		<p class="checkbox-wrapper">
			<input id="eve_page_is_corp_page" name="eve_page_is_corp_page" type="checkbox" <?php \checked($eve_page_is_corp_page); ?>>
			<label for="eve_page_is_corp_page"><?php _e('Is Corp Page?', 'eve-online'); ?></label>
		</p>
		<p class="checkbox-wrapper">
			<label for="eve_page_corp_name"><?php _e('Corporation Name:', 'eve-online'); ?></label><br>
			<input id="eve_page_corp_name" name="eve_page_corp_name" type="text" value="<?php echo $eve_page_corp_name; ?>">
		</p>
		<?php
		if(!empty($eve_page_corp_eve_ID)) {
			?>
			<p class="checkbox-wrapper">
				<label for="eve_page_corp_ID"><?php _e('Corporation ID', 'eve-online'); ?></label>
				<input id="eve_page_corp_ID" name="eve_page_corp_ID" type="text" value="<?php echo \esc_html($eve_page_corp_eve_ID); ?>" disabled>
			</p>
			<p class="checkbox-wrapper">
				<label><strong><?php _e('Corporation Logo', 'eve-online'); ?></strong></label>
				<br>
				<?php
				$corpLogoPath = $this->eveApi->getImageServerEndpoint('corporation') . $eve_page_corp_eve_ID . '_256.png';
				?>
				<img src="<?php echo $corpLogoPath; ?>" alt="<?php echo $eve_page_corp_name; ?>">
			</p>
			<?php
		} // END if(!empty($eve_page_corp_eve_ID))

		\wp_nonce_field('save', '_eve_corp_page_nonce');
	} // END public function renderMetaBox($post)

	public function savePageSettings($postID) {
		if(empty($_POST['_eve_corp_page_nonce']) || !\wp_verify_nonce($_POST['_eve_corp_page_nonce'], 'save')) {
			return false;
		} // END if(empty($_POST['_eve_corp_page_nonce']) || !\wp_verify_nonce($_POST['_eve_corp_page_nonce'], 'save'))

		if(!\current_user_can('edit_post', $postID)) {
			return false;
		} // END if(!\current_user_can('edit_post', $postID))

		if(defined('DOING_AJAX')) {
			return false;
		} // END if(defined('DOING_AJAX'))

		\update_post_meta($postID, 'eve_page_corp_name', $_POST['eve_page_corp_name']);

		$corpID = $this->eveApi->getEveIdFromName(\stripslashes($_POST['eve_page_corp_name']));
		\update_post_meta($postID, 'eve_page_corp_eve_ID', \esc_html($corpID));

		$isCorpPage = \filter_input(INPUT_POST, 'eve_page_is_corp_page') == "on";
		\update_post_meta($postID, 'eve_page_is_corp_page', $isCorpPage);(\stripslashes($_POST['eve_page_corp_name']));

		\update_post_meta($postID, 'eve_page_corp_eve_ID', \esc_html($corpID));
	} // END public function savePageSettings($postID)
} // END class Corppage

new Corppage();