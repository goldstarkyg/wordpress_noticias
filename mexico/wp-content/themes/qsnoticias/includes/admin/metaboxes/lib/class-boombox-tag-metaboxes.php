<?php
/**
 * Register a post_tag meta box using a class.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

if ( ! class_exists( 'Boombox_Post_Tag_Custom_Meta_Box' ) ) {

    class Boombox_Post_Tag_Custom_Meta_Box {

        /**
         * Constructor.
         */
        public function __construct() {
            add_action( 'post_tag_add_form_fields', array( $this, 'add_post_tag_fields' ) );
            add_action( 'post_tag_edit_form_fields', array( $this, 'edit_post_tag_fields' ), 10 );
            add_action( 'created_term', array( $this, 'save_post_tag_fields' ), 10, 1 );
            add_action( 'edit_term', array( $this, 'save_post_tag_fields' ), 10, 1 );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ) );
        }

        /**
         * Singleton.
         */
        public static function get_instance() {
            static $Inst = null;
            if ( $Inst == null ) {
                $Inst = new self();
            }

            return $Inst;
        }

        /**
         * Load scripts
         */
        public function enqueue_script() {
            global $current_screen;
            global $wp_scripts;
            if ( isset( $current_screen ) && 'edit-post_tag' === $current_screen->id ) {
                $protocol = is_ssl() ? 'https' : 'http';
                $ui       = $wp_scripts->query( 'jquery-ui-core' );
                $url      = "$protocol://ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/smoothness/jquery-ui.min.css";
                wp_enqueue_style( 'jquery-ui-smoothness', $url, array(), boombox_get_assets_version() );
                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_style( 'boombox-icomoon-style', BOOMBOX_THEME_URL . 'fonts/icon-fonts/icomoon/icons.min.css', array(), boombox_get_assets_version() );
                wp_enqueue_style( 'boombox-admin-meta-style', BOOMBOX_ADMIN_URL . 'metaboxes/css/boombox-metabox-style.css', array(), boombox_get_assets_version() );

                wp_enqueue_media();
                wp_enqueue_script( 'wp-color-picker' );
                wp_enqueue_script( 'jquery-ui-selectmenu' );
                wp_enqueue_script( 'boombox-reaction-meta-scripts', BOOMBOX_ADMIN_URL . 'metaboxes/js/boombox-metabox-script.js', array( 'jquery' ), boombox_get_assets_version(), true );
            }

        }

        /**
         * Post Tag fields.
         */
        public function add_post_tag_fields() {
            wp_nonce_field( 'update_term_meta', 'term_meta_nonce' );
            ?>

            <input type="hidden" id="cat_placeholder_img_src" name="cat_placeholder_img_src" value="<?php echo esc_js( $this->placeholder_img_src() ); ?>"/>

            <div class="form-field term-thumbnail-wrap">
                <label><?php esc_html_e( 'Thumbnail', 'boombox' ); ?></label>
                <div id="cat_thumbnail"><img src="<?php echo esc_url( $this->placeholder_img_src() ); ?>" width="60px" height="60px"/></div>
                <div class="buttons-wrap">
                    <?php wp_nonce_field( 'update_term_meta', 'term_meta_nonce' ); ?>
                    <input type="hidden" id="cat_thumbnail_id" name="post_tag_thumbnail_id"/>
                    <button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'boombox' ); ?></button>
                    <button type="button" class="remove_image_button button"><?php esc_html_e( 'Remove image', 'boombox' ); ?></button>
                </div>
                <div class="clear"></div>
            </div>

            <?php $post_tag_icons = get_icomoon_icons_array(); ?>
            <div class="form-field term-icon-name-wrap">
                <label for="cat_icon_name"><?php esc_html_e( 'Icon', 'boombox' ); ?></label>
                <select id="cat_icon_name" name="post_tag_icon_name">
                    <option value="" data-class=""><?php echo esc_html__( 'Select icon', 'boombox' ); ?></option>
                    <?php foreach ( $post_tag_icons as $post_tag_icon ) { ?>
                        <option value="<?php echo esc_html( esc_attr( $post_tag_icon['icon'] ) ); ?>" data-class="icon-<?php echo  esc_attr( $post_tag_icon['icon'] ); ?>">
                            <?php echo esc_html( $post_tag_icon['name'] ); ?>
                        </option>
                    <?php } ?>
                </select>
                <div class="clear"></div>
            </div>

            <?php $term_icon_background_color = boombox_get_theme_option( 'design_badges_category_background_color' ); ?>
            <div class="form-field term-icon-background-color-wrap">
                <label for="term_icon_background_color"><?php esc_html_e( 'Badge Background Color', 'boombox' ); ?></label>
                <input type="text" value="<?php echo esc_attr( $term_icon_background_color ); ?>" name="term_icon_background_color" />
                <div class="clear"></div>
            </div>

            <div class="form-field hide-attached-posts-featured-media-wrap">
                <input name="hide_attached_posts_featured_media" type="hidden" value="0">
                <label for="hide_attached_posts_featured_media">
                    <input name="hide_attached_posts_featured_media" id="hide_attached_posts_featured_media" type="checkbox" value="1">
                    <?php _e( 'Hide Attached Posts Featured Media', 'boombox' ); ?>
                </label>
                <p><?php __( 'Check to hide featured media from the single article page of posts - attached to this tag', 'boombox' ); ?></p>
            </div>

            <?php

            do_action( 'boombox/admin/tag/meta-boxes/add-fields', $this );
        }

        /**
         * Edit Post Tag field.
         *
         * @param mixed $term Term (post_tag) being edited
         */
        public function edit_post_tag_fields( $term ) {
            // Put the term ID into a variable.
            $t_id = $term->term_id;

            $thumbnail_id = boombox_get_term_meta( $t_id, 'post_tag_thumbnail_id' );
            $thumbnail_id = !empty( $thumbnail_id ) ? absint( $thumbnail_id ) : false;
            $post_tag_icon_name = sanitize_text_field( boombox_get_term_meta( $t_id, 'post_tag_icon_name' ) );

            $placeholder = $this->placeholder_img_src();

            if ( $thumbnail_id ) {
                $image = wp_get_attachment_thumb_url( $thumbnail_id );
            } else {
                $image = $placeholder;
            }

            $term_icon_background_color = boombox_get_term_meta( $t_id, 'term_icon_background_color' );
            $term_icon_background_color = !empty( $term_icon_background_color ) ? sanitize_text_field( $term_icon_background_color ) : boombox_get_theme_option( 'design_badges_category_background_color' );

            $hide_attached_posts_featured_media = boombox_get_term_meta( $t_id, 'hide_attached_posts_featured_media' );

            wp_nonce_field( 'update_term_meta', 'term_meta_nonce' ); ?>

            <table class="form-table term-thumbnail-wrap">
                <tbody>
                <tr>
                    <th></th>
                    <td><input type="hidden" id="cat_placeholder_img_src" name="cat_placeholder_img_src" value="<?php echo esc_url( $placeholder ); ?>"/></td>
                </tr>
                <tr class="form-field term-taxonomy-wrap">
                    <th scope="row" valign="top">
                        <label><?php esc_html_e( 'Thumbnail', 'boombox' ); ?></label>
                    </th>
                    <td>
                        <div id="cat_thumbnail"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px"/>
                        </div>
                        <div class="buttons-wrap">
                            <input type="hidden" id="cat_thumbnail_id" name="post_tag_thumbnail_id" value="<?php echo esc_html( $thumbnail_id ); ?>">
                            <button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'boombox' ); ?></button>
                            <button type="button" class="remove_image_button button"><?php esc_html_e( 'Remove image', 'boombox' ); ?></button>
                        </div>
                        <div class="clear"></div>
                    </td>
                </tr>
                <tr>
                    <th scope="row" valign="top">
                        <label for="cat_icon_name"><?php esc_html_e( 'Icon', 'boombox' ); ?></label>
                    </th>
                    <td>
                        <?php $post_tag_icons = get_icomoon_icons_array(); ?>
                        <div class="form-field term-icon-name-wrap">
                            <select id="cat_icon_name" name="post_tag_icon_name">
                                <option value="" data-class=""><?php echo esc_html__( 'Select icon', 'boombox' ); ?></option>
                                <?php foreach ( $post_tag_icons as $post_tag_icon ) {
                                    $selected = selected( $post_tag_icon['icon'], $post_tag_icon_name, false ); ?>
                                    <option value="<?php echo esc_html( esc_attr( $post_tag_icon['icon'] ) ); ?>" data-class="icon-<?php echo  esc_attr( $post_tag_icon['icon'] ); ?>"  <?php echo $selected; ?>>
                                        <?php echo esc_html( $post_tag_icon['name'] ); ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <div class="clear"></div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <th scope="row" valign="top">
                        <label for="term_icon_background_color"><?php esc_html_e( 'Badge Background Color', 'boombox' ); ?></label>
                    </th>
                    <td>
                        <div class="form-field term-icon-background-color-wrap">
                            <input type="text" value="<?php echo esc_attr( $term_icon_background_color ); ?>" name="term_icon_background_color" />
                            <div class="clear"></div>
                        </div>
                    </td>
                </tr>

                <tr class="form-field hide-attached-posts-featured-media-wrap">
                    <th scope="row"><label for="hide_attached_posts_featured_media"><?php _e( 'Hide Attached Posts Featured Media', 'boombox' ); ?></label></th>
                    <td>
                        <input name="hide_attached_posts_featured_media" type="hidden" value="0">
                        <input name="hide_attached_posts_featured_media" id="hide_attached_posts_featured_media" type="checkbox" <?php checked( $hide_attached_posts_featured_media, 1 ); ?> value="1">
                        <p class="description"><?php __( 'Check to hide featured media from the single article page of posts - attached to this tag', 'boombox' ); ?></p>
                    </td>
                </tr>

                <?php do_action( 'boombox/admin/tag/meta-boxes/edit-fields', $this, $term ); ?>

                </tbody>
            </table>
            <?php ob_end_flush();
        }

        /**
         * Placeholder image url
         */
        public function placeholder_img_src() {
            return apply_filters( 'boombox_post_tag_placeholder_img_src', BOOMBOX_THEME_URL . 'images/placeholder.png' );
        }

        /**
         * Save Post Tag function.
         *
         * @param mixed $term_id Term ID being saved
         */
        public function save_post_tag_fields( $term_id ) {

            $nonce_name   = isset( $_POST['term_meta_nonce'] ) ? $_POST['term_meta_nonce'] : '';
            $nonce_action = 'update_term_meta';

            // Check if nonce is set.
            if ( ! isset( $nonce_name ) ) {
                return;
            }

            if ( isset( $_POST['post_tag_thumbnail_id'] ) ) {
                update_term_meta( $term_id, 'post_tag_thumbnail_id', absint( $_POST['post_tag_thumbnail_id'] ) );
            }

            // Check if nonce is valid.
            if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
                return;
            }

            if( isset( $_POST['post_tag_icon_name'] ) ){
                update_term_meta( $term_id, 'post_tag_icon_name', sanitize_text_field( $_POST['post_tag_icon_name'] ) );
            }

            if( isset( $_POST['term_icon_background_color'] ) ){
                update_term_meta( $term_id, 'term_icon_background_color', sanitize_text_field( $_POST['term_icon_background_color'] ) );
            }

            if( isset( $_POST['hide_attached_posts_featured_media'] ) ){
                $hide_attached_posts_featured_media = ( isset( $_POST['hide_attached_posts_featured_media'] ) && $_POST['hide_attached_posts_featured_media'] ) ? 1 : 0;
                update_term_meta( $term_id, 'hide_attached_posts_featured_media', $hide_attached_posts_featured_media );
            }

            do_action( 'boombox/admin/tag/meta-boxes/save', $this, $term_id );

        }

        /**
         * Get Icomoon icons array
         *
         * @param $selected_icon
         *
         * @return array
         */
        public function get_icomoon_icons_array() {
            static $icons_array;
            if( !$icons_array ){
                $icons_array = array();
                $path        = BOOMBOX_THEME_URL . 'fonts/icon-fonts/icomoon/selection.json';
                $response    = wp_remote_get( $path );
                if ( ! is_wp_error( $response ) ) {
                    $icons    = json_decode( $response['body'] );
                    $exclude  = array( 'skull-real' );
                    $item_num = 0;
                    if( isset( $icons->icons ) && is_array( $icons->icons ) ){
                        foreach ( $icons->icons as $icon ) {
                            $icon_name  = $icon->properties->name;
                            $icon_names = explode( ', ', $icon_name );
                            if ( ! in_array( $icon_names[0], $exclude ) ) {
                                $icons_array[ $item_num ]['icon'] = $icon_names[0];
                                $icons_array[ $item_num ]['name'] = $icon_name;
                                ++ $item_num;
                            }
                        }
                    }
                }

                usort( $icons_array,  array( __CLASS__, 'sort_by_name' ) );
            }

            return $icons_array;
        }

        /**
         * Sort array by key 'name'
         *
         * @param $a
         * @param $b
         *
         * @return int
         */
        public function sort_by_name($a, $b) {
            if ( $a['name'] < $b['name'] ) return -1;
            if ( $a['name'] > $b['name'] ) return 1;
            return 0;
        }
    }
}

Boombox_Post_Tag_Custom_Meta_Box::get_instance();