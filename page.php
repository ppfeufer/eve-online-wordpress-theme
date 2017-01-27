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
				\WordPress\Themes\EveOnline\Helper\NavigationHelper::getBreadcrumbs();
				?>
			</div><!--/.col -->
		</div><!--/.row -->

		<?php
		if(\have_posts()) {
			while(\have_posts()) {
				\the_post();
				?>
				<div class="row main-content">
					<div class="<?php echo \WordPress\Themes\EveOnline\Helper\PostHelper::getMainContentColClasses(); ?>">
						<div class="content content-page">
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

		if(\WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-page') || \WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-general')) {
			?>
			<div class="col-lg-3 col-md-3 col-sm-3 col-3 sidebar-wrapper">
			<?php
			if(\WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-page')) {
				\get_sidebar('page');
			} // END if(\WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-page'))

			if(\WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-general')) {
				\get_sidebar('general');
			} // END if(\WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-general'))
			?>
			</div><!--/.col -->
			<?php
		} // END if(\WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-page') || \WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-general'))
		?>
	</div> <!--/.row .main-content -->
</div><!-- container -->

<?php \get_footer(); ?>