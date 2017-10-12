<?php
/*
Plugin Name: Multimedia Carousel - Classic and Perspective - Visual Composer Addon
Description: Premium multimedia carousel - Visual Composer Addon
Version: 1.3.1
Author: Lambert Group
Author URI: https://codecanyon.net/user/LambertGroup/portfolio?ref=LambertGroup
*/


//all the messages
$rand_id=rand(10,999999);

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


function multimedia_carousel_addon_vc_map_dependencies() {
	if ( ! defined( 'WPB_VC_VERSION' ) ) {
		$plugin_data = get_plugin_data(__FILE__);
        echo '<div class="updated">
          <p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="https://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'vc_extend'), $plugin_data['Name']).'</p>
        </div>';
	}
}
add_action( 'admin_notices', 'multimedia_carousel_addon_vc_map_dependencies' );


function multimedia_carousel_addon_vc_map_init() {
	global $wpdb;
	global $rand_id;
	

	
	
	
	//Create New Param Type 'multimedia_carousel_attach_media'
			vc_add_shortcode_param( 'multimedia_carousel_attach_media', 'multimedia_carousel_attach_media_callback', plugins_url() . '/lbg_multimedia_carousel_addon_visual_composer/assets/new_param_type/attach_media.js');
			function multimedia_carousel_attach_media_callback( $settings, $value ) {   
				return '<div class="my_param_block">'
				.'<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' . esc_attr( $settings['param_name'] ) . ' ' .              esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" style="width:70%;" />' .
				'<input name="upload_' . esc_attr( $settings['param_name'] ) . '_button" type="button" id="upload_' . esc_attr( $settings['param_name'] ) . '_button" value="Upload File" style="width:30%;" /> '.'</div>'; // This is html markup that will be outputted in content elements edit form
			}


	
	
	vc_map( array(
		'name' => __( 'Multimedia Carousel', 'js_composer' ),
		'base' => 'multimedia_carousel',
		"icon" => plugins_url('assets/images/multimedia_carousel_icon.png', __FILE__), // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
		"category" => __('LBG Multimedia Addons', 'js_composer'),
		"description" => __("perspective and classic versions", 'vc_extend'),	
		'show_settings_on_create' => false,
		'is_container' => true,
		'admin_enqueue_js'      => preg_replace( '/\s/', '%20', plugins_url( 'assets/multimedia_carousel.js', __FILE__ ) ),
		// This will load extra js file in backend (when you edit page with VC)
		// use preg replace to be sure that "space" will not break logic

		//'admin_enqueue_css'     => preg_replace( '/\s/', '%20', plugins_url( 'assets/admin_enqueue_css.css', __FILE__ ) ),
		'admin_enqueue_css'     => preg_replace( '/\s/', '%20', plugins_url( 'assets/multimedia_carousel.css', __FILE__ ) ),
		// This will load extra css file in backend (when you edit page with VC)				
		'params' => array(
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'Player ID', 'js_composer' ),
				'param_name' => 'id',
				'value' => __( "".$rand_id."", "my-text-domain" ),
				'description' => __( "It is automaticaly generated and it has to be unique. You can leave it just like it is.", "js_composer" )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Type', 'js_composer' ),
				'param_name' => 'type',
				'value' => array(
					'perspective' => 'perspective',
					'classic' => 'classic'					
				)
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Skin Name', 'js_composer' ),
				'param_name' => 'skin',
				'value' => array(
					'white' => 'white',
					'black' => 'black'					
				)
			),
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'Carousel Width', 'js_composer' ),
				'param_name' => 'width', //width
				'value' => __( "990", "my-text-domain" )
			),
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'Carousel Height', 'js_composer' ),
				'param_name' => 'height', //height
				'value' => __( "454", "my-text-domain" )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Width 100%', 'js_composer' ),
				'param_name' => 'width_100_proc', //width100Proc
				'value' => array(				
					'No' => 'false',
					'Yes' => 'true'
				 ),
				'description' => __( "Set the carousel as full width", "js_composer" )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Center Carousel', 'js_composer' ),
				'param_name' => 'center_plugin',
				'value' => array(				
					'Yes' => 'true',
					'No' => 'false'
				 ),
				'description' => __( "Center the carousel", "js_composer" )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Responsive', 'js_composer' ),
				'param_name' => 'responsive',
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Responsive Relative To Browser', 'js_composer' ),
				'param_name' => 'responsive_relative_to_browser', //responsiveRelativeToBrowser
				'value' => array(
					'No' => 'false',
					'Yes' => 'true'
				  )
			),			
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'Image Width', 'js_composer' ),
				'param_name' => 'image_width', //imageWidth
				'value' => __( "452", "my-text-domain" )
			),
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'Image Height', 'js_composer' ),
				'param_name' => 'image_height', //imageHeight
				'value' => __( "302", "my-text-domain" )
			),
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'Auto Play', 'js_composer' ),
				'param_name' => 'auto_play', //autoPlay
				'value' => __( "5", "my-text-domain" ),
				'description' => __( "seconds", "js_composer" )
			),			
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'Number Of Thumbs Per Screen', 'js_composer' ),
				'param_name' => 'number_of_thumbs_per_screen', //numberOfThumbsPerScreen
				'value' => __( "0", "my-text-domain" ),
				'description' => __( "If you set it to 0, it will be calculated automatically. You can set a fixed number, for example 3", "js_composer" )  
			),	
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Target Window For Link', 'js_composer' ),
				'param_name' => 'target', //target
				'value' => array(
					'_blank' => '_blank',
					'_self' => '_self'
				  )
			),			
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Enable Touch Screen', 'js_composer' ),
				'param_name' => 'enable_touch_screen',//enableTouchScreen
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),					
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'LightBox Window Width Divider', 'js_composer' ),
				'param_name' => 'lightbox_width_divider', //lightbox_width_divider
				'value' => __( "2", "my-text-domain" )
			),	
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'LightBox Window Height Divider', 'js_composer' ),
				'param_name' => 'lightbox_height_divider', //lightbox_height_divider
				'value' => __( "9/16", "my-text-domain" )
			),	
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Show Navigation Arrows', 'js_composer' ),
				'param_name' => 'show_nav_arrows',//showNavArrows
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Show Navigation Arrows On Init', 'js_composer' ),
				'param_name' => 'show_on_init_nav_arrows',//showOnInitNavArrows
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Auto Hide Navigation Arrows', 'js_composer' ),
				'param_name' => 'auto_hide_nav_arrows',//autoHideNavArrows
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Show Bottom Navigation', 'js_composer' ),
				'param_name' => 'show_bottom_nav',//showBottomNav
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Show Bottom Navigation On Init', 'js_composer' ),
				'param_name' => 'show_on_init_bottom_nav',//showOnInitBottomNav
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Auto Hide Bottom Navigation', 'js_composer' ),
				'param_name' => 'auto_hide_bottom_nav',//autoHideBottomNav
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'Bottom Navigation Margin Bottom', 'js_composer' ),
				'param_name' => 'bottom_nav_margin_bottom', //bottomNavMarginBottom
				'value' => __( "-10", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),	

			
			
			
			

			array(
				'group' => 'Only Perspective',
				'type' => 'textfield',
				'heading' => __( 'Animation Time', 'js_composer' ),
				'param_name' => 'animation_time', //animationTime
				'value' => __( "0.8", "my-text-domain" ),
				'description' => __( "seconds", "js_composer" )  
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'dropdown',
				'heading' => __( 'Easing', 'js_composer' ),
				'param_name' => 'easing', //easing
				'value' => array(
					'easeOutQuad' => 'easeOutQuad',
					'easeInQuad' => 'easeInQuad',					
					'easeInOutQuad' => 'easeInOutQuad',
					'easeInCubic' => 'easeInCubic',
					'easeOutCubic' => 'easeOutCubic',
					'easeInOutCubic' => 'easeInOutCubic',
					'easeInQuart' => 'easeInQuart',
					'easeOutQuart' => 'easeOutQuart',
					'easeInOutQuart' => 'easeInOutQuart',
					'easeInSine' => 'easeInSine',
					'easeOutSine' => 'easeOutSine',
					'easeOutSine' => 'easeOutSine',
					'easeInExpo' => 'easeInExpo',
					'easeOutExpo' => 'easeOutExpo',
					'easeInOutExpo' => 'easeInOutExpo',
					'easeInQuint' => 'easeInQuint',
					'easeOutQuint' => 'easeOutQuint',
					'easeInOutQuint' => 'easeInOutQuint',
					'easeInCirc' => 'easeInCirc',
					'easeOutCirc' => 'easeOutCirc',
					'easeInOutCirc' => 'easeInOutCirc',
					'easeInElastic' => 'easeInElastic',
					'easeOutElastic' => 'easeOutElastic',
					'easeInOutElastic' => 'easeInOutElastic',
					'easeInBack' => 'easeInBack',
					'easeOutBack' => 'easeOutBack',
					'easeInOutBack' => 'easeInOutBack',
					'easeInBounce' => 'easeInBounce',
					'easeOutBounce' => 'easeOutBounce',
					'easeInOutBounce' => 'easeInOutBounce',
					'swing' => 'swing',
					'linear' => 'linear'			
				  )
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'textfield',
				'heading' => __( 'Number Of Visible Items', 'js_composer' ),
				'param_name' => 'number_of_visible_items', //numberOfVisibleItems
				'value' => __( "3", "my-text-domain" ),
				'description' => __( "odd number", "js_composer" )  
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'textfield',
				'heading' => __( 'Elements Horizontal Spacing', 'js_composer' ),
				'param_name' => 'elements_horizontal_spacing', //elementsHorizontalSpacing
				'value' => __( "120", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )  
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'textfield',
				'heading' => __( 'Elements Vertical Spacing', 'js_composer' ),
				'param_name' => 'elements_vertical_spacing', //elementsVerticalSpacing
				'value' => __( "20", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )  
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'textfield',
				'heading' => __( 'Vertical Adjustment', 'js_composer' ),
				'param_name' => 'vertical_adjustment', //verticalAdjustment
				'value' => __( "50", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )  
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'dropdown',
				'heading' => __( 'Resize Images', 'js_composer' ),
				'param_name' => 'resize_images',//resizeImages
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'dropdown',
				'heading' => __( 'Show Element Title', 'js_composer' ),
				'param_name' => 'show_element_title',//showElementTitle
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'colorpicker',
				'heading' => __( "Title Color", "js_composer" ),
				'param_name' => 'title_color', //titleColor
				'value' => '#000000',
				'description' => __( "Choose the color", "js_composer" )
			),		
			array(
				'group' => 'Only Perspective',
				'type' => 'textfield',
				'heading' => __( 'Border Width', 'js_composer' ),
				'param_name' => 'border', //border
				'value' => __( "0", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Only Perspective',            
				'type' => 'colorpicker',
				'heading' => __( "Border Color OFF", "js_composer" ),
				'param_name' => 'border_color_off', //borderColorOFF
				'value' => 'transparent',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'colorpicker',
				'heading' => __( "Border Color ON", "js_composer" ),
				'param_name' => 'border_color_on', //borderColorON
				'value' => '#FF0000',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'dropdown',
				'heading' => __( 'Show Preview Thumbs', 'js_composer' ),
				'param_name' => 'show_preview_thumbs',//showPreviewThumbs
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'textfield',
				'heading' => __( 'Next&Prev Buttons Margin Top', 'js_composer' ),
				'param_name' => 'next_prev_margin_top', //nextPrevMarginTop
				'value' => __( "15", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'textfield',
				'heading' => __( 'Play Movie Margin Top', 'js_composer' ),
				'param_name' => 'play_movie_margin_top', //playMovieMarginTop
				'value' => __( "0", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'dropdown',
				'heading' => __( 'Show All Controllers', 'js_composer' ),
				'param_name' => 'show_all_controllers',//showAllControllers
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'dropdown',
				'heading' => __( 'Show Circle Timer', 'js_composer' ),
				'param_name' => 'show_circle_timer',//showCircleTimer
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'textfield',
				'heading' => __( 'Circle Radius', 'js_composer' ),
				'param_name' => 'circle_radius', //circleRadius
				'value' => __( "10", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'textfield',
				'heading' => __( 'Circle Line Width', 'js_composer' ),
				'param_name' => 'circle_line_width', //circleLineWidth
				'value' => __( "4", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'colorpicker',
				'heading' => __( "Circle Color", "js_composer" ),
				'param_name' => 'circle_color', //circleColor
				'value' => '#FF0000',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'textfield',
				'heading' => __( 'Circle Alpha', 'js_composer' ),
				'param_name' => 'circle_alpha', //circleAlpha
				'value' => __( "100", "my-text-domain" ),
				'description' => __( "Values between 0-100", "js_composer" )
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'colorpicker',
				'heading' => __( "Behind Circle Color", "js_composer" ),
				'param_name' => 'behind_circle_color', //behindCircleColor
				'value' => '#000000',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'textfield',
				'heading' => __( 'Behind Circle Alpha', 'js_composer' ),
				'param_name' => 'behind_circle_alpha', //behindCircleAlpha
				'value' => __( "50", "my-text-domain" ),
				'description' => __( "Values between 0-100", "js_composer" )
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'textfield',
				'heading' => __( 'Circle Left Position Correction', 'js_composer' ),
				'param_name' => 'circle_left_position_correction', //circleLeftPositionCorrection
				'value' => __( "3", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Only Perspective',
				'type' => 'textfield',
				'heading' => __( 'Circle Top Position Correction', 'js_composer' ),
				'param_name' => 'circle_top_position_correction', //circleTopPositionCorrection
				'value' => __( "3", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			
			
			
			
			
			
			
			
			array(
				'group' => 'Only Classic',
				'type' => 'dropdown',
				'heading' => __( 'Bottom Navigation Position', 'js_composer' ),
				'param_name' => 'bottom_nav_position', //bottomNavPosition
				'value' => array(
					'right' => 'right',
					'center' => 'center',	
					'left' => 'left'										
				  )
			),
			array(
				'group' => 'Only Classic',
				'type' => 'textfield',
				'heading' => __( 'Item Padding', 'js_composer' ),
				'param_name' => 'item_padding', //itemPadding
				'value' => __( "7", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Only Classic',
				'type' => 'colorpicker',
				'heading' => __( "Item Background Color OFF State", "js_composer" ),
				'param_name' => 'item_background_color_off', //itemBackgroundColorOFF
				'value' => '#FFFFFF',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Only Classic',
				'type' => 'colorpicker',
				'heading' => __( "Item Background Color ON State", "js_composer" ),
				'param_name' => 'item_background_color_on', //itemBackgroundColorON
				'value' => '#000000',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Only Classic',
				'type' => 'colorpicker',
				'heading' => __( "Title Color OFF", "js_composer" ),
				'param_name' => 'title_color_off', //titleColorOFF
				'value' => '#444444',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Only Classic',
				'type' => 'colorpicker',
				'heading' => __( "Title Color ON", "js_composer" ),
				'param_name' => 'title_color_on', //titleColorON
				'value' => '#FFFFFF',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Only Classic',
				'type' => 'textfield',
				'heading' => __( 'Title Font Size', 'js_composer' ),
				'param_name' => 'title_font_size', //titleFontSize
				'value' => __( "18", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Only Classic',
				'type' => 'textfield',
				'heading' => __( 'Title Margin Top', 'js_composer' ),
				'param_name' => 'title_margin_top', //titleMarginTop
				'value' => __( "20", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Only Classic',
				'type' => 'colorpicker',
				'heading' => __( "Description Color OFF", "js_composer" ),
				'param_name' => 'desc_color_off', //descColorOFF
				'value' => '#7e7e7e',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Only Classic',
				'type' => 'colorpicker',
				'heading' => __( "Description Color ON", "js_composer" ),
				'param_name' => 'desc_color_on', //descColorON
				'value' => '#FFFFFF',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Only Classic',
				'type' => 'textfield',
				'heading' => __( 'Description Font Size', 'js_composer' ),
				'param_name' => 'desc_font_size', //descFontSize
				'value' => __( "14", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Only Classic',
				'type' => 'textfield',
				'heading' => __( 'Description Margin Top', 'js_composer' ),
				'param_name' => 'desc_margin_top', //descMarginTop
				'value' => __( "8", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			)
			
			
		),
		'custom_markup' => '
	<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
	%content%
	</div>
	<div class="tab_controls lbg_add_playlist_item">
		<a class="add_tab vc_add_playlist_item" title="' . __( 'Add Playlist Item', 'js_composer' ) . '"><span class="vc_playlist_item_vc_icon"></span>' . __( 'Add Playlist Item', 'js_composer' ) . '</a>
	</div>
	',
		'default_content' => '
		[multimedia_carousel_playlist_item title="' . __( 'Playlist Item  1', 'js_composer' ) . '"][/multimedia_carousel_playlist_item]
	',
		'js_view' => 'VcMultimediaCarouselView'
	) );
	
	vc_map( array(
		'name' => __( 'Carousel Item', 'js_composer' ),
		'base' => 'multimedia_carousel_playlist_item',
		'allowed_container_element' => 'vc_row',
		'is_container' => true,
		'content_element' => false,
		'admin_enqueue_js'      => preg_replace( '/\s/', '%20', plugins_url( 'assets/playlist_item.js', __FILE__ ) ),
		// This will load extra js file in backend (when you edit page with VC)
		// use preg replace to be sure that "space" will not break logic

		'admin_enqueue_css'     => preg_replace( '/\s/', '%20', plugins_url( 'assets/playlist_item.css', __FILE__ ) ),
		// This will load extra css file in backend (when you edit page with VC)		
		'params' => array(
			array(
				'group' => 'Image Settings',
				'type' => 'attach_image',
				'heading' => __( 'Image', 'js_composer' ),
				'param_name' => 'img',
				'description' => __( 'Select an image from Media Library', 'js_composer' )
				
			),
			array(
				'group' => 'Image Settings',
				'type' => 'textfield',
				'heading' => __( 'Image Title', 'js_composer' ),
				'param_name' => 'title'
			),
			array(
				'group' => 'Image Settings',
				'type' => 'textarea',
				'heading' => __( 'Image description', 'js_composer' ),
				'param_name' => 'desc',
				'description' => __( 'Only for Classic carousel version', 'js_composer' )
			),	
			array(
				'group' => 'Image Settings',
				'type' => 'textfield',
				'heading' => __( 'Link For The Image', 'js_composer' ),
				'param_name' => 'data_link' //data-link
			),
			array(
				'group' => 'Image Settings',
				'type' => 'dropdown',
				'heading' => __( 'Link Target', 'js_composer' ),
				'param_name' => 'data_target', //data-target
				'value' => array(
					'_blank' => '_blank',
					'_self' => '_self'
				  )
			),
			array(
				'group' => 'Image Settings',
				'type' => 'attach_image',
				'heading' => __( 'Preview Thumb', 'js_composer' ),
				'param_name' => 'data_bottom_thumb', //
				'description' => __( 'Select an image from Media Library. Recommended size: 79px x 79px. Only for Perspective version', 'js_composer' )
				
			),
			
			array(
				'group' => 'LightBox Settings',
				'type' => 'attach_image',
				'heading' => __( 'Large Image', 'js_composer' ),
				'param_name' => 'data_large_image', //data_large_image
				'description' => __( 'Select an image from Media Library', 'js_composer' )
				
			),
			array(
				'group' => 'LightBox Settings',
				'type' => 'textfield',
				'heading' => __( 'YouTube Video ID', 'js_composer' ),
				'param_name' => 'data_video_youtube' //data-video-youtube
			),
			array(
				'group' => 'LightBox Settings',
				'type' => 'textfield',
				'heading' => __( 'Vimeo Video ID', 'js_composer' ),
				'param_name' => 'data_video_vimeo' //data-video-vimeo
			),
			array(
				'group' => 'LightBox Settings',
				'type' => 'multimedia_carousel_attach_media',
				'heading' => __( 'Self Hosted/Third Party Hosted Video', 'js_composer' ),
				'param_name' => 'data_video_selfhosted', //data_video_selfhosted
				'description' => __( 'Enter an URL or upload a .mp4 file', 'js_composer' )
			),
			array(
				'group' => 'LightBox Settings',
				'type' => 'multimedia_carousel_attach_media',
				'heading' => __( 'Audio File', 'js_composer' ),
				'param_name' => 'data_audio', //data_audio
				'description' => __( 'Enter an URL or upload a .mp3 file', 'js_composer' )
			)
			
			
			
			
		),
		'js_view' => 'VcPlaylistItemView'
	) );
	




	if ( class_exists( "WPBakeryShortCode" ) ) {
		$multimedia_carousel_addon_path = trailingslashit(dirname(__FILE__)); 
		include_once($multimedia_carousel_addon_path . 'vc_contentadmin/multimedia_carousel-accordion.php');
		include_once($multimedia_carousel_addon_path . 'vc_contentadmin/multimedia_carousel-accordion-tab.php');
		

	} // End Class

}


//add_action('vc_after_init', 'multimedia_carousel_addon_vc_map_init');
add_action('vc_before_init', 'multimedia_carousel_addon_vc_map_init');






if (!function_exists('lbg_unstrip_array')) {
	//stripslashes for an entire array
	function lbg_unstrip_array($array){
		if (is_array($array)) {	
			foreach($array as &$val){
				if(is_array($val)){
					$val = unstrip_array($val);
				} else {
					$val = stripslashes($val);
					
				}
			}
		}
		return $array;
	}
}




function multimedia_carousel_addon_enqueue_scripts_and_styles() {
	//load scripts in front-end
	//if (!is_admin()) {
				wp_enqueue_style('multimedia_carousel_classic_css', plugins_url('classic/css/multimedia_classic_carousel.css', __FILE__));
				wp_enqueue_style('multimedia_carousel_perspective_css', plugins_url('perspective/css/multimedia_perspective_carousel.css', __FILE__));	
				wp_enqueue_style('lbg_prettyPhoto_css', plugins_url('perspective/css/prettyPhoto.css', __FILE__));
				
		
				wp_enqueue_script('jquery');

				wp_enqueue_script('jquery-ui-core');
				wp_enqueue_script('jquery-ui-widget');
				wp_enqueue_script('jquery-ui-mouse');
				wp_enqueue_script('jquery-ui-accordion');
				wp_enqueue_script('jquery-ui-autocomplete');
				wp_enqueue_script('jquery-ui-slider');
				wp_enqueue_script('jquery-ui-tabs');
				wp_enqueue_script('jquery-ui-sortable');
				wp_enqueue_script('jquery-ui-draggable');
				wp_enqueue_script('jquery-ui-droppable');
				wp_enqueue_script('jquery-ui-selectable');
				wp_enqueue_script('jquery-ui-position');
				wp_enqueue_script('jquery-ui-datepicker');
				wp_enqueue_script('jquery-ui-resizable');
				wp_enqueue_script('jquery-ui-dialog');
				wp_enqueue_script('jquery-ui-button');				
				wp_enqueue_script('jquery-form');
				wp_enqueue_script('jquery-color');
				wp_enqueue_script('jquery-masonry');
				wp_enqueue_script('jquery-ui-progressbar');
				wp_enqueue_script('jquery-ui-tooltip');				
				wp_enqueue_script('jquery-effects-core');
				wp_enqueue_script('jquery-effects-blind');
				wp_enqueue_script('jquery-effects-bounce');
				wp_enqueue_script('jquery-effects-clip');
				wp_enqueue_script('jquery-effects-drop');
				wp_enqueue_script('jquery-effects-explode');
				wp_enqueue_script('jquery-effects-fade');
				wp_enqueue_script('jquery-effects-fold');
				wp_enqueue_script('jquery-effects-highlight');
				wp_enqueue_script('jquery-effects-pulsate');
				wp_enqueue_script('jquery-effects-scale');
				wp_enqueue_script('jquery-effects-shake');
				wp_enqueue_script('jquery-effects-slide');			
				wp_enqueue_script('jquery-effects-transfer');
				
				
				
				
				wp_register_script('lbg-touchSwipe', plugins_url('perspective/js/jquery.touchSwipe.min.js', __FILE__));
				wp_enqueue_script('lbg-touchSwipe');	
				
		
				wp_register_script('lbg-multimedia_carousel_classic', plugins_url('classic\js\multimedia_classic_carousel.js', __FILE__));
				wp_enqueue_script('lbg-multimedia_carousel_classic');			
		
				wp_register_script('lbg-multimedia_carousel_perspective', plugins_url('perspective\js\multimedia_perspective_carousel.js', __FILE__));
				wp_enqueue_script('lbg-multimedia_carousel_perspective');	
			
				
				/*wp_dequeue_script( 'prettyPhoto' );
				wp_deregister_script('prettyPhoto');
				wp_register_script( 'prettyphoto', vc_asset_url( 'lib/prettyphoto/js/jquery.prettyPhoto.js' ), array( 'jquery' ), WPB_VC_VERSION, true );


				wp_register_script('lbg-prettyPhoto', plugins_url('perspective\js\jquery.prettyPhoto.js', __FILE__),'','1.0',true);
				wp_enqueue_script('lbg-prettyPhoto');*/
	//}
}
add_action( 'wp_enqueue_scripts', 'multimedia_carousel_addon_enqueue_scripts_and_styles' );



function multimedia_carousel_addon_footer_script() {
	if (!is_admin()) {
		/*wp_dequeue_script( 'prettyPhoto' );
		  wp_deregister_script('prettyPhoto');
		  wp_register_script( 'prettyphoto', vc_asset_url( 'lib/prettyphoto/js/jquery.prettyPhoto.js' ), array( 'jquery' ), WPB_VC_VERSION, true );*/
		  wp_register_script('lbg-prettyPhoto', plugins_url('perspective\js\jquery.prettyPhoto.js', __FILE__),'','1.0',true);
		  wp_enqueue_script('lbg-prettyPhoto');
	}
}
add_action( 'wp_footer', 'multimedia_carousel_addon_footer_script' );


//the shortcodes
add_shortcode( 'multimedia_carousel_playlist_item', 'multimedia_carousel_playlist_item_func' );
function multimedia_carousel_playlist_item_func( $atts, $content = null ) { // New function parameter $content is added!
	global $wpdb;
	
		$the_img=wp_get_attachment_image_src($atts["img"], "large");
		$img_over='';
		if ($the_img[0]!='') {
			if (strpos($the_img[0], 'wp-content',9)===false)
				list($width, $height, $type, $attr) = getimagesize($the_img[0]);
			else
				list($width, $height, $type, $attr) = getimagesize( ABSPATH.substr($the_img[0],strpos($the_img[0], 'wp-content',9)) );
			$img_over='<img src="'.$the_img[0].'" width="'.$width.'" height="'.$height.'" alt="'.$atts['title'].'"  title="'.$atts['title'].'" />';
		}
		
		$data_audio='';
		$data_video='';
		if ($atts['data_audio']!='') {
			$data_audio=substr($atts['data_audio'],0,strlen($atts['data_audio'])-4);;
		}
		if ($atts['data_video_selfhosted']!='') {
			if (strpos($atts['data_video_selfhosted'],'.webm')!=false)
				$data_video=substr($atts['data_video_selfhosted'],0,strlen($atts['data_video_selfhosted'])-5);
			else
				$data_video=substr($atts['data_video_selfhosted'],0,strlen($atts['data_video_selfhosted'])-4);
		}
		
/*switch ($atts["type"]) {
		case 'classic':
			$playlist_str.='<li data-link="'.$atts['data_link'].'" data-target="'.$atts['data_target'].'" data-large-image="'.$atts['data_large_image'].'" data-video-vimeo="'.$atts['data_video_vimeo'].'" data-video-youtube="'.$atts['data_video_youtube'].'" data-audio="'.$data_audio.'" data-video-selfhosted="'.$data_video.'" >'.$img_over.'<div class="titlez">'.$atts['title'].'</div><div class="descz">'.$atts['desc'].'</div></li>';
			break;
		case 'perspective':
			$playlist_str.='<li data-bottom-thumb="'.$atts['data_bottom_thumb'].'" data-title="'.$atts['title'].'" data-link="'.$atts['data_link'].'" data-target="'.$atts['data_target'].'" data-large-image="'.$atts['data_large_image'].'" data-video-vimeo="'.$atts['data_video_vimeo'].'" data-video-youtube="'.$atts['data_video_youtube'].'" data-audio="'.$data_audio.'" data-video-selfhosted="'.$data_video.'" >'.$img_over.'</li>';
			break;
	}	*/
	
	
	$the_large_img=wp_get_attachment_image_src($atts["data_large_image"], "large");
	$the_thumb=wp_get_attachment_image_src($atts["data_bottom_thumb"], "large");
	$aux_content.='<li data-bottom-thumb="'.$the_thumb[0].'" data-title="'.$atts['title'].'" data-link="'.$atts['data_link'].'" data-target="'.$atts['data_target'].'" data-large-image="'.$the_large_img[0].'" data-video-vimeo="'.$atts['data_video_vimeo'].'" data-video-youtube="'.$atts['data_video_youtube'].'" data-audio="'.$data_audio.'" data-video-selfhosted="'.$data_video.'" >'.$img_over.'<div class="titlez">'.$atts['title'].'</div><div class="descz">'.$atts['desc'].'</div></li>';	
	
	return str_replace("\r\n", '', $aux_content);
		
	
	/*$the_img=wp_get_attachment_image_src($atts["img"], "large");
	$img_over='';
	if ($the_img[0]!='') {
		if (strpos($the_img[0], 'wp-content',9)===false)
			list($width, $height, $type, $attr) = getimagesize($the_img[0]);
		else
			list($width, $height, $type, $attr) = getimagesize( ABSPATH.substr($the_img[0],strpos($the_img[0], 'wp-content',9)) );
		$img_over='<img src="'.$the_img[0].'" width="'.$width.'" height="'.$height.'" alt="'.$atts['title'].'"  title="'.$atts['title'].'" />';
		
	}


	$aux_content='<li data-link="'.$atts['data_link'].'" data-target="'.$atts['data_target'].'" >'.$img_over.'</li>';	
	

	return str_replace("\r\n", '', $aux_content);	*/
}


add_shortcode( 'multimedia_carousel', 'lbg_multimedia_carousel_func' );
	function lbg_multimedia_carousel_func( $atts, $content = null ) { // New function parameter $content is added!
		global $rand_id;
		$initial_vals_arr=array(
			'id' => $rand_id,
			'type' => 'perspective',
			'skin' => 'white',
			'width' => 990,
			'height' => 454,
			'width_100_proc' => 'false',
			'center_plugin' => 'true',
			'responsive' => 'true',
			'responsive_relative_to_browser' => 'false',
			'image_width' => 452,
			'image_height' => 302,
			'auto_play' => 5,
			'number_of_thumbs_per_screen' => 0,
			'target' => '_blank',
			'enable_touch_screen' => 'true',
			'lightbox_width_divider' => 2,
			'lightbox_height_divider' => '9/16',
			'show_nav_arrows' => 'true',
			'show_on_init_nav_arrows' => 'true',
			'auto_hide_nav_arrows' => 'true',
			'show_bottom_nav' => 'true',
			'show_on_init_bottom_nav' => 'true',
			'auto_hide_bottom_nav' => 'true',
			'bottom_nav_margin_bottom' => -10,
			'animation_time' => 0.8,
			'easing' => 'easeOutQuad',
			'number_of_visible_items' => 3,
			'elements_horizontal_spacing' => 120,
			'elements_vertical_spacing' => 20,
			'vertical_adjustment' => 50,
			'resize_images' => 'true',
			'show_element_title' => 'true',
			'title_color' => '#000000',
			'border' => 0,
			'border_color_off' => 'transparent',
			'border_color_on' => '#FF0000',
			'show_preview_thumbs' => 'true',
			'next_prev_margin_top' => 15,
			'play_movie_margin_top' => 0,
			'show_all_controllers' => 'true',
			'show_circle_timer' => 'true',
			'circle_radius' => 10,
			'circle_line_width' => 4,
			'circle_color' => '#FF0000',
			'circle_alpha' => 100,
			'behind_circle_color' => '#000000',
			'behind_circle_alpha' => 50,
			'circle_left_position_correction' => 3,
			'circle_top_position_correction' => 3,
			'bottom_nav_position' => 'right',
			'item_padding' => 7,
			'item_background_color_off' => '#FFFFFF',
			'item_background_color_on' => '#000000',
			'title_color_off' => '#444444',
			'title_color_on' => '#FFFFFF',
			'title_font_size' => 18,
			'title_margin_top' => 20,
			'desc_color_off' => '#7e7e7e',
			'desc_color_on' => '#FFFFFF',
			'desc_font_size' => 14,
			'desc_margin_top' => 8
	   );
	
	   extract( shortcode_atts( $initial_vals_arr, $atts ) );
	   foreach ($initial_vals_arr as $key => $value) {
    		//echo "Key: $key; Value: $value<br />\n";
			if (!isset($atts[$key])) {
				$atts[$key]=$value;
			}
		}
	  
	   //$content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content*/
	 
	   

	 
	$playlist_str=''; 
	$playlist_str=do_shortcode( $content );

	
	$aux_content='';
	$new_div_start='';
	$new_div_end='';
	if ($atts["center_plugin"]=='true') {
		$new_div_start='<div class="multimedia_carousel_my_center_'.$atts["id"].'">';
		$new_div_end='</div>';
		$aux_content='<style>
		.multimedia_carousel_my_center_'.$atts["id"].' {
			width:'.$atts["width"].'px;  margin:0px auto;
		}
		@media screen and (max-width:'.($atts["width"]).'px) {
			.multimedia_carousel_my_center_'.$atts["id"].' {
				width:100%;  margin:0px auto;
			}
		}
	</style>';
		
	}
	

	   

	
	
	$showcase_function='';
	$myxloader='';
	$list_name='';
	$the_parameters='';
	switch ($atts["type"]) {
		case 'classic':
			$carousel_function='multimedia_classic_carousel';
			$myxloader='<div class="myloader"></div>';
			$list_name='multimedia_classic_carousel_list';
			$the_parameters='skin:"'.$atts["skin"].'",
				responsive:'.$atts["responsive"].',
				responsiveRelativeToBrowser:'.$atts["responsive_relative_to_browser"].',				
				width:'.$atts["width"].',
				height:'.$atts["height"].',
				width100Proc:'.$atts["width_100_proc"].',
				height100Proc:false,				
				autoPlay:'.$atts["auto_play"].',
				numberOfThumbsPerScreen:'.$atts["number_of_thumbs_per_screen"].',
				itemPadding:'.$atts["item_padding"].',
				showElementTitle:'.$atts["show_element_title"].',
				itemBackgroundColorOFF:"'.$atts["item_background_color_off"].'",
				itemBackgroundColorON:"'.$atts["item_background_color_on"].'",
				titleColorOFF:"'.$atts["title_color_off"].'",
				descColorOFF:"'.$atts["desc_color_off"].'",
				titleColorON:"'.$atts["title_color_on"].'",
				descColorON:"'.$atts["desc_color_on"].'",
				titleFontSize:'.$atts["title_font_size"].',
				descFontSize:'.$atts["desc_font_size"].',	
				titleMarginTop:'.$atts["title_margin_top"].',
				descMarginTop:'.$atts["desc_margin_top"].',				
				imageWidth:'.$atts["image_width"].',
				imageHeight:'.$atts["image_height"].',
				enableTouchScreen:'.$atts["enable_touch_screen"].',
				target:"'.$atts["target"].'",				
				absUrl:"'.plugins_url("", __FILE__).'/classic/",
				showNavArrows:'.$atts["show_nav_arrows"].',
				showOnInitNavArrows:'.$atts["show_on_init_nav_arrows"].',
				autoHideNavArrows:'.$atts["auto_hide_nav_arrows"].',
				showBottomNav:'.$atts["show_bottom_nav"].',
				showOnInitBottomNav:'.$atts["show_on_init_bottom_nav"].',
				autoHideBottomNav:'.$atts["auto_hide_bottom_nav"].',
				bottomNavMarginBottom:'.$atts["bottom_nav_margin_bottom"].',
				bottomNavPosition:"'.$atts["bottom_nav_position"].'"';
			break;
		case 'perspective':
			$carousel_function='multimedia_perspective_carousel';
			$myxloader='<div class="myloader"></div>';
			$list_name='multimedia_perspective_carousel_list';
			$the_parameters='skin:"'.$atts["skin"].'",
				responsive:'.$atts["responsive"].',
				responsiveRelativeToBrowser:'.$atts["responsive_relative_to_browser"].',				
				width:'.$atts["width"].',
				height:'.$atts["height"].',
				width100Proc:'.$atts["width_100_proc"].',
				height100Proc:false,				
				autoPlay:'.$atts["auto_play"].',
				numberOfVisibleItems:'.$atts["number_of_visible_items"].',
				verticalAdjustment:'.$atts["vertical_adjustment"].',
				elementsHorizontalSpacing:'.$atts["elements_horizontal_spacing"].',
				elementsVerticalSpacing:'.$atts["elements_vertical_spacing"].',
				animationTime:'.$atts["animation_time"].',
				easing:"'.$atts["easing"].'",
				resizeImages:'.$atts["resize_images"].',
				showElementTitle:'.$atts["show_element_title"].',
				titleColor:"'.$atts["title_color"].'",
				imageWidth:'.$atts["image_width"].',
				imageHeight:'.$atts["image_height"].',
				border:'.$atts["border"].',
				borderColorOFF:"'.$atts["border_color_off"].'",
				borderColorON:"'.$atts["border_color_on"].'",				
				enableTouchScreen:'.$atts["enable_touch_screen"].',
				target:"'.$atts["target"].'",				
				absUrl:"'.plugins_url("", __FILE__).'/perspective/",
				showAllControllers:'.$atts["show_all_controllers"].',
				showNavArrows:'.$atts["show_nav_arrows"].',
				showOnInitNavArrows:'.$atts["show_on_init_nav_arrows"].',
				autoHideNavArrows:'.$atts["auto_hide_nav_arrows"].',
				showBottomNav:'.$atts["show_bottom_nav"].',
				showOnInitBottomNav:'.$atts["show_on_init_bottom_nav"].',
				autoHideBottomNav:'.$atts["auto_hide_bottom_nav"].',
				showPreviewThumbs:'.$atts["show_preview_thumbs"].',
				nextPrevMarginTop:'.$atts["next_prev_margin_top"].',
				playMovieMarginTop:'.$atts["play_movie_margin_top"].',
				bottomNavMarginBottom:'.$atts["bottom_nav_margin_bottom"].',				
				circleLeftPositionCorrection:'.$atts["circle_left_position_correction"].',
				circleTopPositionCorrection:'.$atts["circle_top_position_correction"].',
				showCircleTimer:'.$atts["show_circle_timer"].',
				circleRadius:'.$atts["circle_radius"].',
				circleLineWidth:'.$atts["circle_line_width"].',
				circleColor:"'.$atts["circle_color"].'",
				circleAlpha:'.$atts["circle_alpha"].',
				behindCircleColor:"'.$atts["behind_circle_color"].'",
				behindCircleAlpha:'.$atts["behind_circle_alpha"];
			break;
	}		
	
	
	
	$aux_content.= '<script>
		jQuery(function() {
			jQuery("#'.$carousel_function.'_'.$atts["id"].'").'.$carousel_function.'({'.
				$the_parameters.
			'});	
			
			jQuery(document).ready(function(){
				jQuery("a[rel^=\'prettyPhoto\']").prettyPhoto({
					default_width: jQuery(window).width()/'.$atts["lightbox_width_divider"].',
					default_height: jQuery(window).width()/'.$atts["lightbox_width_divider"].'*'.$atts["lightbox_height_divider"].',
					social_tools:false,
					callback: function(){
						jQuery.'.$carousel_function.'.continueAutoplay();
					}
				});
			});			
		});
	</script>	
            '.$new_div_start.'<div id="'.$carousel_function.'_'.$atts["id"].'">'.$myxloader.'<ul class="'.$list_name.'">'.$playlist_str.'</ul></div>'.$new_div_end;
			
	return str_replace("\r\n", '', $aux_content);
}
	
?>