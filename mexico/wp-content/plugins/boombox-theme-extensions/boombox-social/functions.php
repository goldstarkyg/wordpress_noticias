<?php
/**
 * Boombox Social functions
 *
 * @package BoomBox_Theme_Extensions
 *
 */

// Prevent direct script access
if ( ! defined( 'ABSPATH' ) ) {
	die ( 'No direct script access allowed' );
}

if ( ! class_exists( 'Boombox_Social' ) ) {
	include_once( BBTE_PLUGIN_PATH .'/boombox-social/class-boombox-social.php' );
}

/**
 * Show social links on front
 *
 * @param string $exclude
 *
 * @return string
 */
function boombox_social_links( $exclude = '' ) {
	$html       = '';
	$exclude_ar = array();
	if ( ! empty( $exclude ) ) {
		$exclude = str_replace( ' ', '', $exclude );
		$exclude_ar = explode( ',', $exclude );
	}
	$boombox_social_items = get_option( 'boombox_social_items' );
	if ( $boombox_social_items && is_array( $boombox_social_items ) ) {

		foreach ( $boombox_social_items as $item_key => $boombox_social_item ) {
			if ( '' != $boombox_social_item['link'] && ! in_array( $item_key, $exclude_ar ) ) {
				$boombox_social_item['link'] = is_email( $boombox_social_item['link'] ) ? ( 'mailto:' . $boombox_social_item['link'] ) : esc_url( $boombox_social_item['link'] );
				$html .= '<li><a class="icon-' . esc_attr__( $boombox_social_item['icon'] ) . '" href="' . $boombox_social_item['link'] . '" title="' . esc_html( $boombox_social_item['title'] ) . '" rel="nofollow" target="_blank" ></a></li>';
			}
		}

	}

	if( !empty ($html)){
		$html = '<ul>' . $html . '</ul>';
	}else{
		$html = esc_html__('No social links are set.', 'boombox-theme-extensions');
	}

	return $html;
}