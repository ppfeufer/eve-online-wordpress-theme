<?php defined('ABSPATH') or die(); ?>

<div class="row">
    <div class="col-sm-6 col-md-3">
        <?php
        if(\function_exists('\dynamic_sidebar')) {
            \dynamic_sidebar('home-column-1');
        }
        ?>
    </div>

    <div class="col-sm-6 col-md-3">
        <?php
        if(\function_exists('\dynamic_sidebar')) {
            \dynamic_sidebar('home-column-2');
        }
        ?>
    </div>

    <div class="col-sm-6 col-md-3">
        <?php
        if(\function_exists('\dynamic_sidebar')) {
            \dynamic_sidebar('home-column-3');
        }
        ?>
    </div>

    <div class="col-sm-6 col-md-3">
        <?php
        if(\function_exists('\dynamic_sidebar')) {
            \dynamic_sidebar('home-column-4');
        }
        ?>
    </div>
</div>
