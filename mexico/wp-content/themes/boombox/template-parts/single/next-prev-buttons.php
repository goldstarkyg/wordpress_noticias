<?php
/**
 * The template part for displaying the Next/Prev buttons
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

 $boombox_featured_image_size = 'boombox_image768';
$boombox_single_options = boombox_get_single_page_settings($boombox_featured_image_size);
$boombox_template_options = $boombox_single_options['template_options'];

/*global $page, $numpages;
boombox_single_post_link_pages( array(
    'before'                => sprintf( '<div class="next-prev-pagination %s">', ($numpages <= 1) ? 'no-pages' : '' ),
    'after'                 => '</div>',
    'link_before'           => '',
    'link_after'            => '',
    'reverse'               => (boombox_get_theme_option( 'layout_post_navigation_direction' ) == 'to-oldest'),
    'link_wrap_before'      => '<span class="nav %s">',
    'link_wrap_after'       => '</span>',
    'go_to_prev_next'       => $boombox_template_options['next_prev_buttons'],
    'paging'                => sprintf( '<span class="pages"><span>%1$d</span> / %2$d</span>', $page, $numpages ),
    'previous_page_link'    => '<i class="icon icon-chevron-left"></i><span class="text">' . esc_html__( 'Previous Page', 'boombox' ) . '</span><span class="mobile-text">' . esc_html__( 'Previous', 'boombox' ) . '</span>',
    'next_page_link'        => '<i class="icon icon-chevron-right"></i><span class="text">' . esc_html__( 'Next Page', 'boombox' ) . '</span><span class="mobile-text">' . esc_html__( 'Next', 'boombox' ) . '</span>',
    'previous_post_link'    => '<i class="icon icon-chevron-left"></i><span class="text">' . esc_html__( 'Previous Post', 'boombox' ) . '</span><span class="mobile-text">' . esc_html__( 'Previous', 'boombox' ) . '</span>',
    'next_post_link'        => '<i class="icon icon-chevron-right"></i><span class="text">' . esc_html__( 'Next Post', 'boombox' ) . '</span><span class="mobile-text">' . esc_html__( 'Next', 'boombox' ) . '</span>',
) );*/