<?php
/**
 * The template for displaying the single post
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if (!defined('ABSPATH')) {
	die('No direct script access allowed');
}

get_header();

$boombox_featured_image_size = 'boombox_image768';
$boombox_single_options = boombox_get_single_page_settings($boombox_featured_image_size);
$boombox_featured_video = $boombox_single_options['featured_video'];
$boombox_template_options = $boombox_single_options['template_options'];
$boombox_post_template = $boombox_single_options['post_template'];
$boombox_is_nsfw_post = $boombox_single_options['is_nsfw'];
$boombox_article_classes = $boombox_single_options['classes'];
$boombox_disable_strip = $boombox_single_options['disable_strip'];
$boombox_enable_sidebar = $boombox_single_options['enable_sidebar'];

if (!$boombox_disable_strip):
	get_template_part('template-parts/featured', 'strip');
endif;

boombox_the_advertisement('boombox-single-before-content', 'large');

if ('full-width' == $boombox_post_template && have_posts()): the_post();
	$boombox_fimage_style = '';
	if ($boombox_template_options['media'] && boombox_has_post_thumbnail() && boombox_show_thumbail() ) :
		$thumbnail_size = 'full';
		$boombox_thumbnail_url = wp_get_attachment_image_src( get_post_thumbnail_id(), $thumbnail_size );
		$boombox_thumbnail_url = isset( $boombox_thumbnail_url[0] ) ? $boombox_thumbnail_url[0] : apply_filters( 'boombox/post-default-thumbnail', '', $thumbnail_size );
		$boombox_fimage_style = $boombox_thumbnail_url ? 'style="background-image: url(\'' . esc_url( $boombox_thumbnail_url ) . '\')"' : '';
	endif; ?>
	<div class="post-featured-image" <?php echo $boombox_fimage_style; ?>>
		<div class="content">
			<!-- entry-header -->
			<header class="entry-header">
				<?php get_template_part('template-parts/single/single', 'header'); ?>
			</header>
		</div>
	</div>
	<?php
	rewind_posts();
endif; ?>

<div class="container main-container">
	<div id="main" class="site-main" role="main">
		<?php if (have_posts()): the_post(); ?>

			<?php

			get_template_part('template-parts/single/content');

			// If comments are open or we have at least one comment, load up the comment template.
			if (comments_open() || get_comments_number()) :
				comments_template();
			endif;

			do_action( 'boombox_single_before_navigation' );

			boombox_the_advertisement('boombox-single-before-navigation', 'large');

			if ($boombox_template_options['navigation']) :
				get_template_part('template-parts/single/navigation');
			endif;

			if ($boombox_template_options['floating_navbar']) :
				get_template_part( 'template-parts/single/fixed', 'header' );
			endif;

			if( $boombox_template_options['side_navigation'] ) :
				get_template_part( 'template-parts/single/fixed', 'navigation' );
			endif;

		endif; ?>


	</div>

	<?php if ('no-sidebar' != $boombox_post_template && $boombox_enable_sidebar):
		get_sidebar();
	endif; ?>
</div>

<?php get_footer(); ?>
