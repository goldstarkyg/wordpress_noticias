<?php
/**
 * Mashshare plugin functions
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

if( boombox_is_plugin_active( 'mashsharer/mashshare.php' ) ) {

    if (!class_exists('Boombox_Buddypress')) {

        class Boombox_Mashshare
        {
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
             * Init required actions
             */
            private function hooks( ) {

                add_filter( 'mashsb_opengraph_meta', array( $this, 'opengraph_meta' ), 10, 1 );
                add_filter( 'mashsb_twitter_title', array( $this, 'bb_listings_page_mashsb_twitter_title' ), 10, 1 );

            }

            /**
             * Set right title for listing posts for twitter share
             * @param $title
             * @return string
             */
            public function bb_listings_page_mashsb_twitter_title( $title ) {
                if( is_page() ) {
                    $listing_type = boombox_get_post_meta( get_the_ID(), 'boombox_listing_type' );
                    if( $listing_type != 'none' ) {
                        global $post;
                        $title = strtr( htmlspecialchars_decode( $post->post_title ), array(
                            '"'         => '\'',
                            '&#8216;'   => '\'',
                            '&#8217;'   => '\'',
                            '&#8220;'   => '\'',
                            '&#8221;'   => '\'',
                            '“'         => '&quot;',
                            '”'         => '&quot;'
                        ) );
                    }
                }

                return $title;
            }

            /**
             * Modify opengraph meta tags
             *
             * @param $opengraph_meta
             * @return string
             */
            public function opengraph_meta( $opengraph_meta ) {

                preg_match( '/https?:\/\/[^ ]+?(?:\.gif)/', $opengraph_meta, $matches );

                if( empty( $matches ) && ( strpos( $opengraph_meta, 'og:description' ) == false ) ) {
                    $opengraph_meta .= PHP_EOL . '<meta property="og:description" content="&nbsp;" />';
                }

                return $opengraph_meta;

            }

        }

    }

    Boombox_Mashshare::get_instance();
}