<?php

	$lbg_categories_collate = ' COLLATE utf8_general_ci';
	
	$sql3 = "CREATE TABLE `". $wpdb->prefix . "lbg_categories` (
	  `id` int(10) unsigned NOT NULL auto_increment,
	  `categ` varchar(255) NOT NULL,
	  PRIMARY KEY  (`id`),
	  UNIQUE KEY `categ` ( `categ` )
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8";	
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql3.$lbg_categories_collate);
	
	//initialize categories
	$rows_count = $wpdb->get_var( "SELECT COUNT(*) FROM ". $wpdb->prefix ."lbg_categories;" );
	if (!$rows_count) {
		$wpdb->insert( 
			$wpdb->prefix . "lbg_categories", 
			array( 
				'categ' => 'ALL CATEGORIES'
			), 
			array(
				'%s'			
			) 
		);	
	}
	//categories end	
	

?>