<?php
\defined('ABSPATH') or die();

/**
 * Template Name: Default Page (With Sidebar)
 */

\get_header();
?>

	<div class="container main">
		<div class="row">
			<div class="col-md-12">
				<?php
				if(\function_exists('\WordPress\Themes\EveOnline\eve_breadcrumbs')) {
					\WordPress\Themes\EveOnline\eve_breadcrumbs();
				} // END if(\function_exists('\EveOnline\eve_breadcrumbs'))
				?>
			</div><!--/.col -->
		</div><!--/.row -->

		<?php
		if(\have_posts()) {
			while(\have_posts()) {
				\the_post();
				?>
				<div class="row main-content">
					<!--<div class="<?php //echo $contentColClass; ?>">-->
					<div class="<?php echo \WordPress\Themes\EveOnline\eve_get_mainContentColClasses(); ?>">
						<div class="content">
							<header>
								<?php
								if(\is_front_page()) {
									?>
									<h1><?php echo \get_bloginfo('name'); ?></h1>
									<?php
								} else {
									?>
									<h1><?php \the_title(); ?></h1>
									<?php
								} // END if(\is_front_page())
								?>
							</header>
							<article class="post clearfix" id="post-<?php \the_ID(); ?>">
								<?php
								/**
								 * Let's see if we are by any chance in a Video Page
								 */
								$isVideoGalleryPage = \get_post_meta($post->ID, 'eve_page_is_video_gallery_page', true);
								if($isVideoGalleryPage) {
									$videoUrl = \get_post_meta($post->ID, 'eve_page_video_url', true);
									$oEmbed = \wp_oembed_get($videoUrl);

									echo $oEmbed;
								} // END if($isVideoGalleryPage)

								echo the_content();
								?>
							</article>
						</div> <!-- /.content -->
					</div> <!-- /.col -->
				<?php
			} // END while(have_posts())
		} // END if(have_posts())

		if(\WordPress\Themes\EveOnline\eve_has_sidebar('sidebar-page')) {
			?>
			<div class="col-lg-3 col-md-3 col-sm-3 col-3 sidebar-wrapper">
				<?php \get_sidebar('page'); ?>
			</div><!--/.col -->
			<?php
		} // END if(\WordPress\Themes\EveOnline\eve_has_sidebar('sidebar-page'))

		if(\WordPress\Themes\EveOnline\eve_has_sidebar('sidebar-general')) {
			?>
			<div class="col-lg-3 col-md-3 col-sm-3 col-3 sidebar-wrapper">
				<?php \get_sidebar('general'); ?>
			</div><!--/.col -->
			<?php
		} // END if(\WordPress\Themes\EveOnline\eve_has_sidebar('sidebar-general'))
		?>
	</div> <!--/.row .main-content -->
</div><!-- container -->

<?php \get_footer(); ?>