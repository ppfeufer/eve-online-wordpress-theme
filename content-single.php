<?php defined('ABSPATH') or die(); ?>

<article id="post-<?php the_ID(); ?>" <?php \post_class('clearfix'); ?>>
	<header class="entry-header">
		<h1 class="entry-title">
			<!--<a href="<?php \the_permalink(); ?>" title="<?php \printf(\esc_attr__('Permalink to %s', 'eve-online'), \the_title_attribute('echo=0')); ?>" rel="bookmark">-->
				<?php \the_title(); ?>
			<!--</a>-->
		</h1>
		<aside class="entry-details">
			<p class="meta">
				<?php
				echo \WordPress\Themes\EveOnline\eve_posted_on();

				\edit_post_link(__('Edit', 'eve-online'));
				?>
				<!--<br/>-->
				<?php
				\WordPress\Themes\EveOnline\eve_cats_tags();
				?>
			</p>
		</aside><!--end .entry-details -->
	</header><!--end .entry-header -->

	<section class="post-content">
		<div class="entry-content">
			<?php
			// only show if option set TODO
			$options = \get_option('eve_theme_options', \WordPress\Themes\EveOnline\eve_get_options_default());

			if(isset($options['featured_single']) && $options['featured_single'] == true) {
				if(\has_post_thumbnail()) {
					?>
					<a href="<?php \the_permalink(); ?>" title="<?php \the_title_attribute('echo=0'); ?>">
						<?php
						switch($options['featured_img_sing_size']) {
							case 1:
								$thumbnail_size="thumbnail";
								break;

							case 2:
								$thumbnail_size="medium";
								break;

							case 3:
								$thumbnail_size="large";
								break;

							default:
								$thumbnail_size="thumbnail";
								break;
						} // END switch($options['featured_img_sing_size'])

						\the_post_thumbnail($thumbnail_size);
						?>
					</a>
					<?php
				} // END if(has_post_thumbnail())
			} // END if(isset($options['featured_single']) && $options['featured_single'] == true)

			echo \the_content();

			if(\function_exists('\WordPress\Themes\EveOnline\eve_link_pages')) {
				\WordPress\Themes\EveOnline\eve_link_pages(array(
					'before' => '<ul class="pagination">',
					'after' => '</ul>',
					'before_link' => '<li>',
					'after_link' => '</li>',
					'current_before' => '<li class="active">',
					'current_after' => '</li>',
					'previouspagelink' => '&laquo;',
					'nextpagelink' => '&raquo;'
				));
			} else {
				\wp_link_pages( array(
					'before' => '<div class="page-links">' . __('Pages:', 'thamm-it'),
					'after'  => '</div>',
				));
			} // END if(\function_exists('\EveOnline\eve_link_pages'))
			?>
		</div>
	</section>

	<?php
	// AUTHOR INFO
	if(\get_the_author_meta('description')) {
		?>
		<hr/>
		<div class="author-info">
			<?php echo \get_avatar(\get_the_author_meta('user_email'), 100); ?>
			<div class="author-details">
				<h3>
					<?php
					print(__('Posted by ', 'eve-online'));
					\the_author_link();
					?>
				</h3>
			</div><!-- end .author-details -->
			<p class="author-description">
				<?php \the_author_meta('description'); ?>
			</p>
		</div><!-- end .author-info -->
		<?php
	} // END if(get_the_author_meta('description'))
	?>
	<hr/>
	<?php \comments_template(); ?>
</article><!-- /.post-->