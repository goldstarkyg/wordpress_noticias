<?php
/*
Plugin Name: Universal Video Player - Addon For Visual Composer
Description: A Visual Composer Addon which will allow you to insert an advanced HTML5 Video Player With Playlist, Categories and Search. It has support for YouTube, Vimeo and Self-Hosted videos
Version: 1.4.7
Author: Lambert Group
Author URI: https://codecanyon.net/user/LambertGroup/portfolio?ref=LambertGroup
*/


//all the messages
$lbg_categories_messages = array(
		'data_saved' => 'Data Saved!',
		'empty_categ' => 'Category - required',
		'invalid_request' => 'Invalid Request!'
	);
$lbg_categories_path = trailingslashit(dirname(__FILE__)); 	
$rand_id=rand(10,999999);

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


function univp_addon_vc_map_dependencies() {
	if ( ! defined( 'WPB_VC_VERSION' ) ) {
		$plugin_data = get_plugin_data(__FILE__);
        echo '<div class="updated">
          <p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="https://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'vc_extend'), $plugin_data['Name']).'</p>
        </div>';
	}
}
add_action( 'admin_notices', 'univp_addon_vc_map_dependencies' );


function univp_addon_activate() {
	global $wpdb;
	
	//categories database
	include_once('categs/db.php');
}
register_activation_hook(__FILE__,"univp_addon_activate"); //activate plugin and create the database


function univp_addon_vc_map_init() {
	//categories start
	global $wpdb;
	global $rand_id;
	
	//categories database
	//include_once($univp_addon_path . 'categs/db.php');
	


	//get categories values	array for playlist checkbox start	
	$categories_arr=array();
	$safe_sql="SELECT * FROM (".$wpdb->prefix ."lbg_categories) ORDER BY categ";
	$result = $wpdb->get_results($safe_sql,ARRAY_A);
	foreach ( $result as $row ) 
	{
		$row=lbg_unstrip_array($row);
		//$categories_arr[$row['categ']]=$row['categ'];
		$categories_arr[$row['categ']]=$row['id'];
	}
	//get categories values	array for playlist checkbox end
	
	
	//Create New Param Type 'universal_video_player_attach_media'
	//if (is_admin() && !function_exists('attach_media_callback')) {//check if the categories menu already exists	
			//add_shortcode_param
			vc_add_shortcode_param( 'universal_video_player_attach_media', 'universal_video_player_attach_media_callback', plugins_url() . '/lbg_universal_video_player_addon_visual_composer/assets/new_param_type/attach_media.js');
			function universal_video_player_attach_media_callback( $settings, $value ) {   
				return '<div class="my_param_block">'
				.'<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' . esc_attr( $settings['param_name'] ) . ' ' .              esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" style="width:70%;" />' .
				'<input name="upload_' . esc_attr( $settings['param_name'] ) . '_button" type="button" id="upload_' . esc_attr( $settings['param_name'] ) . '_button" value="Upload File" style="width:30%;" /> '.'</div>'; // This is html markup that will be outputted in content elements edit form
			}
	//}

/**/

	
	
	vc_map( array(
		'name' => __( 'Universal Video Player', 'js_composer' ),
		'base' => 'universal_video_player',
		"icon" => plugins_url('assets/images/universal_video_player_icon.png', __FILE__), // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
		"category" => __('LBG Multimedia Addons', 'js_composer'),
		"description" => __("YouTube, Vimeo and Self-Hosted videos", 'vc_extend'),	
		'show_settings_on_create' => false,
		'is_container' => true,
		'admin_enqueue_js'        => preg_replace( '/\s/', '%20', plugins_url( 'assets/universal_video_player.js', __FILE__ ) ),
		// This will load extra js file in backend (when you edit page with VC)
		// use preg replace to be sure that "space" will not break logic

		//'admin_enqueue_css'       => preg_replace( '/\s/', '%20', plugins_url( 'assets/admin_enqueue_css.css', __FILE__ ) ),
		'admin_enqueue_css'       => preg_replace( '/\s/', '%20', plugins_url( 'assets/universal_video_player.css', __FILE__ ) ),
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
				'heading' => __( 'Skin Name', 'js_composer' ),
				'param_name' => 'skin',
				'value'       => array(
					'whiteControllers'   => 'whiteControllers',
					'blackControllers'   => 'blackControllers'
				)
			),
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'Player Width', 'js_composer' ),
				'param_name' => 'width', //width
				'value' => __( "980", "my-text-domain" )
			),
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'Player Height', 'js_composer' ),
				'param_name' => 'height', //height
				'value' => __( "399", "my-text-domain" )
			),
			array(
				'group' => 'General',
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
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Responsive', 'js_composer' ),
				'param_name' => 'responsive',
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'					
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Responsive Relative To Browser', 'js_composer' ),
				'param_name' => 'responsive_relative_to_browser', //responsiveRelativeToBrowser
				'value'       => array(
					'No'   => 'false',
					'Yes'   => 'true'
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Automatically Obtain Thumbnail, Title &amp; Description From YouTube Server', 'js_composer' ),
				'param_name' => 'get_youtube_data', //getYouTubeData
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'					
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Loop', 'js_composer' ),
				'param_name' => 'loop',
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
					
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Auto Play', 'js_composer' ),
				'param_name' => 'auto_play_first_video', //autoPlayFirstVideo
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Shuffle', 'js_composer' ),
				'param_name' => 'shuffle',
				'value'       => array(
					'No'   => 'false',
					'Yes'   => 'true'
				  )
			),
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'Initial Volume Value', 'js_composer' ),
				'param_name' => 'initial_volume', //initialVolume
				'value' => __( "100", "my-text-domain" ),
				'description' => __( "Values between 0-100", "js_composer" )
			),
			array(
				'group' => 'General',            
				'type' => 'colorpicker',
				'heading' => __( "Player Border Color", "js_composer" ),
				'param_name' => 'border_color', //borderColor
				'value' => '#4a4a4a',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'General',            
				'type' => 'colorpicker',
				'heading' => __( "Controls Bg FullScreen Color", "js_composer" ),
				'param_name' => 'controls_bg_full_screen_color', //controlsBgFullScreenColor
				'value' => '#000000',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'General',            
				'type' => 'colorpicker',
				'heading' => __( "Empty Buffer Color", "js_composer" ),
				'param_name' => 'buffer_empty_color', //bufferEmptyColor
				'value' => '#929292',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'General',            
				'type' => 'colorpicker',
				'heading' => __( "Full Buffer Color", "js_composer" ),
				'param_name' => 'buffer_full_color', //bufferFullColor
				'value' => '#333333',
				'description' => __( "Choose 'full' buffer color", "js_composer" )
			),
			array(
				'group' => 'General',            
				'type' => 'colorpicker',
				'heading' => __( "SeekBar Color", "js_composer" ),
				'param_name' => 'seekbar_color', //seekbarColor
				'value' => '#FFFFFF',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'General',            
				'type' => 'colorpicker',
				'heading' => __( "Volume Off State Color", "js_composer" ),
				'param_name' => 'volume_off_color', //volumeOffColor
				'value' => '#454545',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'General',            
				'type' => 'colorpicker',
				'heading' => __( "Volume On State Color", "js_composer" ),
				'param_name' => 'volume_on_color', //volumeOnColor
				'value' => '#FFFFFF',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'General',            
				'type' => 'colorpicker',
				'heading' => __( "Timer Color", "js_composer" ),
				'param_name' => 'timer_color', //timerColor
				'value' => '#FFFFFF',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'General',            
				'type' => 'colorpicker',
				'heading' => __( "Top Title Color", "js_composer" ),
				'param_name' => 'top_title_color', //topTitleColor
				'value' => '#FFFFFF',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'First Video To Play', 'js_composer' ),
				'param_name' => 'first_video', //firstVideo
				'value' => __( "0", "my-text-domain" ),
				'description' => __( "Counting starts from 0", "js_composer" )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Suggested Quality', 'js_composer' ),
				'param_name' => 'suggested_quality', //suggestedQuality
				'value'       => array(
					'default'   => 'default',
					'small'   => 'small',
					'medium'   => 'medium',
					'large'   => 'large',
					'hd720'   => 'hd720',
					'hd1080'   => 'hd1080',
					'highres'   => 'highres'
				 ),
				'description' => __( "Only For YouTube", "js_composer" )	  
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Show Logo', 'js_composer' ),
				'param_name' => 'show_logo', //showLogo
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'General',
				'type' => 'attach_image',
				'heading' => __( 'Logo', 'js_composer' ),
				'param_name' => 'logo_image_path', //logoImagePath
				'description' => __( 'Select an image from Media Library', 'js_composer' )
			),
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'Logo Link', 'js_composer' ),
				'param_name' => 'logo_link', //logoLink
				'value' => __( "", "my-text-domain" )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Logo Target', 'js_composer' ),
				'param_name' => 'logo_target', //logoTarget
				'value'       => array(
					'_blank'   => '_blank',
					'_self'   => '_self'
				  )
			),
			array(
				'group' => 'General',            
				'type' => 'colorpicker',
				'heading' => __( "Play Button Color OFF State", "js_composer" ),
				'param_name' => 'play_but_color_off', //playButColorOff
				'value' => '#de1a21',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'General',            
				'type' => 'colorpicker',
				'heading' => __( "Play Button Color ON State", "js_composer" ),
				'param_name' => 'play_but_color_on', //playButColorOn
				'value' => '#de1a21',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Show Rewind Button', 'js_composer' ),
				'param_name' => 'show_rewind_but', //showRewindBut
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Show Play Button', 'js_composer' ),
				'param_name' => 'show_play_but', //showPlayBut
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Show Volume Button', 'js_composer' ),
				'param_name' => 'show_volume_but', //showVolumeBut
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Show Shuffle Button', 'js_composer' ),
				'param_name' => 'show_shuffle_but', //showShuffleBut
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Show Download Button', 'js_composer' ),
				'param_name' => 'show_download_but', //showDownloadBut
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Show Twitter Button', 'js_composer' ),
				'param_name' => 'show_twitter_but', //showTwitterBut
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Show Info Button', 'js_composer' ),
				'param_name' => 'show_info_but', //showInfoBut
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Show FullScreen Button', 'js_composer' ),
				'param_name' => 'show_fullscreen_but', //showFullscreenBut
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Show Next/Prev Buttons', 'js_composer' ),
				'param_name' => 'show_next_prev_but', //showNextPrevBut
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Show Movie Top Title', 'js_composer' ),
				'param_name' => 'show_top_title', //showTopTitle
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Show Timer', 'js_composer' ),
				'param_name' => 'show_timer', //showTimer
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Show FaceBook Button', 'js_composer' ),
				'param_name' => 'show_facebook_but', //showFacebookBut
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'FaceBook AppID', 'js_composer' ),
				'param_name' => 'facebook_app_id', //facebookAppID
				'value' => __( "", "my-text-domain" )
			),
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'FaceBook Share Title', 'js_composer' ),
				'param_name' => 'facebook_share_title', //facebookShareTitle
				'value' => __( "", "my-text-domain" )
			),
			array(
				'group' => 'General',
				'type' => 'textarea',
				'heading' => __( 'FaceBook Share Description', 'js_composer' ),
				'param_name' => 'facebook_share_description', //facebookShareDescription
				'value' => __( "", "my-text-domain" )
			),
			array(
				'group' => 'General',
				'type' => 'dropdown',
				'heading' => __( 'Activate Google Analytics Traking', 'js_composer' ),
				'param_name' => 'google_traking_on', //googleTrakingOn
				'value'       => array(
					'No'   => 'false',
					'Yes'   => 'true'
				  )
			),
			array(
				'group' => 'General',
				'type' => 'textfield',
				'heading' => __( 'Your Google Analytics Traking Code', 'js_composer' ),
				'param_name' => 'google_traking_code', //googleTrakingCode
				'value' => __( "", "my-text-domain" ),
				'description' => __( "Example of code: UA-3245593-1", "js_composer" )
			),
			
			
			
			array(
				'group' => 'Playlist',
				'type' => 'dropdown',
				'heading' => __( 'Show Playlist On Init', 'js_composer' ),
				'param_name' => 'show_playlist_on_init', //showPlaylistOnInit
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'Playlist',
				'type' => 'dropdown',
				'heading' => __( 'Show Playlist Button', 'js_composer' ),
				'param_name' => 'show_playlist_but', //showPlaylistBut
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'Playlist',
				'type' => 'textfield',
				'heading' => __( 'Playlist Width', 'js_composer' ),
				'param_name' => 'playlist_width', //playlistWidth
				'value' => __( "282", "my-text-domain" )
			),
			array(
				'group' => 'Playlist',
				'type' => 'textfield',
				'heading' => __( 'Thumb Width', 'js_composer' ),
				'param_name' => 'orig_thumb_img_w', //origThumbImgW
				'value' => __( "69", "my-text-domain" )
			),
			array(
				'group' => 'Playlist',
				'type' => 'textfield',
				'heading' => __( 'Thumb Height', 'js_composer' ),
				'param_name' => 'orig_thumb_img_h', //origThumbImgH
				'value' => __( "69", "my-text-domain" )
			),
			array(
				'group' => 'Playlist',            
				'type' => 'colorpicker',
				'heading' => __( "Playlist Background Color", "js_composer" ),
				'param_name' => 'playlist_bg_color', //playlistBgColor
				'value' => '#4a4a4a',
				'description' => __( "Choose the color", "js_composer" )
			),			
			array(
				'group' => 'Playlist',
				'type' => 'dropdown',
				'heading' => __( 'Show Thumbs', 'js_composer' ),
				'param_name' => 'playlist_record_show_img', //playlistRecordShowImg
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'Playlist',
				'type' => 'dropdown',
				'heading' => __( 'Show Title', 'js_composer' ),
				'param_name' => 'playlist_record_show_title', //playlistRecordShowTitle
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'Playlist',
				'type' => 'dropdown',
				'heading' => __( 'Show Description', 'js_composer' ),
				'param_name' => 'playlist_record_show_desc', //playlistRecordShowDesc
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'Playlist',
				'type' => 'textfield',
				'heading' => __( 'Playlist Record Height', 'js_composer' ),
				'param_name' => 'playlist_record_height', //playlistRecordHeight
				'value' => __( "89", "my-text-domain" )
			),
			array(
				'group' => 'Playlist',
				'type' => 'textfield',
				'heading' => __( 'Playlist Record Padding', 'js_composer' ),
				'param_name' => 'playlist_record_padding', //playlistRecordPadding
				'value' => __( "10", "my-text-domain" )
			),
			array(
				'group' => 'Playlist',
				'type' => 'textfield',
				'heading' => __( 'Title Font Size', 'js_composer' ),
				'param_name' => 'playlist_title_font_size', //playlistTitleFontSize
				'value' => __( "15", "my-text-domain" )
			),
			array(
				'group' => 'Playlist',
				'type' => 'textfield',
				'heading' => __( 'Title Line Height', 'js_composer' ),
				'param_name' => 'playlist_title_line_height', //playlistTitleLineHeight
				'value' => __( "15", "my-text-domain" )
			),			
			array(
				'group' => 'Playlist',
				'type' => 'textfield',
				'heading' => __( 'Title Characters Limit', 'js_composer' ),
				'param_name' => 'playlist_record_title_limit', //playlistRecordTitleLimit
				'value' => __( "22", "my-text-domain" )
			),
			array(
				'group' => 'Playlist',
				'type' => 'textfield',
				'heading' => __( 'Description Font Size', 'js_composer' ),
				'param_name' => 'playlist_desc_font_size', //playlistDescFontSize
				'value' => __( "12", "my-text-domain" )
			),
			array(
				'group' => 'Playlist',
				'type' => 'textfield',
				'heading' => __( 'Description Line Height', 'js_composer' ),
				'param_name' => 'playlist_desc_line_height', //playlistDescLineHeight
				'value' => __( "15", "my-text-domain" )
			),		
			array(
				'group' => 'Playlist',
				'type' => 'textfield',
				'heading' => __( 'Description Characters Limit', 'js_composer' ),
				'param_name' => 'playlist_record_desc_limit', //playlistRecordDescLimit
				'value' => __( "84", "my-text-domain" )
			),
			array(
				'group' => 'Playlist',            
				'type' => 'colorpicker',
				'heading' => __( "Playlist Record Background OFF Color", "js_composer" ),
				'param_name' => 'playlist_record_bg_off_color', //playlistRecordBgOffColor
				'value' => '#eeeeee',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Playlist',            
				'type' => 'colorpicker',
				'heading' => __( "Playlist Record Title OFF Color", "js_composer" ),
				'param_name' => 'playlist_record_title_off_color', //playlistRecordTitleOffColor
				'value' => '#333333',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Playlist',            
				'type' => 'colorpicker',
				'heading' => __( "Playlist Record Desc OFF Color", "js_composer" ),
				'param_name' => 'playlist_record_desc_off_color', //playlistRecordDescOffColor
				'value' => '#333333',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Playlist',
				'type' => 'textfield',
				'heading' => __( 'Playlist Record Bg OFF Img Opacity', 'js_composer' ),
				'param_name' => 'playlist_record_bg_off_img_opacity', //playlistRecordBgOffImgOpacity
				'value' => __( "100", "my-text-domain" ),
				'description' => __( "Values between 0-100", "js_composer" )
			),
			array(
				'group' => 'Playlist',            
				'type' => 'colorpicker',
				'heading' => __( "Playlist Record Background ON Color", "js_composer" ),
				'param_name' => 'playlist_record_bg_on_color', //playlistRecordBgOnColor
				'value' => '#de1a21',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Playlist',            
				'type' => 'colorpicker',
				'heading' => __( "Playlist Record Title ON Color", "js_composer" ),
				'param_name' => 'playlist_record_title_on_color', //playlistRecordTitleOnColor
				'value' => '#FFFFFF',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Playlist',            
				'type' => 'colorpicker',
				'heading' => __( "Playlist Record Desc ON Color", "js_composer" ),
				'param_name' => 'playlist_record_desc_on_color', //playlistRecordDescOnColor
				'value' => '#FFFFFF',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Playlist',
				'type' => 'textfield',
				'heading' => __( 'Playlist Record Bg ON Img Opacity', 'js_composer' ),
				'param_name' => 'playlist_record_bg_on_img_opacity', //playlistRecordBgOnImgOpacity
				'value' => __( "100", "my-text-domain" ),
				'description' => __( "Values between 0-100", "js_composer" )
			),
			array(
				'group' => 'Playlist',
				'type' => 'textfield',
				'heading' => __( 'Number Of Items Per Screen', 'js_composer' ),
				'param_name' => 'number_of_thumbs_per_screen', //numberOfThumbsPerScreen
				'value' => __( "0", "my-text-domain" )
			),
			array(
				'group' => 'Playlist',            
				'type' => 'colorpicker',
				'heading' => __( "Playlist Scroller Bg Color OFF", "js_composer" ),
				'param_name' => 'playlist_scroller_bg_color_off', //playlistScrollerBgColorOFF
				'value' => '#CCCCCC',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Playlist',            
				'type' => 'colorpicker',
				'heading' => __( "Playlist Scroller Bg Color ON", "js_composer" ),
				'param_name' => 'playlist_scroller_bg_color_on', //playlistScrollerBgColorON
				'value' => '#FFFFFF',
				'description' => __( "Choose the color", "js_composer" )
			),
			


			
			
			array(
				'group' => 'Selected Category',
				'type' => 'dropdown',
				'heading' => __( 'Show Categories', 'js_composer' ),
				'param_name' => 'show_categs', //showCategs
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'Selected Category',
				'type' => 'textfield',
				'heading' => __( 'First Category', 'js_composer' ),
				'param_name' => 'first_categ', //firstCateg
				'value' => __( "", "my-text-domain" ),
				'description' => __( "Write the exact name of the category you want to be the first one. Leave it blank and the first one, in alphabetical order, will be displayed", "js_composer" )
			),
			array(
				'group' => 'Selected Category',
				'type' => 'colorpicker',
				'heading' => __( "Selected Categ Background Color", "js_composer" ),
				'param_name' => 'selected_categ_bg', //selectedCategBg
				'value' => 'transparent',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Selected Category',
				'type' => 'colorpicker',
				'heading' => __( "Selected Categ OFF Color", "js_composer" ),
				'param_name' => 'selected_categ_off_color', //selectedCategOffColor
				'value' => '#FFFFFF',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Selected Category',
				'type' => 'colorpicker',
				'heading' => __( "Selected Categ ON Color", "js_composer" ),
				'param_name' => 'selected_categ_on_color', //selectedCategOnColor
				'value' => '#de1a28',
				'description' => __( "Choose the color", "js_composer" )
			),
			
			
			
			
			array(
				'group' => 'Categories',            
				'type' => 'colorpicker',
				'heading' => __( "Category Record Background OFF Color", "js_composer" ),
				'param_name' => 'category_record_bg_off_color', //categoryRecordBgOffColor
				'value' => '#000000',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Categories',            
				'type' => 'colorpicker',
				'heading' => __( "Category Record Background ON Color", "js_composer" ),
				'param_name' => 'category_record_bg_on_color', //categoryRecordBgOnColor
				'value' => '#252525',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Categories',            
				'type' => 'colorpicker',
				'heading' => __( "Category Record Bottom Border OFF Color", "js_composer" ),
				'param_name' => 'category_record_bottom_border_off_color', //categoryRecordBottomBorderOffColor
				'value' => '#333333',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Categories',            
				'type' => 'colorpicker',
				'heading' => __( "Category Record Bottom Border ON Color", "js_composer" ),
				'param_name' => 'category_record_bottom_border_on_color', //categoryRecordBottomBorderOnColor
				'value' => '#333333',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Categories',            
				'type' => 'colorpicker',
				'heading' => __( "Category Record Text OFF Color", "js_composer" ),
				'param_name' => 'category_record_text_off_color', //categoryRecordTextOffColor
				'value' => '#4c4c4c',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Categories',            
				'type' => 'colorpicker',
				'heading' => __( "Category Record Text ON Color", "js_composer" ),
				'param_name' => 'category_record_text_on_color', //categoryRecordTextOnColor
				'value' => '#de1a21',
				'description' => __( "Choose the color", "js_composer" )
			),
			
			
			
			
			
			
			array(
				'group' => 'Search Area',
				'type' => 'dropdown',
				'heading' => __( 'Show Search Area', 'js_composer' ),
				'param_name' => 'show_search', //showSearch
				'value'       => array(
					'Yes'   => 'true',
					'No'   => 'false'
				  )
			),
			array(
				'group' => 'Search Area',            
				'type' => 'colorpicker',
				'heading' => __( "Search Area Background Color", "js_composer" ),
				'param_name' => 'search_area_bg', //searchAreaBg
				'value' => 'transparent',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Search Area',
				'type' => 'textfield',
				'heading' => __( 'Search Input Text', 'js_composer' ),
				'param_name' => 'search_input_text', //searchInputText
				'value' => __( "search...", "my-text-domain" )
			),			
			array(
				'group' => 'Search Area',            
				'type' => 'colorpicker',
				'heading' => __( "Search Input Background Color", "js_composer" ),
				'param_name' => 'search_input_bg', //searchInputBg
				'value' => '#cccccc',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Search Area',            
				'type' => 'colorpicker',
				'heading' => __( "Search Input Border Color", "js_composer" ),
				'param_name' => 'search_input_border_color', //searchInputBorderColor
				'value' => '#4a4a4a',
				'description' => __( "Choose the color", "js_composer" )
			),
			array(
				'group' => 'Search Area',            
				'type' => 'colorpicker',
				'heading' => __( "Search Input Text Color", "js_composer" ),
				'param_name' => 'search_input_text_color', //searchInputTextColor
				'value' => '#333333',
				'description' => __( "Choose the color", "js_composer" )
			),
			
			
			
			
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
		[universal_video_player_playlist_item title="' . __( 'Playlist Item  1', 'js_composer' ) . '"][/universal_video_player_playlist_item]
	',
		'js_view' => 'VcUniversalVideoPlayerView'
	) );
	
	vc_map( array(
		'name' => __( 'UP Playlist Item', 'js_composer' ),
		'base' => 'universal_video_player_playlist_item',
		'allowed_container_element' => 'vc_row',
		'is_container' => true,
		'content_element' => false,
		'admin_enqueue_js'        => preg_replace( '/\s/', '%20', plugins_url( 'assets/playlist_item.js', __FILE__ ) ),
		// This will load extra js file in backend (when you edit page with VC)
		// use preg replace to be sure that "space" will not break logic

		'admin_enqueue_css'       => preg_replace( '/\s/', '%20', plugins_url( 'assets/playlist_item.css', __FILE__ ) ),
		// This will load extra css file in backend (when you edit page with VC)		
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'YouTube Video ID', 'js_composer' ),
				'param_name' => 'youtube'
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Vimeo Video ID', 'js_composer' ),
				'param_name' => 'vimeo'
			),		
			array(
				'type' => 'textfield',
				'heading' => __( 'Video Title', 'js_composer' ),
				'param_name' => 'title'
			),
			array(
				'type' => 'attach_image',
				'heading' => __( 'Thumbnail', 'js_composer' ),
				'param_name' => 'imgplaylist',
				'description' => __( 'Select an image from Media Library', 'js_composer' )
				
			),			
			array(
				'type' => 'textarea',
				'heading' => __( 'Video Description', 'js_composer' ),
				'param_name' => 'desc'
			),
			array(
				'type' => 'checkbox',
				'heading' => __( 'Category', 'js_composer' ),
				"value"       => $categories_arr, 
				'param_name' => 'xcategory',
				'description' => __( 'Select at least one category', 'js_composer' )
			),

			array(
				'type' => 'universal_video_player_attach_media',
				'heading' => __( 'MP4 file (Chrome, Mozzila, IE, Safari)', 'js_composer' ),
				'param_name' => 'mp4',
				'description' => __( 'Select a MP4 file from Media Library or just insert the URL', 'js_composer' )
			),
			array(
				'type' => 'universal_video_player_attach_media',
				'heading' => __( 'WEBM file (Opera) - not mandatory', 'js_composer' ),
				'param_name' => 'webm',
				'description' => __( 'Select a WEBM file from Media Library or just insert the URL', 'js_composer' )
			)
		),
		'js_view' => 'VcPlaylistItemView'
	) );
	




	if ( class_exists( "WPBakeryShortCode" ) ) {
		$univp_addon_path = trailingslashit(dirname(__FILE__)); 
		include_once($univp_addon_path . 'vc_contentadmin/universal_video_player-accordion.php');
		include_once($univp_addon_path . 'vc_contentadmin/universal_video_player-accordion-tab.php');
		

	} // End Class

}


//add_action('vc_after_init', 'univp_addon_vc_map_init');
add_action('vc_before_init', 'univp_addon_vc_map_init');



function universal_video_player_addon_enqueue_scripts_and_styles() {
	//if (!is_admin()) {
				wp_enqueue_style('universal_video_player_site_css', plugins_url('universal_video_player/universal_video_player.css', __FILE__));
				wp_register_style('pt_sans-googleFonts', 'https://fonts.googleapis.com/css?family=PT+Sans:400,700');
				wp_enqueue_style( 'pt_sans-googleFonts');
		
				wp_enqueue_script('jquery');

				wp_enqueue_script('jquery-ui-core');
	
				//wp_enqueue_script('jquery-ui-widget');
				//wp_enqueue_script('jquery-ui-mouse');
				//wp_enqueue_script('jquery-ui-accordion');
				//wp_enqueue_script('jquery-ui-autocomplete');
				wp_enqueue_script('jquery-ui-slider');
				//wp_enqueue_script('jquery-ui-tabs');
				//wp_enqueue_script('jquery-ui-sortable');
				//wp_enqueue_script('jquery-ui-draggable');
				//wp_enqueue_script('jquery-ui-droppable');
				//wp_enqueue_script('jquery-ui-selectable');
				//wp_enqueue_script('jquery-ui-position');
				//wp_enqueue_script('jquery-ui-datepicker');
				//wp_enqueue_script('jquery-ui-resizable');
				//wp_enqueue_script('jquery-ui-dialog');
				//wp_enqueue_script('jquery-ui-button');
				
				//wp_enqueue_script('jquery-form');
				//wp_enqueue_script('jquery-color');
				//wp_enqueue_script('jquery-masonry');
				wp_enqueue_script('jquery-ui-progressbar');
				//wp_enqueue_script('jquery-ui-tooltip');
				
				//wp_enqueue_script('jquery-effects-core');
				//wp_enqueue_script('jquery-effects-blind');
				//wp_enqueue_script('jquery-effects-bounce');
				//wp_enqueue_script('jquery-effects-clip');
				wp_enqueue_script('jquery-effects-drop');
				/*wp_enqueue_script('jquery-effects-explode');
				wp_enqueue_script('jquery-effects-fade');
				wp_enqueue_script('jquery-effects-fold');
				wp_enqueue_script('jquery-effects-highlight');
				wp_enqueue_script('jquery-effects-pulsate');
				wp_enqueue_script('jquery-effects-scale');
				wp_enqueue_script('jquery-effects-shake');
				wp_enqueue_script('jquery-effects-slide');			
				wp_enqueue_script('jquery-effects-transfer');*/
				
				
			wp_register_script('lbg-mousewheel', plugins_url('universal_video_player/js/jquery.mousewheel.min.js', __FILE__));
			wp_enqueue_script('lbg-mousewheel');
			
			wp_register_script('lbg-touchSwipe', plugins_url('universal_video_player/js/jquery.touchSwipe.min.js', __FILE__));
			wp_enqueue_script('lbg-touchSwipe');	
			
			wp_register_script('lbg-screenfull', plugins_url('universal_video_player/js/screenfull.min.js', __FILE__));
			wp_enqueue_script('lbg-screenfull');			
			
			//wp_register_script('lbg-vimeo', 'http://a.vimeocdn.com/js/froogaloop2.min.js');
			if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || (!empty($_SERVER['HTTP_HTTPS']) && $_SERVER['HTTP_HTTPS'] != 'off') || $_SERVER['REQUEST_SCHEME'] == 'https' || $_SERVER['SERVER_PORT'] == 443) {
				wp_register_script('lbg-vimeo', 'https://secure-a.vimeocdn.com/js/froogaloop2.min.js');
			} else {
				wp_register_script('lbg-vimeo', 'http://a.vimeocdn.com/js/froogaloop2.min.js');
			}
			wp_enqueue_script('lbg-vimeo');
			
			wp_register_script('lbg-universal_video_player', plugins_url('universal_video_player/js/universal_video_player.js', __FILE__));
			wp_enqueue_script('lbg-universal_video_player');		
			
			wp_register_script('lbg-google_a', plugins_url('universal_video_player/js/google_a.js', __FILE__));
			wp_enqueue_script('lbg-google_a');
	//}
}
add_action( 'wp_enqueue_scripts', 'universal_video_player_addon_enqueue_scripts_and_styles' );



function universal_video_player_addon_enqueue_admin_scripts_and_styles() {
	//load scripts in admin
    //categories menu  start
	//if (is_admin() && !function_exists('audio_player_pro_menu')) {//check if the categories menu already exists
			wp_enqueue_script('jquery-effects-highlight');
			
			wp_register_script('lbg-admin-editinplace', plugins_url('assets/jquery.editinplace.js', __FILE__));
			wp_enqueue_script('lbg-admin-editinplace');	
	//}
	//categories menu end
}
add_action( 'admin_enqueue_scripts', 'universal_video_player_addon_enqueue_admin_scripts_and_styles' );




//categories menu  start
if (is_admin() && !function_exists('audio_player_pro_menu')) {//check if the categories menu already exists
		include_once('categs/categs_functions.php');
}
//categories menu end







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




//the shortcodes
add_shortcode( 'universal_video_player_playlist_item', 'universal_video_player_playlist_item_func' );
function universal_video_player_playlist_item_func( $atts, $content = null ) { // New function parameter $content is added!
	global $wpdb;
	
	$safe_sql="SELECT * FROM (".$wpdb->prefix ."lbg_categories) ORDER BY categ";
	$result_categ = $wpdb->get_results($safe_sql,ARRAY_A);
	$categ_arr=array();
	foreach ( $result_categ as $row_categ ) {
		if (preg_match_all('/\b'.$row_categ["id"].'\b/', str_replace(',',';',$atts["xcategory"]), $matches)) {
			$categ_arr[]=stripslashes($row_categ['categ']);	
		}
	}
	
	
	$the_thumb=wp_get_attachment_image_src($atts["imgplaylist"], "large");


	$aux_content='<li data-bottom-thumb="'.$the_thumb[0].'" 
                    	data-title="'.$atts["title"].'" 
                        data-desc="'.$atts["desc"].'" 
                        data-youtube="'.$atts["youtube"].'"
						data-vimeo="'.$atts["vimeo"].'"
						data-selfhostedMP4="'.$atts["mp4"].'"
						data-selfhostedWEBM="'.$atts["webm"].'"
                        data-category="'.implode(";",$categ_arr).'">
                   </li>';
	return str_replace("\r\n", '', $aux_content);	
}


add_shortcode( 'universal_video_player', 'universal_video_player_func' );
	function universal_video_player_func( $atts, $content = null ) { // New function parameter $content is added!
		global $rand_id;
		$initial_vals_arr=array(
			'id' => $rand_id,
			'skin' => 'whiteControllers',
			'width' => 980,
			'height' => 399,
			'center_plugin' => 'true',
			'responsive' => 'true',
			'responsive_relative_to_browser' => 'false',
			'get_youtube_data' => 'true',
			'loop' => 'true',
			'auto_play_first_video' => 'true',
			'shuffle' => 'false',
			'initial_volume' => 100,
			'border_color' => '#4a4a4a',
			'controls_bg_full_screen_color' => '#000000',
			'buffer_empty_color' => '#929292',
			'buffer_full_color' => '#333333',
			'seekbar_color' => '#FFFFFF',
			'volume_off_color' => '#454545',
			'volume_on_color' => '#FFFFFF',
			'timer_color' => '#FFFFFF',
			'top_title_color' => '#FFFFFF',
			'first_video' => 0,
			'suggested_quality' => 'default',
			'show_logo' => 'true',
			'logo_image_path' => '',
			'logo_link' => '',
			'logo_target' => '_blank',
			'play_but_color_off' => '#de1a21',
			'play_but_color_on' => '#de1a21',
			'show_rewind_but' => 'true',
			'show_play_but' => 'true',
			'show_volume_but' => 'true',
			'show_shuffle_but' => 'true',
			'show_download_but' => 'true',
			'show_twitter_but' => 'true',
			'show_info_but' => 'true',
			'show_fullscreen_but' => 'true',
			'show_next_prev_but' => 'true',
			'show_top_title' => 'true',
			'show_timer' => 'true',
			'show_facebook_but' => 'true',
			'facebook_app_id' => '',
			'facebook_share_title' => '',
			'facebook_share_description' => '',
			'google_traking_on' => 'false',
			'google_traking_code' => '',
			'show_playlist_on_init' => 'true',
			'show_playlist_but' => 'true',
			'playlist_width' => 282,
			'orig_thumb_img_w' => 69,
			'orig_thumb_img_h' => 69,
			'playlist_bg_color' => '#4a4a4a',
			'playlist_record_show_img' => 'true',
			'playlist_record_show_title' => 'true',
			'playlist_record_show_desc' => 'true',
			'playlist_record_height' => 89,
			'playlist_record_padding' => 10,
			'playlist_title_font_size' => 15,
			'playlist_title_line_height' => 15,
			'playlist_record_title_limit' => 22,
			'playlist_desc_font_size' => 12,
			'playlist_desc_line_height' => 15,
			'playlist_record_desc_limit' => 84,
			'playlist_record_bg_off_color' => '#eeeeee',
			'playlist_record_title_off_color' => '#333333',
			'playlist_record_desc_off_color' => '#333333',
			'playlist_record_bg_off_img_opacity' => 100,
			'playlist_record_bg_on_color' => '#de1a21',
			'playlist_record_title_on_color' => '#FFFFFF',
			'playlist_record_desc_on_color' => '#FFFFFF',
			'playlist_record_bg_on_img_opacity' => 100,
			'number_of_thumbs_per_screen' => 0,
			'playlist_scroller_bg_color_off' => '#CCCCCC',
			'playlist_scroller_bg_color_on' => '#FFFFFF',
			'show_categs' => 'true',
			'first_categ' => '',
			'selected_categ_bg' => 'transparent',
			'selected_categ_off_color' => '#FFFFFF',
			'selected_categ_on_color' => '#de1a28',
			'category_record_bg_off_color' => '#000000',
			'category_record_bg_on_color' => '#252525',
			'category_record_bottom_border_off_color' => '#333333',
			'category_record_bottom_border_on_color' => '#333333',
			'category_record_text_off_color' => '#4c4c4c',
			'category_record_text_on_color' => '#de1a21',
			'show_search' => 'true',
			'search_area_bg' => 'transparent',
			'search_input_text' => 'search...',
			'search_input_bg' => '#cccccc',
			'search_input_border_color' => '#4a4a4a',
			'search_input_text_color' => '#333333'
	   );
	
	   extract( shortcode_atts( $initial_vals_arr, $atts ) );
	   foreach ($initial_vals_arr as $key => $value) {
    		//echo "Key: $key; Value: $value<br />\n";
			if (!isset($atts[$key])) {
				$atts[$key]=$value;
			}
		}
	  
	   //$content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content*/
	 
	   
	//download
	//$pathToDownloadFile_aux= WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'universal_video_player/';
	$pathToDownloadFile_aux = plugin_dir_url( __FILE__ ).'universal_video_player/';
	 
	$playlist_str=''; 
	$playlist_str=do_shortcode( $content );
	
	$the_logo=wp_get_attachment_image_src($atts["logo_image_path"], "large");

	
	$aux_content='';
	$new_div_start='';
	$new_div_end='';
	if ($atts["center_plugin"]=='true') {
		$new_div_start='<div class="universal_video_player_my_center_'.$atts["id"].'">';
		$new_div_end='</div>';
		$aux_content='<style>
		.universal_video_player_my_center_'.$atts["id"].' {
			width:'.$atts["width"].'px;  margin:0px auto;
		}
		@media screen and (max-width:'.($atts["width"]).'px) {
			.universal_video_player_my_center_'.$atts["id"].' {
				width:100%;  margin:0px auto;
			}
		}
	</style>';
		
	}
	

	   
	$aux_content.='<p><script>
		jQuery(function() {
			jQuery("#universal_video_player_'.$atts["id"].'").universal_video_player({
				skin:"'.$atts["skin"].'",
				width:'.$atts["width"].',
				height:'.$atts["height"].',
				responsive:'.$atts["responsive"].',
				responsiveRelativeToBrowser:'.$atts["responsive_relative_to_browser"].',
				shuffle:'.$atts["shuffle"].',
				firstVideo:'.$atts["first_video"].',
				initialVolume:'.($atts["initial_volume"]/100).',
				loop:'.$atts["loop"].',
				showCategs:'.$atts["show_categs"].',
				showSearch:'.$atts["show_search"].',
				showTopTitle:'.$atts["show_top_title"].',
				showTimer:'.$atts["show_timer"].',
				showRewindBut:'.$atts["show_rewind_but"].',
				showPlayBut:'.$atts["show_play_but"].',
				showVolumeBut:'.$atts["show_volume_but"].',
				showFacebookBut:'.$atts["show_facebook_but"].',
				facebookAppID:"'.$atts["facebook_app_id"].'",
				facebookShareTitle:"'.$atts["facebook_share_title"].'",
				facebookShareDescription:"'.$atts["facebook_share_description"].'",
				showTwitterBut:'.$atts["show_twitter_but"].',
				showInfoBut:'.$atts["show_info_but"].',
				showDownloadBut:'.$atts["show_download_but"].',
				showPlaylistBut:'.$atts["show_playlist_but"].',
				showFullscreenBut:'.$atts["show_fullscreen_but"].',
				showShuffleBut:'.$atts["show_shuffle_but"].',
				showNextPrevBut:'.$atts["show_next_prev_but"].',
				borderColor:"'.$atts["border_color"].'",
				playlistWidth:'.$atts["playlist_width"].',
				pathToDownloadFile:"'.plugins_url("universal_video_player/", __FILE__).'",
				absUrl:"'.plugins_url("universal_video_player/", __FILE__).'",
				suggestedQuality:"'.$atts["suggestedQuality"].'",
				controlsBgFullScreenColor:"'.$atts["controls_bg_full_screen_color"].'",
				playlistScrollerBgColorOFF:"'.$atts["playlist_scroller_bg_color_off"].'",
				playlistScrollerBgColorON:"'.$atts["playlist_scroller_bg_color_on"].'",
				numberOfThumbsPerScreen:'.$atts["number_of_thumbs_per_screen"].',
				logoImagePath:"'.$the_logo[0].'",
				logoLink:"'.$atts["logo_link"].'",
				logoTarget:"'.$atts["logo_target"].'",
				showLogo:'.$atts["show_logo"].',
				autoPlayFirstVideo:'.$atts["auto_play_first_video"].',
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
				showPlaylistOnInit:'.$atts["show_playlist_on_init"].',
				categoryRecordBgOffColor:"'.$atts["category_record_bg_off_color"].'",
				categoryRecordBgOnColor:"'.$atts["category_record_bg_on_color"].'",
				categoryRecordBottomBorderOffColor:"'.$atts["category_record_bottom_border_off_color"].'",
				categoryRecordBottomBorderOnColor:"'.$atts["category_record_bottom_border_on_color"].'",
				categoryRecordTextOffColor:"'.$atts["category_record_text_off_color"].'",
				categoryRecordTextOnColor:"'.$atts["category_record_text_on_color"].'",
				firstCateg:"'.$atts["first_categ"].'",
				selectedCategBg:"'.$atts["selected_categ_bg"].'",
				selectedCategOffColor:"'.$atts["selected_categ_off_color"].'",
				selectedCategOnColor:"'.$atts["selected_categ_on_color"].'",
				searchAreaBg:"'.$atts["search_area_bg"].'",
				searchInputText:"'.$atts["search_input_text"].'",
				searchInputBg:"'.$atts["search_input_bg"].'",
				searchInputBorderColor:"'.$atts["search_input_border_color"].'",
				searchInputTextColor:"'.$atts["search_input_text_color"].'",
				topTitleColor:"'.$atts["top_title_color"].'",
				timerColor:"'.$atts["timer_color"].'",
				bufferEmptyColor:"'.$atts["buffer_empty_color"].'",
				bufferFullColor:"'.$atts["buffer_full_color"].'",
				seekbarColor:"'.$atts["seekbar_color"].'",
				volumeOffColor:"'.$atts["volume_off_color"].'",
				volumeOnColor:"'.$atts["volume_on_color"].'",
				playButColorOff:"'.$atts["play_but_color_off"].'",
				playButColorOn:"'.$atts["play_but_color_on"].'",
				googleTrakingOn:'.$atts["google_traking_on"].',
				googleTrakingCode:"'.$atts["google_traking_code"].'",
				getYouTubeData:'.$atts["get_youtube_data"].',
				pathToAjaxFiles:"'.plugins_url("universal_video_player/", __FILE__).'",
				origThumbImgW:'.$atts["orig_thumb_img_w"].',
				origThumbImgH:'.$atts["orig_thumb_img_h"].'


			});
		});
	</script>	
    '.$new_div_start.'<div id="universal_video_player_'.$atts["id"].'" style="display:none;">
                <ul class="universal_video_player_list">'.$playlist_str.' </ul>    
    </div><br style="clear:both;">'.$new_div_end;
	
	return str_replace("\r\n", '', $aux_content);	   
	   
	}
	
?>