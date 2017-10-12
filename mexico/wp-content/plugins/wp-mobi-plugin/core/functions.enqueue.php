<?php
/*
 * Mobi Enqueue Scripts and Style
 * July. 30, 2014
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

function mobiwp_scripts_method() {
	wp_register_style( 'fontawesome-css', plugins_url( 'lib/fonts/font-awesome-4.1.0/css/font-awesome.min.css' , dirname(__FILE__) )  );
	wp_register_style( 'ionicons-css', plugins_url( 'lib/fonts/ionicons-1.5.2/css/ionicons.min.css' , dirname(__FILE__) )  );
	wp_register_style( 'nanoscroller-css', plugins_url( 'lib/css/nanoscroller.css' , dirname(__FILE__) ), array(), null );
	wp_register_style( 'mobiwp-css', plugins_url( 'lib/css/mobinav.css' , dirname(__FILE__) ), array(), null );

	//google fonts
	$fonts = array();
		if(isset(mobiwp_fontOption()->main)){
			$fonts[] = mobiwp_fontOption()->main;
		}
		if(isset(mobiwp_fontOption()->popup)){
			$fonts[] = mobiwp_fontOption()->popup;
		}
	$fonts = array_unique($fonts);

	foreach ($fonts as $key => $value) {
		if(!empty($value)){
			$googlefont = str_replace('-', '+', $value);
			wp_enqueue_style( 'mobiwp-'. $key .'-css', 'https://fonts.googleapis.com/css?family=' . $googlefont . ':300italic,400italic,600italic,700italic,400,600,700,300', array(), null );
		}
	}

	wp_register_script(
		'jquery-overthrow',
		plugins_url( 'lib/js/overthrow.min.js' , dirname(__FILE__) ),
		array( 'jquery' ),
		'',
		true
	);
	wp_register_script(
		'jquery-nanoscroller',
		plugins_url( 'lib/js/jquery.nanoscroller.min.js' , dirname(__FILE__) ),
		array( 'jquery' ),
		'',
		true
	);
	wp_register_script(
		'jquery-mobiwp',
//		plugins_url( 'lib/js/mobi.nav.min.js' , dirname(__FILE__) ),
		plugins_url( 'lib/js/mobi.nav.js' , dirname(__FILE__) ),
		array( 'jquery' ),
		'',
		true
	);

	wp_enqueue_script('jquery-overthrow');
	wp_enqueue_script('jquery-nanoscroller');
	wp_enqueue_script('jquery-mobiwp');

	wp_enqueue_style( 'fontawesome-css' );
	wp_enqueue_style( 'ionicons-css' );
	// wp_enqueue_style( 'nanoscroller-css' );
	wp_enqueue_style( 'mobiwp-css' );
}

add_action( 'wp_enqueue_scripts', 'mobiwp_scripts_method' );
?>
