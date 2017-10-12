<?php

add_action( 'wp_ajax_mobiwp_get_menu_items', 'mobiwp_get_menu_items' );
add_action( 'wp_ajax_nopriv_mobiwp_get_menu_items', 'mobiwp_get_menu_items' );
function mobiwp_get_menu_items( $term_id = null ){
	
	if(isset($_REQUEST['term_id'])){
		$term_id = $_REQUEST['term_id'];
	}
	

	if(!empty($term_id)){
	$menu_items = wp_get_nav_menu_items( $term_id );
	ob_start();
	foreach ($menu_items as $key => $menu_item) {
		$data = array(
				'title'		=>		$menu_item->title,
				'url'		=>		$menu_item->url	
		);
		if(isset($_REQUEST['usage']) && $_REQUEST['usage'] == 'popup' ){
			$data['ID'] = $menu_item->ID;
			mobiwp_menu_block($data, 'popup');
		}else{
			mobiwp_menu_block($data, '');
		}
		
	}
	if(isset($_REQUEST['term_id'])){
		echo ob_get_clean();
		die();
	}else{
		return ob_get_clean();
	}
	
	}
}
function mobiwp_menu_block($data, $action = null){ 
	global $mobiwp_fonts, $mobiwp_fontawesome, $mobiwp_ionicons;
	$name_key = 'mobiwp_general_settings';
	$id_key = (isset($data['ID'])) ? $data['ID'] : '';

	if(!empty($action)){
		$data['attr'] = 'name';
		if( $action == 'popup' ){
			$name_key = 'mobiwp_popup_settings';
		}else if( $action == 'social' ){
			$name_key = 'mobiwp_social_settings';
		}
		if( $action == 'other' ){
			$name_key = 'mobiwp_other_settings';
		}
	}else{
		$data['attr'] = 'data-name';
	}
?>
<?php if( !empty($action) && ($action == 'popup' || $action == 'other' ) ):?>
	<div class="mobiwp-menu-wrap" style="display:block;">
<?php endif;?>
	<div class="<?php if(!isset($data['usage'])):?>mobiwp-widget <?php endif;?>mobiwp-widget-sortable">
		<div class="mobiwp-widget-top">
			<a class="handlediv-mobiwp" title="Click to toggle"><br></a>
			<h3 class="hndle"><?php _e( $data['title'] , 'mobiwp' )?></h3>
		</div>
		<input type="hidden" <?php echo $data['attr'];?>="<?php echo $name_key;?>[menu][<?php echo $id_key;?>]" />
		<?php if(isset($data['usage']) && $data['usage'] == 'social'):?>
			<input type="hidden" <?php echo $data['attr'];?>="<?php echo $name_key;?>[name][<?php echo $id_key;?>]" value="<?php echo $data['key'];?>"/>
		<?php endif;?>
		<div class="mobiwp-widget-inside">
			<div class="mobiwp-inside-opts">
				<div class="mobiwp-icon-options">

					<?php if(empty($action) || $action != 'other'):?>
						<h3><?php _e('Add Link Url', 'mobiwp');?></h3>
						<input type="text" id="mobiwp-icon-url" class="widefat" <?php echo $data['attr'];?>="<?php echo $name_key;?>[url][<?php echo $id_key;?>]" value="<?php echo (isset($data['url']) && !empty($data['url'])) ? $data['url'] : '';?>" />
						<small style="color: #bbb;"><?php _e('Icon will not show if this field is blank', 'mobiwp')?></small>
						<table class="form-table">
							<tbody>
								<tr valign="top" class="mobiwp-menu-permission">
									<th scope="row"><label for="mobiwp-icon-link-target"><?php _e('Open to New Tab','mobiwp'); ?></label></th>
									<td>
										<input type="hidden" class="mobiwp-visibility-input" <?php echo $data['attr'];?>="<?php echo $name_key;?>[target][<?php echo $id_key;?>]" value='<?php echo (isset($data['target']) && !empty($data['target'])) ? $data['target'] : '0';?>' />
										<input type="checkbox" class="mobiwp-visibility-check" value="1" <?php echo (isset($data['target']) && !empty($data['target']) && ( $data['target'] == '1' || $data['target'] == '_blank' )) ? 'checked="checked"' : '';?> />
									</td>
								</tr>
							</tbody>
						</table>
						<br />
					<?php endif;?>

					<h3><?php _e('Icon Options', 'mobiwp');?></h3>
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row"><label for="mobiwp-icon-family"><?php _e('Select Font Icon Group','mobiwp'); ?></label></th>
								<td>
									<select class="mobiwp-icon-group" <?php echo $data['attr'];?>="<?php echo $name_key;?>[icon_group][<?php echo $id_key;?>]">
										<option></option>
										<?php
										foreach ($mobiwp_fonts as $key => $value) { 
											$selected = '';
											if(isset($data['icon_group']) && !empty($data['icon_group']) &&
												$data['icon_group'] == $key
												){
												$selected = 'selected="selected"';
											}
											?>
											<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>

							<tr valign="top" class="mobiwp-icon-selection" <?php if(isset($data['icon_group']) && !empty($data['icon_group'])){ echo 'style="visibility:visible; position:relative;"'; }?> >
								<th scope="row"><label for="mobiwp-icon-label"><?php _e('Select Icon','mobiwp'); ?></label></th>
								<td>
									<select class="mobiwp-icon-fontawesome" <?php echo $data['attr'];?>="<?php echo $name_key;?>[fontawesome][<?php echo $id_key;?>]" <?php if(isset($data['icon_group']) && !empty($data['icon_group']) && $data['icon_group'] == 'fontawesome'){ echo 'style="display:block;"'; }?>>
										<option></option>
										<?php foreach ($mobiwp_fontawesome as $f_key => $f_value) {
											$selected = '';
											if(isset($data['fontawesome']) && !empty($data['fontawesome']) &&
												$data['fontawesome'] == $f_value
												){
												$selected = 'selected="selected"';
											}
										?>
											<option value="<?php echo $f_value;?>" <?php echo $selected;?>><?php echo $f_value;?></option>
										<?php } ?>
									</select>
									<select class="mobiwp-icon-ionicons" <?php echo $data['attr'];?>="<?php echo $name_key;?>[ionicons][<?php echo $id_key;?>]" <?php if(isset($data['icon_group']) && !empty($data['icon_group']) && $data['icon_group'] == 'ionicons'){ echo 'style="display:block;"'; }?>>
										<option></option>
										<?php foreach ($mobiwp_ionicons as $i_key => $i_value) {
											$i_selected = '';
											if(isset($data['ionicons']) && !empty($data['ionicons']) &&
												$data['ionicons'] == $i_value
												){
												$i_selected = 'selected="selected"';
											}
										?>
											<option value="<?php echo $i_value;?>" <?php echo $i_selected;?>><?php echo $i_value;?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr valign="top" class="mobiwp-icon-font-size">
								<th scope="row"><label for="mobiwp-icon-font-size"><?php _e('Icon Font Size','mobiwp'); ?></label><br />
								<small><?php _e('Leave Blank to Use Default', 'mobiwp')?></small>
								</th>
								<td>
									<input type="text" size='2' <?php echo $data['attr'];?>="<?php echo $name_key;?>[icon_size][<?php echo $id_key;?>]" value="<?php echo (isset($data['icon_size']) && !empty($data['icon_size'])) ? $data['icon_size'] : '';?>" />px
								</td>
							</tr>
						</tbody>
					</table>

					<?php if(!isset($data['usage']) || (isset($data['usage']) && $data['usage'] != 'social') ): ?>
					<br />
					<h3><?php _e('Text Label Options', 'mobiwp');?></h3>
					<table class="form-table">
						<tbody>
							<tr valign="top" class="mobiwp-icon-text-size">
								<th scope="row"><label for="mobiwp-icon-text-size"><?php _e('Custom Text Label','mobiwp'); ?></label></th>
								<td>
									<input type="text" class="widefat" <?php echo $data['attr'];?>="<?php echo $name_key;?>[label][<?php echo $id_key;?>]" value="<?php echo (isset($data['title']) && !empty($data['title'])) ? $data['title'] : '';?>" />
								</td>
							</tr>
							<tr valign="top" class="mobiwp-icon-text-size">
								<th scope="row"><label for="mobiwp-icon-text-size"><?php _e('Text Font Size','mobiwp'); ?></label><br />
								<small><?php _e('Leave Blank to Use Default', 'mobiwp')?></small>
							</th>
								<td>
									<input type="text" size='2' <?php echo $data['attr'];?>="<?php echo $name_key;?>[label_font_size][<?php echo $id_key;?>]" value="<?php echo (isset($data['label_font_size']) && !empty($data['label_font_size'])) ? $data['label_font_size'] : '';?>" />px
								</td>
							</tr>
						</tbody>
					</table>
					<?php endif;?>
					<?php if(empty($id_key) || ($id_key != 'closer')):?>
					<br />
					<h3><?php _e('Visibility Options', 'mobiwp');?></h3>
					<table class="form-table">
						<tbody>
							<tr valign="top" class="mobiwp-menu-guest">
								<th scope="row"><label for="mobiwp-icon-text-size"><?php _e('Hide to Guest Users','mobiwp'); ?></label></th>
								<td>
									<input type="hidden" class="mobiwp-visibility-input" <?php echo $data['attr'];?>="<?php echo $name_key;?>[private][<?php echo $id_key;?>]" value='<?php echo (isset($data['private']) && !empty($data['private'])) ? $data['private'] : '0';?>' />
									<input type="checkbox" class="mobiwp-visibility-check" value="1" <?php echo (isset($data['private']) && !empty($data['private']) && $data['private'] == '1') ? 'checked="checked"' : '';?> />
								</td>
							</tr>
							<tr valign="top" class="mobiwp-menu-permission">
								<th scope="row"><label for="mobiwp-icon-text-size"><?php _e('Hide to Logged in Users','mobiwp'); ?></label></th>
								<td>
									<input type="hidden" class="mobiwp-visibility-input" <?php echo $data['attr'];?>="<?php echo $name_key;?>[visibility][<?php echo $id_key;?>]" value='<?php echo (isset($data['visibility']) && !empty($data['visibility'])) ? $data['visibility'] : '0';?>' />
									<input type="checkbox" class="mobiwp-visibility-check" value="1" <?php echo (isset($data['visibility']) && !empty($data['visibility']) && $data['visibility'] == '1') ? 'checked="checked"' : '';?> />
								</td>
							</tr>
						</tbody>
					</table>
					<?php endif;?>
				</div>
				<?php if(empty($action) ||  !in_array($action, array('other','social')) ):?>
					<a href="#" class="mobi-remove-this"><?php _e('Remove','mobiwp');?></a>
				<?php endif;?>
			</div>
		</div>
	</div>
<?php if( !empty($action) && ( $action == 'popup' || $action == 'other' ) ):?>
	</div>
<?php endif;?>
<?php } ?>