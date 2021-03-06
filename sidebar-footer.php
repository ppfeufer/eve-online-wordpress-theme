<?php

/*
 * Copyright (C) 2018 p.pfeufer
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('ABSPATH') or die();
?>

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
