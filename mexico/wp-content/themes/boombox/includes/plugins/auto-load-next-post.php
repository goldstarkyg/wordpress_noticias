<?php
/**
 * Auto Load Nex Post plugin functions
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

if( boombox_is_plugin_active( 'auto-load-next-post/auto-load-next-post.php' ) ) {

    if( ! class_exists( 'Boombox_Auto_Load_Next_Post' ) ) {

        class Boombox_Auto_Load_Next_Post {

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
                $this->hooks();
            }

            /**
             * Setup Hooks
             */
            private function hooks()
            {
                add_action( 'after_setup_theme', array( $this, 'setup_theme' ), 11);
                add_action( 'boombox_single_before_navigation', array( $this, 'single_before_navigation' ), 10);
                add_action( 'balnp_load_before_content', array( $this, 'boombox_alnp_load_before_content' ), 10 );
                add_filter( 'auto_load_next_post_general_settings', array( $this, 'general_settings' ), 10, 1);
                add_action( 'wp_enqueue_scripts', array( $this, 'boombox_alnp_front_scripts_localize') );
                add_action( 'alnp_load_before_loop', array( $this, 'boombox_almp_before_loop' ), 10, 1 );
                add_action( 'alnp_load_after_loop', array( $this, 'boombox_almp_after_loop' ), 10, 1 );
            }

            /**
             * Add theme support
             */
            public function setup_theme()
            {
                add_theme_support('auto-load-next-post');
            }

            /**
             * Add container
             */
            public function single_before_navigation()
            {
                echo '<div id="balnp_content_container"></div>';
            }

            /**
             * Remove adsense for next posts
             */
            public function boombox_alnp_load_before_content() {
                add_filter( 'quads_render_adsense_async', function () { return ''; } );
            }

            /**
             * Setup some predefined settings
             *
             * @param $fields
             * @return mixed
             */
            public function general_settings($fields)
            {

                $denied_fields = array(
                    'auto_load_next_post_content_container' => 'div#balnp_content_container',
                    'auto_load_next_post_title_selector' => 'h1.entry-title',
                    'auto_load_next_post_navigation_container' => 'div.next-prev-pagination',
                    'auto_load_next_post_comments_container' => 'div#comments.comments'
                );

                foreach ($fields as $index => $field) {

                    if (array_key_exists($field['id'], $denied_fields)) {
                        $fields[$index]['default'] = $denied_fields[$field['id']];
                        $fields[$index]['desc'] = sprintf(__('Boombox: <code>%s</code>', 'auto-load-next-post'), $denied_fields[$field['id']]);
                        $fields[$index]['custom_attributes'] = array(
                            'readonly' => 'readonly'
                        );
                    }

                }
                return $fields;
            }

            public function boombox_alnp_front_scripts_localize() {
                wp_add_inline_script( 'auto-load-next-post-script', '
                    var boombox_alnp_container = nav_container;
                    jQuery( document ).on( "ready", function() {
                        var boombox_temp_container = "div.boombox-temp-nav-container",
                            boombox_keep_class = "boombox-keep";

                        jQuery( "body" ).on( "alnp-post-url", function(){
                            nav_container += ":not( ." + boombox_keep_class + " )";
                        } );
                        jQuery("body").on( "alnp-post-data", function(){
                            nav_container = boombox_alnp_container;
                        } );
                    } );
                ', 'after' );
            }

            /**
             * Add additional classes to new loaded posts
             *
             * @param $classes
             * @return string
             */
            public function boombox_alnp_single_article_classes( $classes ) {
                $classes .= ' item-added';

                return $classes;
            }

            /**
             * Hook into before load
             */
            public function boombox_almp_before_loop(  ) {
                add_filter( 'boombox_single_article_classes', array( $this, 'boombox_alnp_single_article_classes' ), 10, 1 );
            }

            /**
             * Hook into after load
             */
            public function boombox_almp_after_loop() {
                remove_filter( 'boombox_single_article_classes', array( $this, 'boombox_alnp_single_article_classes' ), 10, 1 );
            }

        }

    }

    Boombox_Auto_Load_Next_Post::get_instance();

}