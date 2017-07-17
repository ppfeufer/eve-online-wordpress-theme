<?php

namespace WordPress\Themes\EveOnline\Plugins;

use WordPress\Themes\EveOnline;

class EveOnlineAvatar {
	private $eveApi = null;

	public function __construct() {
		$this->eveApi = new EveOnline\Helper\EveApiHelper;

		$this->init();
	} // END public function __construct()

	public function init() {
		\add_filter('get_avatar', array($this, 'eveCharacterAvatar'), 10, 5);
		\add_filter('bp_core_fetch_avatar', array($this, 'fetchEveCharacterAvatar'), 1, 2);
		\add_filter('bp_core_fetch_avatar_url', array($this, 'fetchEveCharacterAvatar'), 1, 2);
		\add_filter('user_profile_picture_description', \create_function('$desc', 'return "' . \__('If you set your nickname to your pilot\'s name, you EVE avatar will be used here.', 'eve-online') . '";'));
	} // END public function init()

	public function eveCharacterAvatar($content, $id_or_email) {
		$returnValue = $content;

		if(\preg_match("/gravatar.com\/avatar/", $content)) {
			// get user login
			if(\is_numeric($id_or_email)) {
				$id = (int) $id_or_email;
				$user = \get_userdata($id);
			} elseif(\is_object($id_or_email)) {
				if(!empty($id_or_email->user_id)) {
					$id = (int) $id_or_email->user_id;
					$user = \get_userdata($id);
				} elseif(!empty($id_or_email->comment_author_email)) {
					// Let's see if we can find an EVE Online Avatar
					if(!empty($id_or_email->comment_author)) {
						$eveImage = $this->eveApi->getCharacterImageByName($id_or_email->comment_author, false);

						if($eveImage !== false) {
							return $eveImage;
						} // END if($eveImage !== false)
					} // END if(!empty($id_or_email->comment_author))

					// Nope, no EVE Online Avatar available
					return $content;
				} // END if(!empty($id_or_email->user_id))
			} else {
				$user = \get_user_by('email', $id_or_email);
			} // END if(\is_numeric($id_or_email))

			$eveImage = $this->eveApi->getCharacterImageByName($user->nickname, false);

			if(!empty($eveImage)) {
				$returnValue = $eveImage;
			} // END if(!empty($eveImage))
		} // END if(\preg_match("/gravatar.com\/avatar/", $content))

		return $returnValue;
	} // END public function eveCharacterAvatar($content, $id_or_email)

	public function fetchEveCharacterAvatar($content, $params) {
		$returnValue = $content;

		if(\is_array($params) && $params['object'] == 'user' ) {
			$returnValue = $this->eveCharacterAvatar($content, $params['item_id']);
		} // END if(\is_array($params) && $params['object'] == 'user' )

		return $returnValue;
	} // END public function fetchEveCharacterAvatar($content, $params)
}
