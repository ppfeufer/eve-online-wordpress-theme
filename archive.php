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

	<div class="row main-content">
		<div class="<?php echo \WordPress\Themes\EveOnline\eve_get_mainContentColClasses(); ?>">
			<div class="content content-archive">
				<header class="page-title">
					<h1>
						<?php
						if(\is_day()) {
							\printf(\__('Daily Archives: %s', 'eve-online'), '<span>' . \get_the_date() . '</span>');
						} elseif(is_month()) {
							\printf(\__('Monthly Archives: %s', 'eve-online'), '<span>' . \get_the_date(\_x('F Y', 'monthly archives date format', 'eve-online')) . '</span>');
						} elseif(is_year()) {
							\printf(\__('Yearly Archives: %s', 'eve-online'), '<span>' . \get_the_date(_x('Y', 'yearly archives date format', 'eve-online')) . '</span>');
						} elseif(is_tag()) {
							\printf(\__('Tag Archives: %s', 'eve-online'), '<span>' . \single_tag_title('', false) . '</span>');
							// Show an optional tag description
							$tag_description = \tag_description();
							if($tag_description) {
								echo \apply_filters('tag_archive_meta', '<div class="tag-archive-meta">' . $tag_description . '</div>');
							} // END if($tag_description)
						} elseif(\is_category()) {
							\printf(\__('Category Archives: %s', 'eve-online'), '<span>' . \single_cat_title('', false) . '</span>');
						} else {
							\_e('Blog Archives', 'eve-online');
						} //END if(is_day())
						?>
					</h1>
					<?php
					// Show an optional category description
					$category_description = \category_description();
					if($category_description) {
						echo \apply_filters('category_archive_meta', '<div class="category-archive-meta">' . $category_description . '</div>');
					} // END if($category_description)
					?>
				</header>
				<?php
				if(\have_posts()) {
					while(\have_posts()) {
						\the_post();
						\get_template_part('content', \get_post_format());
					} // END while(have_posts())
				} // END if(have_posts())

				if(\function_exists('wp_pagenavi')) {
					\wp_pagenavi();
				} else {
					\WordPress\Themes\EveOnline\eve_content_nav('nav-below');
				} // END if(\function_exists('wp_pagenavi'))s
				?>
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