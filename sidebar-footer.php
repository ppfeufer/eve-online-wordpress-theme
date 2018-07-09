<?php defined('ABSPATH') or die(); ?>

<div class="row footer">
    <div class="col-sm-6 col-md-3">
        <?php
        if(\function_exists('\dynamic_sidebar')) {
            \dynamic_sidebar('footer-column-1');
        }
        ?>
    </div>

    <div class="col-sm-6 col-md-3">
        <?php
        if(\function_exists('\dynamic_sidebar')) {
            \dynamic_sidebar('footer-column-2');
        }
        ?>
    </div>

    <div class="col-sm-6 col-md-3">
        <?php
        if(\function_exists('\dynamic_sidebar')) {
            \dynamic_sidebar('footer-column-3');
        }
        ?>
    </div>

    <div class="col-sm-6 col-md-3">
        <?php
        if(\function_exists('\dynamic_sidebar')) {
            \dynamic_sidebar('footer-column-4');
        }
        ?>
    </div>
</div>
