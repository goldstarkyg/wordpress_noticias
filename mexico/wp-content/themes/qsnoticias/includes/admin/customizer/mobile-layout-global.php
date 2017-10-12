<?php
/**
 * WP Customizer panel section to handle post options
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

$boombox_option_name = boombox_get_theme_name();

$mobile_layout_global = array(
    'section' => array(
        'id'   => 'boombox_mobile_layout_global_section',
        'args' => array(
            'title'    => esc_html__( 'Global', 'boombox' ),
            'priority' => 10,
            'panel'    => 'boombox_mobile_panel',
        )
    ),
    'fields' => array(
        // Disable Strip
        array(
            'setting' => array(
                'id' 	=> $boombox_option_name . '[mobile_layout_global_disable_strip]',
                'args'	=> array(
                    'default'           => $boombox_customizer_defaults['mobile_layout_global_disable_strip'],
                    'type'              => 'option',
                    'capability'        => 'edit_theme_options',
                    'sanitize_callback' => 'sanitize_text_field',
                )
            ),
            'control' => array(
                'id' 	=> 'boombox_mobile_layout_global_disable_strip',
                'args' 	=> array(
                    'label'    => esc_html__( 'Disable Strip', 'boombox' ),
                    'section'  => 'boombox_mobile_layout_global_section',
                    'settings' => $boombox_option_name . '[mobile_layout_global_disable_strip]',
                    'type'     => 'checkbox',
                )
            )
        ),
        // Disable Footer Strip
        array(
            'setting' => array(
                'id' 	=> $boombox_option_name . '[mobile_layout_global_disable_footer_strip]',
                'args'	=> array(
                    'default'           => $boombox_customizer_defaults['mobile_layout_global_disable_footer_strip'],
                    'type'              => 'option',
                    'capability'        => 'edit_theme_options',
                    'sanitize_callback' => 'sanitize_text_field',
                )
            ),
            'control' => array(
                'id' 	=> 'boombox_mobile_layout_global_disable_footer_strip',
                'args' 	=> array(
                    'label'    => esc_html__( 'Disable Footer Strip', 'boombox' ),
                    'section'  => 'boombox_mobile_layout_global_section',
                    'settings' => $boombox_option_name . '[mobile_layout_global_disable_footer_strip]',
                    'type'     => 'checkbox',
                )
            )
        ),
        // Disable Featured Area
        array(
            'setting' => array(
                'id' 	=> $boombox_option_name . '[mobile_layout_global_disable_featured_area]',
                'args'	=> array(
                    'default'           => $boombox_customizer_defaults['mobile_layout_global_disable_featured_area'],
                    'type'              => 'option',
                    'capability'        => 'edit_theme_options',
                    'sanitize_callback' => 'sanitize_text_field',
                )
            ),
            'control' => array(
                'id' 	=> 'boombox_mobile_layout_global_disable_featured_area',
                'args' 	=> array(
                    'label'    => esc_html__( 'Disable Featured Area', 'boombox' ),
                    'section'  => 'boombox_mobile_layout_global_section',
                    'settings' => $boombox_option_name . '[mobile_layout_global_disable_featured_area]',
                    'type'     => 'checkbox',
                )
            )
        ),
        // Disable Sidebar
        array(
            'setting' => array(
                'id' 	=> $boombox_option_name . '[mobile_layout_global_disable_sidebar]',
                'args'	=> array(
                    'default'           => $boombox_customizer_defaults['mobile_layout_global_disable_sidebar'],
                    'type'              => 'option',
                    'capability'        => 'edit_theme_options',
                    'sanitize_callback' => 'sanitize_text_field',
                )
            ),
            'control' => array(
                'id' 	=> 'boombox_mobile_layout_global_disable_sidebar',
                'args' 	=> array(
                    'label'    => esc_html__( 'Disable Sidebar', 'boombox' ),
                    'section'  => 'boombox_mobile_layout_global_section',
                    'settings' => $boombox_option_name . '[mobile_layout_global_disable_sidebar]',
                    'type'     => 'checkbox',
                )
            )
        ),
        // Other fields need to go here
    )
);

return $mobile_layout_global;