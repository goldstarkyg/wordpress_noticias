<?php
/**
 * WSL plugin functions
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

if( boombox_is_plugin_active( 'wordpress-social-login/wp-social-login.php' ) ) {

    if( ! class_exists( 'Boombox_WSL' ) ) {

        class Boombox_WSL {

            /**
             * Singleton.
             */
            static function get_instance() {
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
            }

            /**
             * Setup Hooks
             */
            private function hooks() {
                add_filter( 'wsl_component_loginwidget_setup_alter_icon_sets', array( $this, 'add_icon_sets' ), 10, 1 );
                add_filter( 'wsl_render_auth_widget_alter_assets_base_url', array( $this, 'check_icon_sets_base_url' ), 10, 1 );

                if( !is_admin() ) {
                    add_filter('wsl_render_auth_widget_alter_provider_icon_markup', array( $this, 'button_markup' ), 10, 3);
                }
            }

            /**
             * Add boombox social icon sets to wsl
             */
            public function add_icon_sets( $icon_sets ) {

                $icon_sets[ 'boombox' ] = esc_html__( 'Boombox social icons', 'boombox' );

                return $icon_sets;
            }

            /**
             * Modify social icon sets url
             */
            public function check_icon_sets_base_url( $assets_base_url ) {
                $social_icon_set = get_option( 'wsl_settings_social_icon_set' );

                if( 'boombox' == $social_icon_set ) {
                    $assets_base_url = BOOMBOX_THEME_URL . 'images/social-icons/';
                }

                return $assets_base_url;
            }

            /***
             * Modify social icons markup to make it Boomboxed
             */
            public function button_markup($provider_id, $provider_name, $authenticate_url) {
                $icon_id = strtolower( $provider_id );
                $icons_rewrite_map = array(
                    'vkontakte'        => 'vk',
                    'stackoverflow'    => 'stack-overflow',
                    'twitchtv'          => 'twitch',
                    'mailru'            => 'at',
                    'google'            => 'google-plus'
                );

                $icon_name = isset( $icons_rewrite_map[ $icon_id ] ) ? $icons_rewrite_map[ $icon_id ] : $icon_id;

                return sprintf(
                    '<a rel="nofollow" href="%4$s" data-provider="%1$s" class="button _%2$s wp-social-login-provider wp-social-login-provider-%2$s">
                        <i class="icon icon-%5$s"></i> %3$s
                    </a>',
                    $provider_id,
                    $icon_id,
                    $provider_name,
                    $authenticate_url,
                    $icon_name
                );
            }

        }

    }

    Boombox_WSL::get_instance();

}