<?php
defined('ABSPATH') or die();

\get_header();
?>

<div class="container main">
	<?php
	$breadcrumbNavigation = \WordPress\Themes\EveOnline\Helper\NavigationHelper::getBreadcrumbNavigation();
	if(!empty($breadcrumbNavigation)) {
		?>
		<!--
		// Breadcrumb Navigation
		-->
		<!--<div class="row">-->
		<div class="clearfix">
			<div class="col-md-12 breadcrumb-wrapper">
				<?php echo $breadcrumbNavigation; ?>
			</div><!--/.col -->
		</div><!--/.row -->
		<?php
	} // END if(!empty($breadcrumbNavigation))
	?>

	<!--<div class="row main-content">-->
	<div class="main-conten clearfixt">
		<div class="<?php echo \WordPress\Themes\EveOnline\Helper\PostHelper::getMainContentColClasses(); ?> content-wrapper">
			<div class="content content-inner content-index content-loop">
				<?php
				if(\have_posts()) {
					if(\get_post_type() === 'post') {
						$uniqueID = \uniqid();

						echo '<div class="gallery-row row">';
						echo '<ul class="bootstrap-gallery bootstrap-post-loop-gallery bootstrap-post-loop-gallery-' . $uniqueID . ' clearfix">';
					} // END if(\get_post_type() === 'post')

					while(\have_posts()) {
						\the_post();

						if(\get_post_type() === 'post') {
							echo '<li>';
						}

						\get_template_part('content', \get_post_format());

						if(\get_post_type() === 'post') {
							echo '</li>';
						}
					} // END while (have_posts())

					if(\get_post_type() === 'post') {
						echo '</ul>';
						echo '</div>';

						echo '<script type="text/javascript">
								jQuery(document).ready(function() {
									jQuery("ul.bootstrap-post-loop-gallery-' . $uniqueID . '").bootstrapGallery({
										"classes" : "' . \WordPress\Themes\EveOnline\Helper\PostHelper::getLoopContentClasses() . '",
										"hasModal" : false
									});
								});
								</script>';
					} // END if(\get_post_type() === 'post')
				} else {

				} // END if(have_posts())

				if(\function_exists('\wp_pagenavi')) {
					\wp_pagenavi();
				} else {
					\WordPress\Themes\EveOnline\Helper\NavigationHelper::getContentNav('nav-below');
				} // END if(\function_exists('wp_pagenavi'))
				?>
			</div>
		</div><!--/.col -->

		<?php
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
	</div> <!--/.row -->
</div><!-- container -->

<?php \get_footer(); ?>
