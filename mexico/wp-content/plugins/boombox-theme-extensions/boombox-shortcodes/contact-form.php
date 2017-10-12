<?php
/**
 * Shortcode for Contact Form
 *
 * @package BoomBox_Theme_Extensions
 *
 */

// Prevent direct script access
if ( ! defined( 'ABSPATH' ) ) {
	die ( 'No direct script access allowed' );
}

add_shortcode( 'boombox_contact_form', 'boombox_contact_form' );

/**
 * Hooks
 */
add_action( 'wp_ajax_contact_form_submit', 'boombox_contact_form_submit_callback' );
add_action( 'wp_ajax_nopriv_contact_form_submit', 'boombox_contact_form_submit_callback' );
add_action( 'wp_enqueue_scripts', 'boombox_contact_form_scripts' );

/**
 * Enqueue scripts for contact form
 */
function boombox_contact_form_scripts() {
	wp_enqueue_script( 'boombox-shortcodes', BBTE_PLUGIN_URL . '/boombox-shortcodes/js/shortcodes.min.js', array( 'jquery' ), '20160609', true );
	wp_localize_script( 'boombox-shortcodes', 'params', array(
		'ajax_url'           => admin_url( 'admin-ajax.php' ),
		'success_message'    => apply_filters( 'bbte_contact_form_success_message', esc_html__( 'Your message successfully submitted!', 'boombox-theme-extensions' ) ),
		'error_message'      => apply_filters( 'bbte_contact_form_error_message', esc_html__( 'Please fill all required fields!', 'boombox-theme-extensions' ) ),
		'wrong_message'      => apply_filters( 'bbte_contact_form_wrong_message', esc_html__( 'Something went wrong, please try again!', 'boombox-theme-extensions' ) ),
		'captcha_file_url'   => esc_url( BBTE_PLUGIN_URL . '/boombox-shortcodes/captcha.php' ),
		'captcha_type'	     => boombox_auth_captcha_type(),
	) );
}

/**
 * Contact Form Shortcode
 *
 * @param $atts
 *
 * @return string
 */
function boombox_contact_form( $atts ) {
	$a = shortcode_atts( array(
		'message_placeholder' 	=> esc_html__( 'Your Message', 'boombox-theme-extensions' ),
		'label_submit' 			=> esc_html__( 'Submit Your Message', 'boombox-theme-extensions' ),
		'captcha'				=> 1
	), $atts );

	$boombox_auth_captcha_type = boombox_auth_captcha_type();
	$boombox_auth_google_recaptcha_site_key = boombox_get_theme_option( 'auth_google_recaptcha_site_key' );

	$message_placeholder = esc_html( $a['message_placeholder'] );
	$label_submit        = esc_html( $a['label_submit'] );
	$use_captcha	     = (bool)absint( $a['captcha'] ) && $boombox_auth_captcha_type;

	$submit_html = sprintf(
		'<div class="input-field form-submit">
			<input name="submit" type="submit" id="submit" class="submit pull-right" value="%s">
		</div>',
		$label_submit
	);

	ob_start();
	?>
	<div class="boombox-contact-form-wrap">
		<div class="boombox-contact-form-message bb-txt-msg msg-error"></div>
		<form class="boombox-contact-form" action="contact_form" >
			<div class="bb-form-block">
				<div class="row">
					<div class="input-field col-lg-6 col-sm-6">
						<input name="boombox_name" type="text" placeholder="<?php echo esc_html__( 'Name', 'boombox-theme-extensions' ); ?> *">
					</div>
					<div class="input-field col-lg-6 col-sm-6">
						<input name="boombox_email" type="email" placeholder="<?php echo esc_html__( 'Email', 'boombox-theme-extensions' ); ?> *">
					</div>
					<div class="col-lg-12 input-field">
						<textarea name="boombox_comment" placeholder="<?php echo $message_placeholder; ?> *"></textarea>
					</div>
				</div>
			</div>

			<?php if( $use_captcha ) { ?>

				<div class="bb-row-captcha">
					<div class="captcha-col">
						<?php if( $boombox_auth_captcha_type === 'image' ) { ?>
						<div class="captcha-container loading">
							<div class="form-captcha">
								<img src="" alt="Captcha!" class="captcha" />
								<a href="#refresh-captcha" class="boombox-refresh-captcha refresh-captcha"></a>
							</div>
							<input type="text" name="boombox_captcha_code" class="required" placeholder="<?php echo esc_html__('Enter captcha', 'boombox'); ?>">
						</div>
						<?php } elseif( $boombox_auth_captcha_type === 'google' ) { ?>
						<div class="google-captcha-code" id="boombox-contact-captcha" data-boombox-sitekey="<?php echo $boombox_auth_google_recaptcha_site_key; ?>"></div>
						<?php } ?>
					</div>
					<div class="btn-col">
						<?php echo $submit_html; ?>
					</div>
				</div>

			<?php } else { ?>

			<div class="row">
				<div class="col-lg-12">
					<?php echo $submit_html; ?>
				</div>
			</div>

			<?php } ?>

		</form>
	</div>
	<?php

	return ob_get_clean();
}

/**
 * Contact form submit callback
 */
function boombox_contact_form_submit_callback(){
	$valid   = array();
	$sent    = false;
	$message = array();

	$name          = sanitize_text_field( $_POST['name'] );
	$email         = sanitize_email( $_POST['email'] );
	$comment 	   = esc_textarea( $_POST['comment'] );
	$check_captcha = (bool)sanitize_text_field( $_POST['check_captcha'] );

	$admin_email = apply_filters( 'boombox_contact_form_admin_email', get_option( 'admin_email' ) );

	if ( '' == $name ) {
		$valid['name'] = false;
		$message[]     = esc_html__( 'Please enter the name.', 'boombox-theme-extensions' );
	}

	if ( ! is_email( $email ) ) {
		$valid['email'] = false;
		$message[]      = esc_html__( 'Please enter valid email address.', 'boombox-theme-extensions' );
	}

	if ( '' == $comment ) {
		$valid['comment'] = false;
		$message[]        = esc_html__( 'Please fill the comment.', 'boombox-theme-extensions' );
	}

	// Check the captcha
	if($check_captcha) {
		$boombox_auth_captcha_type = boombox_auth_captcha_type();

		if( $boombox_auth_captcha_type === 'image' ) {
			// image captcha validation
			if ( ! boombox_validate_image_captcha( 'captcha', 'contact_form' ) ) {
				$valid['captcha'] = false;
				$message[] = esc_html__('Invalid Captcha! Please, try again.', 'boombox-theme-extensions');
			}
			session_write_close();

		} elseif( $boombox_auth_captcha_type === 'google' ) { // google captcha validation
			// google captcha validation
			$gcaptcha = boombox_validate_google_captcha( 'captcha' );

			if( !$gcaptcha['success'] ) {
				$valid['captcha'] = false;
				$message[] = esc_html__('Invalid Captcha! Please, try again.', 'boombox-theme-extensions');
			}

		}
	}

	if ( empty( $valid ) ) {

		$site_name = get_bloginfo( 'name' );
		$subject   = sprintf( '%1$s %2$s', $site_name, esc_html__( 'Contact Form', 'boombox-theme-extensions' ) );
		$subject   = apply_filters( 'boombox_contact_form_subject', $subject );
		$headers[] = "From: {$name} <{$email}>";
		$headers[] = 'content-type: text/html';

		$body = sprintf( '<b>%1$s:</b> %2$s<br/>  <b>%3$s:</b> %4$s<br/> <b>%5$s:</b> %6$s',
			esc_html__( 'Name', 'boombox-theme-extensions' ), $name,
			esc_html__( 'Email', 'boombox-theme-extensions' ), $email,
			esc_html__( 'Message', 'boombox-theme-extensions' ), $comment
		);

		$sent = wp_mail( $admin_email, $subject, $body, $headers );
	}

	$result = array(
		'valid'   => $valid,
		'message' => ! empty( $message ) ? implode( '<br />', $message ) : '',
		'sent'    => $sent
	);

	echo json_encode( $result );

	wp_die();
}

if( ! function_exists( 'boombox_validate_google_captcha' ) ) {
	/**
	 * Validate google captcha response
	 *
	 * @param $key The key in $_POST array where response is set
	 * @return array
	 */
	function boombox_validate_google_captcha($key) {

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
}

if( !function_exists( 'boombox_recaptcha_http_request_timeout' ) ) {

	/**
	 * Set optimal duration of HTTP request timeout for google recaptcha validating
	 * @param $val
	 * @return int
	 */
	function boombox_recaptcha_http_request_timeout( $val ) {
		return 5; // seconds
	}

}