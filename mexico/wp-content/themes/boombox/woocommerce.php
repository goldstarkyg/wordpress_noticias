<?php
/**
 * The template for displaying the page with right sidebar
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

get_header(); ?>

	<div class="container">
		<?php boombox_the_advertisement( 'boombox-page-before-content', 'large' ); ?>
	</div>

	<div class="container">

		<div id="main" class="site-main" role="main">

			<div class="main-container">

				<?php do_action( 'boombox_before_main_container' ); ?>

				<?php  get_template_part('template-parts/content', 'woocommerce'); ?>

				<?php boombox_the_advertisement( 'boombox-page-after-content', 'large' ); ?>

			</div>

		</div>

		<?php if( apply_filters( 'boombox_is_sidebar_enabled', true ) ) { get_sidebar(); } ?>

	</div>

<?php get_footer(); ?>