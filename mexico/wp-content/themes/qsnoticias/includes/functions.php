<?php
/**
 * Boombox functions and definitions
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Theme Setup
 */
require_once( BOOMBOX_INCLUDES_PATH . 'theme-setup.php' );

/**
 * Badges Navigation Walker Class
 */
require_once( BOOMBOX_FRONT_PATH . 'lib/class-boombox-walker-badges-nav-menu.php' );

/**
 * Header Navigation Walker Class
 */
require_once( BOOMBOX_FRONT_PATH . 'lib/class-boombox-walker-nav-menu-custom-fields.php' );

/**
 * Customizer additions
 */
require_once( BOOMBOX_ADMIN_PATH . 'customizer/customizer.php' );

/**
 * Widgets
 */
require_once( BOOMBOX_INCLUDES_PATH . 'widgets/class-boombox-widget-recent-posts.php' );
require_once( BOOMBOX_INCLUDES_PATH . 'widgets/class-boombox-widget-trending-posts.php' );
require_once( BOOMBOX_INCLUDES_PATH . 'widgets/class-boombox-widget-picked-posts.php' );
require_once( BOOMBOX_INCLUDES_PATH . 'widgets/class-boombox-widget-sticky-sidebar.php' );
require_once( BOOMBOX_INCLUDES_PATH . 'widgets/class-boombox-widget-sidebar-footer.php' );
require_once( BOOMBOX_INCLUDES_PATH . 'widgets/class-boombox-widget-create-post.php' );
require_once( BOOMBOX_INCLUDES_PATH . 'widgets/class-boombox-widget-related-posts.php' );

/**
 * Rate and Vote Restriction Modules
 */
require_once( BOOMBOX_RATE_VOTE_RESTRICTIONS_PATH . 'functions.php' );

/**
 * "MashShare" plugin functions
 */
require_once( BOOMBOX_INCLUDES_PATH . 'plugins/mashshare.php' );

/**
 * WP QUADS plugin functions
 */
require_once( BOOMBOX_INCLUDES_PATH . 'plugins/quick-adsense-reloaded.php' );

/**
 * Buddypress plugin functions
 */
require_once( BOOMBOX_INCLUDES_PATH . 'plugins/buddypress.php' );

/**
 * "Wordpress Social Login" plugin functions
 */
require_once( BOOMBOX_INCLUDES_PATH . 'plugins/wsl.php' );

/**
 * "One Click Demo Import" plugin functions
 */
require_once( BOOMBOX_INCLUDES_PATH . 'plugins/one-click-demo-import.php' );

/**
 * "Auto Load Next Post" plugin functions
 */
require_once( BOOMBOX_INCLUDES_PATH . 'plugins/auto-load-next-post.php' );

/**
 * "Zombify" plugin functions
 */
require_once( BOOMBOX_INCLUDES_PATH . 'plugins/zombify.php' );

/**
 * "W3 Total Cache" plugin functions
 */
require_once( BOOMBOX_INCLUDES_PATH . 'plugins/w3-total-cache.php' );

/**
 * "Easy Social Share Buttons" plugin functions
 */
require_once( BOOMBOX_INCLUDES_PATH . 'plugins/essb.php' );

/**
 * "Woocommerce" plugin functions
 */
require_once( BOOMBOX_INCLUDES_PATH . 'plugins/woocommerce/woocommerce.php' );