<?php
/**
 * Register a post meta box using a class.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

include_once 'class-boombox-base-metabox.php';

if ( ! class_exists( 'Boombox_Custom_Post_Meta_Box' ) ) {

	class Boombox_Custom_Post_Meta_Box extends Boombox_Base_Metabox {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'load-post.php', array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
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

		public function structure() {
			$structure = array(
				'tab_global' => array(
					'title' 	=> esc_html__( 'Global', 'boombox' ),
					'active'	=> true,
					'fields' => array(
						// Hide Featured Image
						'boombox_hide_featured_image' => array(
							'name'      => 'boombox_hide_featured_image',
							'type'		=> 'dropdown',
							'label'		=> esc_html__( 'Hide Featured Image', 'boombox' ),
							'choices' 	=> boombox_single_post_featured_image_choices(),
							'value_key' => 'boombox_hide_featured_image',
							'default'	=> 'customizer',
							'callback'  => 'sanitize_text_field'
						),
						// Post Template
						'boombox_post_template' => array(
							'name'		=> 'boombox_post_template',
							'type'		=> 'dropdown',
							'label'		=> esc_html__( 'Post Template', 'boombox' ),
							'choices' 	=> array_merge( array( 'customizer' => esc_html__( 'Customizer Global Value', 'boombox' ) ), boombox_get_post_template_choices() ),
							'value_key' => 'boombox_post_template',
							'default'	=> 'customizer',
							'callback'  => 'sanitize_text_field'
						),
						// Video URL
						'boombox_video_url' => array(
							'name'		=> 'boombox_video_url',
							'type'		=> 'textfield',
							'label'		=> esc_html__( 'Video URL ( mp4, youtube, vimeo )', 'boombox' ),
							'choices' 	=> array_merge( array( 'customizer' => esc_html__( 'Customizer Global Value', 'boombox' ) ), boombox_get_post_template_choices() ),
							'value_key' => 'boombox_video_url',
							'default'	=> '',
							'callback'  => 'sanitize_video_url',
							'css'		=> array(
								'class' => 'regular-text',
							)
						)
						// other fields go here
					)
				),
				'tab_affiliate' => array(
					'title' => esc_html__( 'Affiliate', 'boombox' ),
					'active'	=> false,
					'fields' => array(
						// Regular Price
						'boombox_post_regular_price' => array(
							'name'      => 'boombox_post_regular_price',
							'type'		=> 'textfield',
							'label'		=> esc_html__( 'Regular Price', 'boombox' ),
							'value_key' => 'boombox_post_regular_price',
							'default'	=> '',
							'callback'  => 'sanitize_text_field',
							'css'		=> array(
								'class' => 'regular-text',
							)
						),
						// Discount Price
						'boombox_post_discount_price' => array(
							'name'		=> 'boombox_post_discount_price',
							'type'		=> 'textfield',
							'label'		=> esc_html__( 'Discount Price', 'boombox' ),
							'value_key' => 'boombox_post_discount_price',
							'default'	=> '',
							'callback'  => 'sanitize_text_field',
							'css'		=> array(
								'class' => 'regular-text',
							)
						),
						// Affiliate Link
						'boombox_post_affiliate_link' => array(
							'name'		=> 'boombox_post_affiliate_link',
							'type'		=> 'textfield',
							'label'		=> esc_html__( 'Affiliate Link', 'boombox' ),
							'value_key' => 'boombox_post_affiliate_link',
							'default'	=> '',
							'callback'  => 'sanitize_text_field',
							'css'		=> array(
								'class' => 'regular-text',
							)
						),
						// Post Link
						'boombox_post_affiliate_link_use_as_post_link' => array(
							'name'		=> 'boombox_post_affiliate_link_use_as_post_link',
							'type'		=> 'checkbox',
							'label'		=> '',
							'value_key' => 'boombox_post_affiliate_link_use_as_post_link',
							'text'		=> esc_html__( 'Use as post link', 'boombox' ),
							'default'	=> '0',
							'callback'  => 'sanitize_checkbox'
						),
						// other fields go here
					)
				),
				// other tabs go here
			);

			return apply_filters( 'boombox/admin/post/meta-boxes/structure', $structure );
		}

		/**
		 * Meta box initialization.
		 */
		public function init_metabox() {
			add_action( 'add_meta_boxes', array( $this, 'add_metabox' ), 1 );
			add_action( 'save_post', array( $this, 'save_metabox' ), 10, 2 );
			add_action( 'admin_print_styles-post.php', array( $this, 'post_post_admin_enqueue_scripts' ) );
			add_action( 'admin_print_styles-post-new.php', array( $this, 'post_post_admin_enqueue_scripts' ) );

		}

		/**
		 * Add the meta box.
		 */
		public function add_metabox() {
			/**
			 * Add Advanced Fields to Post screen
			 */
			if( apply_filters( 'boombox/admin/post/meta-boxes/show-screen-advanced', true ) ) {
				add_meta_box(
					'boombox-post-metabox',
					__('Boombox Post Advanced Fields', 'boombox'),
					array($this, 'render_metabox'),
					'post',
					'side',
					'high'
				);
			}

			if( apply_filters( 'boombox/admin/post/meta-boxes/show-screen-main', true ) ) {
				add_meta_box(
					'boombox-post-metabox-2',
					__('Boombox Post Advanced Fields', 'boombox'),
					array($this, 'render_metabox_2'),
					'post',
					'normal',
					'high'
				);
			}
		}

		/**
		 * Enqueue Scripts and Styles
		 */
		public function post_post_admin_enqueue_scripts() {
			global $current_screen;

			if ( isset( $current_screen ) && 'post' === $current_screen->id  ) {
				wp_enqueue_style( 'boombox-admin-meta-style', BOOMBOX_ADMIN_URL . 'metaboxes/css/boombox-metabox-style.css', array(), boombox_get_assets_version() );
				wp_enqueue_script( 'boombox-admin-meta-script', BOOMBOX_ADMIN_URL . 'metaboxes/js/boombox-metabox-script.js', array( 'jquery' ), boombox_get_assets_version(), true );
			}
		}

		/**
		 * Render the advances fields meta box.
		 *
		 * @param $post
		 */
		public function render_metabox( $post ) {

			// Add nonce for security and authentication.
			wp_nonce_field( 'boombox_advanced_fields_nonce_action', 'boombox_nonce' );

			// Use get_post_meta to retrieve an existing value from the database.
			$boombox_is_featured = boombox_get_post_meta( $post->ID, 'boombox_is_featured' );
			$boombox_is_featured = empty( $boombox_is_featured ) ? false : true;

			$boombox_is_featured_frontpage = boombox_get_post_meta( $post->ID, 'boombox_is_featured_frontpage' );
			$boombox_is_featured_frontpage = empty( $boombox_is_featured_frontpage ) ? false : true;

			$boombox_keep_trending = boombox_get_post_meta( $post->ID, 'boombox_keep_trending' );
			$boombox_keep_trending = empty( $boombox_keep_trending ) ? false : true;

			$boombox_keep_hot = boombox_get_post_meta( $post->ID, 'boombox_keep_hot' );
			$boombox_keep_hot = empty( $boombox_keep_hot ) ? false : true;

			$boombox_keep_popular = boombox_get_post_meta( $post->ID, 'boombox_keep_popular' );
			$boombox_keep_popular = empty( $boombox_keep_popular ) ? false : true;

			// Display the form, using the current value.
			?>
			<div class="boombox-post-advanced-fields">

				<?php // Featured Field ?>
				<div class="boombox-post-form-row">
					<input type="hidden" name="boombox_is_featured" value="0" />
					<input type="checkbox" id="boombox_is_featured" name="boombox_is_featured" value="1" <?php checked( $boombox_is_featured, true, true ); ?> />
					<label for="boombox_is_featured"><?php esc_html_e( 'Featured', 'boombox' ); ?></label>
				</div>

				<?php // Featured On Homepage Field ?>
				<div class="boombox-post-form-row">
					<input type="hidden" name="boombox_is_featured_frontpage" value="0" />
					<input type="checkbox" id="boombox_is_featured_frontpage" name="boombox_is_featured_frontpage" value="10" <?php checked( $boombox_is_featured_frontpage, true, true ); ?> />
					<label for="boombox_is_featured_frontpage"><?php esc_html_e( 'Featured On Front Page', 'boombox' ); ?></label>
				</div>

				<?php // Keep Trending ?>
				<div class="boombox-post-form-row">
					<input type="hidden" name="boombox_keep_trending" value="0" />
					<input type="checkbox" id="boombox_keep_trending" name="boombox_keep_trending" value="1" <?php checked( $boombox_keep_trending, true, true ); ?> />
					<label for="boombox_keep_trending"><?php esc_html_e( 'Keep Trending', 'boombox' ); ?></label>
				</div>

				<?php // Keep Hot ?>
				<div class="boombox-post-form-row">
					<input type="hidden" name="boombox_keep_hot" value="0" />
					<input type="checkbox" id="boombox_keep_hot" name="boombox_keep_hot" value="1" <?php checked( $boombox_keep_hot, true, true ); ?> />
					<label for="boombox_keep_hot"><?php esc_html_e( 'Keep Hot', 'boombox' ); ?></label>
				</div>

				<?php // Keep Popular ?>
				<div class="boombox-post-form-row">
					<input type="hidden" name="boombox_keep_popular" value="0" />
					<input type="checkbox" id="boombox_keep_popular" name="boombox_keep_popular" value="1" <?php checked( $boombox_keep_popular, true, true ); ?> />
					<label for="boombox_keep_popular"><?php esc_html_e( 'Keep Popular', 'boombox' ); ?></label>
				</div>

			</div>
		<?php
		}

		/**
		 * Render the advances fields meta box.
		 *
		 * @param $post
		 */
		function render_metabox_2( $post ){

			$this->data = boombox_get_post_meta( $post->ID );

			$this->render();

		}

		/**
		 * Handles saving the meta box.
		 *
		 * @param int $post_id Post ID.
		 * @param WP_Post $post Post object.
		 *
		 * @return null
		 */
		public function save_metabox( $post_id, $post ) {

			parent::save_data( $post_id, $post );

			/* OK, it's safe for us to save the data now. */
			$boombox_is_featured = 0;
			if ( isset( $_POST['boombox_is_featured'] ) ) {
				$boombox_is_featured = $_POST['boombox_is_featured'] ? 1 : 0;
			}
			update_post_meta( $post_id, 'boombox_is_featured', (int) $boombox_is_featured );

			$boombox_is_featured_frontpage = 0;
			if ( isset( $_POST['boombox_is_featured_frontpage'] ) ) {
				$boombox_is_featured_frontpage = $_POST['boombox_is_featured_frontpage'] ? $_POST['boombox_is_featured_frontpage'] : 0;
			}
			update_post_meta( $post_id, 'boombox_is_featured_frontpage', (int) $boombox_is_featured_frontpage );

			$boombox_keep_trending = false;
			if ( isset( $_POST['boombox_keep_trending'] ) ) {
				$boombox_keep_trending = $_POST['boombox_keep_trending'] ? 999999999999 : false;
			}
			update_post_meta( $post_id, 'boombox_keep_trending', $boombox_keep_trending );

			$boombox_keep_hot = false;
			if ( isset( $_POST['boombox_keep_hot'] ) ) {
				$boombox_keep_hot = $_POST['boombox_keep_hot'] ? 999999999999 : false;
			}
			update_post_meta( $post_id, 'boombox_keep_hot', $boombox_keep_hot );

			$boombox_keep_popular = false;
			if ( isset( $_POST['boombox_keep_popular'] ) ) {
				$boombox_keep_popular = $_POST['boombox_keep_popular'] ? 999999999999 : false;
			}
			update_post_meta( $post_id, 'boombox_keep_popular', $boombox_keep_popular );

			$boombox_video_url = $this->sanitize_video_url( isset( $_POST['boombox_video_url'] ) ? $_POST['boombox_video_url'] : '' );
			update_post_meta( $post_id, 'boombox_video_url', esc_html( $boombox_video_url ) );

			do_action( 'boombox/admin/post/meta-boxes/save', $post_id, $post );

		}


	}
}

Boombox_Custom_Post_Meta_Box::get_instance();