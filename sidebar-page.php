<?php defined('ABSPATH') or die(); ?>

<section class="sidebar-page">
    <?php
    if(\function_exists('\dynamic_sidebar')) {
        \dynamic_sidebar('sidebar-page');
    }
    ?>
</section>
