<?php
/**
 * WP Customizer panel section to handle mobile post options
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

$boombox_option_name = boombox_get_theme_name();

$mobile_layout_post = array(
    'section' => array(
        'id'   => 'boombox_mobile_layout_post_section',
        'args' => array(
            'title'    => esc_html__( 'Post Single', 'boombox' ),
            'priority' => 10,
            'panel'    => 'boombox_mobile_panel',
        )
    ),
    'fields' => array(
        // Disable Related Posts Section
        /*array(
            'setting' => array(
                'id' 	=> $boombox_option_name . '[mobile_layout_post_disable_related_block]',
                'args'	=> array(
                    'default'           => $boombox_customizer_defaults['mobile_layout_post_disable_related_block'],
                    'type'              => 'option',
                    'capability'        => 'edit_theme_options',
                    'sanitize_callback' => 'sanitize_text_field',
                )
            ),
            'control' => array(
                'id' 	=> 'boombox_mobile_layout_post_disable_related_block',
                'args' 	=> array(
                    'label'    => esc_html__( 'Disable Related Posts Section', 'boombox' ),
                    'section'  => 'boombox_mobile_layout_post_section',
                    'settings' => $boombox_option_name . '[mobile_layout_post_disable_related_block]',
                    'type'     => 'checkbox',
                )
            )
        ),
        // Disable "More From" Section
        array(
            'setting' => array(
                'id' 	=> $boombox_option_name . '[mobile_layout_post_disable_more_block]',
                'args'	=> array(
                    'default'           => $boombox_customizer_defaults['mobile_layout_post_disable_more_block'],
                    'type'              => 'option',
                    'capability'        => 'edit_theme_options',
                    'sanitize_callback' => 'sanitize_text_field',
                )
            ),
            'control' => array(
                'id' 	=> 'boombox_mobile_layout_post_disable_more_block',
                'args' 	=> array(
                    'label'    => esc_html__( 'Disable "More From" Section', 'boombox' ),
                    'section'  => 'boombox_mobile_layout_post_section',
                    'settings' => $boombox_option_name . '[mobile_layout_post_disable_more_block]',
                    'type'     => 'checkbox',
                )
            )
        ),
        // Disable "Don't Miss" Section
        array(
            'setting' => array(
                'id' 	=> $boombox_option_name . '[mobile_layout_post_disable_dont_miss_block]',
                'args'	=> array(
                    'default'           => $boombox_customizer_defaults['mobile_layout_post_disable_dont_miss_block'],
                    'type'              => 'option',
                    'capability'        => 'edit_theme_options',
                    'sanitize_callback' => 'sanitize_text_field',
                )
            ),
            'control' => array(
                'id' 	=> 'boombox_mobile_layout_post_disable_dont_miss_block',
                'args' 	=> array(
                    'label'    => esc_html__( 'Disable "Don\'t Miss" Section', 'boombox' ),
                    'section'  => 'boombox_mobile_layout_post_section',
                    'settings' => $boombox_option_name . '[mobile_layout_post_disable_dont_miss_block]',
                    'type'     => 'checkbox',
                )
            )
        ),
        // Divider
        array(
            'setting' => array(
                'id' 	=> $boombox_option_name . '[mobile_layout_archive_divider_3]',
                'args'	=> array(
                    'type'              => 'option',
                    'capability'        => 'edit_theme_options',
                    'sanitize_callback' => 'sanitize_text_field',
                )
            ),
            'control' => array(
                'id' 	=> 'boombox_mobile_layout_archive_divider_3',
                'type'  => 'custom_content',
                'args' 	=> array(
                    'section' => 'boombox_mobile_layout_post_section',
                    'content' => '<hr>',
                    'settings' => $boombox_option_name . '[mobile_layout_archive_divider_3]',
                )
            )
        ),
        */
        // Other fields need to go here
    )
);

return $mobile_layout_post;