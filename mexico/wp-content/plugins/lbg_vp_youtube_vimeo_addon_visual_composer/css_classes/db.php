<?php

	$lbg_classes_collate = ' COLLATE utf8_general_ci';
	
	$sql3 = "CREATE TABLE IF NOT EXISTS `". $wpdb->prefix . "lbg_classes` (
	  `id` int(2) unsigned NOT NULL auto_increment,
	  `addon_name` varchar(255),
	  `css_styles` text,
	  `css_styles_orig` text,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8";	
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql3.$lbg_classes_collate);
	
	//initialize lbg_classes
	$rows_count = $wpdb->get_var( "SELECT COUNT(*) FROM ". $wpdb->prefix ."lbg_classes WHERE addon_name=".$lbg_addon_name.";" );
	if (!$rows_count) {
		$wpdb->insert( 
			$wpdb->prefix . "lbg_classes", 
			array( 
				'addon_name' => $lbg_addon_name,
				'css_styles' => $general_param['css_styles_const'],
				'css_styles_orig' => $general_param['css_styles_const']
			), 
			array(
				'%s',
				'%s'			
			) 
		);	
	}
	//lbg_classes end	
	

?>