<?php
/**
 * The template for displaying the trending page
 *
 * Template Name: Trending
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

get_header();

$boombox_paged              = boombox_get_paged();
$boombox_trending_settings  = boombox_get_trending_page_settings( $boombox_paged );

if ( true != $boombox_trending_settings['hide_page_title'] ): ?>
	<div class="container">
		<header class="page-header">
			<?php if ( have_posts() ) : the_post();
				the_title( '<h1 class="page-title">', '</h1>' );
				boombox_the_post_subtitle();
				boombox_the_title_badge();
			endif; ?>
		</header>
	</div>
	<?php
	rewind_posts();
endif; ?>

	<div class="container">

		<?php if ( null != $boombox_trending_settings['query'] ) : ?>
			<div class="page-trending">
				<?php get_template_part( 'template-parts/header/navigation', 'trending' ); ?>
			</div>
		<?php endif; ?>

			<div id="main" class="site-main" role="main">
				<div class="main-container">

					<?php do_action( 'boombox_before_main_container' ); ?>

					<?php

						if ( have_posts() ) : the_post();
							if( $post->post_content ) {
								get_template_part('template-parts/content', 'page');
							}
						endif;

						if ( null != $boombox_trending_settings['query'] ) :

							global $wp_query;
							$tmp_query = $wp_query;
							$wp_query = $boombox_trending_settings['query'];
							Boombox_Loop_Helper::set_pagination_type( $boombox_trending_settings['pagination_type'] );
							if ( Boombox_Loop_Helper::have_posts() ): ?>
								<div id="post-items" <?php boombox_list_type_classes( $boombox_trending_settings['listing_type'], array( 'col-2' ) ); ?>>
									<?php
									while( Boombox_Loop_Helper::have_posts() ):
										$is_inject = Boombox_Loop_Helper::the_post();
										if( $is_inject['is_inject'] && $is_inject['is_adv'] ):
											$adv_settings = boombox_get_adv_settings(  $boombox_trending_settings['listing_type'] );
											boombox_the_advertisement( $adv_settings['location'], array( $adv_settings['size'], 'post' ), $tmp_query, $wp_query );
										elseif( $is_inject['is_inject'] && $is_inject['is_newsletter'] ):
											boombox_mailchimp_form( array( 'location' => 'listing', 'classes' => 'post' ) );
										elseif( $is_inject['is_inject'] && $is_inject['is_product'] ):
											get_template_part( 'template-parts/injected/product' );
										else:
											get_template_part( 'template-parts/content-' . $boombox_trending_settings['listing_type'], get_post_format() );
										endif;
									endwhile; ?>
								</div>
								<?php
								if ( 'none' != $boombox_trending_settings['pagination_type'] ):
									Boombox_Loop_Helper::prepare_query_for_pagination( $wp_query );
									get_template_part( 'template-parts/pagination/pagination', $boombox_trending_settings['pagination_type'] );
								endif;
							endif;

						endif;
						wp_reset_query(); ?>
				</div>

			</div>

		<?php if( $boombox_trending_settings['enable_sidebar'] ) { get_sidebar(); } ?>

	</div>

<?php get_footer(); ?>