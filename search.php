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
		</div><!--/.col -->
	</div><!--/.row -->

	<div class="row main-content">
		<div class="<?php echo \WordPress\Themes\EveOnline\Helper\PostHelper::getMainContentColClasses(); ?>">
			<div class="content content-search">
				<?php
				if(\have_posts()) {
					?>
					<header class="post-title">
						<h1><?php \printf(\__('Search Results for: %s', 'eve-online'), '<span>' . \get_search_query() . '</span>'); ?></h1>
					</header>
					<?php
				} else {
					?>
					<header class="post-title">
						<h1><?php \_e('No Results Found', 'eve-online'); ?></h1>
					</header>
					<?php
				} // END if(have_posts())

				if(\have_posts()) {
					while(\have_posts()) {
						\the_post();
						\get_template_part('content', \get_post_format());
					} // END while(have_posts())
				} else {
					?>
					<p class="lead">
						<?php \_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps you should try again with a different search term.', 'eve-online'); ?>
					</p>

					<div class="well">
						<?php \get_search_form(); ?>
					</div><!--/.well -->
					<?php
				} // END if(have_posts())
				?>
			</div> <!-- /.content -->
		</div> <!-- /.col -->

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