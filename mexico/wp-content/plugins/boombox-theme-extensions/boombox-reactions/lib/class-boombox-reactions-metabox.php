<?php
/**
 * Register a Boombox_Reaction_Metabox using a class.
 *
 * @package BoomBox_Theme_Extensions
 */

// Prevent direct script access
if ( ! defined( 'ABSPATH' ) ) {
	die ( 'No direct script access allowed' );
}

if ( ! class_exists( 'Boombox_Reaction_Metabox' ) ) {

	class Boombox_Reaction_Metabox {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'reaction_add_form_fields', array( $this, 'add_reaction_fields' ) );
			add_action( 'reaction_edit_form_fields', array( $this, 'edit_reaction_fields' ), 10 );
			add_action( 'created_term', array( $this, 'save_reaction_fields' ), 10, 1 );
			add_action( 'edit_term', array( $this, 'save_reaction_fields' ), 10, 1 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ) );
			add_action( 'admin_menu' , array( $this, 'remove_post_reaction_fields' ) );
			add_action( 'admin_footer', array( $this, 'boombox_post_quick_edit_script' ));
		}

		/**
		 * Singleton.
		 */
		static function get_instance() {
			static $Inst = null;
			if ( $Inst == null ) {
				$Inst = new self();
			}

			return $Inst;
		}

		/**
		 * Load script
		 */
		public function enqueue_script() {
			global $current_screen;
			global $wp_scripts;
			if ( isset( $current_screen ) && 'edit-reaction' === $current_screen->id ) {
				$protocol = is_ssl() ? 'https' : 'http';
				$ui       = $wp_scripts->query( 'jquery-ui-core' );
				$url      = "$protocol://ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/smoothness/jquery-ui.min.css";
				wp_enqueue_style( 'jquery-ui-smoothness', $url, false, null );
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_style( 'boombox-reaction-meta-styles', BBTE_REACTIONS_URL . 'css/boombox-reaction-meta-styles.css' );

				wp_enqueue_script( 'wp-color-picker' );
				wp_enqueue_script( 'boombox-reaction-meta-scripts', BBTE_REACTIONS_URL . 'js/boombox-reaction-meta-scripts.js', array( 'jquery' ) );
				wp_enqueue_script( 'jquery-ui-selectmenu' );
			}
		}

		/**
		 * Removes a reaction meta box from post edit screen
		 */
		public function remove_post_reaction_fields(){
			remove_meta_box( 'reactiondiv' , 'post' , 'normal' );
		}

		/**
		 * Add Reaction fields.
		 */
		public function add_reaction_fields() {
			$reactions = $this->get_reactions_svgs_list();
			$term_icon_background_color = boombox_get_theme_option( 'design_badges_reactions_background_color' );
			$color_scheme_choices = $this->boombox_get_color_scheme_choices();
			wp_nonce_field( 'update_reaction_meta', 'reaction_meta_nonce' );?>

			<div class="form-field term-disable-vote-wrap">
				<label for="reaction_disable_vote"><?php _e( 'Disable Vote', 'boombox' ); ?></label>
				<input type="checkbox" name="reaction_disable_vote" id="reaction_disable_vote" value="1">
			</div>

			<div class="form-field term-reaction-wrap">
				<label for="reaction_icon_file_name"><?php _e( 'Reaction Icon', 'boombox' ); ?></label>

				<div id="reaction-thumb" style="background-color:<?php echo $term_icon_background_color; ?>" data-default-color="<?php echo $term_icon_background_color; ?>">
					<img src="<?php echo esc_url( $this->placeholder_img_src() ); ?>" width="60px" height="60px"/>
				</div>

				<?php if ( ! empty( $reactions ) && is_array( $reactions ) ) { ?>
					<div class="reaction-select-wrap">
						<select name="reaction_icon_file_name" id="reaction_icon_file_name">
							<option value=""><?php esc_html_e( 'Choose reaction icon' ); ?></option>
							<?php foreach ( $reactions as $reaction ) { ?>
								<option value="<?php echo $reaction['basename']; ?>"
										data-url="<?php echo $reaction['filepath']; ?>"><?php echo $reaction['filename']; ?></option>
							<?php } ?>
						</select>
					</div>
				<?php } ?>
				<div class="clear"></div>
			</div>

			<div class="form-field color-wrap">
				<div id="term-icon-background-color-wrap" color-scheme="default">
					<label for="term_icon_background_color"><?php esc_html_e( 'Color Scheme', 'boombox' ); ?></label>

					<div id="reaction-color-select-wrap">
						<select name="term_icon_color_scheme" id="term_icon_color_scheme">
							<?php foreach( $color_scheme_choices as $choice => $name ) { ?>
								<option value="<?php echo esc_attr($choice); ?>" <?php selected( esc_attr($choice), "default" ) ?>><?php echo $name; ?></option>
							<?php } ?>
						</select>
					</div>
					<div id="reaction-custom-color-wrap">
						<input type="text" value="<?php echo esc_attr( $term_icon_background_color ); ?>" name="term_icon_background_color" />
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<?php
		}

		/**
		 * Edit Reaction fields.
		 *
		 * @param $term
		 */
		public function edit_reaction_fields( $term ) {
			$reaction_icon_file_name = boombox_get_term_meta( $term->term_id, 'reaction_icon_file_name' );
			$reaction_disable_vote = boombox_get_term_meta( $term->term_id, 'reaction_disable_vote' );
			if ( $reaction_icon_file_name ) {
				$image_url = boombox_get_reaction_icon_url( $term->term_id, $reaction_icon_file_name );
			} else {
				$image_url = $this->placeholder_img_src();
			}

			$color_scheme_choices = $this->boombox_get_color_scheme_choices();
			$term_icon_default_background_color = boombox_get_theme_option('design_badges_reactions_background_color');

			$term_icon_color_scheme = boombox_get_term_meta( $term->term_id, 'term_icon_color_scheme' );
			$term_icon_color_scheme = $term_icon_color_scheme ? $term_icon_color_scheme : "default";

			$term_icon_background_color = boombox_get_term_meta( $term->term_id, 'term_icon_background_color' );
			$term_icon_background_color = !empty($term_icon_background_color) ? sanitize_text_field($term_icon_background_color) : $term_icon_default_background_color;

			$term_icon_color_scheme = ( $term_icon_background_color == $term_icon_default_background_color ) ? "default" : "custom";

			wp_nonce_field( 'update_reaction_meta', 'reaction_meta_nonce' ); ?>
			<table class="form-table term-reaction-wrap">
				<tbody>

					<tr class="form-field term-disable-vote-wrap">
						<th scope="row" valign="top">
							<label for="reaction_disable_vote"><?php _e( 'Disable Vote', 'boombox' ); ?></label>
						</th>
						<td>
							<input type="checkbox" name="reaction_disable_vote" id="reaction_disable_vote" value="1" <?php checked( $reaction_disable_vote, true, true ); ?>>
						</td>
					</tr>

					<tr class="form-field term-taxonomy-wrap">
						<th scope="row" valign="top">
							<label for="reaction_icon_file_name"><?php _e( 'Reaction Icon', 'boombox' ); ?></label>
						</th>
						<td>
							<?php
							$reactions = $this->get_reactions_svgs_list();
							if ( ! empty( $reactions ) && is_array( $reactions ) ) {
								?>
								<div class="reaction-select-wrap">
									<select name="reaction_icon_file_name" id="reaction_icon_file_name">
										<option value=""><?php esc_html_e( 'Choose reaction icon' ); ?></option>
										<?php foreach ( $reactions as $reaction ) {
											$selected = selected( $reaction_icon_file_name, $reaction['basename'], false );?>
											<option value="<?php echo $reaction['basename']; ?>"
													data-url="<?php echo $reaction['filepath']; ?>" <?php echo $selected; ?>><?php echo $reaction['filename']; ?></option>
										<?php } ?>
									</select>
								</div>
							<?php } ?>

							<div id="reaction-thumb" style="background-color:<?php echo $term_icon_background_color; ?>;" data-default-color="<?php echo $term_icon_default_background_color; ?>">
								<img src="<?php echo esc_url( $image_url ); ?>" width="60px" height="60px"/>
							</div>

						</td>
					</tr>

					<tr class="form-field color-wrap">
						<th scope="row" valign="top">
							<label for="term_icon_background_color"><?php esc_html_e( 'Icon Background Color', 'boombox' ); ?></label>
						</th>
						<td>
							<div id="term-icon-background-color-wrap" color-scheme="<?php echo $term_icon_color_scheme; ?>">

								<div id="reaction-color-select-wrap">
									<select name="term_icon_color_scheme" id="term_icon_color_scheme">
										<?php foreach( $color_scheme_choices as $choice => $name ) { ?>
										<option value="<?php echo esc_attr($choice); ?>" <?php selected( esc_attr($choice), $term_icon_color_scheme ) ?>><?php echo $name; ?></option>
										<?php } ?>
									</select>
								</div>

								<div id="reaction-custom-color-wrap">
									<input type="text" value="<?php echo esc_attr( $term_icon_background_color ); ?>" name="term_icon_background_color" />
								</div>

							</div>
						</td>
					</tr>

				</tbody>
			</table>
			<?php
		}

		/**
		 * Save Reaction fields.
		 *
		 * @param $term_id
		 */
		public function save_reaction_fields( $term_id ) {
			$nonce_name   = isset( $_POST['reaction_meta_nonce'] ) ? $_POST['reaction_meta_nonce'] : '';
			$nonce_action = 'update_reaction_meta';

			// Check if nonce is set.
			if ( ! isset( $nonce_name ) ) {
				return;
			}

			// Check if nonce is valid.
			if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
				return;
			}

			if ( isset( $_POST['reaction_icon_file_name'] ) ) {
				update_term_meta( $term_id, 'reaction_icon_file_name', esc_html( $_POST['reaction_icon_file_name'] ) );
			}

			$reaction_disable_vote = 0;
			if ( isset( $_POST['reaction_disable_vote'] ) ) {
				$reaction_disable_vote = $_POST['reaction_disable_vote'];
			}
			update_term_meta( $term_id, 'reaction_disable_vote', absint( $reaction_disable_vote ) );

			$color_scheme = isset( $_POST['term_icon_color_scheme'] ) ? $_POST['term_icon_color_scheme'] : "default";
			if ( isset( $_POST['term_icon_color_scheme'] ) ) {
				update_term_meta( $term_id, 'term_icon_color_scheme', esc_html( $color_scheme ) );
			}

			if( "default" === $color_scheme || !isset( $_POST['term_icon_background_color'] ) ) {
				$term_icon_background_color = boombox_get_theme_option('design_badges_reactions_background_color');
			} else {
				$term_icon_background_color = $_POST['term_icon_background_color'];
			}
			update_term_meta( $term_id, 'term_icon_background_color', esc_html( $term_icon_background_color ) );
		}

		/**
		 * Placeholder image url
		 */
		public function placeholder_img_src() {
			return apply_filters( 'boombox_reaction_placeholder_img_src', BBTE_REACTIONS_URL . 'images/placeholder.png' );
		}

		/**
		 * Get Reactions SVG's list
		 *
		 * @return array
		 */
		public function get_reactions_svgs_list() {
			$reactions = array();
			$dirs       = apply_filters( 'boombox_reaction_icons_path', array( array('path' => BBTE_REACTIONS_PATH . 'svg/', 'url' => BBTE_REACTIONS_ICON_URL) ) );

			foreach( (array)$dirs as $dir ) {
				if ( is_dir( $dir['path'] ) ) {
					if ( $dh = opendir( $dir['path'] ) ) {
						while ( ( $file = readdir( $dh ) ) !== false ) {
							if ( in_array( $file, array( '.', '..', 'index.php' ) ) ) {
								continue;
							}

							$reactions_pathinfo             = pathinfo( $file );
							$index = strtr( $reactions_pathinfo['filename'], array(' ' => '_', '-' => '_') );

							$reactions_pathinfo['filepath'] = $dir['url'] . $reactions_pathinfo['basename'];
							$reactions_pathinfo[ 'filename' ] = strtr( $reactions_pathinfo['filename'], array('_' => ' ', '-' => ' ') );
							$reactions[ $index ]                    = $reactions_pathinfo;
						}
						closedir( $dh );
					}
				}
			}

			ksort( $reactions );

			return $reactions;
		}



		/**
		 * Remove reactions from quick edit
		 */
		function boombox_post_quick_edit_script() {
			global $current_screen;
			if ($current_screen->post_type != 'post' && $current_screen->id != 'edit-post' ) {
				return;
			} ?>
			<script type="text/javascript">
				var reactions_container = jQuery('.inline-edit-categories .reaction-checklist');
				reactions_container.prev('input[type="hidden"]').remove();
				reactions_container.prev('span.inline-edit-categories-label').remove();
				reactions_container.remove();
			</script>
			<?php
		}

		public function boombox_get_color_scheme_choices() {
			return array(
				'default' 	=> esc_html__( 'Default', 'boombox' ),
				'custom' 	=> esc_html__( 'Custom', 'boombox' ),
			);
		}
	}
}

Boombox_Reaction_Metabox::get_instance();