<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 Register Default Settings
*/
function mobiwp_defaults(){
	$general = array(
				'position'			=>	'bottom',
		);
	if(!get_option( 'mobiwp_general_settings')){
		add_option('mobiwp_general_settings', $general);
	}

	$popup = array(
				'popup_label'		=>	'Navigation',
				'animation'			=>	'scale'
		);
	if(!get_option( 'mobiwp_popup_settings')){
		add_option('mobiwp_popup_settings', $popup);
	}

	$social = array(
				'social_label'		=>	'Follow Us',
				'icon_group'		=>	array(
											'facebook'      =>    'fontawesome',
										    'twitter'     	=>    'fontawesome',
										    'instagram'     =>    'fontawesome',
										    'pinterest'     =>    'fontawesome',
										    'googleplus'    =>    'fontawesome',
										    'linkedin'      =>    'fontawesome',
										    'delicious'     =>    'fontawesome',
										    'youtube'     	=>    'fontawesome',
										    'vimeo'       	=>    'fontawesome',
										    'dribbble'      =>    'fontawesome',
										),
				'fontawesome'		=>	array(
											'facebook'      =>    'fa-facebook',
										    'twitter'     	=>    'fa-twitter',
										    'instagram'     =>    'fa-instagram',
										    'pinterest'     =>    'fa-pinterest-square',
										    'googleplus'    =>    'fa-google-plus',
										    'linkedin'      =>    'fa-linkedin',
										    'delicious'     =>    'fa-delicious',
										    'youtube'     	=>    'fa-youtube',
										    'vimeo'       	=>    'fa-vimeo-square',
										    'dribbble'      =>    'fa-dribbble',
										),
		);
	if(!get_option( 'mobiwp_social_settings')){
		add_option('mobiwp_social_settings', $social);
	}

	$appearance = array(
				'font_size_main'			=>	'8',
				'font_color_main'			=>	'#ffffff',
				'icon_size_main'			=>	'20',
				'icon_color_main'			=>	'#ffffff',
				// 'background_color_main'		=>	'#039e79',
				'background_color_main'		=>	'#3498db',
				'hover_color_main'			=>	'#0074a2',
				'background_color_submenu'	=>	'#191c21',
				'border_color_main'			=>	'#43494b',
				'background_color_popup'	=>	'#1d2127',
				'font_size_popup'			=>	'13',
				'font_color_popup'			=>	'#abb4be',
				'icon_size_popup'			=>	'18',
				'icon_color_popup'			=>	'#abb4be'
		);
	if(!get_option( 'mobiwp_appearance_settings')){
		add_option('mobiwp_appearance_settings', $appearance);
	}

	$other = array(
				'position'			=> 'right',
				'label'				=> array(
											'opener'	=>	'Menu',
											'closer'	=>	'Close'
										),
				'icon_group'		=> array(
											'opener'	=>	'fontawesome',
											'closer'	=>	'fontawesome'
										),
				'fontawesome'		=> array(
											'opener'	=>	'fa-align-justify',
											'closer'	=>	'fa-times'
										),
		);
	if(!get_option( 'mobiwp_other_settings')){
		add_option('mobiwp_other_settings', $other);
	}
}
?>
