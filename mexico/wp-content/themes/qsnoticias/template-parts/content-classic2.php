<?php
/**
 * The template part for displaying post item for "classic2" listing type
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$boombox_featured_image_size = 'boombox_image768x450';
$boombox_post_classes        = 'post';
$boombox_has_post_thumbnail  = boombox_has_post_thumbnail();
$boombox_template_options    = boombox_get_template_grid_elements_options();
$boombox_featured_video      = boombox_get_post_featured_video( get_the_ID(), $boombox_featured_image_size );
$boombox_show_media          = apply_filters( 'boombox/loop-item/show-media', ( $boombox_template_options['media'] && ( $boombox_has_post_thumbnail || $boombox_featured_video ) ), $boombox_template_options['media'], ( $boombox_has_post_thumbnail || $boombox_featured_video ), 'content-classic2' );

if ( ! $boombox_show_media ) {
	$boombox_post_classes .= ' no-thumbnail';
}

$permalink = get_permalink();
$url = apply_filters( 'boombox_loop_item_url', $permalink, get_the_ID() );
$target = apply_filters( 'boombox_loop_item_url_target', '', $permalink, $url );

do_action( 'boombox/loop-item/before-content', 'content-classic2' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $boombox_post_classes ); ?>>

		<!-- thumbnail -->
		<div class="post-thumbnail">
			<?php
			if ( apply_filters( 'boombox/loop-item/show-badges', $boombox_template_options['badges'] ) ) {
				$badges_list = boombox_get_post_badge_list();
				echo $badges_list['badges'];
			}

			if ( $boombox_show_media ) {
				if ($boombox_featured_video) {
					echo $boombox_featured_video;
				} else { ?>
				<a href="<?php echo $url; ?>" title="<?php echo esc_attr( the_title_attribute( array( 'echo' => false ) ) ); ?>" class="fixed-height" <?php echo $target; ?>>
					<?php the_post_thumbnail( $boombox_featured_image_size ); ?>
				</a>
				<?php }
			} ?>
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

			if( apply_filters( 'boombox/loop-item/show-post-author-meta', true ) ) {
				boombox_post_author_meta(array(
					'author' => $boombox_template_options['author'],
					'author_args' => array('with_avatar' => true),
					'date' => $boombox_template_options['date']
				));
			}

			do_action( 'boombox/loop-item/content-end' );

			?>

		</header>
		<!-- entry-header -->

		<?php if ( apply_filters( 'boombox/loop-item/show-post-excerpt', $boombox_template_options['excerpt'] ) ) { ?>
			<div class="entry-content"><?php echo wp_trim_excerpt(); ?></div>
		<?php } ?>

	</div>

	<!-- entry-footer -->
	<footer class="entry-footer">
		<div class="post-share-box">
			<?php get_template_part( 'template-parts/single/share', 'box' ); ?>
		</div>
	</footer>
	<!-- entry-footer -->

	<?php do_action( 'boombox_affiliate_content' ); ?>

</article>

<?php do_action( 'boombox/loop-item/after-content', 'content-classic2' ); ?>