<?php
add_action('admin_menu', 'lbg_classes_menu'); // create menus
// adds the menu pages
function lbg_classes_menu() {
	add_menu_page('LBG Classes Interface', 'LBG CLASSES', 'edit_posts', 'LBG_CLASSES', 'lbg_classes_edit_classes_page',	str_replace ('css_classes/','',plugins_url('assets/images/plg_icon.png', __FILE__))  );
	//add_submenu_page( 'LBG_CLASSES', 'LBG CLASSES Edit Classes', 'Edit Classes', 'edit_posts', 'LBG_CLASSES', 'lbg_classes_edit_classes_page');
}


function lbg_classes_edit_classes_page()
{
	global $wpdb;
	global $lbg_css_path;
	global $general_param;
	global $lbg_addon_name;
	
	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_classes) WHERE addon_name = %s",$lbg_addon_name );
	
	if($_POST['Submit'] == 'Update') {
			$wpdb->update( 
				$wpdb->prefix .'lbg_classes', 
				array( 
				'css_styles' => $_POST['css_styles']
				), 
				array( 'addon_name' => $lbg_addon_name )
			);	?>
	<div id="message" class="updated"><p>Data saved!</p></div>
	<?php			
	}


	if($_GET['restore'] == 'yes') {
			
			$row2=$wpdb->get_row($safe_sql, ARRAY_A);
			$wpdb->update( 
				$wpdb->prefix .'lbg_classes', 
				array( 
				'css_styles' => $row2['css_styles_orig']
				), 
				array( 'addon_name' => $lbg_addon_name )
			);	?>
	<div id="message" class="updated"><p>Data restored!</p></div>
    
    <?php 
	}
	
	if($_POST['Submit'] == 'Update' || $_GET['restore'] == 'yes') {
		$row3=$wpdb->get_row($safe_sql, ARRAY_A);
		$filename=$lbg_css_path . 'video_player_youtube_vimeo/text_classes.css';
		$fp = fopen($filename, 'w+');
		$fwrite = fwrite($fp, $row3['css_styles']);
	}
	
	$row=$wpdb->get_row($safe_sql, ARRAY_A);

	include_once($lbg_css_path . 'tpl/classes.php');
}


