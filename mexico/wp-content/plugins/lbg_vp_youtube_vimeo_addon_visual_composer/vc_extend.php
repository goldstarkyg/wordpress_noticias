<?php
/*
Plugin Name: Multimedia Playlist Slider - Visual Composer Addon
Description: This visual composer addon will allow you to insert a Multimedia Slider or a YouTube & Vimeo video player
Version: 1.4
Author: Lambert Group
Author URI: https://codecanyon.net/user/LambertGroup/portfolio?ref=LambertGroup
*/


//all the messages
$lbg_css_path = trailingslashit(dirname(__FILE__)); 	
$rand_id=rand(10,999999);
global $general_param; // for activation_hook only
$general_param = array(
	'css_styles_const' => '/*textWhiteBgBlack*/
.vc_textWhiteBgBlack_small {
	font-family: Arial;
	font-size:12px;
	line-height:12px;
	font-weight:bold;
	color:#FFF;
	background:#000;
	padding:5px 6px;
	margin:0;
}
.vc_textWhiteBgBlack_small a {
	color:#FFF;
	text-decoration:underline;
}
.vc_textWhiteBgBlack_small a:hover {
	color:#F00;
	text-decoration:underline;
}
.vc_textWhiteBgBlack_medium {
	font-family: Arial;
	font-size:22px;
	line-height:22px;
	font-weight:normal;
	color:#FFF;
	background:#000;
	padding:6px 7px;
	margin:0;
}
.vc_textWhiteBgBlack_medium a {
	color:#FFF;
	text-decoration:underline;
}
.vc_textWhiteBgBlack_medium a:hover {
	color:#F00;
	text-decoration:underline;
}
.vc_textWhiteBgBlack_large {
	font-family: Arial;
	font-size:36px;
	line-height:36px;
	font-weight:normal;
	color:#FFF;
	background:#000;
	padding:7px 10px;
	margin:0;
}
.vc_textWhiteBgBlack_large a {
	color:#FFF;
	text-decoration:underline;
}
.vc_textWhiteBgBlack_large a:hover {
	color:#F00;
	text-decoration:underline;
}
/*textBlackBgWhite*/
.vc_textBlackBgWhite_small {
	font-family: Arial;
	font-size:12px;
	line-height:12px;
	font-weight:bold;
	color:#000;
	background:#FFF;
	padding:5px 6px;
	margin:0;
}
.vc_textBlackBgWhite_small a {
	color:#000;
	text-decoration:underline;
}
.vc_textBlackBgWhite_small a:hover {
	color:#F00;
	text-decoration:underline;
}
.vc_textBlackBgWhite_medium {
	font-family: Arial;
	font-size:22px;
	line-height:22px;
	font-weight:normal;
	color:#000;
	background:#FFF;
	padding:6px 7px;
	margin:0;
}
.vc_textBlackBgWhite_medium a {
	color:#000;
	text-decoration:underline;
}
.vc_textBlackBgWhite_medium a:hover {
	color:#F00;
	text-decoration:underline;
}
.vc_textBlackBgWhite_large {
	font-family: Arial;
	font-size:36px;
	line-height:36px;
	font-weight:normal;
	color:#000;
	background:#FFF;
	padding:7px 10px;
	margin:0;
}
.vc_textBlackBgWhite_large a {
	color:#000;
	text-decoration:underline;
}
.vc_textBlackBgWhite_large a:hover {
	color:#F00;
	text-decoration:underline;
}
/*textRedBgWhite*/
.vc_textRedBgWhite_small {
	font-family: Arial;
	font-size:12px;
	line-height:12px;
	font-weight:bold;
	color:#F00;
	background:#FFF;
	padding:5px 6px;
	margin:0;
}
.vc_textRedBgWhite_small a {
	color:#F00;
	text-decoration:underline;
}
.vc_textRedBgWhite_small a:hover {
	color:#000;
	text-decoration:underline;
}
.vc_textRedBgWhite_medium {
	font-family: Arial;
	font-size:22px;
	line-height:22px;
	font-weight:normal;
	color:#F00;
	background:#FFF;
	padding:6px 7px;
	margin:0;
}
.vc_textRedBgWhite_medium a {
	color:#F00;
	text-decoration:underline;
}
.vc_textRedBgWhite_medium a:hover {
	color:#000;
	text-decoration:underline;
}
.vc_textRedBgWhite_large {
	font-family: Arial;
	font-size:36px;
	line-height:36px;
	font-weight:normal;
	color:#F00;
	background:#FFF;
	padding:7px 10px;
	margin:0;
}
.vc_textRedBgWhite_large a {
	color:#F00;
	text-decoration:underline;
}
.vc_textRedBgWhite_large a:hover {
	color:#000;
	text-decoration:underline;
}
/*textBlueBgWhite*/
.vc_textBlueBgWhite_small {
	font-family: Arial;
	font-size:12px;
	line-height:12px;
	font-weight:bold;
	color:#00F;
	background:#FFF;
	padding:5px 6px;
	margin:0;
}
.vc_textBlueBgWhite_small a {
	color:#00F;
	text-decoration:underline;
}
.vc_textBlueBgWhite_small a:hover {
	color:#F00;
	text-decoration:underline;
}
.vc_textBlueBgWhite_medium {
	font-family: Arial;
	font-size:22px;
	line-height:22px;
	font-weight:normal;
	color:#00F;
	background:#FFF;
	padding:6px 7px;
	margin:0;
}
.vc_textBlueBgWhite_medium a {
	color:#00F;
	text-decoration:underline;
}
.vc_textBlueBgWhite_medium a:hover {
	color:#F00;
	text-decoration:underline;
}

.vc_textBlueBgWhite_large {
	font-family: Arial;
	font-size:36px;
	line-height:36px;
	font-weight:normal;
	color:#00F;
	background:#FFF;
	padding:7px 10px;
	margin:0;
}
.vc_textBlueBgWhite_large a {
	color:#00F;
	text-decoration:underline;
}
.vc_textBlueBgWhite_large a:hover {
	color:#F00;
	text-decoration:underline;
}
/*textWhiteBgTransparent*/
.vc_textWhiteBgTransparent_small {
	font-family: Arial;
	font-size:12px;
	line-height:12px;
	font-weight:bold;
	color:#FFF;
	background:none;
	padding:0;
	margin:0;
}
.vc_textWhiteBgTransparent_small a {
	color:#FFF;
	text-decoration:underline;
}
.vc_textWhiteBgTransparent_small a:hover {
	color:#F00;
	text-decoration:underline;
}
.vc_textWhiteBgTransparent_medium {
	font-family: Arial;
	font-size:22px;
	line-height:22px;
	font-weight:normal;
	color:#FFF;
	background:none;
	padding:0;
	margin:0;
}
.vc_textWhiteBgTransparent_medium a {
	color:#FFF;
	text-decoration:underline;
}
.vc_textWhiteBgTransparent_medium a:hover {
	color:#F00;
	text-decoration:underline;
}
.vc_textWhiteBgTransparent_large {
	font-family: Arial;
	font-size:36px;
	line-height:36px;
	font-weight:normal;
	color:#FFF;
	background:none;
	padding:0;
	margin:0;
}
.vc_textWhiteBgTransparent_large a {
	color:#FFF;
	text-decoration:underline;
}
.vc_textWhiteBgTransparent_large a:hover {
	color:#F00;
	text-decoration:underline;
}
/*textBlackBgTransparent*/
.vc_textBlackBgTransparent_small {
	font-family: Arial;
	font-size:12px;
	line-height:12px;
	font-weight:bold;
	color:#000;
	background:none;
	padding:0;
	margin:0;
}
.vc_textBlackBgTransparent_small a {
	color:#000;
	text-decoration:underline;
}
.vc_textBlackBgTransparent_small a:hover {
	color:#F00;
	text-decoration:underline;
}
.vc_textBlackBgTransparent_medium {
	font-family: Arial;
	font-size:22px;
	line-height:22px;
	font-weight:normal;
	color:#000;
	background:none;
	padding:0;
	margin:0;
}
.vc_textBlackBgTransparent_medium a {
	color:#000;
	text-decoration:underline;
}
.vc_textBlackBgTransparent_medium a:hover {
	color:#F00;
	text-decoration:underline;
}
.vc_textBlackBgTransparent_large {
	font-family: Arial;
	font-size:36px;
	line-height:36px;
	font-weight:normal;
	color:#000;
	background:none;
	padding:0;
	margin:0;
}
.vc_textBlackBgTransparent_large a {
	color:#000;
	text-decoration:underline;
}
.vc_textBlackBgTransparent_large a:hover {
	color:#F00;
	text-decoration:underline;
}
/*textRedBgTransparent*/
.vc_textRedBgTransparent_small {
	font-family: Arial;
	font-size:12px;
	line-height:12px;
	font-weight:bold;
	color:#F00;
	background:none;
	padding:0;
	margin:0;
}
.vc_textRedBgTransparent_small a {
	color:#F00;
	text-decoration:underline;
}
.vc_textRedBgTransparent_small a:hover {
	color:#000;
	text-decoration:underline;
}
.vc_textRedBgTransparent_medium {
	font-family: Arial;
	font-size:22px;
	line-height:22px;
	font-weight:normal;
	color:#F00;
	background:none;
	padding:0;
	margin:0;
}
.vc_textRedBgTransparent_medium a {
	color:#F00;
	text-decoration:underline;
}
.vc_textRedBgTransparent_medium a:hover {
	color:#000;
	text-decoration:underline;
}
.vc_textRedBgTransparent_large {
	font-family: Arial;
	font-size:36px;
	line-height:36px;
	font-weight:normal;
	color:#F00;
	background:none;
	padding:0;
	margin:0;
}
.vc_textRedBgTransparent_large a {
	color:#F00;
	text-decoration:underline;
}
.vc_textRedBgTransparent_large a:hover {
	color:#000;
	text-decoration:underline;
}
/*textBlueBgTransparent*/
.vc_textBlueBgTransparent_small {
	font-family: Arial;
	font-size:12px;
	line-height:12px;
	font-weight:bold;
	color:#00F;
	background:none;
	padding:0;
	margin:0;
}
.vc_textBlueBgTransparent_small a {
	color:#00F;
	text-decoration:underline;
}
.vc_textBlueBgTransparent_small a:hover {
	color:#F00;
	text-decoration:underline;
}
.vc_textBlueBgTransparent_medium {
	font-family: Arial;
	font-size:22px;
	line-height:22px;
	font-weight:normal;
	color:#00F;
	background:none;
	padding:0;
	margin:0;
}
.vc_textBlueBgTransparent_medium a {
	color:#00F;
	text-decoration:underline;
}
.vc_textBlueBgTransparent_medium a:hover {
	color:#F00;
	text-decoration:underline;
}
.vc_textBlueBgTransparent_large {
	font-family: Arial;
	font-size:36px;
	line-height:36px;
	font-weight:normal;
	color:#00F;
	background:none;
	padding:0;
	margin:0;
}
.vc_textBlueBgTransparent_large a {
	color:#00F;
	text-decoration:underline;
}
.vc_textBlueBgTransparent_large a:hover {
	color:#F00;
	text-decoration:underline;
}'
);

global $lbg_addon_name;
$lbg_addon_name='youtube_vimeo_addon';
global $layers_arr;
$layers_arr=array();



if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


function youtube_vimeo_addon_vc_map_dependencies() {
	if ( ! defined( 'WPB_VC_VERSION' ) ) {
		$plugin_data = get_plugin_data(__FILE__);
        echo '<div class="updated">
          <p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="https://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'vc_extend'), $plugin_data['Name']).'</p>
        </div>';
	}
}
add_action( 'admin_notices', 'youtube_vimeo_addon_vc_map_dependencies' );



function youtube_vimeo_addon_vc_activate() {
	global $wpdb;
	global $general_param;
	global $lbg_addon_name;
	
	//classes database
	include_once('css_classes/db.php');
}
register_activation_hook(__FILE__,"youtube_vimeo_addon_vc_activate"); //activate plugin and create the database





function youtube_vimeo_addon_vc_map_init() {
	global $wpdb;
	global $rand_id;
	global $lbg_addon_name;
	
	
	//get classes values	array for playlist checkbox start	
	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_classes) WHERE addon_name = %s",$lbg_addon_name );
	$row=$wpdb->get_row($safe_sql, ARRAY_A);
	
	$temp_class='';
	$classes_arr=array('select a class...');
	$startPos=0;
	$endPos=0;
	$endClassContentPos=0;
	//echo $row['css_styles'];
	
	
	$startPos=strpos($row['css_styles'],'.',$startPos);
	$endPos=strpos($row['css_styles'],'{',$startPos);
	while ($startPos!==FALSE) {
		$temp_class=trim(substr($row['css_styles'],$startPos+1,$endPos-1-$startPos));
		//$classes_arr[]=trim(substr($row['css_styles'],$startPos+1,$endPos-1-$startPos));
		if (strpos($temp_class,' a')===FALSE)
			$classes_arr[]=$temp_class;
		$endClassContentPos=strpos($row['css_styles'],'}',$endPos);
		$startPos=strpos($row['css_styles'],'.',$endClassContentPos);
		$endPos=strpos($row['css_styles'],'{',$startPos);
	}
	//get classes values	array for playlist checkbox end	

	
	//Create New Param Type 'youtube_vimeo_attach_media'
			vc_add_shortcode_param( 'youtube_vimeo_attach_media', 'youtube_vimeo_attach_media_callback', plugins_url() . '/lbg_vp_youtube_vimeo_addon_visual_composer/assets/new_param_type/attach_media.js');
			function youtube_vimeo_attach_media_callback( $settings, $value ) {   
				return '<div class="my_param_block">'
				.'<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' . esc_attr( $settings['param_name'] ) . ' ' .              esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" style="width:70%;" />' .
				'<input name="upload_' . esc_attr( $settings['param_name'] ) . '_button" type="button" id="upload_' . esc_attr( $settings['param_name'] ) . '_button" value="Upload File" style="width:30%;" /> '.'</div>'; // This is html markup that will be outputted in content elements edit form
			}


	
	
	vc_map( array(
		'name' => __( 'Multimedia Slider', 'js_composer' ),
		'base' => 'youtube_vimeo',
		"icon" => plugins_url('assets/images/youtube_vimeo_icon.png', __FILE__), // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
		"category" => __('LBG Multimedia Addons', 'js_composer'),
		"description" => __("with playlist", 'vc_extend'),	
		'show_settings_on_create' => false,
		'is_container' => true,
		'admin_enqueue_js'      => preg_replace( '/\s/', '%20', plugins_url( 'assets/youtube_vimeo.js', __FILE__ ) ),
		// This will load extra js file in backend (when you edit page with VC)
		// use preg replace to be sure that "space" will not break logic

		//'admin_enqueue_css'     => preg_replace( '/\s/', '%20', plugins_url( 'assets/admin_enqueue_css.css', __FILE__ ) ),
		'admin_enqueue_css'     => preg_replace( '/\s/', '%20', plugins_url( 'assets/youtube_vimeo.css', __FILE__ ) ),
		// This will load extra css file in backend (when you edit page with VC)				
		'params' => array(
			array(
				'group' => 'General Settings',
				'type' => 'textfield',
				'heading' => __( 'Player ID', 'js_composer' ),
				'param_name' => 'id',
				'value' => __( "".$rand_id."", "my-text-domain" ),
				'description' => __( "It is automaticaly generated and it has to be unique. You can leave it just like it is.", "js_composer" )
			),
			array(
				'group' => 'General Settings',
				'type' => 'textfield',
				'heading' => __( 'Slider/Player Width', 'js_composer' ),
				'param_name' => 'width', //width
				'value' => __( "960", "my-text-domain" )
			),
			array(
				'group' => 'General Settings',
				'type' => 'textfield',
				'heading' => __( 'Slider/Player Height', 'js_composer' ),
				'param_name' => 'height', //height
				'value' => __( "384", "my-text-domain" )
			),
			array(
				'group' => 'General Settings',
				'type' => 'dropdown',
				'heading' => __( 'Center Player', 'js_composer' ),
				'param_name' => 'center_plugin',
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				 ),
				'description' => __( "Center the player", "js_composer" )
			),
			array(
				'group' => 'General Settings',
				'type' => 'dropdown',
				'heading' => __( 'Width 100%', 'js_composer' ),
				'param_name' => 'width_100_proc', //width100Proc
				'value'       => array(
					'No'   => 'false',
					'Yes'   => 'true'					
				 ),
				'description' => __( "Set the player as full-width", "js_composer" )
			),
			array(
				'group' => 'General Settings',
				'type' => 'dropdown',
				'heading' => __( 'Responsive', 'js_composer' ),
				'param_name' => 'responsive',
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'General Settings',
				'type' => 'dropdown',
				'heading' => __( 'Responsive Relative To Browser', 'js_composer' ),
				'param_name' => 'responsive_relative_to_browser', //responsiveRelativeToBrowser
				'value' => array(
					'No' => 'false',
					'Yes' => 'true'
				  )
			),					
			array(
				'group' => 'General Settings',
				'type' => 'dropdown',
				'heading' => __( 'Automatically Obtain Thumb, Title & Description From YouTube Server', 'js_composer' ),
				'param_name' => 'get_you_tube_data', //getYouTubeData
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'General Settings',
				'type' => 'dropdown',
				'heading' => __( 'Loop', 'js_composer' ),
				'param_name' => 'loop', //loop
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'General Settings',
				'type' => 'dropdown',
				'heading' => __( 'Randomize Images', 'js_composer' ),
				'param_name' => 'randomize_images', //randomizeImages
				'value' => array(
					'No' => 'false',
					'Yes' => 'true'								
				  )
			),
			array(
				'group' => 'General Settings',
				'type' => 'textfield',
				'heading' => __( 'First Video To Be Loaded', 'js_composer' ),
				'param_name' => 'first_img', //firstImg
				'value' => __( "0", "my-text-domain" ),
				'description' => __( "the playlist video number. Counting starts from 0", "js_composer" )
			),
			array(
				'group' => 'General Settings',
				'type' => 'textfield',
				'heading' => __( 'Initial Volume Value', 'js_composer' ),
				'param_name' => 'initial_volume', //initialVolume
				'value' => __( "100", "my-text-domain" ),
				'description' => __( "Values between 0-100", "js_composer" )
			),
			array(
				'group' => 'General Settings',
				'type' => 'textfield',
				'heading' => __( 'Number Of Stripes', 'js_composer' ),
				'param_name' => 'number_of_stripes', //numberOfStripes
				'value' => __( "20", "my-text-domain" ),
				'description' => __( "only for images effect", "js_composer" )
			),
			array(
				'group' => 'General Settings',
				'type' => 'textfield',
				'heading' => __( 'Number Of Rows', 'js_composer' ),
				'param_name' => 'number_of_rows', //numberOfRows
				'value' => __( "5", "my-text-domain" ),
				'description' => __( "only for images effect", "js_composer" )
			),
			array(
				'group' => 'General Settings',
				'type' => 'textfield',
				'heading' => __( 'Number Of Columns', 'js_composer' ),
				'param_name' => 'number_of_columns', //numberOfColumns
				'value' => __( "10", "my-text-domain" ),
				'description' => __( "only for images effect", "js_composer" )
			),
			array(
				'group' => 'General Settings',
				'type' => 'dropdown',
				'heading' => __( 'Default Effect', 'js_composer' ),
				'param_name' => 'default_effect', //defaultEffect
				'value' => array(
					'random' => 'random',
		      		'asynchronousDroppingStripes' => 'asynchronousDroppingStripes',
		      		'bottomTopDroppingReverseStripes' => 'bottomTopDroppingReverseStripes',
					'bottomTopDroppingStripes' => 'bottomTopDroppingStripes',
					'fade' => 'fade',
					'leftRightFadingReverseStripes' => 'leftRightFadingReverseStripes',
					'leftRightFadingStripes' => 'leftRightFadingStripes',
					'randomBlocks' => 'randomBlocks',
					'slideFromBottom' => 'slideFromBottom',
					'slideFromLeft' => 'slideFromLeft',
					'slideFromTop' => 'slideFromTop',
					'topBottomDiagonalBlocks' => 'topBottomDiagonalBlocks',
					'topBottomDiagonalReverseBlocks' => 'topBottomDiagonalReverseBlocks',
					'topBottomDroppingReverseStripes' => 'topBottomDroppingReverseStripes',
					'topBottomDroppingStripe' => 'topBottomDroppingStripe'
				  ),
				'description' => __( "only for images effect", "js_composer" )
			),
			array(
				'group' => 'General Settings',
				'type' => 'textfield',
				'heading' => __( 'Effect Duration', 'js_composer' ),
				'param_name' => 'effect_duration', //effectDuration
				'value' => __( "0.5", "my-text-domain" ),
				'description' => __( "seconds. Only for images effect", "js_composer" )
			),
			array(
				'group' => 'General Settings',
				'type' => 'textfield',
				'heading' => __( 'Auto Play', 'js_composer' ),
				'param_name' => 'auto_play', //autoPlay
				'value' => __( "4", "my-text-domain" ),
				'description' => __( "seconds. Only for images effect", "js_composer" )
			),
			array(
				'group' => 'General Settings',
				'type' => 'dropdown',
				'heading' => __( 'Auto-Play First Video', 'js_composer' ),
				'param_name' => 'auto_play_first_video', //autoPlayFirstVideo
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'												
				  )
			),
			array(
				'group' => 'General Settings',
				'type' => 'dropdown',
				'heading' => __( 'Auto-Play Next Video', 'js_composer' ),
				'param_name' => 'auto_play_next_video', //autoPlayNextVideo
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'												
				  )
			),
			array(
				'group' => 'General Settings',
				'type' => 'dropdown',
				'heading' => __( 'Target Window For Link', 'js_composer' ),
				'param_name' => 'target', //target
				'value' => array(
					'_blank' => '_blank',
					'_self' => '_self'
				  )
			),			
			array(
				'group' => 'General Settings',
				'type' => 'dropdown',
				'heading' => __( 'Enable Touch Screen', 'js_composer' ),
				'param_name' => 'enable_touch_screen', //enableTouchScreen
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'General Settings',
				'type' => 'textfield',
				'heading' => __( 'Border Width', 'js_composer' ),
				'param_name' => 'border_width', //borderWidth
				'value' => __( "14", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'General Settings',            
				'type' => 'colorpicker',
				'heading' => __( "Border Color", "js_composer" ),
				'param_name' => 'border_color', //borderColor
				'value' => '#4a4a4a',
				'description' => __( "Choose the color", "js_composer" )
			),
			
			
			
			
			
			
			
			
			
			
			array(
				'group' => 'Controllers Settings',
				'type' => 'dropdown',
				'heading' => __( 'Show All Controllers', 'js_composer' ),
				'param_name' => 'show_all_controllers',//showAllControllers
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'dropdown',
				'heading' => __( 'Show Navigation Arrows', 'js_composer' ),
				'param_name' => 'show_nav_arrows',//showNavArrows
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'dropdown',
				'heading' => __( 'Show Navigation Arrows On Init', 'js_composer' ),
				'param_name' => 'show_on_init_nav_arrows',//showOnInitNavArrows
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'dropdown',
				'heading' => __( 'Auto Hide Navigation Arrows', 'js_composer' ),
				'param_name' => 'auto_hide_nav_arrows',//autoHideNavArrows
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'textfield',
				'heading' => __( 'Playlist Width', 'js_composer' ),
				'param_name' => 'playlist_width', //playlistWidth
				'value' => __( "300", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'textfield',
				'heading' => __( 'Thumbnail Width', 'js_composer' ),
				'param_name' => 'orig_thumb_img_w', //origThumbImgW
				'value' => __( "90", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'textfield',
				'heading' => __( 'Thumbnail Height', 'js_composer' ),
				'param_name' => 'orig_thumb_img_h', //origThumbImgH
				'value' => __( "90", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'textfield',
				'heading' => __( 'Number Of Thumbs Per Screen', 'js_composer' ),
				'param_name' => 'number_of_thumbs_per_screen', //numberOfThumbsPerScreen
				'value' => __( "0", "my-text-domain" ),
				'description' => __( "If you set it to 0, it will be calculated automatically.", "js_composer" )  
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'dropdown',
				'heading' => __( 'Show Thumbs', 'js_composer' ),
				'param_name' => 'playlist_record_show_img',//playlistRecordShowImg
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'dropdown',
				'heading' => __( 'Show Title', 'js_composer' ),
				'param_name' => 'playlist_record_show_title',//playlistRecordShowTitle
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'dropdown',
				'heading' => __( 'Show Description', 'js_composer' ),
				'param_name' => 'playlist_record_show_desc',//playlistRecordShowDesc
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'textfield',
				'heading' => __( 'Playlist Record Height', 'js_composer' ),
				'param_name' => 'playlist_record_height', //playlistRecordHeight
				'value' => __( "110", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'textfield',
				'heading' => __( 'Playlist Record Padding', 'js_composer' ),
				'param_name' => 'playlist_record_padding', //playlistRecordPadding
				'value' => __( "10", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'textfield',
				'heading' => __( 'Playlist Title Font Size', 'js_composer' ),
				'param_name' => 'playlist_title_font_size', //playlistTitleFontSize
				'value' => __( "15", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'textfield',
				'heading' => __( 'Playlist Title Line Height', 'js_composer' ),
				'param_name' => 'playlist_title_line_height', //playlistTitleLineHeight
				'value' => __( "19", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'textfield',
				'heading' => __( 'Playlist Desc Font Size', 'js_composer' ),
				'param_name' => 'playlist_desc_font_size', //playlistDescFontSize
				'value' => __( "12", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'textfield',
				'heading' => __( 'Playlist Desc Line Height', 'js_composer' ),
				'param_name' => 'playlist_desc_line_height', //playlistDescLineHeight
				'value' => __( "15", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',            
				'type' => 'colorpicker',
				'heading' => __( "Playlist Bg. OFF Color", "js_composer" ),
				'param_name' => 'playlist_record_bg_off_color', //playlistRecordBgOffColor
				'value' => '#000000',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',            
				'type' => 'colorpicker',
				'heading' => __( "Playlist Title OFF Color", "js_composer" ),
				'param_name' => 'playlist_record_title_off_color', //playlistRecordTitleOffColor
				'value' => '#CCCCCC',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',            
				'type' => 'colorpicker',
				'heading' => __( "Playlist Desc OFF Color", "js_composer" ),
				'param_name' => 'playlist_record_desc_off_color', //playlistRecordDescOffColor
				'value' => '#CCCCCC',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'textfield',
				'heading' => __( 'Playlist Bg. OFF Img Opacity', 'js_composer' ),
				'param_name' => 'playlist_record_bg_off_img_opacity', //playlistRecordBgOffImgOpacity
				'value' => __( "100", "my-text-domain" ),
				'description' => __( "Values between 0-100", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',            
				'type' => 'colorpicker',
				'heading' => __( "Playlist Bg ON Color", "js_composer" ),
				'param_name' => 'playlist_record_bg_on_color', //playlistRecordBgOnColor
				'value' => '#cc181e',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',            
				'type' => 'colorpicker',
				'heading' => __( "Playlist Title ON Color", "js_composer" ),
				'param_name' => 'playlist_record_title_on_color', //playlistRecordTitleOnColor
				'value' => '#FFFFFF',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',            
				'type' => 'colorpicker',
				'heading' => __( "Playlist Desc ON Color", "js_composer" ),
				'param_name' => 'playlist_record_desc_on_color', //playlistRecordDescOnColor
				'value' => '#FFFFFF',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'textfield',
				'heading' => __( 'Playlist Bg. ON Opacity', 'js_composer' ),
				'param_name' => 'playlist_record_bg_on_img_opacity', //playlistRecordBgOnImgOpacity
				'value' => __( "100", "my-text-domain" ),
				'description' => __( "Values between 0-100", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'textfield',
				'heading' => __( 'Playlist Title Limit', 'js_composer' ),
				'param_name' => 'playlist_record_title_limit', //playlistRecordTitleLimit
				'value' => __( "25", "my-text-domain" ),
				'description' => __( "number of characters", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',
				'type' => 'textfield',
				'heading' => __( 'Playlist Desc Limit ', 'js_composer' ),
				'param_name' => 'playlist_record_desc_limit', //playlistRecordDescLimit
				'value' => __( "100", "my-text-domain" ),
				'description' => __( "number of characters", "js_composer" )
			),
			array(
				'group' => 'Controllers Settings',            
				'type' => 'colorpicker',
				'heading' => __( "Entire Playlist Bg. Color", "js_composer" ),
				'param_name' => 'playlist_bg_color', //playlistBgColor
				'value' => '#4a4a4a',
				'description' => __( "Choose the color", "js_composer" )
			),
			
			

			
			

			array(
				'group' => 'Circle Timer Settings',
				'type' => 'dropdown',
				'heading' => __( 'Show Circle Timer', 'js_composer' ),
				'param_name' => 'show_circle_timer', //showCircleTimer
				'value' => array(
					'Yes' => 'true',
					'No' => 'false'					
				  )
			),
			array(
				'group' => 'Circle Timer Settings',
				'type' => 'textfield',
				'heading' => __( 'Circle Radius', 'js_composer' ),
				'param_name' => 'circle_radius', //circleRadius
				'value' => __( "10", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Circle Timer Settings',
				'type' => 'textfield',
				'heading' => __( 'Circle Line Width', 'js_composer' ),
				'param_name' => 'circle_line_width', //circleLineWidth
				'value' => __( "4", "my-text-domain" ),
				'description' => __( "pixels", "js_composer" )
			),
			array(
				'group' => 'Circle Timer Settings',
				'type' => 'colorpicker',
				'heading' => __( "Circle Color", "js_composer" ),
				'param_name' => 'circle_color', //circleColor
				'value' => '#FF0000',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Circle Timer Settings',
				'type' => 'textfield',
				'heading' => __( 'Circle Alpha', 'js_composer' ),
				'param_name' => 'circle_alpha', //circleAlpha
				'value' => __( "100", "my-text-domain" ),
				'description' => __( "Values between 0-100", "js_composer" )
			),
			array(
				'group' => 'Circle Timer Settings',
				'type' => 'colorpicker',
				'heading' => __( "Behind Circle Color", "js_composer" ),
				'param_name' => 'behind_circle_color', //behindCircleColor
				'value' => '#000000',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Circle Timer Settings',
				'type' => 'textfield',
				'heading' => __( 'Behind Circle Alpha', 'js_composer' ),
				'param_name' => 'behind_circle_alpha', //behindCircleAlpha
				'value' => __( "50", "my-text-domain" ),
				'description' => __( "Values between 0-100", "js_composer" )
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
		[youtube_vimeo_playlist_item title="' . __( 'Playlist Item  1', 'js_composer' ) . '"][/youtube_vimeo_playlist_item]
	',
		'js_view' => 'VcYoutubeVimeoView'
	) );
	
	vc_map( array(
		'name' => __( 'Playlist Item', 'js_composer' ),
		'base' => 'youtube_vimeo_playlist_item',
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
			    'group' => 'Video Slide',
				'type' => 'textfield',
				'heading' => __( 'YouTube Video ID', 'js_composer' ),
				'param_name' => 'youtube' //data-youtube
			),
			array(
				'group' => 'Video Slide',
				'type' => 'textfield',
				'heading' => __( 'Vimeo Video ID', 'js_composer' ),
				'param_name' => 'data_vimeo' //data-vimeo
			),
			array(
				'group' => 'Video Slide',
				'type' => 'attach_image',
				'heading' => __( 'Thumbnail', 'js_composer' ),
				'param_name' => 'data_bottom_thumb', //data-bottom-thumb
				'description' => __( 'Select an image from Media Library', 'js_composer' )
				
			),
			array(
				'group' => 'Video Slide',
				'type' => 'textfield',
				'heading' => __( 'Video/Image Title', 'js_composer' ),
				'param_name' => 'title' //data-title
			),
			array(
				'group' => 'Video Slide',
				'type' => 'textarea',
				'heading' => __( 'Video/Image Description', 'js_composer' ),
				'param_name' => 'data_desc' //data-desc
			),
			array(
				'group' => 'Image Slide',
				'type' => 'attach_image',
				'heading' => __( 'Image', 'js_composer' ),
				'param_name' => 'img', //img
				'description' => __( 'Select an image from Media Library', 'js_composer' )
			),
			array(
				'group' => 'Image Slide',
				'type' => 'textfield',
				'heading' => __( 'Link For The Image', 'js_composer' ),
				'param_name' => 'data_link' //data-link
			),
			array(
				'group' => 'Image Slide',
				'type' => 'dropdown',
				'heading' => __( 'Image link target', 'js_composer' ),
				'param_name' => 'data_target', //data-target
				'value' => array(
					'_blank' => '_blank',
					'_self' => '_self'
				  )
			),
			array(
				'group' => 'Image Slide',
				'type' => 'dropdown',
				'heading' => __( 'Particular Image Effect', 'js_composer' ),
				'param_name' => 'data_transition', //data-transition
				'value' => array(
					'select an effect...' => '',
					'random' => 'random',
		      		'asynchronousDroppingStripes' => 'asynchronousDroppingStripes',
		      		'bottomTopDroppingReverseStripes' => 'bottomTopDroppingReverseStripes',
					'bottomTopDroppingStripes' => 'bottomTopDroppingStripes',
					'fade' => 'fade',
					'leftRightFadingReverseStripes' => 'leftRightFadingReverseStripes',
					'leftRightFadingStripes' => 'leftRightFadingStripes',
					'randomBlocks' => 'randomBlocks',
					'slideFromBottom' => 'slideFromBottom',
					'slideFromLeft' => 'slideFromLeft',
					'slideFromTop' => 'slideFromTop',
					'topBottomDiagonalBlocks' => 'topBottomDiagonalBlocks',
					'topBottomDiagonalReverseBlocks' => 'topBottomDiagonalReverseBlocks',
					'topBottomDroppingReverseStripes' => 'topBottomDroppingReverseStripes',
					'topBottomDroppingStripe' => 'topBottomDroppingStripe'
				  ),
				'description' => __( "if no effect is selected, the default effect will be used", "js_composer" )
			),
			
			
			
			array(
				'group' => 'Layer 1',
				'type' => 'textfield',
				'heading' => __( 'Text Layer Content', 'js_composer' ),
				'param_name' => 'layer_1_text',
				'description' => __( 'Write the layer text', 'js_composer' )
			),
			array(
				'group' => 'Layer 1',
				'type' => 'textfield',
				'heading' => __( 'Initial Left', 'js_composer' ),
				'param_name' => 'initial_left_1',
				'description' => __( 'pixels', 'js_composer' )
			),
			array(
				'group' => 'Layer 1',
				'type' => 'textfield',
				'heading' => __( 'Initial Top', 'js_composer' ),
				'param_name' => 'initial_top_1',
				'description' => __( 'pixels', 'js_composer' )
			),
			array(
				'group' => 'Layer 1',
				'type' => 'textfield',
				'heading' => __( 'Final Left', 'js_composer' ),
				'param_name' => 'final_left_1',
				'description' => __( 'pixels', 'js_composer' )
			),
			array(
				'group' => 'Layer 1',
				'type' => 'textfield',
				'heading' => __( 'Final Top', 'js_composer' ),
				'param_name' => 'final_top_1',
				'description' => __( 'pixels', 'js_composer' )
			),
			array(
				'group' => 'Layer 1',
				'type' => 'textfield',
				'heading' => __( 'Animation Duration', 'js_composer' ),
				'param_name' => 'layer_1_duration',
				'description' => __( 'seconds. Example:0.5', 'js_composer' )
			),
			array(
				'group' => 'Layer 1',
				'type' => 'textfield',
				'heading' => __( 'Animation Delay', 'js_composer' ),
				'param_name' => 'layer_1_delay',
				'description' => __( 'seconds Example:0.5', 'js_composer' )
			),
			array(
				'group' => 'Layer 1',
				'type' => 'textfield',
				'heading' => __( 'Initial Opacity', 'js_composer' ),
				'param_name' => 'layer_1_fade',
				'description' => __( 'Values between 0-100', 'js_composer' )
			),
			array(
				'group' => 'Layer 1',
				'type' => 'dropdown',
				'heading' => __( 'CSS Class', 'js_composer' ),
				'param_name' => 'layer_1_css',
				'value' => $classes_arr
			),





			array(
				'group' => 'Layer 2',
				'type' => 'textfield',
				'heading' => __( 'Text Layer Content', 'js_composer' ),
				'param_name' => 'layer_2_text',
				'description' => __( 'Write the layer text', 'js_composer' )
			),
			array(
				'group' => 'Layer 2',
				'type' => 'textfield',
				'heading' => __( 'Initial Left', 'js_composer' ),
				'param_name' => 'initial_left_2',
				'description' => __( 'pixels', 'js_composer' )
			),
			array(
				'group' => 'Layer 2',
				'type' => 'textfield',
				'heading' => __( 'Initial Top', 'js_composer' ),
				'param_name' => 'initial_top_2',
				'description' => __( 'pixels', 'js_composer' )
			),
			array(
				'group' => 'Layer 2',
				'type' => 'textfield',
				'heading' => __( 'Final Left', 'js_composer' ),
				'param_name' => 'final_left_2',
				'description' => __( 'pixels', 'js_composer' )
			),
			array(
				'group' => 'Layer 2',
				'type' => 'textfield',
				'heading' => __( 'Final Top', 'js_composer' ),
				'param_name' => 'final_top_2',
				'description' => __( 'pixels', 'js_composer' )
			),		
			array(
				'group' => 'Layer 2',
				'type' => 'textfield',
				'heading' => __( 'Animation Duration', 'js_composer' ),
				'param_name' => 'layer_2_duration',
				'description' => __( 'seconds. Example:0.5', 'js_composer' )
			),
			array(
				'group' => 'Layer 2',
				'type' => 'textfield',
				'heading' => __( 'Animation Delay', 'js_composer' ),
				'param_name' => 'layer_2_delay',
				'description' => __( 'seconds Example:0.5', 'js_composer' )
			),
			array(
				'group' => 'Layer 2',
				'type' => 'textfield',
				'heading' => __( 'Initial Opacity', 'js_composer' ),
				'param_name' => 'layer_2_fade',
				'description' => __( 'Values between 0-100', 'js_composer' )
			),
			array(
				'group' => 'Layer 2',
				'type' => 'dropdown',
				'heading' => __( 'CSS Class', 'js_composer' ),
				'param_name' => 'layer_2_css',
				'value' => $classes_arr
			),



			array(
				'group' => 'Layer 3',
				'type' => 'textfield',
				'heading' => __( 'Text Layer Content', 'js_composer' ),
				'param_name' => 'layer_3_text',
				'description' => __( 'Write the layer text', 'js_composer' )
			),
			array(
				'group' => 'Layer 3',
				'type' => 'textfield',
				'heading' => __( 'Initial Left', 'js_composer' ),
				'param_name' => 'initial_left_3',
				'description' => __( 'pixels', 'js_composer' )
			),
			array(
				'group' => 'Layer 3',
				'type' => 'textfield',
				'heading' => __( 'Initial Top', 'js_composer' ),
				'param_name' => 'initial_top_3',
				'description' => __( 'pixels', 'js_composer' )
			),
			array(
				'group' => 'Layer 3',
				'type' => 'textfield',
				'heading' => __( 'Final Left', 'js_composer' ),
				'param_name' => 'final_left_3',
				'description' => __( 'pixels', 'js_composer' )
			),
			array(
				'group' => 'Layer 3',
				'type' => 'textfield',
				'heading' => __( 'Final Top', 'js_composer' ),
				'param_name' => 'final_top_3',
				'description' => __( 'pixels', 'js_composer' )
			),
			array(
				'group' => 'Layer 3',
				'type' => 'textfield',
				'heading' => __( 'Animation Duration', 'js_composer' ),
				'param_name' => 'layer_3_duration',
				'description' => __( 'seconds. Example:0.5', 'js_composer' )
			),
			array(
				'group' => 'Layer 3',
				'type' => 'textfield',
				'heading' => __( 'Animation Delay', 'js_composer' ),
				'param_name' => 'layer_3_delay',
				'description' => __( 'seconds Example:0.5', 'js_composer' )
			),
			array(
				'group' => 'Layer 3',
				'type' => 'textfield',
				'heading' => __( 'Initial Opacity', 'js_composer' ),
				'param_name' => 'layer_3_fade',
				'description' => __( 'Values between 0-100', 'js_composer' )
			),
			array(
				'group' => 'Layer 3',
				'type' => 'dropdown',
				'heading' => __( 'CSS Class', 'js_composer' ),
				'param_name' => 'layer_3_css',
				'value' => $classes_arr
			),



			array(
				'group' => 'Layer 4',
				'type' => 'textfield',
				'heading' => __( 'Text Layer Content', 'js_composer' ),
				'param_name' => 'layer_4_text',
				'description' => __( 'Write the layer text', 'js_composer' )
			),
			array(
				'group' => 'Layer 4',
				'type' => 'textfield',
				'heading' => __( 'Initial Left', 'js_composer' ),
				'param_name' => 'initial_left_4',
				'description' => __( 'pixels', 'js_composer' )
			),
			array(
				'group' => 'Layer 4',
				'type' => 'textfield',
				'heading' => __( 'Initial Top', 'js_composer' ),
				'param_name' => 'initial_top_4',
				'description' => __( 'pixels', 'js_composer' )
			),
			array(
				'group' => 'Layer 4',
				'type' => 'textfield',
				'heading' => __( 'Final Left', 'js_composer' ),
				'param_name' => 'final_left_4',
				'description' => __( 'pixels', 'js_composer' )
			),
			array(
				'group' => 'Layer 4',
				'type' => 'textfield',
				'heading' => __( 'Final Top', 'js_composer' ),
				'param_name' => 'final_top_4',
				'description' => __( 'pixels', 'js_composer' )
			),
			array(
				'group' => 'Layer 4',
				'type' => 'textfield',
				'heading' => __( 'Animation Duration', 'js_composer' ),
				'param_name' => 'layer_4_duration',
				'description' => __( 'seconds. Example:0.5', 'js_composer' )
			),
			array(
				'group' => 'Layer 4',
				'type' => 'textfield',
				'heading' => __( 'Animation Delay', 'js_composer' ),
				'param_name' => 'layer_4_delay',
				'description' => __( 'seconds Example:0.5', 'js_composer' )
			),
			array(
				'group' => 'Layer 4',
				'type' => 'textfield',
				'heading' => __( 'Initial Opacity', 'js_composer' ),
				'param_name' => 'layer_4_fade',
				'description' => __( 'Values between 0-100', 'js_composer' )
			),
			array(
				'group' => 'Layer 4',
				'type' => 'dropdown',
				'heading' => __( 'CSS Class', 'js_composer' ),
				'param_name' => 'layer_4_css',
				'value' => $classes_arr
			),



		),
		'js_view' => 'VcPlaylistItemView'
	) );
	


	if ( class_exists( "WPBakeryShortCode" ) ) {
		$youtube_vimeo_addon_path = trailingslashit(dirname(__FILE__)); 
		include_once($youtube_vimeo_addon_path . 'vc_contentadmin/youtube_vimeo-accordion.php');
		include_once($youtube_vimeo_addon_path . 'vc_contentadmin/youtube_vimeo-accordion-tab.php');
		

	} // End Class

}


//add_action('vc_after_init', 'youtube_vimeo_addon_vc_map_init');
add_action('vc_before_init', 'youtube_vimeo_addon_vc_map_init');






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



function youtube_vimeo_addon_enqueue_scripts_and_styles() {
	//load scripts in front-end
	//if (!is_admin()) {
				wp_enqueue_style('video_player_youtube_vimeo_site_css', plugins_url('video_player_youtube_vimeo/youtubeVimeoWithPlaylist.css', __FILE__));
				wp_enqueue_style('video_player_youtube_vimeo_text_classes', plugins_url('video_player_youtube_vimeo/text_classes.css', __FILE__));

				
		
				wp_enqueue_script('jquery');

			wp_enqueue_script('jquery-ui-core');
			/*wp_enqueue_script('jquery-ui-widget');
			wp_enqueue_script('jquery-ui-mouse');
			wp_enqueue_script('jquery-ui-accordion');
			wp_enqueue_script('jquery-ui-autocomplete');*/
			wp_enqueue_script('jquery-ui-slider');
			/*wp_enqueue_script('jquery-ui-tabs');
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
			wp_enqueue_script('jquery-ui-tooltip');*/
			
			/*wp_enqueue_script('jquery-effects-core');
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
			wp_enqueue_script('jquery-effects-slide');*/		
			wp_enqueue_script('jquery-effects-transfer');
				
				
				//wp_register_script('lbg-vimeo', 'http://a.vimeocdn.com/js/froogaloop2.min.js');
				if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || (!empty($_SERVER['HTTP_HTTPS']) && $_SERVER['HTTP_HTTPS'] != 'off') || $_SERVER['REQUEST_SCHEME'] == 'https' || $_SERVER['SERVER_PORT'] == 443) {
					wp_register_script('lbg-vimeo', 'https://secure-a.vimeocdn.com/js/froogaloop2.min.js');
				} else {
					wp_register_script('lbg-vimeo', 'http://a.vimeocdn.com/js/froogaloop2.min.js');
				}
				wp_enqueue_script('lbg-vimeo');
			
				
				wp_register_script('lbg-mousewheel', plugins_url('video_player_youtube_vimeo/js/jquery.mousewheel.min.js', __FILE__));
				wp_enqueue_script('lbg-mousewheel');
				
				wp_register_script('lbg-touchSwipe', plugins_url('video_player_youtube_vimeo/js/jquery.touchSwipe.min.js', __FILE__));
				wp_enqueue_script('lbg-touchSwipe');				
				
				wp_register_script('lbg-video_player_youtube_vimeo', plugins_url('video_player_youtube_vimeo/js/youtubeVimeoWithPlaylist.js', __FILE__));
				wp_enqueue_script('lbg-video_player_youtube_vimeo');	
	//}
}
add_action( 'wp_enqueue_scripts', 'youtube_vimeo_addon_enqueue_scripts_and_styles' );



//classes menu  start
if (is_admin() && !function_exists('lbg_classes_menu')) {//check if the css classes menu already exists
		include_once('css_classes/css_functions.php');
}
//classes menu end




//the shortcodes
add_shortcode( 'youtube_vimeo_playlist_item', 'youtube_vimeo_playlist_item_func' );
function youtube_vimeo_playlist_item_func( $atts, $content = null ) { // New function parameter $content is added!
	global $wpdb;
	global $layers_arr;
	
	$the_imgplaylist=wp_get_attachment_image_src($atts["data_bottom_thumb"], "large");
	$the_image=wp_get_attachment_image_src($atts["img"], "large");
	$img_aux='';
	if ($the_image[0]!='') {
		$img_aux='<img src="'.$the_image[0].'" alt="'.$atts["title"].'" />';
	}
	
	$have_layers=false;
	$aux_layers='';
	if ($atts['layer_1_text']!='') {
		$have_layers=true;
		
		//initial values
		$initial_left=0;
		$initial_top=0;
		$final_left=0;
		$final_top=0;
		$layer_duration=0;
		$layer_delay=0;
		$layer_fade=0;
		

		if ($atts['layer_1_duration']!='') {
			$layer_duration=$atts['layer_1_duration'];
		}
		if ($atts['layer_1_delay']!='') {
			$layer_delay=$atts['layer_1_delay'];
		}
		if ($atts['layer_1_fade']!='') {
			$layer_fade=$atts['layer_1_fade'];
		}
		
		if ($atts['initial_left_1']!='') {
			$initial_left=$atts['initial_left_1'];
		}
		if ($atts['initial_top_1']!='') {
			$initial_top=$atts['initial_top_1'];
		}
		if ($atts['final_left_1']!='') {
			$final_left=$atts['final_left_1'];
		}
		if ($atts['final_top_1']!='') {
			$final_top=$atts['final_top_1'];
		}
		
		$aux_layers.='<div class="youtubeVimeoWithPlaylist_text_line '.$atts['layer_1_css'].'" data-initial-left="'.$initial_left.'" data-initial-top="'.$initial_top.'" data-final-left="'.$final_left.'" data-final-top="'.$final_top.'" data-duration="'.$layer_duration.'" data-fade-start="'.$layer_fade.'" data-delay="'.$layer_delay.'">'.$atts['layer_1_text'].'</div>';
	}


	if ($atts['layer_2_text']!='') {
		$have_layers=true;
		
		//initial values
		$initial_left=0;
		$initial_top=0;
		$final_left=0;
		$final_top=0;
		$layer_duration=0;
		$layer_delay=0;
		$layer_fade=0;
		

		if ($atts['layer_2_duration']!='') {
			$layer_duration=$atts['layer_2_duration'];
		}
		if ($atts['layer_2_delay']!='') {
			$layer_delay=$atts['layer_2_delay'];
		}
		if ($atts['layer_2_fade']!='') {
			$layer_fade=$atts['layer_2_fade'];
		}
		
		if ($atts['initial_left_2']!='') {
			$initial_left=$atts['initial_left_2'];
		}
		if ($atts['initial_top_2']!='') {
			$initial_top=$atts['initial_top_2'];
		}
		if ($atts['final_left_2']!='') {
			$final_left=$atts['final_left_2'];
		}
		if ($atts['final_top_2']!='') {
			$final_top=$atts['final_top_2'];
		}
		
		$aux_layers.='<div class="youtubeVimeoWithPlaylist_text_line '.$atts['layer_2_css'].'" data-initial-left="'.$initial_left.'" data-initial-top="'.$initial_top.'" data-final-left="'.$final_left.'" data-final-top="'.$final_top.'" data-duration="'.$layer_duration.'" data-fade-start="'.$layer_fade.'" data-delay="'.$layer_delay.'">'.$atts['layer_2_text'].'</div>';
	}
	
	


	if ($atts['layer_3_text']!='') {
		$have_layers=true;
		
		//initial values
		$initial_left=0;
		$initial_top=0;
		$final_left=0;
		$final_top=0;
		$layer_duration=0;
		$layer_delay=0;
		$layer_fade=0;
		

		if ($atts['layer_3_duration']!='') {
			$layer_duration=$atts['layer_3_duration'];
		}
		if ($atts['layer_3_delay']!='') {
			$layer_delay=$atts['layer_3_delay'];
		}
		if ($atts['layer_3_fade']!='') {
			$layer_fade=$atts['layer_3_fade'];
		}
		
		if ($atts['initial_left_3']!='') {
			$initial_left=$atts['initial_left_3'];
		}
		if ($atts['initial_top_3']!='') {
			$initial_top=$atts['initial_top_3'];
		}
		if ($atts['final_left_3']!='') {
			$final_left=$atts['final_left_3'];
		}
		if ($atts['final_top_3']!='') {
			$final_top=$atts['final_top_3'];
		}
		
		$aux_layers.='<div class="youtubeVimeoWithPlaylist_text_line '.$atts['layer_3_css'].'" data-initial-left="'.$initial_left.'" data-initial-top="'.$initial_top.'" data-final-left="'.$final_left.'" data-final-top="'.$final_top.'" data-duration="'.$layer_duration.'" data-fade-start="'.$layer_fade.'" data-delay="'.$layer_delay.'">'.$atts['layer_3_text'].'</div>';
	}



	if ($atts['layer_4_text']!='') {
		$have_layers=true;
		
		//initial values
		$initial_left=0;
		$initial_top=0;
		$final_left=0;
		$final_top=0;
		$layer_duration=0;
		$layer_delay=0;
		$layer_fade=0;
		

		if ($atts['layer_4_duration']!='') {
			$layer_duration=$atts['layer_4_duration'];
		}
		if ($atts['layer_4_delay']!='') {
			$layer_delay=$atts['layer_4_delay'];
		}
		if ($atts['layer_4_fade']!='') {
			$layer_fade=$atts['layer_4_fade'];
		}
		
		if ($atts['initial_left_4']!='') {
			$initial_left=$atts['initial_left_4'];
		}
		if ($atts['initial_top_4']!='') {
			$initial_top=$atts['initial_top_4'];
		}
		if ($atts['final_left_4']!='') {
			$final_left=$atts['final_left_4'];
		}
		if ($atts['final_top_4']!='') {
			$final_top=$atts['final_top_4'];
		}
		
		$aux_layers.='<div class="youtubeVimeoWithPlaylist_text_line '.$atts['layer_4_css'].'" data-initial-left="'.$initial_left.'" data-initial-top="'.$initial_top.'" data-final-left="'.$final_left.'" data-final-top="'.$final_top.'" data-duration="'.$layer_duration.'" data-fade-start="'.$layer_fade.'" data-delay="'.$layer_delay.'">'.$atts['layer_4_text'].'</div>';
	}	
	
		


	$text_id='';
	if ($have_layers==true) {
		$text_id='#youtubeVimeoWithPlaylist_photoText_'.count($layers_arr);
		$layers_arr[count($layers_arr)]='<div id="youtubeVimeoWithPlaylist_photoText_'.count($layers_arr).'" class="youtubeVimeoWithPlaylist_texts">'.$aux_layers.'</div>';
		
	}
	
	
	
	$aux_content='<li data-text-id="'.$text_id.'" data-bottom-thumb="'.$the_imgplaylist[0].'" data-title="'.$atts['title'].'" data-desc="'.$atts['data_desc'].'" data-youtube="'.$atts['youtube'].'" data-vimeo="'.$atts['data_vimeo'].'" data-transition="'.$atts['data_transition'].'" data-link="'.$atts['data_link'].'" data-target="'.$atts['data_target'].'" >'.$img_aux.'</li>';
	

	return str_replace("\r\n", '', $aux_content);	
}


add_shortcode( 'youtube_vimeo', 'youtube_vimeo_func' );
	function youtube_vimeo_func( $atts, $content = null ) { // New function parameter $content is added!
		global $rand_id;
		global $layers_arr;
		
		$initial_vals_arr=array(
			'id' => $rand_id,
			'width' => 960,
			'height' => 384,
			'center_plugin' => 'true',
			'width_100_proc' => 'false',
			'responsive' => 'true',
			'responsive_relative_to_browser' => 'false',
			'get_you_tube_data' => 'true',
			'loop' => 'true',
			'randomize_images' => 'false',
			'first_img' => 0,
			'initial_volume' => 100,
			'number_of_stripes' => 20,
			'number_of_rows' => 5,
			'number_of_columns' => 10,
			'default_effect' => 'random',
			'effect_duration' => 0.5,
			'auto_play' => 4,
			'auto_play_first_video' => 'true',
			'auto_play_next_video' => 'true',
			'target' => '_blank',
			'enable_touch_screen' => 'true',
			'border_width' => 14,
			'border_color' => '#4a4a4a',
			'show_all_controllers' => 'true',
			'show_nav_arrows' => 'true',
			'show_on_init_nav_arrows' => 'true',
			'auto_hide_nav_arrows' => 'true',
			'playlist_width' => 300,
			'orig_thumb_img_w' => 90,
			'orig_thumb_img_h' => 90, 
			'number_of_thumbs_per_screen' => 0,
			'playlist_record_show_img' => 'true',
			'playlist_record_show_title' => 'true',
			'playlist_record_show_desc' => 'true',
			'playlist_record_height' => 110,
			'playlist_record_padding' => 10,
			'playlist_title_font_size' => 15,
			'playlist_title_line_height' => 19,
			'playlist_desc_font_size' => 12,
			'playlist_desc_line_height' => 15,
			'playlist_record_bg_off_color' => '#000000',
			'playlist_record_title_off_color' => '#CCCCCC',
			'playlist_record_desc_off_color' => '#CCCCCC',
			'playlist_record_bg_off_img_opacity' => 100,
			'playlist_record_bg_on_color' => '#cc181e',
			'playlist_record_title_on_color' => '#FFFFFF',
			'playlist_record_desc_on_color' => '#FFFFFF',
			'playlist_record_bg_on_img_opacity' => 100,
			'playlist_record_title_limit' => 25,
			'playlist_record_desc_limit' => 100,
			'playlist_bg_color' => '#4a4a4a',
			'show_circle_timer' => 'true',
			'circle_radius' => 10,
			'circle_line_width' => 4,
			'circle_color' => '#FF0000',
			'circle_alpha' => 100,
			'behind_circle_color' => '#000000',
			'behind_circle_alpha' => 50
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

	$text_str='';

	
	$aux_content='';
	if ($atts["center_plugin"]=='true') {
		$new_div_start='<div class="youtube_vimeo_my_center_'.$atts["id"].'">';
		$new_div_end='</div>';
		
		$aux_content='<style>
		.youtube_vimeo_my_center_'.$atts["id"].' {
			width:'.$atts["width"].'px;  margin:0px auto;
		}
		@media screen and (max-width:'.$atts["width"].'px) {
			.youtube_vimeo_my_center_'.$atts["id"].' {
				width:100%;  margin:0px auto;
			}
		}
	</style>';
		
	}
	


	$aux_content.='<script>
		jQuery(function() {
			jQuery("#youtubeVimeoWithPlaylist_'.$atts["id"].'").youtubeVimeoWithPlaylist({
				responsive:'.$atts["responsive"].',
				responsiveRelativeToBrowser:'.$atts["responsive_relative_to_browser"].',				
				width:'.$atts["width"].',
				height:'.$atts["height"].',
				width100Proc:'.$atts["width_100_proc"].',
				randomizeImages:'.$atts["randomize_images"].',
				firstImg:'.$atts["first_img"].',
				initialVolume:'.($atts["initial_volume"]/100).',
				numberOfStripes:'.$atts["number_of_stripes"].',
				numberOfRows:'.$atts["number_of_rows"].',
				numberOfColumns:'.$atts["number_of_columns"].',
				defaultEffect:"'.$atts["default_effect"].'",
				effectDuration:'.$atts["effect_duration"].',
				autoPlay:'.$atts["auto_play"].',
				autoPlayFirstVideo:'.$atts["auto_play_first_video"].',
				autoPlayNextVideo:'.$atts["auto_play_next_video"].',
				loop:'.$atts["loop"].',
				target:"'.$atts["target"].'",
				enableTouchScreen:'.$atts["enable_touch_screen"].',
				borderWidth:'.$atts["border_width"].',
				borderColor:"'.$atts["border_color"].'",
				showCircleTimer:'.$atts["show_circle_timer"].',
				circleRadius:'.$atts["circle_radius"].',
				circleLineWidth:'.$atts["circle_line_width"].',
				circleColor:"'.$atts["circle_color"].'",
				circleAlpha:'.$atts["circle_alpha"].',
				behindCircleColor:"'.$atts["behind_circle_color"].'",
				behindCircleAlpha:'.$atts["behind_circle_alpha"].',				
				showAllControllers:'.$atts["show_all_controllers"].',
				showNavArrows:'.$atts["show_nav_arrows"].',
				showOnInitNavArrows:'.$atts["show_on_init_nav_arrows"].',
				autoHideNavArrows:'.$atts["auto_hide_nav_arrows"].',
				playlistWidth:'.$atts["playlist_width"].',
				numberOfThumbsPerScreen:'.$atts["number_of_thumbs_per_screen"].',
				playlistRecordShowImg:'.$atts["playlist_record_show_img"].',
				playlistRecordShowTitle:'.$atts["playlist_record_show_title"].',
				playlistRecordShowDesc:'.$atts["playlist_record_show_desc"].',
				playlistRecordHeight:'.$atts["playlist_record_height"].',
				playlistRecordPadding:'.$atts["playlist_record_padding"].',
				playlistTitleFontSize:'.$atts["playlist_title_font_size"].',
				playlistTitleLineHeight:'.$atts["playlist_title_line_height"].',
				playlistDescFontSize:'.$atts["playlist_desc_font_size"].',
				playlistDescLineHeight:'.$atts["playlist_desc_line_height"].',
				playlistRecordBgOffColor:"'.$atts["playlist_record_bg_off_color"].'",
				playlistRecordTitleOffColor:"'.$atts["playlist_record_title_off_color"].'",
				playlistRecordDescOffColor:"'.$atts["playlist_record_desc_off_color"].'",
				playlistRecordBgOffImgOpacity:'.$atts["playlist_record_bg_off_img_opacity"].',
				playlistRecordBgOnColor:"'.$atts["playlist_record_bg_on_color"].'",
				playlistRecordTitleOnColor:"'.$atts["playlist_record_title_on_color"].'",
				playlistRecordDescOnColor:"'.$atts["playlist_record_desc_on_color"].'",	
				playlistRecordBgOnImgOpacity:'.$atts["playlist_record_bg_on_img_opacity"].',			
				playlistBgColor:"'.$atts["playlist_bg_color"].'",	
				playlistRecordTitleLimit:'.$atts["playlist_record_title_limit"].',
				playlistRecordDescLimit:'.$atts["playlist_record_desc_limit"].',
				getYouTubeData:'.$atts["get_you_tube_data"].',
				pathToAjaxFiles:"'.plugins_url("", __FILE__).'/video_player_youtube_vimeo/",
				origThumbImgW:'.$atts["orig_thumb_img_w"].',
				origThumbImgH:'.$atts["orig_thumb_img_h"].'
			});	
		});
	</script>	
            '.$new_div_start.'<div id="youtubeVimeoWithPlaylist_'.$atts["id"].'" style="display:none;"><ul class="youtubeVimeoWithPlaylist_list">'.$playlist_str.'</ul>'.implode(' ',$layers_arr).'</div>'.$new_div_end;
			
			
	return str_replace("\r\n", '', $aux_content);	   
	   
	}
	
?>