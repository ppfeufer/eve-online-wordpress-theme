<?php defined('ABSPATH') or die(); ?>

<section class="sidebar-general">
	<?php
	if(\function_exists('\dynamic_sidebar')) {
		\dynamic_sidebar('sidebar-general');
	} // END if(function_exists('dynamic_sidebar'))
	?>
</section>