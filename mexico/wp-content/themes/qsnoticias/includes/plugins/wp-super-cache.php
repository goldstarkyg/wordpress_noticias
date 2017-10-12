<?php
/**
 * W3 Total Cache plugin functions
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

if ( boombox_is_plugin_active( 'wp-super-cache/wp-cache.php' ) ) {
	
	class Boombox_WP_Super_Cache {
		
		public static function get_instance() {
			static $inst = null;
			if ($inst === null) {
				$inst = new self();
			}
			return $inst;
		}
		
		private function __construct() {
			// Change this to a secret placeholder tag
			$this->hooks();
		}
		
		private function hooks() {
			define( 'DYNAMIC_CACHE_TEST_TAG', 'asidhausydasldjhalksjdhakjshgd' );
			//if ( DYNAMIC_CACHE_TEST_TAG != '' ) {
				function dynamic_cache_test_safety( $safety ) {
					return 1;
				}
				add_cacheaction( 'wpsc_cachedata_safety', 'dynamic_cache_test_safety' );

				function dynamic_cache_test_filter( &$cachedata) {
					return str_replace( DYNAMIC_CACHE_TEST_TAG, "<!-- Hello world at " . date( 'H:i:s' ) . " -->", $cachedata );
				}
				add_cacheaction( 'wpsc_cachedata', 'dynamic_cache_test_filter' );

				function dynamic_cache_test_template_tag() {
					echo DYNAMIC_CACHE_TEST_TAG; // This is the template tag
				}

				function dynamic_cache_test_init() {
					add_action( 'wp_footer', 'dynamic_cache_test_template_tag' );
				}
				add_cacheaction( 'add_cacheaction', 'dynamic_cache_test_init' );
			//}
		}
	
	}
	
	Boombox_WP_Super_Cache::get_instance();
	
}
?>