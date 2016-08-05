<?php
defined('ABSPATH') or die();

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

	<div class="row main-content">
		<div class="<?php echo \WordPress\Themes\EveOnline\eve_get_mainContentColClasses(); ?>">
			<div class="content">
				<?php
				if(\have_posts()) {
					echo \get_post_format();
					while(\have_posts()) {
						\the_post();
						\get_template_part('content', \get_post_format());
					} // END while (have_posts())
				} else {

				} // END if(have_posts())

				if(\function_exists('\wp_pagenavi')) {
					\wp_pagenavi();
				} else {
					\WordPress\Themes\EveOnline\eve_content_nav('nav-below');
				} // END if(\function_exists('wp_pagenavi'))
				?>
			</div>
		</div><!--/.col -->

		<?php
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
	</div> <!--/.row -->
</div><!-- container -->

<?php \get_footer(); ?>