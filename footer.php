<?php
defined('ABSPATH') or die();

//$options = \get_option('eve_theme_options', \WordPress\Themes\EveOnline\eve_get_options_default());
$options = \get_option('eve_theme_options', \WordPress\Themes\EveOnline\Helper\ThemeHelper::getThemeDefaultOptions());
?>
		</main>
		<footer>
			<div class="footer-wrapper">
				<div class="row">
					<!--<div class="footer-divider"></div>-->
					<div class="container">
						<?php
//						if(\WordPress\Themes\EveOnline\eve_has_sidebar('footer-column-1') || \WordPress\Themes\EveOnline\eve_has_sidebar('footer-column-2') || \WordPress\Themes\EveOnline\eve_has_sidebar('footer-column-3') || \WordPress\Themes\EveOnline\eve_has_sidebar('footer-column-4')) {
						if(\WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('footer-column-1') || \WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('footer-column-2') || \WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('footer-column-3') || \WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('footer-column-4')) {
							\get_sidebar('footer');
						} // END if(\WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('footer-column-1') || \WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('footer-column-2') || \WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('footer-column-3') || \WordPress\Themes\EveOnline\Helper\ThemeHelper::hasSidebar('footer-column-4'))
						?>
					</div>
				</div>
			</div>

			<div class="copyright-wrapper">
				<div class="row ">
					<div class="container">
						<div class="row copyright">
							<div class="col-md-12">
								<div class="pull-left copyright-text">
									<?php
									if(!empty($options['footertext'])) {
										echo '<p>';
										echo stripslashes($options['footertext']);
										echo '</p>';
									} // END if(!empty($options['footertext']))
									?>
									<ul class="credit">
										<li>
											&copy; <?php echo date('Y'); ?> <a href="<?php \bloginfo('url'); ?>"><?php \bloginfo(); ?></a>
										</li>
										<li>
											(<?php
											\printf(\__('%1$s design and programming by %2$s', 'eve-online'),
												'<a href="https://github.com/ppfeufer/eve-online-wordpress-theme">EVE Online theme</a>',
												'<a href="https://gate.eveonline.com/Profile/Rounon%20Dax" rel="nofollow">Rounon Dax</a>'
											);
											?>)
										</li>
									</ul><!-- end .credit -->
								</div>

								<div class="footer-menu-wrapper">
									<?php
									if(\has_nav_menu('footer-menu')) {
										\wp_nav_menu(array(
											'menu' => '',
											'theme_location' => 'footer-menu',
											'depth' => 1,
											'container' => false,
											'menu_class' => 'footer-menu',
											'fallback_cb' => '\WordPress\Themes\EveOnline\Addons\BootstrapMenuWalker::fallback',
											'walker' => new \WordPress\Themes\EveOnline\Addons\BootstrapMenuWalker
										));
									} // END if(has_nav_menu('footer-menu'))
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="legal-wrapper">
				<div class="row ">
					<div class="container">
						<div class="row copyright">
							<div class="col-md-12">
								<h5>CCP Copyright Notice</h5>
								<p>EVE Online and the EVE logo are the registered trademarks of CCP hf. All rights are reserved worldwide. All other trademarks are the property of their respective owners. EVE Online, the EVE logo, EVE and all associated logos and designs are the intellectual property of CCP hf. All artwork, screenshots, characters, vehicles, storylines, world facts or other recognizable features of the intellectual property relating to these trademarks are likewise the intellectual property of CCP hf.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<a href="#pagetop" tabindex="-1" class="totoplink">
				<i class="icon icon-totop"></i>
				<span class="sr-hint">
					<?php \_e('back to top', 'eve-online'); ?>
				</span>
			</a>
		</footer>
		<?php \wp_footer(); ?>
	</body>
</html>