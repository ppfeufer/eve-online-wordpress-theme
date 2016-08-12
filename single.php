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
			} // END if(function_exists('\EveOnline\eve_breadcrumbs'))
			?>
		</div><!--/.span12 -->
	</div><!--/.row -->

	<div class="row">
		<div class="<?php echo \WordPress\Themes\EveOnline\eve_get_mainContentColClasses(); ?>">
			<div class="content single">
				<?php
				if(\have_posts()) {
					while(\have_posts()) {
						\the_post();
						\get_template_part('content-single');
					} // END while(have_posts())
				} // END if(have_posts())
				?>
			</div> <!-- /.content -->
		</div> <!-- /.col-lg-9 /.col-md-9 /.col-sm-9 /.col-9 -->

		<?php
		if(\WordPress\Themes\EveOnline\eve_has_sidebar('sidebar-post')) {
			?>
			<div class="col-lg-3 col-md-3 col-sm-3 col-3 sidebar-wrapper">
				<?php \get_sidebar('post'); ?>
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
	</div> <!-- /.row -->
</div> <!-- /.container -->

<?php \get_footer(); ?>