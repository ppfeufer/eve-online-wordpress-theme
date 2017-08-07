<?php
namespace WordPress\Themes\EveOnline\Helper;

\defined('ABSPATH') or die();

class CommentHelper {
	public static function getComments($comment, $args, $depth) {
		switch($comment->comment_type) {
			case 'pingback':
			case 'trackback':
				echo self::getTrackbackTemplate();
				break;
			default :
				// Proceed with normal comments.
				global $post;
				?>
				<li class="comment media" id="comment-<?php \comment_ID(); ?>">
					<?php echo self::getCommenterAvatar($comment); ?>
					<div class="media-body">
						<h4 class="media-heading comment-author vcard">
							<?php echo self::getCommentAuthor($post, $comment); ?>
						</h4>
						<?php
						echo self::getCommentModerated($comment);

						\comment_text();
						?>
						<p class="meta">
							<?php echo self::getCommentMeta($comment); ?>
						</p>
						<p class="reply">
							<?php echo self::getCommentReply($args, $depth); ?>
						</p>
					</div> <!--/.media-body -->
					<?php
				break;
		} // END switch ($comment->comment_type)
	} // END public static function getComments($comment, $args, $depth)

	/**
	 * The template for Trackbacks and Pingbacks
	 *
	 * @return string
	 */
	public static function getTrackbackTemplate() {
		$returnValue = '<li class="comment media" id="comment-' . \get_comment_ID() . '">';
		$returnValue .= '<div class="media-body">';
		$returnValue .= '<p>' . \__('Pingback:', 'eve-online') . ' ' . \get_comment_author_link() . '</p>';
		$returnValue .= '</div><!--/.media-body -->';

		return $returnValue;
	} // END public static function getTrackbackTemplate()

	/**
	 * The commenters avatar
	 *
	 * @param object $comment
	 * @return string
	 */
	public static function getCommenterAvatar($comment) {
		$returnValue = null;

		if(!empty($comment->comment_author_url)) {
			$returnValue = '<a href="' . $comment->comment_author_url . '" class="pull-left comment-avatar">' . \get_avatar($comment, 64) . '</a>';
		} else {
			$returnValue = '<span class="pull-left comment-avatar">' . \get_avatar($comment, 64) . '</span>';
		} // END if(!empty($comment->comment_author_url))

		return $returnValue;
	} // END public static function getCommenterAvatar($comment)

	/**
	 * Getting the comment author
	 *
	 * @param object $post
	 * @param object $comment
	 * @return string
	 */
	public static function getCommentAuthor($post, $comment) {
		return \sprintf('<cite class="fn">%1$s %2$s</cite>',
			\get_comment_author_link(),
			// If current post author is also comment author, make it known visually.
			($comment->user_id === $post->post_author) ? '<span class="label"> ' . \__('Post author', 'eve-online') . '</span> ' : ''
		);
	} // END public static function getCommentAuthor($post, $comment)

	/**
	 * The "Comment awaits moderation" message ...
	 *
	 * @param object $comment
	 * @return string
	 */
	public static function getCommentModerated($comment) {
		$returnValue = '';

		if('0' === $comment->comment_approved) {
			$returnValue = '<p class="comment-awaiting-moderation">' . \__('Your comment is awaiting moderation.', 'eve-online') . '</p>';
		} // END if('0' == $comment->comment_approved)

		return $returnValue;
	} // END public static function getCommentModerated($comment)

	/**
	 * Getting the comment meta
	 *
	 * @param object $comment
	 * @return string
	 */
	public static function getCommentMeta($comment) {
		return \sprintf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
			\esc_url(\get_comment_link($comment->comment_ID)),
			\get_comment_time('c'),
			\sprintf(\__('%1$s at %2$s', 'eve-online'), \get_comment_date(), \get_comment_time())
		);
	} // END public static function getCommentMeta($comment)

	/**
	 * Getting the comment reply
	 *
	 * @param array $args
	 * @param int $depth
	 * @return string
	 */
	public static function getCommentReply($args, $depth) {
		return \get_comment_reply_link(\array_merge($args, array(
			'reply_text' => __('Reply <span>&darr;</span>', 'eve-online'),
			'depth' => $depth,
			'max_depth' => $args['max_depth']
		)));
	} // END public static function getCommentReply($args, $depth)
} // END class CommentHelper
