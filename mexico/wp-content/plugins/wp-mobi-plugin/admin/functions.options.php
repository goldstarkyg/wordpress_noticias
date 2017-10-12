<?php
/*
 * GilidPanel Admin Settings
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

class Settings_API_Tabs_WPMOBIPLUGIN_Plugin {

	/*
	 * For easier overriding we declared the keys
	 * here as well as our tabs array which is populated
	 * when registering settings
	 */
	private $general_settings_key 		= 'mobiwp_general_settings';
	private $popup_settings_key 		= 'mobiwp_popup_settings';
	private $appearance_settings_key 	= 'mobiwp_appearance_settings';
	private $social_settings_key 		= 'mobiwp_social_settings';
	private $font_settings_key 			= 'mobiwp_font_settings';
	private $logo_settings_key 			= 'mobiwp_logo_settings'; //added on version 2.0
	private $other_settings_key 		= 'mobiwp_other_settings';
	private $plugin_options_key 		= 'mobiwp_plugin_options';
	private $wpmenus;
	private $plugin_settings_tabs 		= array();

	/**
    * @var array $options for the options for this plugin
    */
    var $options 						= array();
	var $api_key 						= '?key=AIzaSyBUXYMZFOjCaIQc292tYCu3e03-_yHD4NA';
	var $api_url 						= 'https://www.googleapis.com/webfonts/v1/webfonts';
	var $gf_data_option_name 			= "mobiwp_googlefonts_data";
	var $gf_fonts_file 					= 'webfonts.php';


	/*
	 * Fired during plugins_loaded (very very early),
	 * so don't miss-use this, only actions and filters,
	 * current ones speak for themselves.
	 */
	function __construct() {
		add_action( 'init', array( &$this, 'mobiwp_load_settings' ) );
		add_action( 'admin_init', array( &$this, 'mobiwp_register_general_settings' ) );
		add_action( 'admin_init', array( &$this, 'mobiwp_register_popup_settings' ) );
		add_action( 'admin_init', array( &$this, 'mobiwp_register_appearance_settings' ) );
		add_action( 'admin_init', array( &$this, 'mobiwp_register_social_settings' ) );
		add_action( 'admin_init', array( &$this, 'mobiwp_register_font_settings' ) );
		add_action( 'admin_init', array( &$this, 'mobiwp_register_logo_settings' ) ); //added on version 2.0
		add_action( 'admin_init', array( &$this, 'mobiwp_register_other_settings' ) );
		add_action( 'admin_menu', array( &$this, 'mobiwp_add_admin_menus' ) );
		add_action( 'admin_init' , array(&$this,'mobiwp_on_load_page' ));
		$this->mobiwp_update_font_cache();
	}

	function mobiwp_on_load_page(){
		wp_register_style( 'select2-css', plugins_url( 'lib/js/select2-3.5.0/select2.css' , dirname(__FILE__) )  );
		wp_register_style( 'mobiwp-css', plugins_url( 'lib/css/options.css' , dirname(__FILE__) )  );
		wp_register_style( 'fontawesome-css', plugins_url( 'lib/fonts/font-awesome-4.1.0/css/font-awesome.min.css' , dirname(__FILE__) )  );
		wp_register_style( 'ionicons-css', plugins_url( 'lib/fonts/ionicons-1.5.2/css/ionicons.min.css' , dirname(__FILE__) )  );

		wp_register_script(
			'jquery-select2',
			plugins_url( 'lib/js/select2-3.5.0/select2.min.js' , dirname(__FILE__) ),
			array( 'jquery' ),
			'',
			true
		);
		wp_register_script(
			'jquery-mobi-options',
			plugins_url( 'lib/js/jquery.mobi.options.js' , dirname(__FILE__) ),
			array( 'jquery-ui-sortable', 'jquery', 'jquery-ui-draggable', 'jquery-ui-droppable', 'wp-color-picker' ),
			'',
			true
		);
		wp_localize_script( 'jquery-mobi-options', 'mobiwpAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		if( isset( $_GET['page'] ) && $_GET['page'] == 'mobiwp_plugin_options' ){
			wp_enqueue_media(); //version 2
		}

		wp_enqueue_style( 'select2-css' );
		wp_enqueue_style( 'fontawesome-css' );
		wp_enqueue_style( 'ionicons-css' );
		wp_enqueue_style( 'mobiwp-css' );
		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_script( 'common' );
		wp_enqueue_script( 'wp-lists' );
		wp_enqueue_script( 'postbox' );
		wp_enqueue_script( 'jquery-select2' );
		wp_enqueue_script( 'jquery-mobi-options' );
		wp_enqueue_script( 'jquery-ui-accordion' );

		add_meta_box( 'mobiwp-draggable-right', __( 'Drag Menu Items Here','mobiwp' ), array( &$this, 'mobiwp_right_draggable' ), $this->plugin_options_key, 'normal', 'core' );
	}

	/*
	 * Loads both the general and advanced settings from
	 * the database into their respective arrays. Uses
	 * array_merge to merge with default values if they're
	 * missing.
	 */
	function mobiwp_load_settings() {
		$this->wpmenus 				= get_terms( 'nav_menu', array( 'hide_empty' => true ) );
		$this->general_settings 	= (array) get_option( $this->general_settings_key );
		$this->popup_settings 		= (array) get_option( $this->popup_settings_key );
		$this->appearance_settings 	= (array) get_option( $this->appearance_settings_key );
		$this->social_settings 		= (array) get_option( $this->social_settings_key );
		$this->font_settings 		= (array) get_option( $this->font_settings_key );
		$this->logo_settings 		= (array) get_option( $this->logo_settings_key ); //added on version 2.0
		$this->other_settings 		= (array) get_option( $this->other_settings_key );
	}

	/*
	 * Registers the general settings via the Settings API,
	 * appends the setting to the tabs array of the object.
	 */
	function mobiwp_register_general_settings() {
		$this->plugin_settings_tabs[$this->general_settings_key] = __( 'Main Menu', 'mobiwp' );

		register_setting( $this->general_settings_key, $this->general_settings_key );
		add_settings_section( 'general_section', __( 'Menu Options', 'mobiwp' ), array( &$this, 'mobiwp_general_options_section' ), $this->general_settings_key );
	}

	function mobiwp_general_options_section(){ ?>
		<p><?php _e( 'This options provides general settings to control the mobile navigation.','mobiwp' );?></p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-enable"><?php _e( 'Enable Mobi','mobiwp' ); ?></label></th>
					<td>
						<input type="checkbox" id="mobiwp-enable" name="<?php echo $this->general_settings_key; ?>[enable]" value="1" <?php echo ( isset($this->general_settings['enable']) ) ? 'checked=""checked' : ''; ?> />
						<small><?php _e( 'Check to Show Mobile Menu on your Website','mobiwp' );?></small>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-scroll_animation"><?php _e( 'Enable Scrolling Animation on Mobile','mobiwp' ); ?></label></th>
					<td>
						<input type="checkbox" id="mobiwp-scroll_animation" name="<?php echo $this->general_settings_key; ?>[scroll_animation]" value="1" <?php echo ( isset($this->general_settings['scroll_animation']) ) ? 'checked=""checked' : ''; ?> />
						<small><?php _e( 'Check enable show/hide animation when user scrolls the website using Mobile Devices','mobiwp' );?></small>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-scroll_animation_desktop"><?php _e( 'Enable Scrolling Animation on Desktop','mobiwp' ); ?></label></th>
					<td>
						<input type="checkbox" id="mobiwp-scroll_animation_desktop" name="<?php echo $this->general_settings_key; ?>[scroll_animation_desktop]" value="1" <?php echo ( isset($this->general_settings['scroll_animation_desktop']) ) ? 'checked=""checked' : ''; ?> />
						<small><?php _e( 'Check enable show/hide animation when user scrolls the website on Desktop','mobiwp' );?></small>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-position"><?php _e( 'Position on Mobile Devices','mobiwp' ); ?></label></th>
					<td>
						<select name="<?php echo $this->general_settings_key; ?>[position]" id="mobiwp-position" class="mobiwp-select2">
							<option value="bottom" <?php if( isset($this->general_settings['position']) && 'bottom' == $this->general_settings['position'] ){ echo 'selected="selected"'; }?>><?php _e( 'Bottom','mobiwp' ); ?></option>
							<option value="top" <?php if( isset($this->general_settings['position']) && 'top' == $this->general_settings['position'] ){ echo 'selected="selected"'; }?>><?php _e( 'Top','mobiwp' ); ?></option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-position-desktop"><?php _e( 'Position on Desktop','mobiwp' ); ?></label></th>
					<td>
						<select name="<?php echo $this->general_settings_key; ?>[position_desktop]" id="mobiwp-position-desktop" class="mobiwp-select2">
							<option value="hidden" <?php if( isset($this->general_settings['position_desktop']) && 'hidden' == $this->general_settings['position_desktop'] ){ echo 'selected="selected"'; }?>><?php _e( 'Hidden','mobiwp' ); ?></option>
							<option value="top-center" <?php if( isset($this->general_settings['position_desktop']) && 'top-center' == $this->general_settings['position_desktop'] ){ echo 'selected="selected"'; }?>><?php _e( 'Top Center','mobiwp' ); ?></option>
							<option value="top-right" <?php if( isset($this->general_settings['position_desktop']) && 'top-right' == $this->general_settings['position_desktop'] ){ echo 'selected="selected"'; }?>><?php _e( 'Top Right','mobiwp' ); ?></option>
							<option value="top-left" <?php if( isset($this->general_settings['position_desktop']) && 'top-left' == $this->general_settings['position_desktop'] ){ echo 'selected="selected"'; }?>><?php _e( 'Top Left','mobiwp' ); ?></option>
							<option value="right-center" <?php if( isset($this->general_settings['position_desktop']) && 'right-center' == $this->general_settings['position_desktop'] ){ echo 'selected="selected"'; }?>><?php _e( 'Right Center','mobiwp' ); ?></option>
							<option value="left-center" <?php if( isset($this->general_settings['position_desktop']) && 'left-center' == $this->general_settings['position_desktop'] ){ echo 'selected="selected"'; }?>><?php _e( 'Left Center','mobiwp' ); ?></option>
							<option value="bottom-center" <?php if( isset($this->general_settings['position_desktop']) && 'bottom-center' == $this->general_settings['position_desktop'] ){ echo 'selected="selected"'; }?>><?php _e( 'Bottom Center','mobiwp' ); ?></option>
							<option value="bottom-right" <?php if( isset($this->general_settings['position_desktop']) && 'bottom-right' == $this->general_settings['position_desktop'] ){ echo 'selected="selected"'; }?>><?php _e( 'Bottom Right','mobiwp' ); ?></option>
							<option value="bottom-left" <?php if( isset($this->general_settings['position_desktop']) && 'bottom-left' == $this->general_settings['position_desktop'] ){ echo 'selected="selected"'; }?>><?php _e( 'Bottom Left','mobiwp' ); ?></option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-main"><?php _e( 'Choose Main Menu','mobiwp' ); ?></label></th>
					<td>
						<select id="mobiwp-main" name="<?php echo $this->general_settings_key; ?>[main_menu]">
							<option value=""><?php _e( 'Choose Navigation Menu','mobiwp' );?></option>
							<?php foreach ( $this->wpmenus as $menu ) {
								$selected = '';
								if( isset($this->general_settings['main_menu']) && $menu->term_id == $this->general_settings['main_menu'] ){
									$selected = 'selected="selected"';
								}
							?>
								<option value="<?php echo $menu->term_id;?>" <?php echo $selected;?>><?php echo $menu->name;?></option>
							<?php } ?>
						</select>
						<!-- <small><?php _e( 'Assign Navigation Menu that you want to show.','mobiwp' );?></small><br /> -->
					</td>
				</tr>
			</tbody>
		</table>
		<div class="mobiwp-menu-wrap" <?php if( isset($this->general_settings['main_menu']) && !empty($this->general_settings['main_menu']) ){ echo 'style="display:block;"'; } ?>>
			<div class="mobiwp-draggable-left">
				<?php echo mobiwp_get_menu_items( $this->general_settings['main_menu'] ); ?>
			</div>

			<div class="mobiwp-draggable-right">
				<?php do_meta_boxes( $this->plugin_options_key, 'normal', '' ); ?>
			</div>
			<div class="mobiwp-clear"></div>
		</div>
		<script>
			jQuery(document).ready(function(){
				// jQuery("#mobiwp-main").select2();
			});
		</script>
	<?php }

	function mobiwp_right_draggable(){?>
		<div id="mobiwp-droppable">
			<p class="description mobi-description"><?php _e( 'Drag Menu Items you want to Show on Main Mobile Navigation.' );?><br /><?php _e( '<strong>Note: ONLY THREE(3) items will be show on the menu navigation.</strong>' );?></p>
			<div class="mobiwp-sortable">
				<?php if( isset($this->general_settings['main_menu']) && !empty($this->general_settings['main_menu']) ){
					if( isset($this->general_settings['menu']) && !empty($this->general_settings['menu']) ){
						foreach ($this->general_settings['menu'] as $key => $value) {
							$menu_data = array(
									'url'				=>	$this->general_settings['url'][$key],
									'target'			=>	$this->general_settings['target'][$key],
									'title'				=>	$this->general_settings['label'][$key],
									'icon_group'		=>	$this->general_settings['icon_group'][$key],
									'fontawesome'		=>	$this->general_settings['fontawesome'][$key],
									'ionicons'			=>	$this->general_settings['ionicons'][$key],
									'icon_size'			=>	$this->general_settings['icon_size'][$key],
									'label_font_size'	=>	$this->general_settings['label_font_size'][$key],
									'private'			=>	$this->general_settings['private'][$key],
									'visibility'		=>	$this->general_settings['visibility'][$key],
									'usage'				=>	'init'
								);
							mobiwp_menu_block( $menu_data, 'saved' );
						}
					}
				}else{?>
				<div class="mobiwp-placeholder mobiwp-widget-sortable">
					<div>
						<h3 class="hndle">&nbsp;</h3>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	<?php }

	/*
	 * Registers the animation settings via the Settings API,
	 * appends the setting to the tabs array of the object.
	 */
	function mobiwp_register_popup_settings() {
		$this->plugin_settings_tabs[$this->popup_settings_key] = __( 'Pop-Up Navigation','mobiwp' );

		register_setting( $this->popup_settings_key, $this->popup_settings_key );
		add_settings_section( 'popup_section', 'Pop-Up Navigation Options', array( &$this, 'mobiwp_popup_section' ), $this->popup_settings_key );
	}
	function mobiwp_popup_section(){
		?>
		<p><?php _e( 'Select menu that you want to show on pop-up full navigation. Modify and Customize the content to fit your needs.' );?></p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-popup_label"><?php _e( 'Navigation Label','mobiwp' );?></label></th>
					<td>
						<input type="text" id="mobiwp-popup_label" name="<?php echo $this->popup_settings_key; ?>[popup_label]" value='<?php echo ( isset($this->popup_settings['popup_label']) && !empty($this->popup_settings['popup_label']) ) ? $this->popup_settings['popup_label'] : '';?>' />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-popup-disable"><?php _e( 'Disable Pop-Up Navigation' );?></label></th>
					<td>
						<input type="checkbox" id="mobiwp-popup-disable" name="<?php echo $this->popup_settings_key; ?>[disable]" value='1' <?php echo ( isset($this->popup_settings['disable']) && !empty($this->popup_settings['disable']) ) ? 'checked="checked"' : '';?> />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-search"><?php _e( 'Hide Search Field','mobiwp' );?></label></th>
					<td>
						<input type="checkbox" id="mobiwp-search" name="<?php echo $this->popup_settings_key; ?>[search]" value='1' <?php echo ( isset($this->popup_settings['search']) && !empty($this->popup_settings['search']) ) ? 'checked="checked"' : '';?> />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-popup-animation"><?php _e( 'Select Animation','mobiwp' ); ?></label></th>
					<td>
						<select id="mobiwp-popup-animation" class="mobiwp-select2" name="<?php echo $this->popup_settings_key; ?>[animation]">
							<option value=""><?php _e( 'Select Animation','mobiwp' );?></option>
							<option value="fade" <?php if( isset($this->popup_settings['animation']) && $this->popup_settings['animation'] == 'fade' ){ echo 'selected="selected"'; }?>><?php _e( 'Fade Effect', 'mobiwp' );?></option>
							<option value="corner" <?php if( isset($this->popup_settings['animation']) && $this->popup_settings['animation'] == 'corner' ){ echo 'selected="selected"'; }?>><?php _e( 'Corner Effect', 'mobiwp' );?></option>
							<option value="ginie" <?php if( isset($this->popup_settings['animation']) && $this->popup_settings['animation'] == 'ginie' ){ echo 'selected="selected"'; }?>><?php _e( 'Ginie Effect', 'mobiwp' );?></option>
							<option value="scale" <?php if( isset($this->popup_settings['animation']) && $this->popup_settings['animation'] == 'scale' ){ echo 'selected="selected"'; }?>><?php _e( 'Scale Effect', 'mobiwp' );?></option>
							<option value="slide-down" <?php if( isset($this->popup_settings['animation']) && $this->popup_settings['animation'] == 'slide-down' ){ echo 'selected="selected"'; }?>><?php _e( 'Slide Down Effect', 'mobiwp' );?></option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-main"><?php _e( 'Choose Main Menu','mobiwp' ); ?></label></th>
					<td>
						<select id="mobiwp-popup-menu" name="<?php echo $this->popup_settings_key; ?>[popup_menu]">
							<option value=""><?php _e( 'Choose Navigation Menu','mobiwp' );?></option>
							<?php foreach ( $this->wpmenus as $menu ) {
								$selected = '';
								if( isset($this->popup_settings['popup_menu']) && $menu->term_id == $this->popup_settings['popup_menu'] ){
									$selected = 'selected="selected"';
								}
							?>
								<option value="<?php echo $menu->term_id;?>" <?php echo $selected;?>><?php echo $menu->name;?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
			</tbody>
		</table><br />
		<div class="mobiwp-menu-customizer mobiwp_popup_section" <?php if(isset($this->popup_settings['popup_menu']) && !empty($this->popup_settings['popup_menu'])){ echo 'style="display:block;"'; }?> >
			<?php if(isset($this->popup_settings['popup_menu']) && !empty($this->popup_settings['popup_menu'])){
				$menu_items = wp_get_nav_menu_items( $this->popup_settings['popup_menu'], array(
						'order'                  => 'ASC',
						'orderby'                => 'menu_order',
						'post_status'            => 'publish',
						'output'                 => ARRAY_N,
						'output_key'             => 'menu_order',
					) );
				$items = array();
				foreach ($menu_items as $item) {
				    $items[$item->menu_item_parent][] = $item; // create new array
				}
				foreach ($items[0] as $parent) {
					$menu_data = $this->mobiwp_menu_data( $parent );

					if (isset($items[$parent->ID])) {
						mobiwp_menu_block($menu_data, 'popup' );
						echo '<div class="mobi-wp-children">';
							foreach ($items[$parent->ID] as $child) {
								$child_data = $this->mobiwp_menu_data( $child );
								mobiwp_menu_block($child_data, 'popup' );
								if (isset($items[$child->ID])) {
									echo '<div class="mobi-wp-grandchildren">';
									foreach ($items[$child->ID] as $grandchild) {
										$grand_data = $this->mobiwp_menu_data( $grandchild );
					                    mobiwp_menu_block($grand_data, 'popup' );
					                    if (isset($items[$grandchild->ID])) {
					                    	echo '<div class="mobi-wp-grandchildren">';
					                    	foreach ($items[$grandchild->ID] as $grandchildren) {
					                    		$grandchildren_data = $this->mobiwp_menu_data( $grandchildren );
					                    		mobiwp_menu_block($grandchildren_data, 'popup' );
					                    		if (isset($items[$grandchildren->ID])) {
					                    			echo '<div class="mobi-wp-notsupported">';
					                    				_e( '<em>This menu depth level is not supported. Thanks</em>','mobiwp' );
					                    			echo '</div>';
					                    		}
					                    	}
					                    	echo '</div>';
					                    }
					                }
									echo '</div>';
								}
							}
						echo '</div>';
					}else{
						mobiwp_menu_block($menu_data, 'popup' );
					}
				}
				// foreach ($menu_items as $item_key => $menu_item) {
				// 	$menu_data = $this->mobiwp_menu_data( $menu_item );

				// 	// if( $menu_item->menu_item_parent == 0 ){
				// 	// 	mobiwp_menu_block($menu_data, 'popup' );

				// 	// 	//Sorry I need to replicate the menu, there is no depth option for wp_get_nav_menu_items. This is a real BS
				// 	// 	echo '<div class="mobi-wp-children">';

				// 	// 	echo '</div>';
				// 	// }

				// 	$count++;
				// }
			}?>
		</div>
	<?php }

	function mobiwp_menu_data($menu_item){
		return array(
				'ID'				=>	$menu_item->ID,
				'url'				=>	isset($this->popup_settings['url'][$menu_item->ID]) ? $this->popup_settings['url'][$menu_item->ID] : $menu_item->url,
				'target'			=>	isset($this->popup_settings['target'][$menu_item->ID]) ? $this->popup_settings['target'][$menu_item->ID] : $menu_item->target,
				'title'				=>	isset($this->popup_settings['label'][$menu_item->ID]) ? $this->popup_settings['label'][$menu_item->ID] : $menu_item->title,
				'icon_group'		=>	isset($this->popup_settings['icon_group'][$menu_item->ID]) ? $this->popup_settings['icon_group'][$menu_item->ID] : '',
				'fontawesome'		=>	isset($this->popup_settings['fontawesome'][$menu_item->ID]) ? $this->popup_settings['fontawesome'][$menu_item->ID] : '',
				'ionicons'			=>	isset($this->popup_settings['ionicons'][$menu_item->ID]) ? $this->popup_settings['ionicons'][$menu_item->ID] : '',
				'icon_size'			=>	isset($this->popup_settings['icon_size'][$menu_item->ID]) ? $this->popup_settings['icon_size'][$menu_item->ID] : '',
				'label_font_size'	=>	isset($this->popup_settings['label_font_size'][$menu_item->ID]) ? $this->popup_settings['label_font_size'][$menu_item->ID] : '',
				'visibility'		=>	isset($this->popup_settings['visibility'][$menu_item->ID]) ? $this->popup_settings['visibility'][$menu_item->ID] : '',
			);
	}

	/*
	 * Registers the appearance settings via the Settings API,
	 * appends the setting to the tabs array of the object.
	 */
	function mobiwp_register_appearance_settings() {
		$this->plugin_settings_tabs[$this->appearance_settings_key] = __( 'Appearance','mobiwp' );

		register_setting( $this->appearance_settings_key, $this->appearance_settings_key );
		add_settings_section( 'appearance_section', 'Appearance Options', array( &$this, 'mobiwp_appearance_section' ), $this->appearance_settings_key );
	}
	function mobiwp_appearance_section(){
		if( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' ){
			delete_transient( 'mobiwp_styles' );
		}
	?>
	<p><?php _e( 'Provides display customization options to make the mobile navigation fit on your current installed themes.' );?></p>
	<hr />
	<h3><?php _e( 'Mobile Menu','mobiwp' );?></h3>
	<hr />
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-main-label"><?php _e( 'Hide Text Label' );?></label></th>
				<td>
					<input type="checkbox" id="mobiwp-appearance-main-label" name="<?php echo $this->appearance_settings_key; ?>[hide_label_main]" value='1' <?php echo ( isset($this->appearance_settings['hide_label_main']) && !empty($this->appearance_settings['hide_label_main']) ) ? 'checked="checked"' : '';?> />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-font-size-main"><?php _e( 'Text Label Font Size','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" size='2' id="mobiwp-appearance-font-size-main" name="<?php echo $this->appearance_settings_key; ?>[font_size_main]" value="<?php if(isset($this->appearance_settings['font_size_main']) && !empty($this->appearance_settings['font_size_main'])){ echo $this->appearance_settings['font_size_main']; }?>" />px
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-font-color-mainmain"><?php _e( 'Text Label Color','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" class="mobiwp-colorpicker" name="<?php echo $this->appearance_settings_key; ?>[font_color_main]" value="<?php if(isset($this->appearance_settings['font_color_main']) && !empty($this->appearance_settings['font_color_main'])){ echo $this->appearance_settings['font_color_main']; }?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-icon-main"><?php _e( 'Hide Icon' );?></label></th>
				<td>
					<input type="checkbox" id="mobiwp-appearance-icon-main" name="<?php echo $this->appearance_settings_key; ?>[hide_icon_main]" value='1' <?php echo ( isset($this->appearance_settings['hide_icon_main']) && !empty($this->appearance_settings['hide_icon_main']) ) ? 'checked="checked"' : '';?> />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-icon-size-main"><?php _e( 'Icon Font Size','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" size='2' id="mobiwp-appearance-icon-size-main" name="<?php echo $this->appearance_settings_key; ?>[icon_size_main]" value="<?php if(isset($this->appearance_settings['icon_size_main']) && !empty($this->appearance_settings['icon_size_main'])){ echo $this->appearance_settings['icon_size_main']; }?>" />px
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-icon-color-main"><?php _e( 'Icon Font Color','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" class="mobiwp-colorpicker" name="<?php echo $this->appearance_settings_key; ?>[icon_color_main]" value="<?php if(isset($this->appearance_settings['icon_color_main']) && !empty($this->appearance_settings['icon_color_main'])){ echo $this->appearance_settings['icon_color_main']; }?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-background-color"><?php _e( 'Background Color','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" class="mobiwp-colorpicker" name="<?php echo $this->appearance_settings_key; ?>[background_color_main]" value="<?php if(isset($this->appearance_settings['background_color_main']) && !empty($this->appearance_settings['background_color_main'])){ echo $this->appearance_settings['background_color_main']; }?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-background-color-hover"><?php _e( 'Hover Background Color','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" class="mobiwp-colorpicker" name="<?php echo $this->appearance_settings_key; ?>[hover_color_main]" value="<?php if(isset($this->appearance_settings['hover_color_main']) && !empty($this->appearance_settings['hover_color_main'])){ echo $this->appearance_settings['hover_color_main']; }?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-border-color-main"><?php _e( 'Border Color','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" class="mobiwp-colorpicker" name="<?php echo $this->appearance_settings_key; ?>[border_color_main]" value="<?php if(isset($this->appearance_settings['border_color_main']) && !empty($this->appearance_settings['border_color_main'])){ echo $this->appearance_settings['border_color_main']; }?>" />
				</td>
			</tr>
		</tbody>
	</table>

	<hr />
	<h3><?php _e( 'Pop-Up Navigation','mobiwp' );?></h3>
	<hr />
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-background-image"><?php _e( 'Background Image','mobiwp' ); ?></label><br />
					<small><?php _e( 'Upload your image or paste the image url on the textbox.', 'mobiwp' );?></small>
				</th>
				<td>
					<input type='button' class="button-primary mobiwp_media_upload" id="mobiwp-uploader" value="<?php _e ( 'Upload Image','mobiwp' ); ?>" data-uploader_title="<?php _e( 'Choose Image', 'gilidpanel' ); ?>" data-uploader_button_text="<?php _e( 'Use Image', 'gilidpanel' ); ?>" />
					<input type="button" class="button-secondary mobiwp_remove_image" value="<?php _e ( 'Remove Image','mobiwp' ); ?>" /> <br /><br />
					<input type="text" class="widefat" id="mobiwp_image_assigned" name="<?php echo $this->appearance_settings_key; ?>[background_image_popup]" value="<?php if(isset($this->appearance_settings['background_image_popup']) && !empty($this->appearance_settings['background_image_popup'])){ echo $this->appearance_settings['background_image_popup']; }?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-background-color"><?php _e( 'Background Color','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" class="mobiwp-colorpicker" name="<?php echo $this->appearance_settings_key; ?>[background_color_popup]" value="<?php if(isset($this->appearance_settings['background_color_popup']) && !empty($this->appearance_settings['background_color_popup'])){ echo $this->appearance_settings['background_color_popup']; }?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-hover-color"><?php _e( 'Hover Background Color','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" class="mobiwp-colorpicker" name="<?php echo $this->appearance_settings_key; ?>[hover_color_popup]" value="<?php if(isset($this->appearance_settings['hover_color_popup']) && !empty($this->appearance_settings['hover_color_popup'])){ echo $this->appearance_settings['hover_color_popup']; }?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-background-submenu"><?php _e( 'Submenu Background Color','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" class="mobiwp-colorpicker" name="<?php echo $this->appearance_settings_key; ?>[background_color_submenu]" value="<?php if(isset($this->appearance_settings['background_color_submenu']) && !empty($this->appearance_settings['background_color_submenu'])){ echo $this->appearance_settings['background_color_submenu']; }?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-border-color"><?php _e( 'Menu Border Color','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" class="mobiwp-colorpicker" name="<?php echo $this->appearance_settings_key; ?>[border_color_popup]" value="<?php if(isset($this->appearance_settings['border_color_popup']) && !empty($this->appearance_settings['border_color_popup'])){ echo $this->appearance_settings['border_color_popup']; }?>" />
				</td>
			</tr>
			<!-- since version 2.0 -->
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-title"><?php _e( 'Hide Title' );?></label></th>
				<td>
					<input type="checkbox" id="mobiwp-appearance-title" name="<?php echo $this->appearance_settings_key; ?>[hide_title_popup]" value='1' <?php echo ( isset($this->appearance_settings['hide_title_popup']) && !empty($this->appearance_settings['hide_title_popup']) ) ? 'checked="checked"' : '';?> />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-font-title"><?php _e( 'Title Font Size','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" size='2' name="<?php echo $this->appearance_settings_key; ?>[font_size_title_popup]" value="<?php if(isset($this->appearance_settings['font_size_title_popup']) && !empty($this->appearance_settings['font_size_title_popup'])){ echo $this->appearance_settings['font_size_title_popup']; }else{ echo '12'; }?>" />px
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-title-color"><?php _e( 'Title Text Color','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" class="mobiwp-colorpicker" name="<?php echo $this->appearance_settings_key; ?>[title_color_popup]" value="<?php if(isset($this->appearance_settings['title_color_popup']) && !empty($this->appearance_settings['title_color_popup'])){ echo $this->appearance_settings['title_color_popup']; }?>" />
				</td>
			</tr>
			<!-- end added 2.0 -->

			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-label"><?php _e( 'Hide Text Label' );?></label></th>
				<td>
					<input type="checkbox" id="mobiwp-appearance-label" name="<?php echo $this->appearance_settings_key; ?>[hide_label_popup]" value='1' <?php echo ( isset($this->appearance_settings['hide_label_popup']) && !empty($this->appearance_settings['hide_label_popup']) ) ? 'checked="checked"' : '';?> />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-font-size"><?php _e( 'Text Label Font Size','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" size='2' name="<?php echo $this->appearance_settings_key; ?>[font_size_popup]" value="<?php if(isset($this->appearance_settings['font_size_popup']) && !empty($this->appearance_settings['font_size_popup'])){ echo $this->appearance_settings['font_size_popup']; }?>" />px
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-font-color"><?php _e( 'Text Label Color','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" class="mobiwp-colorpicker" name="<?php echo $this->appearance_settings_key; ?>[font_color_popup]" value="<?php if(isset($this->appearance_settings['font_color_popup']) && !empty($this->appearance_settings['font_color_popup'])){ echo $this->appearance_settings['font_color_popup']; }?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-icon"><?php _e( 'Hide Icon' );?></label></th>
				<td>
					<input type="checkbox" id="mobiwp-appearance-icon" name="<?php echo $this->appearance_settings_key; ?>[hide_icon_popup]" value='1' <?php echo ( isset($this->appearance_settings['hide_icon_popup']) && !empty($this->appearance_settings['hide_icon_popup']) ) ? 'checked="checked"' : '';?> />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-icon-size"><?php _e( 'Icon Font Size','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" size="2" name="<?php echo $this->appearance_settings_key; ?>[icon_size_popup]" value="<?php if(isset($this->appearance_settings['icon_size_popup']) && !empty($this->appearance_settings['icon_size_popup'])){ echo $this->appearance_settings['icon_size_popup']; }?>" />px
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-icon-color"><?php _e( 'Icon Font Color','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" class="mobiwp-colorpicker" name="<?php echo $this->appearance_settings_key; ?>[icon_color_popup]" value="<?php if(isset($this->appearance_settings['icon_color_popup']) && !empty($this->appearance_settings['icon_color_popup'])){ echo $this->appearance_settings['icon_color_popup']; }?>" />
				</td>
			</tr>
			<!-- <tr valign="top">
				<th scope="row"><label for="mobiwp-appearance-icon-bgcolor"><?php _e( 'Icon Background Color','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" class="mobiwp-colorpicker" name="<?php echo $this->appearance_settings_key; ?>[icon_bgcolor]" value="<?php if(isset($this->appearance_settings['icon_bgcolor']) && !empty($this->appearance_settings['icon_bgcolor'])){ echo $this->appearance_settings['icon_bgcolor']; }?>" />
				</td>
			</tr> -->

		</tbody>
	</table>
	<hr />
	<?php }

	/*
	 * Registers the appearance settings via the Settings API,
	 * appends the setting to the tabs array of the object.
	 */
	function mobiwp_register_social_settings() {
		$this->plugin_settings_tabs[$this->social_settings_key] = __( 'Social Media','mobiwp' );

		register_setting( $this->social_settings_key, $this->social_settings_key );
		add_settings_section( 'social_section', 'Social Settings', array( &$this, 'mobiwp_social_section' ), $this->social_settings_key );
	}
	function mobiwp_social_section(){
		global $mobiwp_socials;
	?>
	<p><?php _e( 'Set Social Icon Links Display and Values.' );?></p>
	<hr />
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-social-hide"><?php _e( 'Hide Social Icons', 'mobiwp' );?></label></th>
				<td>
					<input type="checkbox" id="mobiwp-social-hide" name="<?php echo $this->social_settings_key; ?>[hide]" value='1' <?php echo ( isset($this->social_settings['hide']) && !empty($this->social_settings['hide']) ) ? 'checked="checked"' : '';?> />
				</td>
			</tr>
			<tr valign="top">
					<th scope="row"><label for="mobiwp-social_label"><?php _e( 'Navigation Label','mobiwp' );?></label></th>
					<td>
						<input type="text" id="mobiwp-social_label" name="<?php echo $this->social_settings_key; ?>[social_label]" value='<?php echo ( isset($this->social_settings['social_label']) && !empty($this->social_settings['social_label']) ) ? $this->social_settings['social_label'] : '';?>' />
					</td>
				</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-social-font-size"><?php _e( 'Social Icon Font Size' );?></label><br />
				<small><?php _e( 'Leave Blank to use default.','mobiwp' );?></small></th>
				<td>
					<input type="text" size="2" id="mobiwp-social-font-size" name="<?php echo $this->social_settings_key; ?>[font_size]" value="<?php echo ( isset($this->social_settings['font_size']) && !empty($this->social_settings['font_size']) ) ? $this->social_settings['font_size'] : '';?>" />px
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mobiwp-social-custom"><?php _e( 'Use Custom Color', 'mobiwp' );?></label></th>
				<td>
					<input type="checkbox" id="mobiwp-social-custom" name="<?php echo $this->social_settings_key; ?>[custom]" value='1' <?php echo ( isset($this->social_settings['custom']) && !empty($this->social_settings['custom']) ) ? 'checked="checked"' : '';?> />
				</td>
			</tr>
			<tr valign="top" class="for-social-custom" <?php echo ( isset($this->social_settings['custom']) && !empty($this->social_settings['custom']) ) ? '' : 'style="display:none;"';?>>
				<th scope="row"><label for="mobiwp-social-color"><?php _e( 'Social Icon Color','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" class="mobiwp-colorpicker" name="<?php echo $this->social_settings_key; ?>[color]" value="<?php if(isset($this->social_settings['color']) && !empty($this->social_settings['color'])){ echo $this->social_settings['color']; }?>" />
				</td>
			</tr>
			<tr valign="top" class="for-social-custom" <?php echo ( isset($this->social_settings['custom']) && !empty($this->social_settings['custom']) ) ? '' : 'style="display:none;"';?>>
				<th scope="row"><label for="mobiwp-social-bgcolor"><?php _e( 'Social Icon Background Color','mobiwp' ); ?></label>
				</th>
				<td>
					<input type="text" class="mobiwp-colorpicker" name="<?php echo $this->social_settings_key; ?>[bgcolor]" value="<?php if(isset($this->social_settings['bgcolor']) && !empty($this->social_settings['bgcolor'])){ echo $this->social_settings['bgcolor']; }?>" />
				</td>
			</tr>
		</tbody>
	</table>
	<hr />
	<h3><?php _e( 'Social Media','mobiwp' );?></h3>
	<div class="mobiwp-menu-customizer" style="display:block;">
		<div class="mobiwp-menu-wrap mobiwp-sortable" style="display:block;">
		<?php
		// print_r($this->social_settings);
		if(isset($this->social_settings['menu']) && !empty($this->social_settings['menu'])){
			$mobiwp_socials_dummy = $mobiwp_socials;
			$mobiwp_socials = $this->social_settings['name'];
		}
		foreach ($mobiwp_socials as $key => $social) {
			if(isset($this->social_settings['menu']) && !empty($this->social_settings['menu'])){
				$social_label = $mobiwp_socials_dummy[ $social ];
				$input_key = $social;
			}else{
				$social_label = $social;
				$input_key = $key;
			}
			$data = array(
					'ID'			=>		$input_key,
					'title'			=>		$social_label,
					'usage'			=>		'social',
					'key'			=>		$input_key
				);
			//set values
			if(isset($this->social_settings['url'][$key]) && !empty($this->social_settings['url'][$key]) ){
				$data['url'] = $this->social_settings['url'][$key];
			}
			if(isset($this->social_settings['icon_group'][$key]) && !empty($this->social_settings['icon_group'][$key]) ){
				$data['icon_group'] = $this->social_settings['icon_group'][$key];
			}
			if(isset($this->social_settings['fontawesome'][$key]) && !empty($this->social_settings['fontawesome'][$key]) ){
				$data['fontawesome'] = $this->social_settings['fontawesome'][$key];
			}
			if(isset($this->social_settings['ionicons'][$key]) && !empty($this->social_settings['ionicons'][$key]) ){
				$data['ionicons'] = $this->social_settings['ionicons'][$key];
			}
			if(isset($this->social_settings['icon_size'][$key]) && !empty($this->social_settings['icon_size'][$key]) ){
				$data['icon_size'] = $this->social_settings['icon_size'][$key];
			}
			if(isset($this->social_settings['visibility'][$key]) && !empty($this->social_settings['visibility'][$key]) ){
				$data['visibility'] = $this->social_settings['visibility'][$key];
			}
			if(isset($this->social_settings['target'][$key]) && !empty($this->social_settings['target'][$key]) ){
				$data['target'] = $this->social_settings['target'][$key];
			}
			mobiwp_menu_block( $data, 'social' );
		}
		?>
		</div>
	</div>
	<?php }

	/*
	 * Registers the font settings via the Settings API,
	 * appends the setting to the tabs array of the object.
	 */
	function mobiwp_register_font_settings() {
		$this->plugin_settings_tabs[$this->font_settings_key] = 'Google Fonts';

		register_setting( $this->font_settings_key, $this->font_settings_key );
		add_settings_section( 'font_section', 'Font Settings', array( &$this, 'mobiwp_font_section' ), $this->font_settings_key );
	}
	function mobiwp_font_section(){
		$fonts = get_option($this->gf_data_option_name);
		$fonts = json_decode($fonts); ?>
		<p><?php _e( 'Assign Google Webfonts to the Navigation Text Label.' );?></p>
		<hr />
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-font-main"><?php _e( 'Main Menu Font Family', 'mobiwp' );?></label></th>
					<td>
						<select name="<?php echo $this->font_settings_key; ?>[main]" id="mobiwp-font-main" class="mobiwp-select2">
							<option value="">Default</option>
							<?php foreach($fonts->items as $font){
								$selected = '';
								$normalized_name = $this->mobiwp_normalize_font_name($font->family);
								if($this->font_settings['main'] == $normalized_name)
									$selected = 'selected="selected"';
								echo '<option value="'. $normalized_name .'" '. $selected .'>'. $font->family .'</option>';
							}?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-font-popup"><?php _e( 'Popup Menu Font Family', 'mobiwp' );?></label></th>
					<td>
						<select name="<?php echo $this->font_settings_key; ?>[popup]" id="mobiwp-font-popup" class="mobiwp-select2">
							<option value="">Default</option>
							<?php foreach($fonts->items as $font){
								$selected = '';
								$normalized_name = $this->mobiwp_normalize_font_name($font->family);
								if($this->font_settings['popup'] == $normalized_name)
									$selected = 'selected="selected"';
								echo '<option value="'. $normalized_name .'" '. $selected .'>'. $font->family .'</option>';
							}?>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}


	/*
	 * Registers the font settings via the Settings API,
	 * appends the setting to the tabs array of the object.
	 */
	function mobiwp_register_logo_settings() {
		$this->plugin_settings_tabs[$this->logo_settings_key] = __( 'Logo', 'mobiwp' );

		register_setting( $this->logo_settings_key, $this->logo_settings_key );
		add_settings_section( 'logo_section', __( 'Logo', 'mobiwp' ), array( &$this, 'mobiwp_logo_section' ), $this->logo_settings_key );
	}
	function mobiwp_logo_section(){
		?>
		<p><?php _e( 'Added on version 2.0 to add logo on top of the Pop-up navigation', 'mobiwp' );?></p>
		<hr />
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-appearance-logo"><?php _e( 'Assign Logo','mobiwp' ); ?></label><br />
					<small><?php _e( 'Upload your logo or paste the image url on the textbox.', 'mobiwp' );?></small>
					</th>
					<td>
						<input type='button' class="button-primary mobiwp_media_upload" id="mobiwp-uploader" value="<?php _e ( 'Upload Logo','mobiwp' ); ?>" data-uploader_title="<?php _e( 'Choose Image', 'gilidpanel' ); ?>" data-uploader_button_text="<?php _e( 'Use Image', 'gilidpanel' ); ?>" />
						<input type="button" class="button-secondary mobiwp_remove_image" value="<?php _e ( 'Remove Logo','mobiwp' ); ?>" /> <br /><br />
						<input type="text" class="widefat" id="mobiwp_image_assigned" name="<?php echo $this->logo_settings_key; ?>[url]" value="<?php if(isset($this->logo_settings['url']) && !empty($this->logo_settings['url'])){ echo $this->logo_settings['url']; }?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-logo-alignment"><?php _e( 'Alignment', 'mobiwp' );?></label></th>
					<td>
						<select name="<?php echo $this->logo_settings_key; ?>[alignment]" id="mobiwp-logo-alignment" class="mobiwp-select2">
							<option value="center"><?php _e( 'Center', 'mobiwp' );?></option>
							<option value="left" <?php if(isset($this->logo_settings['alignment']) && $this->logo_settings['alignment'] == 'left' ){ echo 'selected="selected"'; }?>><?php _e( 'Left', 'mobiwp' );?></option>
							<option value="right" <?php if(isset($this->logo_settings['alignment']) && $this->logo_settings['alignment'] == 'right' ){ echo 'selected="selected"'; }?>><?php _e( 'Right', 'mobiwp' );?></option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/*
	 * Called during admin_menu, adds an options
	 * page under Settings called My Settings, rendered
	 * using the wplftr_plugin_options_page method.
	 */
	function mobiwp_add_admin_menus() {
		add_theme_page( __( 'Mobi Menus', 'mobiwp' ), __( 'Mobi Menus', 'mobiwp' ), 'manage_options', $this->plugin_options_key, array( &$this, 'mobiwp_plugin_options_page' ) );
	}

	/*
	 * Plugin Options page rendering goes here, checks
	 * for active tab and replaces key with the related
	 * settings key. Uses the wplftr_plugin_options_tabs method
	 * to render the tabs.
	 */
	function mobiwp_plugin_options_page() {
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->general_settings_key;
		?>
		<div class="wrap">
			<?php $this->mobiwp_plugin_options_tabs(); ?>
			<?php if( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' ):?>
				<div id="setting-error-settings_updated" class="updated settings-error">
					<p><strong><?php _e( 'Settings saved.','mobiwp' );?></strong></p>
				</div>
			<?php endif;?>
			<form method="post" action="options.php">
				<?php wp_nonce_field( 'update-options' ); ?>
				<?php settings_fields( $tab ); ?>
				<?php do_settings_sections( $tab ); ?>
				<?php
				if(function_exists( 'submit_button' )) { submit_button(); } else { ?>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'mobiwp' );?>"></p>
				<?php }?>
			</form>
		</div>
		<?php
	}

	/*
	 * Renders our tabs in the plugin options page,
	 * walks through the object's tabs array and prints
	 * them one by one. Provides the heading for the
	 * wplftr_plugin_options_page method.
	 */
	function mobiwp_plugin_options_tabs() {
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->general_settings_key; ?>
		<h1 class="mobiwp-mtitle"><span class="mobi-version"><?php _e( 'version', 'mobiwp' );?> <?php echo MOBI_VERSION;?></span><?php _e( 'Mobi - Menu Settings', 'mobiwp' );?></h1>

		<?php echo '<h2 class="nav-tab-wrapper">';
		foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
		}
		echo '</h2>';
	}

	/*
	 * Google Fonts
	 */
	function mobiwp_download_font_list($url){
		$fonts_json = null;

		if(function_exists( 'wp_remote_get' )){

			$response = wp_remote_get($url, array( 'sslverify' => false));

			if( is_wp_error($response)){

			}else{
				/* see if the response has an error */

				if(isset($response['body']) && $response['body']){

					if(strpos($response['body'], 'error' ) === false){
						/* no errors, good to go */
						$fonts_json = $response['body'];

					}
				}
			}
		}

		return $fonts_json;
	}
	function mobiwp_get_local_fonts(){
		$fonts = null;

		include( dirname( __FILE__ ) . '/../lib/fonts/' .$this->gf_fonts_file );

		return $fonts;
	}
	function mobiwp_update_font_cache(){
		$updated = false;
		$mobiwp_cache = get_option( 'mobiwp_cache' );
		$date = date( 'd-m-Y' );
		update_option( 'mobiwp_cache',$date);
		if($date != $mobiwp_cache){
			$fonts_json = $this->mobiwp_download_font_list($this->api_url);

			/* if we didn't get anything, try with api key */
			if(!$fonts_json){
				$fonts_json = $this->mobiwp_download_font_list($this->api_url.$this->api_key);
			}

			/* if still nothing and do not have a cache already, then get the local file instead */
			if(!$fonts_json){
				$fonts_json = $this->mobiwp_get_local_fonts();
			}

			if($fonts_json){
				/* put into option in WordPress */
				$updated = update_option($this->gf_data_option_name,$fonts_json);
			}

			return $updated;
		}
	}
	function mobiwp_normalize_font_name($name){
		return(str_replace(" ","-",trim($name)));
	}

	//end fonts

	/*
	 * Registers the other settings via the Settings API,
	 * appends the setting to the tabs array of the object.
	 */
	function mobiwp_register_other_settings() {
		$this->plugin_settings_tabs[$this->other_settings_key] = __( 'Other Options', 'mobiwp' );

		register_setting( $this->other_settings_key, $this->other_settings_key );
		add_settings_section( 'other_section', __( 'Other Options', 'mobiwp' ), array( &$this, 'mobiwp_other_options_section' ), $this->other_settings_key );
	}

	function mobiwp_other_options_section(){
		// echo '<pre>';
		// print_r($this->other_settings);
		// echo '</pre>';
		?>
		<p><?php _e( 'This section provides other options for your mobile menu.' );?></p>
		<hr />
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-class"><?php _e( 'Current Menu ID or Class', 'mobiwp' );?></label>
						<br /><small><?php _e( 'If you want to hide the current navigation, just add the menu id or class here.','mobiwp' );?></small>
					</th>
					<td>
						<input type="text" id="mobiwp-class" name="<?php echo $this->other_settings_key; ?>[class]" value='<?php echo ( isset($this->other_settings['class']) && !empty($this->other_settings['class']) ) ? $this->other_settings['class'] : '';?>' />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-opener-position"><?php _e( 'Opener Position','mobiwp' ) ?></label></th>
					<td>
						<select name="<?php echo $this->other_settings_key; ?>[position]" id="mobiwp-opener-position" class="mobiwp-select2">
							<option value="left" <?php if( isset($this->other_settings['position']) && 'left' == $this->other_settings['position'] ){ echo 'selected="selected"'; }?>>Left</option>
							<option value="right" <?php if( isset($this->other_settings['position']) && 'right' == $this->other_settings['position'] ){ echo 'selected="selected"'; }?>>Right</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-class"><?php _e( 'Menu Opener', 'mobiwp' );?></label>
						<br /><small><?php _e( 'Customize the Main Menu Icon Opener','mobiwp' );?></small>
					</th>
					<td>
						<div class="mobiwp-menu-customizer">
							<?php
							$menu_data = array(
									'ID'				=>	'opener',
									'title'				=>	(isset($this->other_settings['label']['opener'])) ? $this->other_settings['label']['opener'] : '',
									'icon_group'		=>	(isset($this->other_settings['icon_group']['opener'])) ? $this->other_settings['icon_group']['opener'] : '',
									'fontawesome'		=>	(isset($this->other_settings['fontawesome']['opener'])) ? $this->other_settings['fontawesome']['opener'] : '',
									'ionicons'			=>	(isset($this->other_settings['ionicons']['opener'])) ? $this->other_settings['ionicons']['opener'] : '',
									'icon_size'			=>	(isset($this->other_settings['icon_size']['opener'])) ? $this->other_settings['icon_size']['opener'] : '',
									'label_font_size'	=>	(isset($this->other_settings['label_font_size']['opener'])) ? $this->other_settings['label_font_size']['opener'] : '',
									'private'			=>	(isset($this->other_settings['private']['opener'])) ? $this->other_settings['private']['opener'] : '',
									'visibility'		=>	(isset($this->other_settings['visibility']['opener'])) ? $this->other_settings['visibility']['opener'] : '',
									'usage'				=>	'other'
								);
							mobiwp_menu_block( $menu_data, 'other' );
							?>
						</div>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mobiwp-class"><?php _e( 'Close Menu Icon', 'mobiwp' );?></label>
						<br /><small><?php _e( 'Customize the Main Menu Close Icon and Label','mobiwp' );?></small>
					</th>
					<td>
						<div class="mobiwp-menu-customizer">
							<?php
							$closer_data = array(
									'ID'				=>	'closer',
									'title'				=>	(isset($this->other_settings['label']['closer'])) ? $this->other_settings['label']['closer'] : '',
									'icon_group'		=>	(isset($this->other_settings['icon_group']['closer'])) ? $this->other_settings['icon_group']['closer'] : '',
									'fontawesome'		=>	(isset($this->other_settings['fontawesome']['closer'])) ? $this->other_settings['fontawesome']['closer'] : '',
									'ionicons'			=>	(isset($this->other_settings['ionicons']['closer'])) ? $this->other_settings['ionicons']['closer'] : '',
									'icon_size'			=>	(isset($this->other_settings['icon_size']['closer'])) ? $this->other_settings['icon_size']['closer'] : '',
									'label_font_size'	=>	(isset($this->other_settings['label_font_size']['closer'])) ? $this->other_settings['label_font_size']['closer'] : '',
									'private'			=>	(isset($this->other_settings['private']['closer'])) ? $this->other_settings['private']['closer'] : '',
									'visibility'		=>	(isset($this->other_settings['visibility']['closer'])) ? $this->other_settings['visibility']['closer'] : '',
									'usage'				=>	'other'
								);
							mobiwp_menu_block( $closer_data, 'other' );
							?>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}
};

// Initialize the plugin
add_action( 'plugins_loaded', create_function( '', '$settings_api_tabs_WPMOBIPLUGIN_plugin = new Settings_API_Tabs_WPMOBIPLUGIN_Plugin;' ) );

?>
