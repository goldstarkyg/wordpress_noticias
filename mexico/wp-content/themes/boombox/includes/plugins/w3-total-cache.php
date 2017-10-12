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

if ( boombox_is_plugin_active( 'w3-total-cache/w3-total-cache.php' ) ) {

    if( ! class_exists( 'Boombox_W3_Total_Cache' ) ) {

        class Boombox_W3_Total_Cache
        {

            public $config = array();

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
            function __construct()
            {

                if (!function_exists('w3tc_config')) {
                    return;
                }

                if( ! defined( 'W3TC_DYNAMIC_SECURITY' ) ) {
                    define( 'W3TC_DYNAMIC_SECURITY', md5( $_SERVER['HTTP_HOST'] ) );
                }

                $this->config = w3tc_config();
                $this->hooks();
            }

            /**
             * Setup Hooks
             */
            private function hooks()
            {
                add_action('boombox_after_user_login_success', array($this, 'bw3tc_after_user_login_success'), 10, 2);

                if ($this->config->get_boolean('pgcache.enabled')) {

                    //add_filter( 'boombox/frgcache.enabled', function(){ return true; } );
                    add_filter( 'boombox/pgcache.enabled', function(){ return true; } );

                    add_action( 'boombox_after_post_points_update', array($this, 'bw3tc_after_post_points_update' ), 10, 3);
                    add_action( 'boombox_after_reaction_add', array($this, 'bw3tc_after_reaction_add' ), 10, 3);

                    add_filter( 'boombox_ajax_login_check_referer', array( $this, 'remove_ajax_login_register_referer_check' ), 10, 1 );
                    add_filter( 'boombox_ajax_register_check_referer', array( $this, 'remove_ajax_login_register_referer_check' ), 10, 1 );

                }
            }

            /**
             * Clear post cache after post point update
             */
            public function bw3tc_after_post_points_update($post_id, $action, $status)
            {
                w3tc_flush_post( $post_id );
            }

            /**
             * Clear post cache after adding reaction on the post
             */
            public function bw3tc_after_reaction_add($post_id, $reaction_id, $status)
            {
                w3tc_flush_post( $post_id );
            }

            /**
             * Clear current page cache on login success
             *
             * @param $user
             * @param $redirect_url
             */
            public function bw3tc_after_user_login_success($user, $redirect_url)
            {
                if ( $redirect_url ) {
                    w3tc_flush_url( $redirect_url );
                }
            }

            /**
             * Callback to remove ajax referer check functionality
             *
             * @param $check
             * @return bool
             */
            public function remove_ajax_login_register_referer_check( $check ) {
                $check = false;

                return $check;
            }

        }

    }

    Boombox_W3_Total_Cache::get_instance();

}