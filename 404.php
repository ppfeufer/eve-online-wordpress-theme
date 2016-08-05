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
			} // END if(\function_exists('\WordPress\Themes\EveOnline\eve_breadcrumbs'))
			?>
		</div><!--/.col -->
	</div><!--/.row -->

	<div class="row main-top">
		<div class="<?php echo \WordPress\Themes\EveOnline\eve_get_mainContentColClasses(); ?>">
			<header>
				<h1>
					<a href="<?php \the_permalink() ?>" rel="bookmark" title="<?php \the_title(); ?>"><?php \the_title(); ?></a>
				</h1>
			</header><!-- / header -->
		</div><!--/.col -->
	</div><!--/.row -->

	<div class="row main-content">
		<div class="<?php echo \WordPress\Themes\EveOnline\eve_get_mainContentColClasses(); ?>">
			<div class="content main">
				<header class="page-title">
					<h1><?php \_e('This is Embarrassing', 'eve-online'); ?></h1>
				</header>

				<p class="lead">
					<?php \_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links below, can help.', 'eve-online'); ?>
				</p>

				<div class="well">
					<?php \get_search_form(); ?>
				</div>

				<h2><?php \_e('All Pages', 'eve-online'); ?></h2>

				<?php
				\wp_page_menu();
				\the_widget('WP_Widget_Recent_Posts');
				?>

				<h2><?php \_e('Categories', 'eve-online'); ?></h2>

				<ul>
					<?php
					\wp_list_categories(array(
						'orderby' => 'count',
						'order' => 'DESC',
						'show_count' => 1,
						'title_li' => '',
						'number' => 100
					));
					?>
				</ul>
			</div> <!-- /.content -->
		</div> <!-- /.col -->

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