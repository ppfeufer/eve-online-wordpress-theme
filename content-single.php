<?php defined('ABSPATH') or die(); ?>

<article id="post-<?php the_ID(); ?>" <?php \post_class('clearfix content-single'); ?>>
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
					'before' => '<div class="page-links">' . __('Pages:', 'eve-online'),
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