<?php defined('ABSPATH') or die(); ?>

<div class="row footer">
    <div class="col-sm-6 col-md-3">
        <?php
        if(\function_exists('\dynamic_sidebar')) {
            \dynamic_sidebar('footer-column-1');
        } // END if(function_exists('dynamic_sidebar'))
        ?>
    </div>

    <div class="col-sm-6 col-md-3">
        <?php
        if(\function_exists('\dynamic_sidebar')) {
            \dynamic_sidebar('footer-column-2');
        } // END if(function_exists('dynamic_sidebar'))
        ?>
    </div>

    <div class="col-sm-6 col-md-3">
        <?php
        if(\function_exists('\dynamic_sidebar')) {
            \dynamic_sidebar('footer-column-3');
        } // END if(function_exists('dynamic_sidebar'))
        ?>
    </div>

    <div class="col-sm-6 col-md-3">
        <?php
        if(\function_exists('\dynamic_sidebar')) {
            \dynamic_sidebar('footer-column-4');
        } // END if(function_exists('dynamic_sidebar'))
        ?>
    </div>
</div>
