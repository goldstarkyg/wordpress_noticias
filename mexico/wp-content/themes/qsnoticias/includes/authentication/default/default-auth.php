<?php
/**
 * Boombox default authentication
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}


/**
 * Hooks
 */

// Enable the user with no privileges to run ajax_login() in AJAX
add_action( 'wp_ajax_nopriv_boombox_ajax_login', 'boombox_ajax_login' );

// Enable the user with no privileges to run ajax_register() in AJAX
add_action( 'wp_ajax_nopriv_boombox_ajax_register', 'boombox_ajax_register' );

// Enable the user with no privileges to run ajax_forgotPassword() in AJAX
add_action( 'wp_ajax_nopriv_boombox_ajax_forgot_password', 'boombox_ajax_forgot_password' );

// Enqueue scripts
add_action( 'wp_enqueue_scripts', 'boombox_default_auth_scripts' );


/**
 * Enqueue Global Authentication scripts
 */
function boombox_default_auth_scripts() {
	global $wp;
	wp_enqueue_script( 'boombox-validate-scripts', BOOMBOX_INCLUDES_URL . 'authentication/assets/js/jquery.validate.min.js', array( 'jquery' ), boombox_get_assets_version(), true );
	wp_enqueue_script( 'boombox-default-auth-scripts', BOOMBOX_INCLUDES_URL . 'authentication/default/js/default-auth-scripts.min.js', array( 'jquery' ), boombox_get_assets_version(), true );

	$current_url = esc_url( home_url( add_query_arg( array(), $wp->request ) ) );
	$ajax_auth_object = array(
		'ajaxurl'               		=> admin_url( 'admin-ajax.php' ),
		'login_redirect_url'    		=> apply_filters( 'boombox_auth_login_redirect_url', $current_url ),
		'register_redirect_url' 		=> apply_filters( 'boombox_auth_register_redirect_url', site_url() ),
		'nsfw_redirect_url'     		=> apply_filters( 'boombox_auth_nsfw_redirect_url', $current_url ),
		'loading_message'       		=> esc_html__( 'Sending user info, please wait...', 'boombox' ),
		'captcha_file_url'      		=> BOOMBOX_INCLUDES_URL . 'authentication/default/captcha/captcha-security-image.php',
		'enable_login_captcha'			=> boombox_get_theme_option( 'auth_enable_login_captcha' ),
		'enable_registration_captcha'	=> boombox_get_theme_option( 'auth_enable_registration_captcha' ),
		'captcha_type'					=> boombox_auth_captcha_type(),
		'site_primary_color'    		=> boombox_get_theme_option( 'design_global_primary_color' )
	);

	wp_localize_script( 'boombox-default-auth-scripts', 'ajax_auth_object', $ajax_auth_object );
}


/**
 * Ajax Login
 */
function boombox_ajax_login() {

	// First check the nonce, if it fails the function will break
	if( apply_filters( 'boombox_ajax_login_check_referer', true ) ) {
		check_ajax_referer('ajax-login-nonce', 'security');
	}

	$boombox_enable_login_captcha = boombox_get_theme_option( 'auth_enable_login_captcha' );
	if( $boombox_enable_login_captcha ) {
		$boombox_auth_captcha_type = boombox_auth_captcha_type();

		if( $boombox_auth_captcha_type === 'image' ) { // image captcha validation

			// Second check the captcha, if it fails the function will break
			if (session_id() == '' || session_status() == PHP_SESSION_NONE) {
				session_start();
			}
			if ( ! boombox_validate_image_captcha( 'captcha', 'login' ) ) {
				echo json_encode(array(
					'loggedin' => false,
					'message' => esc_html__('Invalid Captcha. Please, try again.', 'boombox')
				));
				die();
			}
			session_write_close();

		} elseif( $boombox_auth_captcha_type === 'google' ) { // google captcha validation

			$gcaptcha = boombox_validate_google_captcha( 'captcha' );

			if( !$gcaptcha['success'] ) {
				echo json_encode(array(
					'loggedin' => false,
					'message' => esc_html__('Invalid Captcha. Please, try again.', 'boombox')
				));
				die();
			}

		}
	}

	// Nonce and captcha are checked, get the POST data and sign user on
	// Call auth_user_login
	boombox_auth_user_login( $_POST['useremail'], $_POST['password'], esc_html__( 'Login', 'boombox' ) );

	die();
}

/**
 * Ajax Registration
 */
function boombox_ajax_register() {

	// First check the nonce, if it fails the function will break
	if( apply_filters( 'boombox_ajax_register_check_referer', true ) ) {
		check_ajax_referer('ajax-register-nonce', 'security');
	}

	// Second check the captcha, if it fails the function will break
	$boombox_enable_registration_captcha = boombox_get_theme_option( 'auth_enable_registration_captcha' );
	if( $boombox_enable_registration_captcha ) {
		$boombox_auth_captcha_type = boombox_auth_captcha_type();

		if( $boombox_auth_captcha_type === 'image' ) { // image captcha validation

			if (session_id() == '' || session_status() == PHP_SESSION_NONE) {
				session_start();
			}
			if ( ! boombox_validate_image_captcha( 'captcha', 'register' ) ) {
				echo json_encode(array(
					'loggedin' => false,
					'message' => esc_html__('Invalid Captcha. Please, try again.', 'boombox')
				));
				die();
			}
			session_write_close();

		} elseif( $boombox_auth_captcha_type === 'google' ) { // google captcha validation

			$gcaptcha = boombox_validate_google_captcha( 'captcha' );

			if( !$gcaptcha['success'] ) {
				echo json_encode(array(
					'loggedin' => false,
					'message' => esc_html__('Invalid Captcha. Please, try again.', 'boombox')
				));
				die();
			}

		}
	}

	// Nonce is checked, get the POST data and sign user on
	$info                  = array();
	$info['user_nicename'] = $info['nickname'] = $info['display_name'] = $info['first_name'] = $info['user_login'] = sanitize_text_field( $_POST['username'] );
	$info['user_pass']     = sanitize_text_field( $_POST['password'] );
	$info['user_email']    = sanitize_email( $_POST['useremail'] );
	$info['role']          = get_option( 'default_role', 'contributor' );

	// Register the user
	$user_register = wp_insert_user( $info );
	if ( is_wp_error( $user_register ) ) {
		$error = $user_register->get_error_codes();

		if ( in_array( 'empty_user_login', $error ) ) {
			echo json_encode( array(
				'loggedin' => false,
				'message'  => esc_html( $user_register->get_error_message( 'empty_user_login' ) )
			) );
		} elseif ( in_array( 'existing_user_email', $error ) || in_array( 'existing_user_login', $error ) ) {
			echo json_encode( array(
				'loggedin' => false,
				'message'  => esc_html__( 'This email address is already registered.', 'boombox' )
			) );
		}
	} else {
		boombox_auth_user_login( $info['user_email'], $info['user_pass'], esc_html__( 'Registration', 'boombox' ) );
	}

	die();
}

/**
 * Auth user login
 *
 * @param $user_email
 * @param $password
 * @param $login
 */
function boombox_auth_user_login( $user_email, $password, $login ) {
	$info = array();
	$user = get_user_by( 'email', $user_email );
	if ( ! $user && strtolower( $login ) == 'login' ) {
		$user = get_user_by( 'login', $user_email );
	}
	if ( $user ) {
		$info['user_login']    = $user->user_login;
		$info['user_password'] = $password;
		$info['remember']      = true;

		$user_signon = wp_signon( $info, false );
		$redirect_url = ( isset( $_POST['redirect'] ) && $_POST['redirect'] ) ? $_POST['redirect'] : false;
		if ( is_wp_error( $user_signon ) ) {

			do_action( 'boombox_after_user_login_fail', $user_signon, $redirect_url );

			echo json_encode( array(
				'loggedin' => false,
				'message'  => esc_html__( 'Wrong username or password.', 'boombox' )
			) );
		} else {
            $user = wp_set_current_user( $user_signon->ID );
            wp_set_auth_cookie( $user->ID );

            do_action( 'boombox_after_user_login_success', $user, $redirect_url );
			echo json_encode( array(
				'loggedin' => true,
				'message'  => $login . esc_html__( ' successful, redirecting...', 'boombox' )
			) );
		}
	} else {
		echo json_encode( array(
			'loggedin' => false,
			'message'  => esc_html__( 'There is no user registered with that username or email address.', 'boombox' )
		) );
	}

	die();
}

/**
 * Ajax Forgot Password
 */
function boombox_ajax_forgot_password() {

	// First check the nonce, if it fails the function will break
	check_ajax_referer( 'ajax-forgot-nonce', 'security' );
	$account = $_POST['userlogin'];
	$get_by  = 'email';

	$is_valid = true;
	$message = '';

	if ( empty( $account ) ) {
		$is_valid = false;
		$message = esc_html__('Enter an username or e-mail address.', 'boombox' );
	} else {
		if ( is_email( $account ) ) {
			if ( email_exists( $account ) ) {
				$get_by = 'email';
				$user_property = 'user_email';
			} else {
				$is_valid = false;
				$message = esc_html__( 'There is no user registered with that email address.', 'boombox' );
			}
		} else if ( validate_username( $account ) ) {
			if ( username_exists( $account ) ) {
				$get_by = 'login';
				$user_property = 'user_login';
			} else {
				$is_valid = false;
				$message = esc_html__( 'There is no user registered with that username.', 'boombox' );
			}
		} else {
			$is_valid = false;
			$message = esc_html__( 'Invalid username or e-mail address.', 'boombox' );
		}
	}

	if ( $is_valid ) {

		// Get user data by field and data, fields are id, slug, email and login
		$user_data = get_user_by( $get_by, $account );

		if( $user_data ) {

			/********** Headers **********/
			$from = get_option( 'admin_email' );
			if ( ! ( isset( $from ) && is_email( $from ) ) ) {
				$sitename = strtolower( $_SERVER['SERVER_NAME'] );
				if ( substr( $sitename, 0, 4 ) == 'www.' ) {
					$sitename = substr( $sitename, 4 );
				}
				$from = 'admin@' . $sitename;
			}

			$headers = array(
				"Content-type: text/plain; charset=UTF-8",
				sprintf( 'From: %1$s <%2$s>', get_option( 'blogname' ), $from )
			);

			/********** Subject **********/
			if ( is_multisite() ) {
				$blogname = get_network()->site_name;
			} else {
				$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			}
			$subject = sprintf( __('[%s] Password Reset'), $blogname );

			/********** Content **********/
			$user_login = $user_data->user_login;
			$key = get_password_reset_key( $user_data );

			$content = __('Someone has requested a password reset for the following account:') . "\r\n\r\n";
			$content .= network_home_url( '/' ) . "\r\n\r\n";
			$content .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
			$content .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
			$content .= __('To reset your password, visit the following address:') . "\r\n\r\n";
			$content .= '< ' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . " >\r\n";

			$subject = apply_filters( 'retrieve_password_title', $subject, $user_login, $user_data );
			$content = apply_filters( 'retrieve_password_message', $content, $key, $user_login, $user_data );

			if ( wp_mail( $user_data->user_email, wp_specialchars_decode( $subject ), $content, $headers ) ) {
				$message = esc_html__( 'Confirmation email is sent to your email address', 'boombox' );
			} else {
				$is_valid = false;
				$message = esc_html__( 'Oops! Something went wrong while updating your account.', 'boombox' );
			}

		}

	}

	$response = array( 'message' => $message );

	if ( $is_valid ) {
		wp_send_json_success( $response );
	} else {
		wp_send_json_error( $response );
	}

}