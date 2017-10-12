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

if( boombox_is_plugin_active( 'zombify/zombify.php' ) ) {

    if ( ! class_exists( 'Boombox_Zombify' ) ) {

        class Boombox_Zombify
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
             * Setup Hooks
             */
            private function hooks() {
                add_filter( 'boombox_template_single_template_options', array( $this, 'zombify_template_single_template_options' ), 10, 1 );
                add_filter( 'boombox_single_post_share_box_elements', array( $this, 'zombify_single_post_share_box_elements' ), 10, 1 );
                add_filter( 'boombox_fixed_navigation_post', array( $this, 'zombify_fixed_navigation_post' ), 10, 2 );

                add_filter( 'zombify_bp_pagination_args', array( $this, 'zombify_bp_pagination_args' ), 10, 1);
                add_filter( 'zombify_bp_posts_per_page', array( $this, 'zombify_bp_posts_per_page' ), 10, 1);
                add_filter( 'zombify_video_tag', array( $this, 'zombify_video_layout' ), 10, 5 );
                add_filter( 'boombox/loop-item/show-media', array( $this, 'force_loop_item_show_thumbnail' ), 10, 4 );
                add_filter( 'post_thumbnail_html', array( $this, 'zf_gif_post_thumbnail_fallback' ), 30, 5 );

                if( boombox_is_plugin_active('boombox-theme-extensions/boombox-theme-extensions.php') ) {
                    add_filter( 'zombify_img_tag', array($this, 'zombify_img_tag'), 10, 3 );
                }
            }

            /**
             * Setup pagination properties
             *
             * @param $args
             * @return array
             */
            public function zombify_bp_pagination_args( $args ) {
                return array_merge( $args, array(
                    'end_size' => 1,
                    'mid_size' => 1,
                    'prev_text' => _x( 'Previous', 'previous set of posts' ),
                    'next_text'  => _x( 'Next', 'next set of posts' )
                ) );
            }

            /**
             * Setup posts per page for zombify posts
             *
             * @param $per_page
             * @return int
             */
            public function zombify_bp_posts_per_page( $per_page ) {
                return 10;
            }

            /**
             * Render gif images as video if possible
             *
             * @param $html
             * @param $post_thumbnail_id
             * @param $size
             * @return string
             */
            public function zombify_img_tag( $html, $post_thumbnail_id, $size ) {

                return Boombox_Gif_To_Video::get_instance()->filter_gif_thumbnail_html( $html, get_the_ID(), $post_thumbnail_id, $size, array( 'play' => true ) );

            }

            /**
             * Change single template options for "list_item" post type
             *
             * @param $template_options
             * @return bool
             */
            public function zombify_template_single_template_options( $template_options ) {

                if( ( 'list_item' == get_post_type() ) ) {
                    $template_options[ 'next_prev_buttons' ] = false;
                    $template_options['reactions'] = false;
                }

                return $template_options;

            }

            /**
             * Hide single template points for "list_item" post type
             *
             * @param $elements
             * @return mixed
             */
            public function zombify_single_post_share_box_elements( $elements ) {

                if( ( 'list_item' == get_post_type() ) ) {

                    $points_key = array_search( 'points', $elements );
                    if( false !== $points_key ) {

                        unset($elements[$points_key]);

                    }

                }

                return $elements;

            }

            /**
             * Modify single template fixed pagination for "list_item" post type
             *
             * @param $boombox_post
             * @param $nav prev|next
             * @return mixed
             */
            public function zombify_fixed_navigation_post( $boombox_post, $nav ) {

                if( 'list_item' == get_post_type() ) {

                    static $fixed_navigation_data;

                    if( ! $fixed_navigation_data ) {

                        global $post;

                        $parent_post_data = json_decode(base64_decode(get_post_meta($post->post_parent, 'zombify_data', true)), true);

                        $prev_data = '';
                        $next_data = '';
                        $prev_data_temp = '';
                        $first_data = '';
                        $last_data = '';

                        $i = 0;
                        foreach ($parent_post_data["list"] as $pdata) {
                            $i++;
                            if ($pdata["post_id"] == $post->ID) {
                                $prev_data = $prev_data_temp;
                            }

                            if ($prev_data_temp != '' && $prev_data_temp["post_id"] == $post->ID) {
                                $next_data = $pdata;
                            }

                            $prev_data_temp = $pdata;

                            if ($first_data == '') {
                                $first_data = $pdata;
                            }

                            $last_data = $pdata;

                        }

                        if (zombify()->sub_posts_loop) {

                            if ($next_data == '') $next_data = $first_data;
                            if ($prev_data == '') $prev_data = $last_data;

                        }

                        $fixed_navigation_data[ 'prev' ] = $prev_data ? get_post( $prev_data[ 'post_id' ] ) : null;
                        $fixed_navigation_data[ 'next' ] = $next_data ? get_post( $next_data[ 'post_id' ] ) : null;

                    }

                    return isset( $fixed_navigation_data[ $nav ] ) ? $fixed_navigation_data[ $nav ] : null;

                }

                return $boombox_post;

            }

            /**
             * Handle video layout to match theme styles
             *
             * @param $html
             * @param $url
             * @param $video_post_id
             * @param $size
             * @return mixed
             */
            public function zombify_video_layout( $html, $url, $type, $video_post_id, $size ) {
                $html = sprintf('
                    <div class="gif-video-wrapper">
                        <video class="gif-video" loop muted>
                            <source src="%1$s" type="%2$s">
                            %3$s
                        </video>
                    </div>',
                    esc_url( $url ),
                    $type,
                    esc_html__( 'To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video', 'boombox' )
                );

                return $html;
            }

            /**
             * Force theme to have a placeholder for post thumbnails in case for gif story type
             *
             * @param $show_media
             * @param $enabled_by_template
             * @param $has_thumbnail_or_video
             * @param $layout
             * @return bool
             */
            public function force_loop_item_show_thumbnail( $show_media, $enabled_by_template, $has_thumbnail_or_video, $layout ) {

                if( $enabled_by_template && in_array( $layout, array( 'content-classic', 'content-stream' ) ) && ! $has_thumbnail_or_video ) {
                    $zombify_data_type = boombox_get_post_meta( get_the_ID(), 'zombify_data_type' );
                    if( 'gif' == $zombify_data_type ) {
                        $show_media = true;
                    }
                }

                return $show_media;
            }

            /**
             * Replace html of posts with gif story type to have video playing functionality
             *
             * @param $html
             * @param $post_id
             * @param $post_thumbnail_id
             * @param $size
             * @param $attr
             * @return mixed
             */
            public function zf_gif_post_thumbnail_fallback( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

                $zombify_data_type = boombox_get_post_meta( $post_id, 'zombify_data_type' );

                if( ( 'gif' == $zombify_data_type ) && isset( $attr['play'] ) && $attr['play'] ) {

                    $zombify_data = boombox_get_post_meta( $post_id, 'zombify_data' );
                    $zombify_data = json_decode( base64_decode( $zombify_data ), true );

                    zf_array_values( zf_array_values( $zombify_data['gif'] )[0]['image_image'] );

                    $video_url = zf_array_values( zf_array_values( $zombify_data['gif'] )[0]['image_image'] )[0]['uploaded']['url'];
                    if( 'mp4' == pathinfo( $video_url, PATHINFO_EXTENSION ) ) {
                        $html = $this->zombify_video_layout( $html, $video_url, 'video/mp4', 0, $size );
                    }
                }

                return $html;

            }
        }
    }

    Boombox_Zombify::get_instance();

}