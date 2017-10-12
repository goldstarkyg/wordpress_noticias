<?php
/**
 * The template part for displaying the site header authentication box
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

do_action( 'boombox/auth_box_icons', boombox_get_header_settings() ); ?>