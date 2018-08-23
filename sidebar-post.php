<?php defined('ABSPATH') or die(); ?>

<section class="sidebar-posts">
    <?php
    if(\function_exists('\dynamic_sidebar')) {
        \dynamic_sidebar('sidebar-post');
    }
    ?>
</section>
