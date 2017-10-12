<?php
/**
 * The template part for displaying the site featured area
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$boombox_featured_query 	= boombox_get_featured_area_items();
$boombox_template_options   = boombox_get_template_grid_elements_options();
$featured_type 				= boombox_get_theme_option( is_archive() ? 'layout_archive_featured_type' : 'layout_page_featured_type' );

if ( null != $boombox_featured_query && $boombox_featured_query->found_posts ):

	$boombox_featured_area_classes = array();
	$boombox_featured_count = count( $boombox_featured_query->posts );

	if( '1' == $featured_type ) {
		$newsletter_html = boombox_mailchimp_form( array( 'echo' => false ) );
		if( $newsletter_html ) {
			$boombox_featured_count++;
			$boombox_featured_area_classes[] = 'with-widget';
		}
	} else {
		$boombox_featured_area_classes[] = 'item-' . $boombox_featured_count;
		if( is_archive() && $featured_type == 2 ) {
			$boombox_featured_area_classes[] = 'type-category';
		}
	} ?>
	<div class="container">
		<div class="featured-area hfeed <?php echo esc_attr( implode( ' ', $boombox_featured_area_classes ) ); ?>">
			<?php while ( $boombox_featured_query->have_posts() ) {
				$boombox_featured_query->the_post();
				$featured_image_size = 'boombox_image768x450';

				$boombox_thumb_style = '';
				if ( boombox_has_post_thumbnail() ) {
					$boombox_thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id(), $featured_image_size );
					$boombox_thumbnail_url = isset( $boombox_thumbnail_url[0] ) ? $boombox_thumbnail_url[0] : apply_filters( 'boombox/post-default-thumbnail', '', $featured_image_size );
					$boombox_thumb_style = $boombox_thumbnail_url ? 'style="background-image: url(\'' . esc_url($boombox_thumbnail_url) . '\')"' : '';
				}
				?>
				<div class="featured-item hentry" <?php echo $boombox_thumb_style; ?>>
					<?php
						$badges_list = boombox_get_post_badge_list( array( 'post_id' => get_the_ID(), 'badges_count' => 2 ) );
						echo $badges_list['badges'];
					?>
					<a class="link" href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"></a>
					<div class="featured-content">
						<div class="post-meta">
							<?php
								if ( $boombox_template_options['share_count'] ) {
									boombox_post_share_count();
								}

								boombox_post_view_vote_count( get_the_ID(), array( 'show_views' => $boombox_template_options['views_count'], 'show_votes' => $boombox_template_options['votes_count'] ) );
							?>
						</div>
						<h2 class="entry-title">
							<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a>
						</h2>
						<?php if ( $boombox_template_options['author'] ) { ?>
						<span class="byline">
							<span class="author vcard">
								<span class="fn n"><?php esc_html_e( 'by', 'boombox' ); ?>&nbsp;<?php the_author_posts_link(); ?></span>
							</span>
						</span>
						<?php } ?>

						<?php if ( $boombox_template_options['date'] ) {
							boombox_post_date();
						} ?>
					</div>
				</div>
			<?php } ?>

			<?php if( '1' == $featured_type && $newsletter_html ) { ?>
			<div class="featured-item"><?php echo $newsletter_html; ?></div>
			<?php } ?>

		</div>
	</div>
<?php endif;
wp_reset_postdata(); ?>