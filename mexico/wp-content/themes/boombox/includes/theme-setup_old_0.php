<?php
/**
 * Boombox theme setup
 *
 * @package BoomBox_Theme
 */

/**
 * Hooks
 */
add_action( 'after_setup_theme', 'boombox_content_width', 0 );
add_action( 'after_setup_theme', 'boombox_setup', 10 );
add_action( 'wp_enqueue_scripts', 'boombox_styles', 10 );
add_action( 'wp_enqueue_scripts', 'boombox_scripts', 10 );
add_action( 'widgets_init', 'boombox_widgets_init', 10 );
add_action( 'wp_head','boombox_global_page_id', 10 );
add_action( 'init', 'boombox_authentication', 10 );
add_action( 'wp_head', 'boombox_meta_tags', 0 );
add_action( 'the_post', 'boombox_cache_post', 10, 1 );
add_action( 'boombox_before_main_container', 'boombox_add_front_page_seo_heading', 10 );
add_action( 'boombox/single/microdata', 'boombox_single_post_microdata', 10, 1 );
add_action( 'logout_url', 'boombox_logout_url', 10, 2 );
add_action( 'boombox/auth_box_icons', 'boombox_auth_box_render_profile_icon', 30, 1 );
add_action( 'boombox/auth_box_icons', 'boombox_auth_box_create_post_button', 40, 1 );

add_filter( 'body_class', 'boombox_body_classes', 10, 1 );
add_filter( 'get_the_archive_title', 'boombox_get_the_archive_title', 10 );
add_filter( 'comment_form_fields', 'boombox_move_comment_field_to_bottom', 10 );
add_filter( 'wp_list_categories', 'boombox_archive_count_no_brackets', 10 );
add_filter( 'mce_buttons', 'boombox_add_next_page_button', 1, 2 );
add_filter( 'excerpt_more', 'boombox_excerpt_more', 10 );
add_filter( 'script_loader_tag', 'boombox_add_script_attribute', 10, 2 );
add_filter( 'boombox_reaction_icons_path', 'boombox_add_theme_reaction_icons_path', 10, 1 );
add_filter( 'widget_title', 'boombox_change_widget_title', 10, 3 );
add_filter( 'post_views_count', 'boombox_post_views_count', 10, 2 );
add_filter( 'post_points_count', 'boombox_post_points_count', 10, 2 );
add_filter( 'boombox_loop_item_url', 'boombox_loop_item_url', 10, 2 );
add_filter( 'boombox_loop_item_url_target', 'boombox_loop_item_url_target', 10, 3 );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
if ( ! function_exists( 'boombox_setup' ) ) {

    function boombox_setup()
    {
        /*
         * Make theme available for translation.
         */
        load_theme_textdomain('boombox', get_stylesheet_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
         */
        add_theme_support('post-thumbnails');
        add_image_size('boombox_image360x270', '360', '270', true);
        add_image_size('boombox_image360x180', '360', '180', true);
        add_image_size('boombox_image200x150', '200', '150', true);
        add_image_size('boombox_image768x450', '768', '450', true);
        add_image_size('boombox_image768', '768');
        add_image_size('boombox_image545', '545');
        add_image_size('boombox_image1600', '1600');

        // This theme uses wp_nav_menu() in five locations.
        register_nav_menus(array(
            'top_header_nav' => esc_html__('Top Header Menu', 'boombox'),
            'bottom_header_nav' => esc_html__('Bottom Header Menu', 'boombox'),
            'badges_nav' => esc_html__('Badges Menu', 'boombox'),
            'burger_top_nav' => esc_html__('Burger Top Menu', 'boombox'),
            'burger_bottom_nav' => esc_html__('Burger Bottom Menu', 'boombox'),
            'burger_badges_nav' => esc_html__('Burger Badges Menu', 'boombox'),
            'footer_nav' => esc_html__('Footer Menu', 'boombox'),
            'profile_nav' => esc_html__('Profile Menu', 'boombox')
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support('post-formats', array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'status',
            'audio',
            'chat',
        ));

        /**
         * This theme styles the visual editor to resemble the theme style,
         * specifically font, colors, icons, and column width.
         */
        add_editor_style(array('editor-style.css', boombox_fonts_url()));
    }

}

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function boombox_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'boombox_content_width', 1160 );
}

/**
 * Get theme data
 *
 * @return false|WP_Theme
 */
function boombox_get_theme_data() {
    $theme = wp_get_theme();
    if( is_child_theme() ) {
        $theme = $theme->parent();
    }

    return $theme;
}

/**
 * Get assets version
 *
 * @return string
 */
function boombox_get_assets_version() {
    return boombox_get_theme_data()->get( 'Version' );
}

/**
 * Enqueue styles.
 */
function boombox_styles() {
	// Plugins
	wp_enqueue_style( 'boombox-styles-min', BOOMBOX_THEME_URL . 'js/plugins/plugins.min.css', array(), boombox_get_assets_version() );

	// Icon fonts
	wp_enqueue_style( 'boombox-icomoon-style', BOOMBOX_THEME_URL . 'fonts/icon-fonts/icomoon/icons.min.css', array(), boombox_get_assets_version() );

	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'boombox-fonts', boombox_fonts_url(), array(), boombox_get_assets_version() );

	// Icon fonts
	wp_enqueue_style( 'boombox-primary-style', BOOMBOX_THEME_URL . 'css/style.min.css', array(), boombox_get_assets_version() );

	// Enqueue stylesheet from the child theme
	if ( get_template_directory() !== get_stylesheet_directory() ) {
		wp_enqueue_style( 'boombox-style', get_stylesheet_uri(), array(), boombox_get_assets_version() );
	}

	if( is_rtl() ) {
		wp_enqueue_style( 'boombox-style-rtl', BOOMBOX_THEME_URL . 'css/rtl.min.css', array(), boombox_get_assets_version() );
	}
}

/**
 * Enqueue scripts
 */
function boombox_scripts() {
	global $is_IE;
	global $post;

	if ( $is_IE ) {
		wp_enqueue_script( 'boombox-html5shiv-min', BOOMBOX_THEME_URL . '/js/html5shiv.min.js', array(), boombox_get_assets_version(), true );
		wp_script_add_data( 'boombox-html5shiv-min', 'conditional', 'lt IE 9' );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply', '', array(), boombox_get_assets_version(), true );
	}

	$boombox_auth_is_disabled = boombox_disabled_site_auth();
	$boombox_auth_captcha_type = boombox_auth_captcha_type();
	$boombox_enable_login_captcha = boombox_get_theme_option( 'auth_enable_login_captcha' );
	$boombox_enable_registration_captcha = boombox_get_theme_option( 'auth_enable_registration_captcha' );

	$recaptcha_include_conditions = array(
		// login/registration condition
		'guests' 		=> !$boombox_auth_is_disabled && !is_user_logged_in() && ( $boombox_auth_captcha_type == 'google' ) && ( $boombox_enable_login_captcha || $boombox_enable_registration_captcha ),
		//pages with contact form shortcodes
		'contact_form'	=> is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'boombox_contact_form')
	);

	if( $recaptcha_include_conditions[ 'guests' ] || $recaptcha_include_conditions[ 'contact_form' ] ) {

		$inline_scripts = '';
		$google_recaptcha_handle = array(
			'name' => 'google-recaptcha',
			'url'  => 'https://www.google.com/recaptcha/api.js'
		);

		/** - Contact form 7 - */
		if( boombox_is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
			wp_deregister_script( $google_recaptcha_handle[ 'name' ] );
			$inline_scripts = '
				if( "function" == typeof window.recaptchaCallback ) {
					recaptchaCallback();
				}
			';
		}

		/** - Buddypress registration - **/
		if( boombox_is_plugin_active( 'buddypress/bp-loader.php' ) && bp_is_register_page() ) {
			wp_enqueue_script( $google_recaptcha_handle[ 'name' ], $google_recaptcha_handle[ 'url' ], array( 'jquery' ), boombox_get_assets_version() );
			wp_add_inline_script(
				$google_recaptcha_handle[ 'name' ],
				sprintf('
					jQuery( document ).on( "ready", function() {
						window.boomboxRecaptchaOnSubmit = function( token ){
							jQuery( "#signup_form" ).data( "_grec", 1 );
						}
					} );
					%s
				',
					$inline_scripts
				),
				'before'
			);
		}
		/** - Standard pages - **/
		else {
			wp_enqueue_script( $google_recaptcha_handle[ 'name' ], add_query_arg( array( 'render' => 'explicit', 'onload' => 'boomboxOnloadCallback' ), $google_recaptcha_handle[ 'url' ] ), array('jquery'), boombox_get_assets_version() );
			wp_add_inline_script(
				$google_recaptcha_handle[ 'name' ],
				sprintf('
					var boomboxOnloadCallback = function() {
						jQuery( "body").trigger( "boombox/grecaptcha_loaded" );
						%s
					};
				',
					$inline_scripts
				),
				'before'
			);
		}
	}

	// Site main scripts
	wp_enqueue_script( 'boombox-scripts-min', BOOMBOX_THEME_URL . 'js/scripts.min.js', array( 'jquery' ), boombox_get_assets_version(), true );
	wp_add_inline_script( 'boombox-scripts-min', sprintf( 'var boombox_gif_event="%s";', boombox_get_theme_option( 'settings_gif_control_animation_event' ) ), 'before' );
}

/**
 * Register Google fonts for Boombox.
 *
 * @return string Google fonts URL for the theme.
 */
function boombox_fonts_url() {
    $fonts_url               = '';
    $default_fonts 			 = boombox_get_default_fonts();
    $fonts                   = array();
    $logo_font_family		 = boombox_get_theme_option( 'design_global_logo_font_family' );
    $primary_font_family     = boombox_get_theme_option( 'design_global_primary_font_family' );
    $secondary_font_family   = boombox_get_theme_option( 'design_global_secondary_font_family' );
    $post_titles_font_family = boombox_get_theme_option( 'design_global_post_titles_font_family' );
    $google_font_subset      = boombox_get_theme_option( 'design_global_google_font_subset' );

    $subsets = ! empty( $google_font_subset ) ? $google_font_subset : 'latin,latin-ext';

    if ( !array_key_exists( $logo_font_family , $default_fonts ) ) {
        $fonts[] = "{$logo_font_family}:400,500,400italic,500italic,600,600italic,700,700italic";
    }

    if ( !array_key_exists( $primary_font_family , $default_fonts ) ) {
        $fonts[] = "{$primary_font_family}:400,500,400italic,600,700";
    }

    if ( !array_key_exists( $secondary_font_family , $default_fonts ) ) {
        $fonts[] = "{$secondary_font_family}:400,500,400italic,600,700";
    }

    if ( !array_key_exists( $post_titles_font_family , $default_fonts ) ) {
        $fonts[] = "{$post_titles_font_family}:700";
    }

    if ( (bool)$fonts ) {
        $fonts_url = add_query_arg( array(
            'family' => urlencode( implode( '|', $fonts ) ),
            'subset' => urlencode( $subsets ),
        ), 'https://fonts.googleapis.com/css' );
    }

    return $fonts_url;
}

/**
 * Get sidebars configuration
 *
 * @return array
 */
function boombox_get_sidebars() {
	return array(
		array(
			'name'          => esc_html__( 'Default', 'boombox' ),
			'id'            => 'default-sidebar',
			'description'   => esc_html__( 'The widgets added here will appear on all the pages, except the post single and the page sidebar.', 'boombox' ),
		),
		array(
			'name'          => esc_html__( 'Page 1', 'boombox' ),
			'id'            => 'page-sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in your page sidebar.', 'boombox' ),
		),
		array(
			'name'          => esc_html__( 'Page 2', 'boombox' ),
			'id'            => 'page-sidebar-2',
			'description'   => esc_html__( 'Add widgets here to appear in your page sidebar.', 'boombox' ),
		),
		array(
			'name'          => esc_html__( 'Page 3', 'boombox' ),
			'id'            => 'page-sidebar-3',
			'description'   => esc_html__( 'Add widgets here to appear in your page sidebar.', 'boombox' ),
		),
		array(
			'name'          => esc_html__( 'Secondary', 'boombox' ),
			'id'            => 'page-secondary',
			'description'   => esc_html__( 'Add widgets here to appear with three column listing type.', 'boombox' ),
		),
		array(
			'name'          => esc_html__( 'Post Single', 'boombox' ),
			'id'            => 'post-sidebar',
			'description'   => esc_html__( 'Add widgets here to appear in your post sidebar.', 'boombox' ),
		),
		array(
			'name'          => esc_html__( 'Archive', 'boombox' ),
			'id'            => 'archive-sidebar',
			'description'   => esc_html__( 'Add widgets here to appear in your post sidebar.', 'boombox' ),
		),
		array(
			'name'          => esc_html__( 'Footer Left', 'boombox' ),
			'id'            => 'footer-left-widgets',
			'description'   => esc_html__( 'Add widgets here to appear in your footer left section.', 'boombox' ),
		),
		array(
			'name'          => esc_html__( 'Footer Middle', 'boombox' ),
			'id'            => 'footer-middle-widgets',
			'description'   => esc_html__( 'Add widgets here to appear in your footer middle section.', 'boombox' ),
		),
		array(
			'name'          => esc_html__( 'Footer Right', 'boombox' ),
			'id'            => 'footer-right-widgets',
			'description'   => esc_html__( 'Add widgets here to appear in your footer right section.', 'boombox' ),
		)
	);
}

/**
 * Registers a widget area.
 */
function boombox_widgets_init() {

	$register_sidebars = boombox_get_sidebars();

	foreach( $register_sidebars as $register_sidebar ){

		register_sidebar( array(
			'name'          => $register_sidebar['name'],
			'id'            => $register_sidebar['id'],
			'description'   => $register_sidebar['description'],
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	}

}

/**
 * Adds custom classes to the array of body classes.
 *
 * @since BoomBox 1.0
 *
 * @param array $classes Classes for the body element.
 *
 * @return array (Maybe) filtered body classes.
 */
function boombox_body_classes( $classes ) {

	$nsfw_category = apply_filters( 'boombox/nsfw_category', 'nsfw' );
	if( term_exists( $nsfw_category, 'category') && !is_user_logged_in() ){
		$classes[] = 'nsfw-post';
	}

	if ( is_singular( 'post' ) ) {
		$post_template = boombox_get_single_post_template();

		$classes[ 'sidebar_position' ] = 'full-width' == $post_template ? esc_attr( $post_template ) . ' ' . 'no-sidebar' : esc_attr( $post_template );
	}

	if( is_archive() || is_home() ) {

		$archive_template = boombox_get_theme_option( 'layout_archive_template' );
		$classes[ 'sidebar_position' ] = esc_attr( $archive_template );

	}

	if( is_page() || is_archive() || is_home() ) {

		if( is_page() ) {
			if (is_page_template('page-with-left-sidebar.php')) {
				$classes['sidebar_position'] = 'left-sidebar';
			} elseif (is_page_template('page-no-sidebar.php')) {
				$classes['sidebar_position'] = 'no-sidebar';
			} elseif (is_page_template('default') || is_page_template('page-trending-result.php')) {
				$classes['sidebar_position'] = 'right-sidebar';
			}
		}

		$disable_full_post_button_conditions = boombox_get_theme_option( 'layout_post_enable_full_post_button_conditions' );
		if( false !== array_search( 'image_ratio', $disable_full_post_button_conditions ) ) {
			$classes[] = 'has-full-post-button';
		}
	}

	if ( is_404() ) {
		$classes[ 'sidebar_position' ] = 'error404 no-sidebar';
	}

	$badges_reactions_type = boombox_get_theme_option( 'design_badges_reactions_type' );
	if ( $badges_reactions_type ) {
		$classes[] = 'badge-' . esc_attr( $badges_reactions_type );
	}

	$design_badges_position_on_thumbnails = boombox_get_theme_option( 'design_badges_position_on_thumbnails' );
	if( $design_badges_position_on_thumbnails ) {
		$classes[] = 'badges-' . esc_attr( $design_badges_position_on_thumbnails );
	}

	if( boombox_is_theme_option_changed( 'design_global_body_background_color' ) || boombox_is_theme_option_changed( 'design_global_body_background_image' ) ) {
		$classes[] = 'with-background-media';
	}

	return $classes;
}

/**
 * Get single post layout
 *
 * @return mixed
 */
function boombox_get_single_post_template() {
	static $post_template;

	if( !$post_template ) {
		global $post;

		$post_template = boombox_get_post_meta( $post->ID, 'boombox_post_template' );
		$post_template = ( !$post_template || ($post_template == 'customizer') ) ? boombox_get_theme_option( 'layout_post_template' ) : $post_template;
	}

	return $post_template;
}

/**
 * Add Next Page/Page Break Button
 * in WordPress Visual Editor
 *
 * @param $buttons
 * @param $id
 *
 * @return mixed
 */
function boombox_add_next_page_button( $buttons, $id ){

	/* only add this for content editor */
	if ( 'content' != $id )
		return $buttons;

	/* add next page after more tag button */
	array_splice( $buttons, 13, 0, 'wp_page' );

	return $buttons;
}

/**
 * Filter the excerpt "read more" string.
 *
 * @param string $more "Read more" excerpt string.
 * @return string (Maybe) modified "read more" excerpt string.
 */
function boombox_excerpt_more( $more ) {
	return '...';
}

/**
 * Filter some scripts to add additional options
 *
 * @param $tag Current Tag
 * @param $handle Handle
 * @return mixed Modified Tag
 */
function boombox_add_script_attribute( $tag, $handle ) {
	if( in_array( $handle, array( 'boombox-google-recaptcha', 'facebook-jssdk', 'boombox-google-platform', 'boombox-google-client' ) ) ) {
		return str_replace( ' src', ' id="' . $handle . '" async defer src', $tag );
	}
	return $tag;
}

/**
 * Detect if Registration is active
 */
function boombox_user_can_register() {
	return (bool) get_option( 'users_can_register' );
}

/**
 * Remove 'category' from archive title
 *
 * @param $title
 *
 * @return string|void
 */
function boombox_get_the_archive_title( $title ) {
	if ( is_category() || is_tax('reaction') ) {
		$title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
	}

	return $title;

}

/**
 * Get Page ID outside the Loop
 *
 * @return int
 */
function boombox_global_page_id() {
	static $cur_page_id;

	if ( ! $cur_page_id ) {
		global $post;
		if( $post ){
			$cur_page_id = $post->ID;
		}else{
			$cur_page_id = 0;
		}
	}

	return $cur_page_id;
}

/**
 * Moving the Comment Text Field to Bottom
 *
 * @param $fields
 *
 * @return mixed
 */
function boombox_move_comment_field_to_bottom( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;

	return $fields;
}

/**
 * Remove Post Count Parentheses From Widget
 *
 * @param $variable
 *
 * @return mixed
 */
function boombox_archive_count_no_brackets( $variable ) {
	return strtr( $variable, array( '(' => '<span class="post_count"> ', ')' => ' <span>' ) );
}

/**
 * Check whether the plugin is active
 *
 * @param $plugin
 *
 * @return bool
 */
function boombox_is_plugin_active( $plugin ) {
    $active_plugins = get_option( 'active_plugins' );
    if( is_multisite() ) {
        $active_plugins = array_merge( $active_plugins, array_keys( get_site_option( 'active_sitewide_plugins') ) );
    }
    return ( array_search( $plugin, $active_plugins ) !== false );
}

/**
 * Get Google fonts
 *
 * @return array
 */
function boombox_get_google_fonts() {
	$google_fonts = get_transient( 'google_fonts' );

	if ( !$google_fonts ) {
		$google_fonts = array();
		$google_api_key = boombox_get_theme_option( 'design_global_google_api_key' );
		if( $google_api_key ){
			$google_api_url = "https://www.googleapis.com/webfonts/v1/webfonts?key={$google_api_key}";
			$response       = wp_remote_retrieve_body( wp_remote_get( $google_api_url, array( 'sslverify' => false ) ) );
			if ( ! is_wp_error( $response ) ) {
				$data  = json_decode( $response, true );
				if ( isset( $data['items'] ) ){
					$google_fonts = $data['items'];
				}
			}
		}
		set_transient( 'google_fonts', $google_fonts, WEEK_IN_SECONDS );
	}

	return $google_fonts;
}

/**
 * Check if a post status is registered.
 *
 * @see get_post_status_object()
 *
 * @param string $postStatus Post status name.
 * @return bool Whether post status is registered.
 */
function post_status_exists( $postStatus ) {
	return (bool) get_post_status_object( $postStatus );
}

/**
 * Return Featured Strip default image URL
 *
 * @return mixed|void
 */
function boombox_get_default_image_url_for_featured_strip(){
	return apply_filters('boombox_default_image_for_featured_strip', BOOMBOX_THEME_URL . 'images/nophoto.png');
}

/**
 * User Authentication
 */
function boombox_authentication() {
	if ( ! is_user_logged_in() ) {
		require_once( BOOMBOX_INCLUDES_PATH . 'authentication/auth.php' );
	}
}

/**
 * Returns site auth is disabled or not
 *
 * @return mixed
 */
function boombox_disabled_site_auth(){
	return (bool) boombox_get_theme_option( 'disable_site_auth' );
}

function boombox_auth_captcha_type( $default = 'image' ) {
	static $captcha_type;

	if( !$captcha_type ) {
		$possible_captchas = array( 'image', 'google' );
		$captcha_type = boombox_get_theme_option( 'auth_captcha_type' );
		$captcha_type = in_array( $captcha_type, $possible_captchas ) ? $captcha_type : $default;

		if( $captcha_type == 'google' ) {
			$site_key = boombox_get_theme_option( 'auth_google_recaptcha_site_key' );
			$secret_key = boombox_get_theme_option( 'auth_google_recaptcha_secret_key' );
			$captcha_type = ( $site_key && $secret_key ) ? $captcha_type : false;
		}
	}

	return $captcha_type;
}

/**
 * Validate image captcha
 *
 * @param $key The key in $_POST array where response is set
 * @param $type login / register
 * @return bool
 */
function boombox_validate_image_captcha( $key, $type ) {
	$session_key = sprintf( 'boombox_%s_captcha_key', $type );
	return ( isset( $_SESSION[ $session_key ] ) && isset( $_POST[ $key ] ) && ( $_SESSION[ $session_key ] == $_POST[ $key ] ) );
}

/**
 * Validate google captcha response
 *
 * @param $key The key in $_POST array where response is set
 * @return array
 */
function boombox_validate_google_captcha( $key ) {
	add_filter( 'http_request_timeout', 'boombox_recaptcha_http_request_timeout', 9999, 1 );

	$gcaptcha = array(
		'success' => false,
		'message' => '',
		'response' => wp_remote_retrieve_body(wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
			'body' => array(
				'secret' => boombox_get_theme_option('auth_google_recaptcha_secret_key'),
				'response' => isset($_POST[$key]) ? $_POST[$key] : ''
			),
		)))
	);

	if (!is_wp_error($gcaptcha['response'])) {
		$gcaptcha['response'] = json_decode($gcaptcha['response'], true);
		if (isset($gcaptcha['response']['success']) && $gcaptcha['response']['success']) {
			$gcaptcha['success'] = true;
		}
	}

	return $gcaptcha;
}

/**
 * Sort icons array by key 'name'
 *
 * @param $a
 * @param $b
 *
 * @return int
 */
function icomoon_icon_sort_by_name( $a, $b ) {
	if ( $a['name'] < $b['name'] ) return -1;
	if ( $a['name'] > $b['name'] ) return 1;
	return 0;
}

/**
 * Get iconmoon icons pack
 * @return array
 */
function get_icomoon_icons_array() {
	static $icons_array;
	if( !$icons_array ){
		$icons_array = array();
		$path        = BOOMBOX_THEME_URL . 'fonts/icon-fonts/icomoon/selection.json';
		$response    = wp_remote_get( $path );
		if ( ! is_wp_error( $response ) ) {
			$icons    = json_decode( $response['body'] );
			$exclude  = array( 'skull-real' );
			$item_num = 0;
			if( isset( $icons->icons ) && is_array( $icons->icons ) ){
				foreach ( $icons->icons as $icon ) {
					$icon_name  = $icon->properties->name;
					$icon_names = explode( ', ', $icon_name );
					if ( ! in_array( $icon_names[0], $exclude ) ) {
						$icons_array[ $item_num ]['icon'] = $icon_names[0];
						$icons_array[ $item_num ]['name'] = $icon_name;
						++ $item_num;
					}
				}
			}
		}

		usort( $icons_array,  'icomoon_icon_sort_by_name' );
	}

	return $icons_array;
}

/**
 * Return HTML from file
 */
if ( ! function_exists( 'boombox_load_template_part' ) ) {
	function boombox_load_template_part( $template_name, $part_name = null ) {
		ob_start();
		get_template_part( $template_name, $part_name );
		$var = ob_get_contents();
		ob_end_clean();

		return $var;
	}
}


if ( ! function_exists( 'boombox_theme_reactions_folder_name' ) ) {
	/**
	 * Get custom reactions folder name
	 *
	 * @return string
	 */
	function boombox_theme_reactions_folder_name() {
		return 'reactions';
	}
}

if ( ! function_exists( 'boombox_add_theme_reaction_icons_path' ) ) {
	/**
	 * Add custom folder data to scan for icons
	 * @param $dirs
	 * @return array
	 */
	function boombox_add_theme_reaction_icons_path( $dirs ) {
		$theme_folder_name = boombox_theme_reactions_folder_name();
		array_unshift( $dirs, array(
			'path' => trailingslashit(get_stylesheet_directory()) . $theme_folder_name . '/',
			'url' => get_stylesheet_directory_uri() . '/' . $theme_folder_name . '/'
		) );

		return $dirs;
	}
}

/**
 * Get regular expression for provided type
 *
 * @param $type
 * @return string
 */
function boombox_get_regex( $type ) {

	switch( $type ) {
		case 'youtube':
			$regex = "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/";
			break;
		case 'vimeo':
			$regex = "/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/";
			break;
		default:
			$regex = '';
	}

	return $regex;
}

/**
 * Custom Opengraph Meta Tags
 */
function boombox_meta_tags() {

	if( ! is_single() ) return;

	if( boombox_get_theme_option( 'settings_gif_control_disable_sharing' ) ) return;

	global $post;
	$thumbnail_id = get_post_thumbnail_id( $post );

	if( ! $thumbnail_id ) return;

	$thumbnail_post = get_post( $thumbnail_id );
	if( !$thumbnail_post ) return;

	if( "image/gif" != $thumbnail_post->post_mime_type ) return;
	list( $thumbnail_url, $thumbnail_width, $thumbnail_height, $thumbnail_is_intermediate ) = wp_get_attachment_image_src( $thumbnail_post->ID, 'full' );

	$opengraph = PHP_EOL . '<meta property="og:type" content="video.other" />';
	$opengraph .= PHP_EOL . sprintf( '<meta property="og:url" content="%s" />', $thumbnail_url );

	echo $opengraph;
}

/**
 * Callback to modify widgets titles
 *
 * @param $title
 * @param $instance
 * @param $id_base
 *
 * @return string
 */
function boombox_change_widget_title( $title = '', $instance = array(), $id_base = '' ) {
	if( 'tag_cloud' == $id_base ) {
		$title = ( isset( $instance['title'] ) && $instance['title'] ) ? $instance['title'] : '';
	}
	return $title;
}

/**
 * Callback to add fake views count to actual ones
 *
 * @param $views_count
 * @param $post_id
 * @return mixed
 */
function boombox_post_views_count( $views_count, $post_id ) {
	$fake_count = boombox_get_theme_option( 'settings_rating_fake_views_count' );
	if( $fake_count > 0 ) {
		$cached_posts = &boombox_get_cached_posts();
		if( ! isset( $cached_posts[ $post_id ][ 'post' ] ) ) {
			$cached_posts[ $post_id ] = isset( $cached_posts[ $post_id ] ) ? $cached_posts[ $post_id ] : array();
			$cached_posts[ $post_id ] = array_merge( $cached_posts[ $post_id ], array( 'post' => get_post( $post_id ) ) );
		}
		$fake_count += strlen( $cached_posts[ $post_id ][ 'post' ]->post_title );
	} else {
		$fake_count = 0;
	}

	return $views_count + $fake_count;
}

/**
 * Callback to add fake points count to actual ones
 *
 * @param $points_count
 * @param $post_id
 * @return mixed
 */
function boombox_post_points_count( $points_count, $post_id ) {
	$fake_count = boombox_get_theme_option( 'settings_rating_fake_points_count' );
	if( $fake_count > 0 ) {
		$cached_posts = &boombox_get_cached_posts();
		if( !isset( $cached_posts[ $post_id ][ 'post' ] ) ) {
			$cached_posts[ $post_id ] = isset( $cached_posts[ $post_id ] ) ? $cached_posts[ $post_id ] : array();
			$cached_posts[ $post_id ] = array_merge( $cached_posts[ $post_id ], array( 'post' => get_post( $post_id ) ) );
		}
		$fake_count += strlen( $cached_posts[ $post_id ][ 'post' ]->post_title );
	} else {
		$fake_count = 0;
	}

	return $points_count + $fake_count;
}

/**
 * Callback to add fake shares count to actual ones
 *
 * @param $share_count
 * @param $post_id
 * @return mixed
 */
function boombox_post_shares_count( $share_count, $post_id ) {
	$fake_count = (int)apply_filters( 'boombox/fake_share_count', 0 );

	if( $fake_count > 0 ) {
		$cached_posts = &boombox_get_cached_posts();
		if( !isset( $cached_posts[ $post_id ][ 'post' ] ) ) {
			$cached_posts[ $post_id ] = isset( $cached_posts[ $post_id ] ) ? $cached_posts[ $post_id ] : array();
			$cached_posts[ $post_id ] = array_merge( $cached_posts[ $post_id ], array( 'post' => get_post( $post_id ) ) );
		}
		$fake_count += strlen( $cached_posts[ $post_id ][ 'post' ]->post_title );
	} else {
		$fake_count = 0;
	}

	return $share_count + $fake_count;
}

/**
 * Get Cached Posts
 * @return array
 */
function &boombox_get_cached_posts() {
	static $posts;

	if( is_null( $posts ) ) {
		$posts = array();
	}

	return $posts;
}

/**
 * Get Cached Terms
 * @return array
 */
function &boombox_get_cached_terms() {
	static $terms;

	if( is_null( $terms ) ) {
		$terms = array();
	}

	return $terms;
}

/**
 * Callback to cache current post
 *
 * @param $current_post
 */
function boombox_cache_post( $current_post ) {
	$cached_posts = &boombox_get_cached_posts();
	if( !isset( $cached_posts[ $current_post->ID ]['post'] ) ) {
		$cached_posts[ $current_post->ID ] = isset( $cached_posts[ $current_post->ID ] ) ? $cached_posts[ $current_post->ID ] : array();
		$cached_posts[ $current_post->ID ] = array_merge( $cached_posts[ $current_post->ID ], array( 'post' => $current_post ) );
	}
}

/**
 * Get post meta and cache it
 *
 * @param $post_id
 * @param string $key
 * @return mixed
 */
function boombox_get_post_meta( $post_id, $key = '' ) {

	if( ! class_exists( 'Boombox_Base_Metabox' ) ) {
		include_once BOOMBOX_ADMIN_PATH . 'metaboxes/lib/class-boombox-base-metabox.php';
	}

	$cached_posts = &boombox_get_cached_posts();
	$special_key = Boombox_Base_Metabox::$key;

	if( !isset( $cached_posts[ $post_id ]['metas'] ) ) {
		$cached_posts[ $post_id ] = isset( $cached_posts[ $post_id ] ) ? $cached_posts[ $post_id ] : array();

		$metas = (array)get_post_meta( $post_id );

		$meta_cache = array();
		foreach( $metas as $meta_key => $meta_value ) {
			$meta_cache[ $meta_key ] = $meta_value[0];
		}
		$cached_posts[ $post_id ] = array_merge( $cached_posts[ $post_id ], array( 'metas' => $meta_cache ) );
		if( isset( $cached_posts[ $post_id ][ 'metas' ][ $special_key ] ) ) {
			$cached_posts[ $post_id ][ 'metas' ][ $special_key ] = maybe_unserialize( $cached_posts[ $post_id ][ 'metas' ][ $special_key ] );
		}
	}

	if( ! $key ) {
		return $cached_posts[ $post_id ][ 'metas' ];
	}

	if( isset( $cached_posts[ $post_id ][ 'metas' ][ $special_key ][ $key ] ) ) {
		return maybe_unserialize( $cached_posts[ $post_id ][ 'metas' ][ $special_key ][ $key ] );
	} elseif( isset( $cached_posts[ $post_id ][ 'metas' ][ $key ] ) ) {
		return maybe_unserialize( $cached_posts[ $post_id ][ 'metas' ][ $key ] );
	}
	return null;
}

function boombox_get_term_meta( $term_id, $key = '' ) {

	$cached_terms = &boombox_get_cached_terms();
	if( !isset( $cached_terms[ $term_id ]['metas'] ) ) {

		$cached_terms[ $term_id ] = isset( $cached_terms[ $term_id ] ) ? $cached_terms[ $term_id ] : array();

		$metas = (array)get_term_meta( $term_id );

		$meta_cache = array();
		foreach( $metas as $meta_key => $meta_value ) {
			$meta_cache[ $meta_key ] = $meta_value[0];
		}
		$cached_terms[ $term_id ] = array_merge( $cached_terms[ $term_id ], array( 'metas' => $meta_cache ) );
	}

	if( ! $key ) {
		return $cached_terms[ $term_id ][ 'metas' ];
	}

	return isset( $cached_terms[ $term_id ][ 'metas' ][ $key ] ) ? maybe_unserialize( $cached_terms[ $term_id ][ 'metas' ][ $key ] ) : null;
}

function boombox_add_front_page_seo_heading() {
	if( is_front_page() ) {
		echo sprintf( '<h1 class="mf-hide">%1$s</h1>', esc_html( get_bloginfo( 'name' ) ) );
	}
}

/**
 * Render microdata for single post
 *
 * @param $post_data
 */
function boombox_single_post_microdata( $post_data ) {

	if( isset( $post_data['post_thumbnail_html'] ) ) {
		preg_match('/src="([^"]+)/i', $post_data['post_thumbnail_html'], $thumbnail_url_matches);
		preg_match('/width="([^"]+)/i', $post_data['post_thumbnail_html'], $thumbnail_width_matches);
		preg_match('/height="([^"]+)/i', $post_data['post_thumbnail_html'], $thumbnail_height_matches);

		$src = isset( $thumbnail_url_matches[1] ) ? $thumbnail_url_matches[1] : false;
		$width = isset( $thumbnail_width_matches[1] ) ? absint( $thumbnail_width_matches[1] ) : false;
		$height = isset( $thumbnail_height_matches[1] ) ? absint( $thumbnail_height_matches[1] ) : false;

		if( $src && $width && $height ) {

			printf(
				'<span class="mf-hide" itemprop=image itemscope itemtype="https://schema.org/ImageObject">
					<meta itemprop="url" content="%1$s">
					<meta itemprop="width" content="%2$d">
					<meta itemprop="height" content="%3$d">
				</span>',
				$src,
				$width,
				$height
			);
		}
	}

	$logo_microdata = '';
	$boombox_logo = boombox_get_logo();
	if( ! empty( $boombox_logo ) ) {
		$logo_microdata = sprintf(
			'<span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
				<meta itemprop="url" content="%1$s">
			</span>',
			esc_url( $boombox_logo['src'] )
		);
	}

	printf(
		'<span class="mf-hide" itemprop="publisher" itemscope="" itemtype="https://schema.org/Organization">
			%1$s
			<meta itemprop="name" content="%2$s">
			<meta itemprop="url" content="%3$s">
		</span>
		<meta itemscope itemprop=mainEntityOfPage itemType="https://schema.org/WebPage" itemid="%4$s"/>',
		$logo_microdata,
		get_bloginfo( 'name' ),
		esc_url( home_url( '/' ) ),
		get_permalink()
	);
}

/**
 * Single post article structured data
 */
function boombox_single_article_structured_data() {
    $data = apply_filters( 'boombox/single/article-structured-data', array(
        "data-post-id"  => get_the_ID(),
        "itemscope"     => "",
        "itemtype"      => "http://schema.org/Article"
    ) );

    $data_array = array();
    foreach( (array)$data as $property => $value ) {
        $data_array[] = sprintf( '%1$s="%2$s"', $property, $value );
    }

    echo implode( ' ', $data_array );
}
/**
 * Modify redirect url to home page
 *
 * @param $logout_url
 * @param $redirect
 * @return string
 */
function boombox_logout_url( $logout_url, $redirect ) {
	$prefered_redirect_url = esc_url( home_url( '/' ) );
	if( $redirect != $prefered_redirect_url ) {
		$logout_url = wp_logout_url( $prefered_redirect_url );
	}
	return $logout_url;
}

/**
 * Render user profile icon in header auth box
 * @param $boombox_header_settings
 */
function boombox_auth_box_render_profile_icon( $boombox_header_settings ) {
	if( boombox_disabled_site_auth() ) {
		return;
	}
	echo boombox_get_profile_button();
}

/**
 * Render create post button in header auth box
 * @param $boombox_header_settings
 */
function boombox_auth_box_create_post_button( $boombox_header_settings ) {
	if( boombox_disabled_site_auth() ) {
		return;
	}

	echo boombox_get_create_post_button(
			array( 'create-post' ),
			$boombox_header_settings['header_button_text'],
			$boombox_header_settings['enable_plus_icon_on_button'],
			$boombox_header_settings['header_button_link']
	);
}

if( !function_exists( 'boombox_recaptcha_http_request_timeout' ) ) {
	/**
	 * Set optimal duration of HTTP request timeout for google recaptcha validating
	 *
	 * @param $val
	 * @return int
	 */
	function boombox_recaptcha_http_request_timeout($val) {
		return 5;
	}

}

/**
 * Change loop item URL
 *
 * @param $url
 * @param $post_id
 * @return string
 */
function boombox_loop_item_url( $url, $post_id ) {
	$affiliate_url = boombox_get_post_meta( $post_id, 'boombox_post_affiliate_link' );
	$use_as_post_link = boombox_get_post_meta( $post_id, 'boombox_post_affiliate_link_use_as_post_link' );

	if( $affiliate_url && $use_as_post_link ) {
		$url = $affiliate_url;
	}

	return esc_url( $url );
}

/**
 * Change loop item URL target
 *
 * @param $target
 * @param $permalink
 * @param $url
 * @return string
 */
function boombox_loop_item_url_target( $target, $permalink, $url ) {
	if( $permalink != $url ) {
		$target = 'target="_blank"';
	}
	return $target;
}

/**
 * Return true, if is NSFW post
 *
 * @param $post_id
 *
 * @return bool
 */
function boombox_is_nsfw_post( $post_id = 0 ){
	if( 0 == $post_id ){
		global $post;
		if( is_object( $post ) ){
			$post_id = $post->ID;
		}
	}

	static $checked_posts;
	$checked_posts = $checked_posts ? $checked_posts : array();

	if( ! isset( $checked_posts[ $post_id ] ) ) {
		$nsfw_category = apply_filters( 'boombox/nsfw_category', 'nsfw' );
		$checked_posts[ $post_id ] = ( has_category( $nsfw_category, $post_id ) && ! is_user_logged_in() );
	}

	return $checked_posts[ $post_id ];
}