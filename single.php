<?php
defined('ABSPATH') or die();

\get_header();
?>

<div class="container main">
	<div class="row">
		<div class="col-md-12">
			<?php
			\WordPress\Themes\EveOnline\Helper\NavigationHelper::getBreadcrumbs();
			?>
		</div><!--/.span12 -->
	</div><!--/.row -->

	<div class="row">
		<div class="<?php echo \WordPress\Themes\EveOnline\Helper\PostHelper::getMainContentColClasses(); ?>">
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
		if(\WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-post') || \WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-general')) {
			?>
			<div class="col-lg-3 col-md-3 col-sm-3 col-3 sidebar-wrapper">
			<?php
			if(\WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-post')) {
				\get_sidebar('post');
			} // END if(\WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-post'))

			if(\WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-general')) {
				\get_sidebar('general');
			} // END if(\WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-general'))
			?>
			</div><!--/.col -->
			<?php
		} // END if(\WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-post') || \WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('sidebar-general'))
		?>
	</div> <!-- /.row -->
</div> <!-- /.container -->

<?php \get_footer(); ?>