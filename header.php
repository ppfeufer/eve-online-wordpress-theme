<?php defined('ABSPATH') or die(); ?>
<!DOCTYPE html>
<html <?php \language_attributes(); ?>>
	<head>
		<meta charset="<?php \bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php \wp_title('»', true, 'right'); ?></title>

		<link rel="pingback" href="<?php \bloginfo('pingback_url'); ?>" />

		<!--[if lt IE 9]>
		<script src="<?php echo \get_template_directory_uri(); ?>/js/html5.min.js" type="text/javascript"></script>
		<script src="<?php echo \get_template_directory_uri(); ?>/js/respond.min.js" type="text/javascript"></script>
		<![endif]-->

		<?php \wp_head(); ?>
	</head>

	<body <?php \body_class('no-js'); ?> id="pagetop">
		<header>
			<!-- Blog Name & Logo -->
			<div class="top-main-menu">
				<div class="container">
					<div class="row">
						<!-- Logo -->
						<div class="<?php echo \WordPress\Themes\EveOnline\eve_get_headerColClasses(); ?> brand clearfix">
							<?php
							$options = \get_option('eve_theme_options', \WordPress\Themes\EveOnline\eve_get_options_default());

							if(!empty($options['name'])) {
								$eveApi = new \WordPress\Themes\EveOnline\Helper\EveApiHelper;
								$siteLogo = $eveApi->getEntityLogoByName($options['name']);

								if($siteLogo !== false) {
									?>
									<div class="site-logo float-left">
										<a href="<?php \bloginfo('url'); ?>"><img src="<?php echo $siteLogo; ?>" class="img-responsive" alt="<?php echo \get_bloginfo('name'); ?>"></a>
									</div>
									<?php
								} // END if($siteLogo !== false)
							} // END if(!empty($options['name']))
							?>
							<div class="site-title">
								<span class="site-name"><?php echo \get_bloginfo('name'); ?></span>
								<span class="site-description"><?php echo \get_bloginfo('description'); ?></span>
							</div>
						</div>

						<!-- Navigation Header -->
						<div class="col-sm-3 col-md-3 col-sm-12 visible-sm visible-md visible-lg">
							<div class="top-head-menu">
								<nav class="navbar navbar-default navbar-headermenu" role="navigation">
									<?php
									if(\has_nav_menu('header-menu')) {
										\wp_nav_menu(array(
											'menu' => '',
											'theme_location' => 'header-menu',
											'depth' => 1,
											'container' => false,
											'menu_class' => 'header-menu nav navbar-nav',
											'fallback_cb' => '\WordPress\Themes\EveOnline\Addons\BootstrapMenuWalker::fallback',
											'walker' => new \WordPress\Themes\EveOnline\Addons\BootstrapMenuWalker
										));
									} // END if(has_nav_menu('header-menu'))
									?>
								</nav>
							</div>
						</div>

						<!-- Header Widget from Theme options -->
						<?php
						if(\WordPress\Themes\EveOnline\eve_has_sidebar('header-widget-area')) {
							?>
							<div class="col-md-3 col-sm-12">
								<div class="row">
									<div class="col-sm-12 header-widget">
										<?php
										if(\function_exists('\dynamic_sidebar')) {
											\dynamic_sidebar('header-widget-area');
										} // END if(\function_exists('dynamic_sidebar'))
										?>
									</div>
								</div>
							</div>
							<?php
						} // END if(\EveOnline\eve_has_sidebar('header-widget-area'))
						?>
					</div>

					<!-- Navigation Main -->
					<?php
					if(\has_nav_menu('main-menu')) {
						?>
						<!-- Menu -->
						<div class="row">
							<div class="col-md-12">
								<div class="top-main-menu">
									<nav class="navbar navbar-default" role="navigation">
										<!-- Brand and toggle get grouped for better mobile display -->
										<div class="navbar-header">
											<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
												<span class="sr-only"><?php \__('Toggle navigation', 'eve-online'); ?></span>
												<span class="icon-bar"></span>
												<span class="icon-bar"></span>
												<span class="icon-bar"></span>
											</button>
											<span class="navbar-toggled-title visible-xs float-right"><?php \printf(\__('Menu', 'eve-online')) ?></span>
										</div>

										<!-- Collect the nav links, forms, and other content for toggling -->
										<div class="collapse navbar-collapse navbar-ex1-collapse">
											<?php
											\wp_nav_menu(array(
												'menu' => '',
												'theme_location' => 'main-menu',
												'depth' => 2,
												'container' => false,
												'menu_class' => 'nav navbar-nav main-navigation',
												'fallback_cb' => '\WordPress\Themes\EveOnline\Addons\BootstrapMenuWalker::fallback',
												'walker' => new \WordPress\Themes\EveOnline\Addons\BootstrapMenuWalker
											));
											?>
										</div><!-- /.navbar-collapse -->
									</nav>
								</div><!-- /.top-main-menu -->
							</div>
						</div>
						<?php
					} // END if(has_nav_menu('main-menu'))

					if(\is_single() && \get_post_type() === 'post' && \has_post_thumbnail()) {
						?>
						<figure class="post-header-image">
							<?php
							if(\function_exists('\fly_get_attachment_image')) {
								echo \fly_get_attachment_image(\get_post_thumbnail_id(), 'header-image');
							} else {
								\the_post_thumbnail('header-image');
							} // END if(\function_exists('\fly_get_attachment_image'))
							?>
						</figure>
						<?php
					} // END if(!\is_home() && \get_post_type() === 'post' && \has_post_thumbnail())

					/**
					 * Render our Slider, if we have one
					 */
					\do_action('eve_render_header_slider');
					?>
				</div><!-- /.container -->
			</div><!-- /.top-main-menu -->
		</header>
		<!-- End Header. Begin Template Content -->

		<?php
		if((\is_front_page()) && (\is_paged() == false)) {
			if(\WordPress\Themes\EveOnline\eve_has_sidebar('home-column-1') || \WordPress\Themes\EveOnline\eve_has_sidebar('home-column-2') || \WordPress\Themes\EveOnline\eve_has_sidebar('home-column-3') || \WordPress\Themes\EveOnline\eve_has_sidebar('home-column-4')) {
				?>
				<!--
				// Marketing Stuff
				-->
				<!--
				// Home Widgets
				-->
				<div class="home-widget-area">
					<div class="home-widget-wrapper">
						<div class="row">
							<div class="container home-widgets">
								<?php \get_sidebar('home'); ?>
							</div>
						</div>
					</div>
				</div><!--/.row -->
				<?php
			} // END if(\EveOnline\eve_has_sidebar('home-left') || \EveOnline\eve_has_sidebar('home-middle') || \EveOnline\eve_has_sidebar('home-right'))
		} // END if((\is_front_page()) && (\is_paged() == false))
		?>

		<main>