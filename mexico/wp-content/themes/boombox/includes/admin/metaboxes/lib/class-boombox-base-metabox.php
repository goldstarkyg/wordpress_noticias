<?php
/**
 * Register a post meta box using a class.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

abstract class Boombox_Base_Metabox {

    abstract function structure();

    public static $key = 'boombox_meta';

    public $data;

    /**
     * Render field
     * @param $field
     */
    protected function render_field( $field, $tab_id = false ) {

        $render_action = 'render' . preg_replace( '/\s+/', '', ucfirst( strtolower( $field['type'] ) ) );
        if( method_exists( $this, $render_action ) ) {

            $field = $this->parse_field_args ( $field );

            if( ! $field['id'] ) return;

            $this->$render_action( $field, $tab_id );
        }

    }

    /**
     * Parse field arguments
     *
     * @param $field
     * @return array
     */
    private function parse_field_args( $field ) {
        return wp_parse_args( $field, array(
            'dependency' => array(),
            'css' => array(
                'id'    => '',
                'class' => '',
                'attr'  => ''
            ),
            'default'       => '',
            'label'         => '',
            'id'            => '',
            'value_key'     => '',
            'description'   => '',
            'callback'      => ''
        ) );
    }

    /**
     * Get dependency attributes
     *
     * @param $field
     * @param $tab_id
     * @return array
     */
    private function get_wrapper_data_from_dependency( $field, $tab_id ) {
        $use_dependency = ( (bool)$field['dependency'] && isset( $field['dependency']['field_id'] ) && $field['dependency']['field_id'] && isset( $field['dependency']['value'] ) && isset( $this->structure()[ $tab_id ][ 'fields' ][ $field['dependency']['field_id'] ] ) );
        $visible = true;
        $wrapper_class = $wrapper_atts = '';
        if( $use_dependency  ) {

            $parent_value_key = $this->structure()[ $tab_id ][ 'fields' ][ $field['dependency']['field_id'] ][ 'value_key' ];
            $parent_value = isset( $this->data[ self::$key ][ $parent_value_key ] ) ? $this->data[ self::$key ][ $parent_value_key ] : $this->structure()[ $tab_id ][ 'fields' ][ $field['dependency']['field_id'] ][ 'default' ];

            $compare = ( isset( $field['dependency']['compare'] ) && in_array( $field['dependency']['compare'], array( '=', '!=' ) ) ) ? $field['dependency']['compare'] : '=';
            $wrapper_atts = sprintf( 'data-compare="%s" data-compare-value="%s"', $compare, $field['dependency']['value'] );

            if( ( $compare === '=' ) && ( $parent_value != $field['dependency']['value'] ) ) {
                $visible = false;
            }

            if( ( $compare === '!=' ) && ( $parent_value == $field['dependency']['value'] ) ) {
                $visible = false;
            }

            $wrapper_class .= ' depended-of-' . $field['dependency']['field_id'];
        }

        if( ! $visible ) {
            $wrapper_class .= ' hidden';
        }

        return array(
            'class' => $wrapper_class,
            'atts'  => $wrapper_atts
        );
    }

    /**
     * Get Field Value
     *
     * @param $field
     * @return mixed
     */
    private function get_field_value( $field ) {
        if( $field['value_key'] && isset( $this->data[ self::$key ][ $field['value_key'] ] ) ) {
            return $this->data[ self::$key ][ $field['value_key'] ];
        } elseif( isset( $this->data[ $field['value_key'] ] ) ) {
            return $this->data[ $field['value_key'] ];
        };
        return $field['default'];
    }

    /**
     * Render meta box
     */
    protected function render() {

        wp_nonce_field( 'boombox_advanced_fields_nonce_action', 'boombox_nonce' );

        ?>

        <div class="boombox-post-advanced-fields">

            <?php
            if( count( $this->structure() ) > 1 ) {
                $this->renderAsTabs();
            } else {
                $this->renderAsSingle();
            }
            ?>

        </div>

        <?php

    }

    /**
     * Render meta box as tabs
     */
    protected function renderAsTabs() {
        ?>
        <div class="boombox-admin-tabs boombox-admin-tabs-horizontal">
            <ul class="boombox-admin-tabs-menu">
                <?php foreach( $this->structure() as $t_id => $tab ) { ?>
                    <li class="<?php echo $tab['active'] ? 'active' : ''; ?>"><a href="#<?php echo $t_id; ?>"><?php echo $tab['title']; ?></a></li>
                <?php } ?>
            </ul>
            <div class="boombox-admin-tabs-content">
                <?php foreach( $this->structure() as $t_id => $tab ) {
                    $this->renderTabContent( $tab, $t_id );
                } ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render meta box as single tab
     */
    protected function renderAsSingle() {
        reset( $this->structure() );
        $tab_id = key( $this->structure() );

        $tab = $this->structure()[ $tab_id ];
        $this->renderTabContent( $tab, $tab_id, true );
    }

    /**
     * Render single tab content
     *
     * @param $tab
     * @param $tab_id
     * @param bool|false $is_single
     */
    protected function renderTabContent( $tab, $tab_id, $is_single = false ) {
        ?>
        <div id="<?php echo $tab_id; ?>" class="boombox-admin-tab-content <?php echo ( $is_single || $tab['active'] ) ? 'active' : ''; ?>">
            <?php
            foreach( $tab['fields'] as $f_id => $field ) {
                $this->render_field( array_merge( $field, array( 'id' => $f_id ) ), $tab_id );
            }
            ?>
        </div>
        <?php
    }

    /**
     * Save meta data
     *
     * @param $post_id
     * @param $post
     * @return bool|int
     */
    protected function save_data( $post_id, $post ) {
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['boombox_nonce'] ) ? $_POST['boombox_nonce'] : '';
        $nonce_action = 'boombox_advanced_fields_nonce_action';

        // Check if nonce is set.
        if ( ! isset( $nonce_name ) ) {
            return false;
        }

        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return false;
        }

        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return false;
        }

        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return false;
        }

        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return false;
        }

        $meta_values = array();
        foreach( $this->structure() as $t_key => $tab ) {
            foreach( $tab['fields'] as $f_key => $field ) {
                $callback = $field['callback'];
                $value = isset( $_POST[ $field['name'] ] ) ? $_POST[ $field['name'] ] : '';
                if( method_exists( $this, $callback ) ) {
                    $value = $this->$callback( $value );
                } elseif( function_exists( $callback ) ) {
                    $value = call_user_func( $callback, $value );
                }
                $key = isset( $field['value_key'] ) && $field['value_key'] ? $field['value_key'] : $field['name'];
                $value = apply_filters( 'boombox/admin/post/meta-boxes/save-value?field=' . $key, $value, $post_id, $post );

                $meta_values[ $key ] = $value;

            }
        }

        $meta_values = apply_filters( 'boombox/admin/post/meta-boxes/save/values', $meta_values, $post_id, $post );

        return update_post_meta( $post_id, self::$key, $meta_values );

    }

    /** =========================== field types =========================== */

    /**
    * Render Delimeter
    * @param $field
    * @param bool|false $tab_id
    */
    private function renderDelimeter( $field, $tab_id = false ) {
        $field = wp_parse_args( $field, array(
            'html' => '<hr/>'
        ) );
        $wrapper = $this->get_wrapper_data_from_dependency( $field, $tab_id );
        ?>
        <div class="<?php echo $wrapper['class']; ?>" <?php echo $wrapper['atts']; ?>><?php echo $field['html']; ?></div>
        <?php
    }

    /**
     * Generate a dropdown
     *
     * @param $field
     */
    private function renderSelect( $field, $tab_id = false ) {
        $this->renderDropdown( $field, $tab_id );
    }

    /**
     * Generate a dropdown
     *
     * @param $field
     */
    private function renderDropdown( $field, $tab_id = false ) {

        $field = wp_parse_args( $field, array(
            'choices' => array()
        ) );

        $field['id'] = ( isset( $field['css']['id'] ) && $field['css']['id'] ) ? $field['css']['id'] : $field['id'];
        $field['name'] = isset( $field['name'] ) ? $field['name'] : $field['id'];
        $class = ( isset( $field['css']['class'] ) && $field['css']['class'] ) ? sprintf( 'class="%s"', $field['css']['class'] ) : '';
        $attr = ( isset( $field['css']['attr'] ) && $field['css']['attr'] ) ? $field['css']['attr'] : '';
        $curr_value = $this->get_field_value( $field );
        $wrapper = $this->get_wrapper_data_from_dependency( $field, $tab_id );
        ?>
        <div class="boombox-post-form-row <?php echo $wrapper['class']; ?>" <?php echo $wrapper['atts']; ?>>
            <label for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label>
            <select id="<?php echo $field['id']; ?>" <?php echo $class; ?> <?php echo $attr; ?> name="<?php echo $field['name']; ?>">
                <?php foreach ( $field['choices'] as $key => $value ) { ?>
                    <option value="<?php echo esc_html( esc_attr( $key ) ); ?>" <?php selected( $curr_value, esc_html( esc_attr( $key ) ), true ); ?>>
                        <?php echo esc_html( $value ); ?>
                    </option>
                <?php } ?>
            </select>
            <?php if( $field['description'] ) { ?>
            <p class="description"><?php echo $field['description']; ?></p>
            <?php } ?>
        </div>
        <?php
    }

    /**
     * Generate text field
     *
     * @param $field
     */
    private function renderText( $field, $tab_id = false ) {
        $this->renderTextfield( $field, $tab_id );
    }

    /**
     * Generate text field
     *
     * @param $field
     */
    private function renderTextfield( $field, $tab_id = false ) {

        $field['id'] = ( isset( $field['css']['id'] ) && $field['css']['id'] ) ? $field['css']['id'] : $field['id'];
        $field['name'] = isset( $field['name'] ) ? $field['name'] : $field['id'];
        $class = ( isset( $field['css']['class'] ) && $field['css']['class'] ) ? sprintf( 'class="%s"', $field['css']['class'] ) : '';
        $attr = ( isset( $field['css']['attr'] ) && $field['css']['attr'] ) ? $field['css']['attr'] : '';
        $value = $this->get_field_value( $field );
        $wrapper = $this->get_wrapper_data_from_dependency( $field, $tab_id );

        ?>
        <div class="boombox-post-form-row <?php echo $wrapper['class']; ?>" <?php echo $wrapper['atts']; ?>>
            <label for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label>
            <input type="text" id="<?php echo $field['id']; ?>" name="<?php echo $field['name']; ?>" <?php echo $class; ?> <?php echo $attr; ?> value="<?php echo $value; ?>"/>
            <?php if( $field['description'] ) { ?>
                <p class="description"><?php echo $field['description']; ?></p>
            <?php } ?>
        </div>
        <?php
    }

    /**
     * Generate textarea
     *
     * @param $field
     */
    private function renderTextarea( $field, $tab_id = false ) {

        $field['id'] = ( isset( $field['css']['id'] ) && $field['css']['id'] ) ? $field['css']['id'] : $field['id'];
        $field['name'] = isset( $field['name'] ) ? $field['name'] : $field['id'];
        $class = ( isset( $field['css']['class'] ) && $field['css']['class'] ) ? sprintf( 'class="%s"', $field['css']['class'] ) : '';
        $attr = ( isset( $field['css']['attr'] ) && $field['css']['attr'] ) ? $field['css']['attr'] : '';
        $value = $this->get_field_value( $field );
        $wrapper = $this->get_wrapper_data_from_dependency( $field, $tab_id );

        ?>
        <div class="boombox-post-form-row <?php echo $wrapper['class']; ?>" <?php echo $wrapper['atts']; ?>>
            <label for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label>
            <textarea id="<?php echo $field['id']; ?>" name="<?php echo $field['name']; ?>" <?php echo $class; ?> <?php echo $attr; ?>><?php echo $value; ?></textarea>
            <?php if( $field['description'] ) { ?>
                <p class="description"><?php echo $field['description']; ?></p>
            <?php } ?>
        </div>
        <?php
    }

    /**
     * Generate checkbox
     *
     * @param $field
     */
    private function renderCheckbox( $field, $tab_id = false ) {

        $field['id'] = ( isset( $field['css']['id'] ) && $field['css']['id'] ) ? $field['css']['id'] : $field['id'];
        $field['name'] = isset( $field['name'] ) ? $field['name'] : $field['id'];
        $class = ( isset( $field['css']['class'] ) && $field['css']['class'] ) ? sprintf( 'class="%s"', $field['css']['class'] ) : '';
        $attr = ( isset( $field['css']['attr'] ) && $field['css']['attr'] ) ? $field['css']['attr'] : '';
        $curr_value = $this->get_field_value( $field );
        $wrapper = $this->get_wrapper_data_from_dependency( $field, $tab_id );

        ?>
        <div class="boombox-post-form-row <?php echo $wrapper['class']; ?>" <?php echo $wrapper['atts']; ?>>
            <label for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label>
            <input type="hidden" name="<?php echo $field['name']; ?>" value="0" />
            <input type="checkbox" id="<?php echo $field['id']; ?>" name="<?php echo $field['name']; ?>" <?php checked( $curr_value, true, true ); ?> value="1" <?php echo $class; ?> <?php echo $attr; ?> />
            <?php if( $field['text'] ) { ?>
            <label for="<?php echo $field['id']; ?>"><strong><?php echo $field['text']; ?></strong></label>
            <?php } ?>
            <?php if( $field['description'] ) { ?>
                <p class="description"><?php echo $field['description']; ?></p>
            <?php } ?>
        </div>
        <?php

    }

    /**
     * Generate radio
     *
     * @param $field
     */
    private function renderRadio( $field, $tab_id = false ) {

        $field = wp_parse_args( $field, array(
            'choices' => array()
        ) );

        $field['id'] = ( isset( $field['css']['id'] ) && $field['css']['id'] ) ? $field['css']['id'] : $field['id'];
        $field['name'] = isset( $field['name'] ) ? $field['name'] : $field['id'];
        $class = ( isset( $field['css']['class'] ) && $field['css']['class'] ) ? sprintf( 'class="%s"', $field['css']['class'] ) : '';
        $attr = ( isset( $field['css']['attr'] ) && $field['css']['attr'] ) ? $field['css']['attr'] : '';
        $curr_value = $this->get_field_value( $field );
        $wrapper = $this->get_wrapper_data_from_dependency( $field, $tab_id );

        ?>
        <div class="boombox-post-form-row <?php echo $wrapper['class']; ?>" <?php echo $wrapper['atts']; ?>>
            <label for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label>

            <?php foreach( $field['choices'] as $key => $value ) { ?>
            <input type="radio" id="<?php echo $field['id']; ?>" name="<?php echo $field['name']; ?>" <?php checked( $curr_value, esc_html( esc_attr( $key ) ), true ); ?> value="<?php echo esc_html( esc_attr( $key ) ); ?>" <?php echo $class; ?> <?php echo $attr; ?> />
            <strong><?php echo $value; ?></strong>
            <?php } ?>
            <?php if( $field['description'] ) { ?>
                <p class="description"><?php echo $field['description']; ?></p>
            <?php } ?>

        </div>
        <?php

    }

    /** =========================== /end - field types =========================== */

    /** =========================== custom sanitation rules =========================== */

    /**
     * Sanitize video url
     *
     * @param string $value
     * @return string
     */
    protected function sanitize_video_url( $value = '' ) {
        if ( $value ) {
            $video_url = trim( $value );

            while( true ) {
                $video_type = wp_check_filetype( $video_url );
                if( isset( $video_type['type'] ) && $video_type['type'] && preg_match("~^(?:f|ht)tps?://~i", $video_url ) ) {
                    $value = $video_url;
                    break;
                }

                preg_match( boombox_get_regex( 'youtube' ), $video_url, $youtube_matches );
                if( isset( $youtube_matches[1] ) && $youtube_matches[1] ) {
                    $value = $video_url;
                    break;
                }

                preg_match( boombox_get_regex( 'vimeo' ) , $video_url, $vimeo_matches );
                if( isset( $vimeo_matches[5] ) && $vimeo_matches[5] ) {
                    $value = $video_url;
                    break;
                }

                break;
            }

        }

        return $value;
    }

    /**
     * Sanitize checkbox
     *
     * @param int $value
     * @return int|mixed
     */
    protected function sanitize_checkbox( $value = 0 ) {
        $value = filter_var( $value, FILTER_SANITIZE_NUMBER_INT );
        if( ! $value ) {
            $value = 0;
        }
        return $value;
    }
}