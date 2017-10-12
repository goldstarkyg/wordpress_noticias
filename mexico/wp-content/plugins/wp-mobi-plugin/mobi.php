<?php
/*
  Plugin Name: Mobi by Phpbits
  Plugin URI: http://mobi-wp.com/
  Description: <strong>Mobile First Responsive WordPress Mobile Menu Plugin</strong>. Upgrade your users' website navigation experience to improve over all website interaction.
  Author: phpbits
  Version: 3.0
  Author URI: https://phpbits.net/
 */

//avoid direct calls to this file

if ( !function_exists('add_action') ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

define( 'MOBI_VERSION', '3.0' );

$mobiwp_fonts = array(
          'fontawesome'  =>  __('Font Awesome', 'mobiwp'),
          'ionicons'     =>  __('Ionicons', 'mobiwp')
);
$mobiwp_socials = array(
    'facebook'      =>    __('Facebook', 'mobiwp'),
    'twitter'       =>    __('Twitter', 'mobiwp'),
    'instagram'     =>    __('Instagram', 'mobiwp'),
    'pinterest'     =>    __('Pinterest', 'mobiwp'),
    'googleplus'    =>    __('Google+', 'mobiwp'),
    'linkedin'      =>    __('Linkedin', 'mobiwp'),
    'delicious'     =>    __('Delicious', 'mobiwp'),
    'youtube'       =>    __('Youtube', 'mobiwp'),
    'vimeo'         =>    __('Vimeo', 'mobiwp'),
    'dribbble'      =>    __('Dribbble', 'mobiwp'),
);

/*##################################
  REQUIRE
################################## */
require_once( dirname( __FILE__ ) . '/admin/defaults.setting.php');
require_once( dirname( __FILE__ ) . '/lib/includes/fontawesome.array.php');
require_once( dirname( __FILE__ ) . '/lib/includes/ionicons.array.php');
require_once( dirname( __FILE__ ) . '/core/functions.menu.walker.php');
require_once( dirname( __FILE__ ) . '/admin/functions.ajax.php');
require_once( dirname( __FILE__ ) . '/admin/functions.options.php');
require_once( dirname( __FILE__ ) . '/core/functions.enqueue.php');
require_once( dirname( __FILE__ ) . '/core/functions.display.php');
require_once( dirname( __FILE__ ) . '/admin/functions.extras.php');

register_activation_hook( __FILE__, 'mobiwp_defaults' );

/*##################################
  SETTINGS OPTION
################################## */
function mobiwp_generalOption(){
  return (object) get_option( 'mobiwp_general_settings' );
}
function mobiwp_popupOption(){
  return (object) get_option( 'mobiwp_popup_settings' );
}
function mobiwp_appearanceOption(){
  return (object) get_option( 'mobiwp_appearance_settings' );
}
function mobiwp_socialOption(){
  return (object) get_option( 'mobiwp_social_settings' );
}
function mobiwp_fontOption(){
  return (object) get_option( 'mobiwp_font_settings' );
}
function mobiwp_otherOption(){
  return (object) get_option( 'mobiwp_other_settings' );
}
function mobiwp_logoOption(){
  return (object) get_option( 'mobiwp_logo_settings' );
}
?>
