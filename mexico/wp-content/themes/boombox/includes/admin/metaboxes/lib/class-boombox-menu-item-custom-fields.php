<?php
/**
 * Add Custom Fields To Admin Menu Items
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

if ( ! class_exists( 'Boombox_Nav_Menu_Item_Custom_Fields' ) ) {

	class Boombox_Nav_Menu_Item_Custom_Fields {

		/**
		 * Constructor
		 *
		 * Initializes the plugin by setting localization, filters, and administration functions.
		 */
		public function __construct() {

			// add custom menu fields to menu
			add_filter( 'wp_setup_nav_menu_item', array( $this, 'add_custom_nav_fields' ), 20 );

			// save menu custom fields
			add_action( 'wp_update_nav_menu_item', array( $this, 'update_custom_nav_fields' ), 10, 3 );

			// edit menu walker
			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_walker' ), 10, 2 );

			// enqueue styles and scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ) );

		}

		/**
		 * Singleton
		 *
		 * @return Boombox_Nav_Menu_Item_Custom_Fields
		 */
		public static function get_instance() {
			static $Inst = null;
			if ( $Inst == null ) {
				$Inst = new self();
			}

			return $Inst;
		}

		/**
		 * Add custom fields to $item nav object
		 * in order to be used in custom Walker
		 *
		 * @param $menu_item
		 *
		 * @return mixed
		 */
		public function add_custom_nav_fields( $menu_item ) {
			$menu_item->icon = boombox_get_post_meta( $menu_item->ID, '_menu_item_icon', true );
			$menu_item->labels = boombox_get_post_meta( $menu_item->ID, '_menu_item_label' );

			return $menu_item;
		}

		/**
		 * Save menu custom fields
		 *
		 * @param $menu_id
		 * @param $menu_item_db_id
		 * @param $args
		 */
		public function update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
			// Check if element is properly sent
			if ( isset( $_REQUEST['menu-item-icon'] ) && is_array( $_REQUEST['menu-item-icon'] ) ) {
				$icon = $_REQUEST['menu-item-icon'][ $menu_item_db_id ];
				update_post_meta( $menu_item_db_id, '_menu_item_icon', $icon );
//				echo '<pre>'; var_dump( get_post_meta( $menu_item_db_id, '_menu_item_icon', true ) ); echo '</pre>'; die;
			}

			if ( isset( $_REQUEST['menu-item-label'] ) && is_array( $_REQUEST['menu-item-label'] ) ) {
				$label = $_REQUEST['menu-item-label'][ $menu_item_db_id ];
				update_post_meta( $menu_item_db_id, '_menu_item_label', $label );
			}
		}

		/**
		 * Define new Walker edit
		 *
		 * @param $walker
		 * @param $menu_id
		 *
		 * @return string
		 */
		public function edit_walker( $walker, $menu_id ) {
			$menu_locations = array_intersect_key( get_nav_menu_locations(), get_registered_nav_menus() );

			if( in_array( $menu_id, $menu_locations ) ){
				$location = array_search( $menu_id, $menu_locations );

				$customizable_menu_ids = array(
					'top_header_nav',
					'bottom_header_nav',
					'burger_top_nav',
					'profile_nav'
				);
				if( $location && in_array( $location, $customizable_menu_ids ) ){
					return 'Boombox_Walker_Nav_Menu_Edit';
				}
			}

			return 'Walker_Nav_Menu_Edit';
		}

		/**
		 * Load script
		 */
		public function enqueue_script() {
			global $current_screen;
			global $wp_scripts;
			if ( isset( $current_screen ) && 'nav-menus' === $current_screen->id ) {
				$protocol = is_ssl() ? 'https' : 'http';
				$ui       = $wp_scripts->query( 'jquery-ui-core' );
				$url      = "$protocol://ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/smoothness/jquery-ui.min.css";
				wp_enqueue_style( 'jquery-ui-smoothness', $url, array(), boombox_get_assets_version() );
				wp_enqueue_style( 'boombox-icomoon-style', BOOMBOX_THEME_URL . 'fonts/icon-fonts/icomoon/icons.min.css', array(), boombox_get_assets_version() );
				wp_enqueue_style( 'boombox-admin-meta-style', BOOMBOX_ADMIN_URL . 'metaboxes/css/boombox-metabox-style.css', array(), boombox_get_assets_version() );

				wp_enqueue_media();
				wp_enqueue_script( 'jquery-ui-selectmenu' );
				wp_enqueue_script( 'boombox-reaction-meta-scripts', BOOMBOX_ADMIN_URL . 'metaboxes/js/boombox-metabox-script.js', array( 'jquery' ), boombox_get_assets_version(), true );
			}
		}

		/**
		 * Get Admin Menu Item Field HTML
		 *
		 * @param $item
		 * @param $depth
		 * @param $args
		 *
		 * @return string
		 */
		public static function get_field( $item, $depth, $args ) {
			$field_html = '';
			// Icons
			$selected_icon = get_post_meta( $item->ID, '_menu_item_icon', true );
			$icons         = get_icomoon_icons_array();
			if ( is_array( $icons ) && count( $icons ) > 0 ) {
				$field_html .= '<p class="field-icons description description-wide ">';
				$field_html .= '<label for="edit-menu-item-icon-' . $item->ID . '">';
				$field_html .= esc_html( 'Icons', 'boombox' ) . '<br />';
				$field_html .= '<select id="edit-menu-item-icon-' . $item->ID . '" name="menu-item-icon[' . $item->ID . ']" class="widefat code edit-menu-item-icon">';
				$field_html .= '<option value="" data-class="">' . esc_html__( 'Select icon', 'boombox' ) . '</option>';
				foreach ( $icons as $icon ) {
					$selected = selected( $selected_icon, $icon['icon'], false );
					$field_html .= '<option value="' . esc_attr( $icon['icon'] ) . '" data-class="icon-' . esc_attr( $icon['icon'] ) . '" ' . $selected . '>' . esc_html( $icon['name'] ) . '</option>';
				}
				$field_html .= '</select>';
				$field_html .= '</label>';
				$field_html .= '</p>';
			}

			// Labels
			$selected_label = get_post_meta( $item->ID, '_menu_item_label', true );
			$labels         = static::get_labels_array();
			if ( is_array( $labels ) && count( $labels ) > 0 ) {
				$field_html .= '<p class="field-labels description description-wide ">';
				$field_html .= '<label for="edit-menu-item-label-' . $item->ID . '">';
				$field_html .= esc_html( 'Labels', 'boombox' ) . '<br />';
				$field_html .= '<select id="edit-menu-item-label' . $item->ID . '" name="menu-item-label[' . $item->ID . ']" class="widefat code edit-menu-item-label">';
				$field_html .= '<option value="" data-class="">' . esc_html__( 'Select label', 'boombox' ) . '</option>';
				foreach ( $labels as $label ) {
					$selected = selected( $selected_label, $label, false );
					$field_html .= '<option value="' . esc_attr( $label ) . '" ' . $selected . '>' . esc_html( ucfirst( $label ) ) . '</option>';
				}
				$field_html .= '</select>';
				$field_html .= '</label>';
				$field_html .= '</p>';
			}


			return $field_html;
		}

		public static function get_labels_array(){
			$labels_array = array(
				'new',
				'hot'
			);
			return apply_filters( 'boombox_menu_items_labels', $labels_array );
		}

		/**
		 * Sort array by key 'name'
		 *
		 * @param $a
		 * @param $b
		 *
		 * @return int
		 */
		public static function sort_by_name($a, $b) {
			if ( $a['name'] < $b['name'] ) return -1;
			if ( $a['name'] > $b['name'] ) return 1;
			return 0;
		}

	}

}

Boombox_Nav_Menu_Item_Custom_Fields::get_instance();
