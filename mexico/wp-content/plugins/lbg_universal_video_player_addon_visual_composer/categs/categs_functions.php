<?php
add_action('admin_menu', 'audio_player_pro_menu'); // create menus
// adds the menu pages
function audio_player_pro_menu() {
	add_menu_page('LBG Categories Interface', 'LBG CATEGORIES', 'edit_posts', 'LBG_CATEGORIES', 'lbg_categories_manage_categories_page',	str_replace ('categs/','',plugins_url('assets/images/plg_icon.png', __FILE__))  );
	add_submenu_page( 'LBG_CATEGORIES', 'LBG CATEGORIES Manage Categories', 'Manage Categories', 'edit_posts', 'LBG_CATEGORIES', 'lbg_categories_manage_categories_page');
	add_submenu_page( 'LBG_CATEGORIES', 'LBG CATEGORIES Manage Categories Add New', 'Add New', 'edit_posts', 'LBG_CATEGORIES_Add_New_Category', 'lbg_categories_add_new_category_page');
}


function lbg_categories_manage_categories_page()
{
	global $wpdb;
	global $lbg_categories_messages;
	global $lbg_categories_path;
	
	//delete player
	if (isset($_GET['id'])) {
		//delete from wp_lbg_categories
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."lbg_categories WHERE id = %d",$_GET['id']));
	}
	
	if (isset($_GET['categID']) && isset($_GET['origCategName'])) {
		$wpdb->update( 
				$wpdb->prefix .'lbg_categories', 
				array( 
				'categ' => $_POST['update_value']
				), 
				array( 'id' => $_GET['categID'] )
			);
	}
	
	
	$safe_sql="SELECT * FROM (".$wpdb->prefix ."lbg_categories) ORDER BY id";
	$result = $wpdb->get_results($safe_sql,ARRAY_A);	
	//echo $wpdb->last_query;
	include_once($lbg_categories_path . 'tpl/categories.php');

}

function lbg_categories_add_new_category_page()
{
	global $wpdb;
	global $lbg_categories_messages;
	global $lbg_categories_path;
	
	if($_POST['Submit'] == 'Add New') {
		$errors_arr=array();
		if (empty($_POST['categ']))
			$errors_arr[]=$lbg_categories_messages['empty_categ'];

		if (count($errors_arr)) { 
				include_once($lbg_categories_path . 'tpl/add_category.php'); ?>
				<div id="error" class="error"><p><?php echo implode("<br>", $errors_arr);?></p></div>
		  	<?php } else { // no errors
					$wpdb->insert( 
						$wpdb->prefix . "lbg_categories", 
						array( 
							'categ' => $_POST['categ']
						), 
						array( 
							'%s'			
						) 
					);	
					?>
						<div class="wrap">
							<div id="lbg_logo">
								<h2>Manage Categories - Add New Category</h2>
				 			</div>
							<div id="message" class="updated"><p><?php echo $lbg_categories_messages['data_saved'];?></p></div>
							<div>
								<p>&raquo; <a href="?page=LBG_CATEGORIES_Add_New_Category">Add New (category)</a></p>
								<p>&raquo; <a href="?page=LBG_CATEGORIES">Back to Manage Categories</a></p>
							</div>
						</div>	
		  	<?php }			
	} else {
		include_once($lbg_categories_path . 'tpl/add_category.php');
	}

}




add_action('wp_ajax_lbg_categories_update_category_record', 'lbg_categories_update_category_record_callback');

function lbg_categories_update_category_record_callback() {
	check_ajax_referer( 'lbg_categories_update_category_record-special-string', 'security' );
	global $wpdb;
	//$wpdb->show_errors();	
	
	
	if ($_POST['theCategory']!='') {
			$wpdb->update( 
				$wpdb->prefix .'lbg_categories', 
				array( 
				'categ' => strip_tags($_POST['theCategory'])
				), 
				array( 'id' => $_POST['theCategoryID'] )
			);	
	}
	
	die(); // this is required to return a proper result
}

?>