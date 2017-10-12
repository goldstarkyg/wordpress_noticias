<?php
/**
 * WP QUADS plugin functions
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

if ( boombox_is_plugin_active( 'quick-adsense-reloaded/quick-adsense-reloaded.php' ) && function_exists( 'quads_register_ad' ) ) {

	if( ! class_exists( 'Boombox_WP_Quads' ) ) {

		class Boombox_WP_Quads {

			private $ads;

			/**
			 * Singleton.
			 */
			static function get_instance()
			{
				static $Inst = null;
				if ($Inst == null) {
					$Inst = new self();
				}

				return $Inst;
			}

			/**
			 * Constructor
			 */
			function __construct() {
				$this->hooks();
				$this->set_properties();
				$this->register_ads();
			}

			/**
			 * Setup Hooks
			 */
			public function hooks() {
				add_action( 'admin_bar_menu', array( $this, 'simulate_the_content_filter' ), 999 );
			}

			/**
			 * Simulate 'the_content' filter run for pages that does not have it
			 */
			public function simulate_the_content_filter() {

				global $wp_the_query, $the_content;
				if( $wp_the_query->is_singular() ) {
					$the_content = true;
				}

			}

			/**
			 * Setup ads custom locations
			 */
			private function set_properties() {

				$this->ads = array(
					array(
						'location' => 'boombox-archive-before-header',
						'description' => esc_html('Archive: Before Header')
					),
					array(
						'location' => 'boombox-archive-before-content',
						'description' => esc_html('Archive: Before content theme area')
					),
					array(
						'location' => 'boombox-archive-after-content',
						'description' => esc_html('Archive: After content theme area')
					),
					array(
						'location' => 'boombox-page-before-header',
						'description' => esc_html('Page: Before Header')
					),
					array(
						'location' => 'boombox-page-before-content',
						'description' => esc_html('Page: Before content theme area')
					),
					array(
						'location' => 'boombox-page-after-content',
						'description' => esc_html('Page: After content theme area')
					),
					array(
						'location' => 'boombox-single-before-header',
						'description' => esc_html('Single: Before Header')
					),
					array(
						'location' => 'boombox-single-before-content',
						'description' => esc_html('Single: Before content theme area')
					),
					array(
						'location' => 'boombox-single-after-next-prev-buttons',
						'description' => esc_html('Single: After "Next/Prev" buttons')
					),
					array(
						'location' => 'boombox-single-before-navigation',
						'description' => esc_html('Single: Before navigation area')
					),
					array(
						'location' => 'boombox-single-after-also-like-section',
						'description' => esc_html('Single: After "Also Like" section')
					),
					array(
						'location' => 'boombox-single-after-more-from-section',
						'description' => esc_html('Single: After "More From" section')
					),
					array(
						'location' => 'boombox-single-after-comments-section',
						'description' => esc_html('Single: After Comments section')
					),
					array(
						'location' => 'boombox-single-after-dont-miss-section',
						'description' => esc_html('Single: After "Don\'t miss" section')
					),
					array(
						'location' => 'boombox-listing-type-grid-instead-post',
						'description' => esc_html('Instead of "grid" or "three column" listing post')
					),
					array(
						'location' => 'boombox-listing-type-non-grid-instead-post',
						'description' => esc_html('Instead of none grid listing post')
					)
				);

			}

			/**
			 * Register ads custom areas
			 */
			private function register_ads() {
				foreach( (array)$this->ads as $ad_location ) {
					quads_register_ad( $ad_location );
				}
			}

		}

	}

	Boombox_WP_Quads::get_instance();

}