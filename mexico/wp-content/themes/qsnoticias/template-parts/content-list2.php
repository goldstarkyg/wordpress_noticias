<?php
/**
 * The template part for displaying post item for "small list" listing type
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$boombox_featured_image_size = 'thumbnail';
$boombox_post_classes        = 'post';
$boombox_has_post_thumbnail  = boombox_has_post_thumbnail();
$boombox_template_options    = boombox_get_template_grid_elements_options();
$boombox_show_media          = apply_filters( 'boombox/loop-item/show-media', ( $boombox_template_options['media'] && $boombox_has_post_thumbnail ), $boombox_template_options['media'], $boombox_has_post_thumbnail, 'content-list2' );

if ( ! $boombox_show_media ) {
	$boombox_post_classes .= ' no-thumbnail';
}

$permalink = get_permalink();
$url = apply_filters( 'boombox_loop_item_url', $permalink, get_the_ID() );
$target = apply_filters( 'boombox_loop_item_url_target', '', $permalink, $url );

do_action( 'boombox/loop-item/before-content', 'content-list2' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $boombox_post_classes ); // aqui va el codigo para el tipo boton?> style="background-color:#fcfcfc;border-style: solid; border-width: 1px; border-color:#e6e6e6">


		<!-- thumbnail -->
		<!--<div class="post-thumbnail">--><div >
			<?php

			if ( apply_filters( 'boombox/loop-item/show-badges', $boombox_template_options['badges'] ) ) {
				$badges_list = boombox_get_post_badge_list();
				echo $badges_list['badges'];
			}

			?>
			<?php if ( $boombox_show_media ) { ?>
			<a href="<?php echo $url; ?>" title="<?php echo esc_attr( the_title_attribute( array( 'echo' => false ) ) ); ?>" <?php echo $target; ?>>
				<?php the_post_thumbnail( $boombox_featured_image_size ); ?>
			</a>
			<div class="post-meta">
				<?php
				if ( apply_filters( 'boombox/loop-item/show-share-count', $boombox_template_options['share_count'] ) ) {
					boombox_post_share_count();
				} ?>
			</div>
			<?php } ?>
		</div>
		<!-- thumbnail -->

	<div class="content">
		<!-- entry-header -->
		<header class="entry-header">
			<?php

			do_action( 'boombox/loop-item/content-start' );

			if ( apply_filters( 'boombox/loop-item/show-categories', $boombox_template_options['categories'] ) ) {
				boombox_categories_list();
			}

			if ( apply_filters( 'boombox/loop-item/show-comments-count', ( comments_open() && $boombox_template_options['comments_count'] ) ) ) {
				boombox_post_comments();
			}

			the_title( sprintf( '<h2 class="entry-title"><a href="%1$s" rel="bookmark" %2$s>', $url, $target ), '</a></h2>' );

			if ( apply_filters( 'boombox/loop-item/show-subtitle', $boombox_template_options['subtitle'] ) ) {
				boombox_the_post_subtitle();
			}

			do_action( 'boombox_affiliate_content' );

			if( apply_filters( 'boombox/loop-item/show-post-author-meta', true ) ) {
				boombox_post_author_meta( array(
					'author' 		=> $boombox_template_options['author'],
					'author_args' 	=> array( 'with_avatar' => true ),
					'date' 			=> $boombox_template_options['date']
				) );
			}

			do_action( 'boombox/loop-item/content-end' );
			?>

		</header>
		<!-- entry-header -->
	</div>

</article>

<?php do_action( 'boombox/loop-item/after-content', 'content-list2' ); ?>