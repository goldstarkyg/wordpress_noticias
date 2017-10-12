<?php
/**
 * Buddypress plugin functions
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

if( boombox_is_plugin_active( 'buddypress/bp-loader.php' ) ) {


    if( ! class_exists( 'Boombox_Buddypress' ) ) {

        class Boombox_Buddypress {

            /**
             * Singleton.
             */
            static function get_instance() {
                static $Inst = null;
                if ($Inst == null) {
                    $Inst = new self();
                }

                return $Inst;
            }

            /**
             * Constructor
             */
            function __construct() {
                $this->define_constants();
                $this->hooks();
            }

            /**
             * Define constants
             */
            private function define_constants() {
                define ( 'BP_AVATAR_FULL_WIDTH', 186 );
                define ( 'BP_AVATAR_FULL_HEIGHT', 186 );
                define ( 'BP_AVATAR_THUMB_WIDTH', 66 );
                define ( 'BP_AVATAR_THUMB_HEIGHT', 66 );

                define ( 'BP_AVATAR_DEFAULT', apply_filters( 'boombox/buddypress/default-avatar', BOOMBOX_THEME_URL . 'buddypress/images/user.jpg' ) );
                define ( 'BP_AVATAR_DEFAULT_THUMB', apply_filters( 'boombox/buddypress/default-avatar-thumb', BOOMBOX_THEME_URL . 'buddypress/images/user-150.jpg' ) );
            }

            /**
             * Setup Hooks
             */
            private function hooks() {

                add_filter( 'boombox_author_avatar_size', array( $this, 'author_avatar_size' ), 10, 1 );
                add_filter( 'bp_before_xprofile_cover_image_settings_parse_args', array( $this, 'attach_theme_handle' ), 10, 1 );
                add_filter( 'bp_before_groups_cover_image_settings_parse_args', array( $this, 'attach_theme_handle' ), 10, 1 );
                add_filter( 'bp_core_get_js_strings', array( $this, 'bbp_core_get_js_strings' ), 10, 1 );
                add_filter( 'bp_get_add_friend_button', array( $this, 'get_add_friend_button' ), 10, 1 );
                add_filter( 'bp_get_send_message_button_args', array( $this, 'get_send_message_button_args' ), 10, 1 );
                add_filter( 'bp_get_send_public_message_button', array( $this, 'get_send_public_message_button' ), 10, 1 );
                add_filter( 'author_link', array( $this, 'author_link' ), 10, 3 );
                add_filter( 'bp_core_avatar_default', array( $this, 'groups_default_avatar' ), 10, 3 );
                add_filter( 'bp_core_avatar_default_thumb', array( $this, 'groups_default_avatar' ), 10, 3 );
                add_filter( 'bp_core_avatar_default', array( $this, 'user_default_avatar' ), 10, 3 );
                add_filter( 'bp_core_avatar_default_thumb', array( $this, 'user_default_avatar' ), 10, 3 );
                add_filter( 'bp_core_fetch_avatar_no_grav', array( $this, 'force_no_grav' ), 10, 2 );
                add_filter( 'bp_get_new_group_invite_friend_list', array( $this, 'get_new_group_invite_friend_list' ), 10, 3);
                add_filter( 'bp_nav_menu_objects', array( $this, 'nav_menu_objects' ), 9999, 2 );
                add_filter( 'bp_get_the_profile_field_required_label', array( $this, 'bbp_get_the_profile_field_required_label' ), 10, 2 );
                add_filter( 'bp_members_signup_error_message', array( $this, 'bbp_members_signup_error_message' ), 10, 1 );
                add_filter( 'author_extended_data', array( $this, 'bbp_author_extended_data' ), 10, 2 );
                add_filter( 'author_bio', array( $this, 'bbp_author_bio' ), 10, 2 );
                add_filter( 'boombox/color_scheme_styles', array( $this, 'bbp_color_scheme_styles' ), 10, 1 );
                add_filter( 'wpseo_title', array( $this, 'boombox_wpseo_fix_title_buddypress' ), 10, 1 );

                if( has_action( 'bp_notification_settings', 'bp_activity_screen_notification_settings' ) ) {
                    remove_action( 'bp_notification_settings', 'bp_activity_screen_notification_settings', 1 );
                    add_action( 'bp_notification_settings', array( $this, 'bp_activity_screen_notification_settings' ), 1 );
                }

                if( has_action( 'bp_notification_settings', 'friends_screen_notification_settings' ) ) {
                    remove_action( 'bp_notification_settings', 'friends_screen_notification_settings' );
                    add_action( 'bp_notification_settings', array( $this, 'friends_screen_notification_settings' ) );
                }

                if( has_action( 'bp_notification_settings', 'messages_screen_notification_settings' ) ) {
                    remove_action( 'bp_notification_settings', 'messages_screen_notification_settings', 2 );
                    add_action( 'bp_notification_settings', array( $this, 'messages_screen_notification_settings' ), 3 );
                }

                if( has_action( 'bp_notification_settings', 'groups_screen_notification_settings' ) ) {
                    remove_action( 'bp_notification_settings', 'groups_screen_notification_settings' );
                    add_action( 'bp_notification_settings', array( $this, 'groups_screen_notification_settings' ), 4 );
                }

                add_action( 'bp_before_member_home_content', array( $this, 'before_member_home_content' ), 10 );
                add_action( 'bp_after_member_home_content', array( $this, 'after_member_home_content' ), 10 );
                add_action( 'bp_before_group_home_content', array( $this, 'before_group_home_content' ), 10 );
                add_action( 'bp_after_group_home_content', array( $this, 'after_group_home_content' ), 10 );
                add_action( 'boombox/auth_box_icons', array( $this, 'user_notifications' ), 25 );
                add_action( 'bbp_captcha', array( $this, 'bbp_before_registration_submit_buttons' ), 10 );
                add_action( 'bp_signup_validate', array( $this, 'bbp_signup_validate' ), 10 );
                add_action( 'bp_before_member_header_meta', array( $this, 'member_social_xprofile_data' ), 20 );
                add_action( 'wp_head', array( $this, 'generate_user_meta_description' ), 10, 1 );

            }

            /**
             * Fix Yoast Seo broken title
             * @param $title
             * @return string
             */
            function boombox_wpseo_fix_title_buddypress( $title ) {
                // Check if we are in a buddypress page
                if ( ( is_user_logged_in() && isset( buddypress()->displayed_user->id ) && buddypress()->displayed_user->id ) || buddypress()->current_component ) {
                    $bp_title_parts = bp_modify_document_title_parts();

                    // let's rebuild the title here
                    $title = $bp_title_parts['title'] . ' ' . $title;
                }
                return $title;
            }

            /**
             * Modify error message layout
             *
             * @param $error_message
             * @return string
             */
            function bbp_members_signup_error_message( $error_message ) {
                return sprintf( '<div class="error bb-txt-msg msg-error">%1$s</div>', strip_tags( $error_message ) );
            }

            /**
             * Add additional JS variables
             *
             * @param $params
             * @return array
             */
            public function bbp_core_get_js_strings( $params ) {
                return array_merge( $params, array(
                    'captcha_file_url' => BOOMBOX_INCLUDES_URL . 'authentication/default/captcha/captcha-security-image.php',
                ) );
            }

            /**
             * Get captcha field name
             *
             * @param $type
             * @return string
             */
            private function get_recaptcha_fieldname( $type ) {
                switch( $type ) {
                    case 'image':
                        $fieldname = 'bp_register';
                        break;
                    case 'google':
                        $fieldname = 'g-recaptcha-response';
                        break;
                    default:
                        $fieldname = false;
                }
                return $fieldname;
            }

            /**
             * Additional validation rules
             */
            public function bbp_signup_validate() {

                $boombox_auth_is_disabled = boombox_disabled_site_auth();
                $boombox_auth_captcha_type = boombox_auth_captcha_type();
                $boombox_enable_registration_captcha = boombox_get_theme_option( 'auth_enable_registration_captcha' );

                if( ! $boombox_auth_is_disabled && ! is_user_logged_in() && $boombox_enable_registration_captcha ) {
                    $bp = buddypress();

                    if( $boombox_auth_captcha_type === 'image' ) {

                        $fieldname = $this->get_recaptcha_fieldname( $boombox_auth_captcha_type );
                        if( ! boombox_validate_image_captcha( $fieldname, 'bp_register' ) ) {
                            $bp->signup->errors[ $fieldname ] = __('Invalid captcha', 'buddypress');
                        }

                    } elseif( $boombox_auth_captcha_type === 'google' ) {

                        $fieldname = $this->get_recaptcha_fieldname( $boombox_auth_captcha_type );
                        $recaptcha_response = boombox_validate_google_captcha( $fieldname );

                        if (!$recaptcha_response['success']) {
                            $bp->signup->errors[ $fieldname ] = __('Invalid recaptcha.', 'buddypress');
                        }

                    }
                }
            }

            /**
             * Render captcha
             */
            public function bbp_before_registration_submit_buttons() {

                $boombox_auth_is_disabled = boombox_disabled_site_auth();
                $boombox_auth_captcha_type = boombox_auth_captcha_type();
                $boombox_enable_registration_captcha = boombox_get_theme_option( 'auth_enable_registration_captcha' );

                if( ! $boombox_auth_is_disabled && ! is_user_logged_in() && $boombox_enable_registration_captcha && $boombox_auth_captcha_type ) {

                    if( $boombox_auth_captcha_type === 'image' ) {

                        $fieldname = $this->get_recaptcha_fieldname( $boombox_auth_captcha_type );
                        do_action( 'bp_' . $fieldname . '_errors' );

                        echo sprintf( '<div class="input-field captcha-container loading">
							<div class="form-captcha">
								<img src="" alt="Captcha!" class="captcha">
								<a href="#refresh-captcha" class="bp-auth-refresh-captcha refresh-captcha" data-action="bp_register"></a>
							</div>
							<input type="text" name="%1$s" class="required" placeholder="%2$s">
						</div>',
                            $fieldname,
                            esc_html__( 'Enter captcha', 'boombox' )
                        );

                    } else if( $boombox_auth_captcha_type === 'google' ) {

                        do_action( 'bp_' . $this->get_recaptcha_fieldname( $boombox_auth_captcha_type ) . '_errors' );
                        echo sprintf('<div class="g-recaptcha google-captcha-code" data-sitekey="%1$s"></div>', boombox_get_theme_option('auth_google_recaptcha_site_key'));

                    }
                }
            }

            /**
             * Modify required label for profile fields
             *
             * @param $translated_string
             * @param $field_id
             * @return string
             */
            public function bbp_get_the_profile_field_required_label( $translated_string, $field_id ) {
                return '*';
            }

            /**
             * Set default avatar size
             *
             * @param $size
             * @return int
             */
            public function author_avatar_size( $size ) {
                return BP_AVATAR_THUMB_WIDTH;
            }

            /**
             * Hook into cover image to attach style handle for profile image
             */
            public function attach_theme_handle($settings = array()) {

                $theme_handle = 'bp-parent-css';
                if( is_rtl() ) {
                    $theme_handle .= '-rtl';
                }
                $settings['theme_handle'] = $theme_handle;
                $settings['width'] = 1920;
                $settings['height'] = 265;

                return apply_filters( 'boombox/buddypress/theme_default_settings', $settings );
            }

            /**
             * Hook into 'add friend' button args to modify required params
             */
            public function get_add_friend_button( $button_args ) {
                $button_args['link_class'] = 'btn btn-primary';

                return $button_args;
            }

            /**
             * Hook into 'private message' button args to modify required params
             */
            public function get_send_message_button_args( $button_args ) {
                $button_args['link_class'] = 'btn btn-primary';

                return $button_args;
            }

            /**
             * Hook into 'public message' button args to modify required params
             */
            public function get_send_public_message_button( $button_args ) {
                $button_args['link_class'] = 'btn btn-primary';

                return $button_args;
            }

            /**
             * Locate wordpress author post link to buddypress profile
             */
            public function author_link( $link, $author_id, $author_nicename ) {
                return bp_core_get_user_domain( $author_id );
            }

            /**
             * Hook for generate the "x members" count string for a group.
             */
            public function make_number_rounded( $value, $number, $decimals ) {
                return sprintf( '<span class="count">%s</span>', $value );
            }

            /**
             * Output the Group members template
             */
            public function groups_members_template_part() {
                ?>
                <div class="item-list-tabs" id="subnav" role="navigation">
                    <ul>
                        <?php do_action('bp_members_directory_member_sub_types'); ?>
                    </ul>
                </div>

                <div class="bbp-filters">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="bbp-filter">
                                <?php $this->groups_members_filter(); ?>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="bbp-search">
                                <div class="groups-members-search" role="search">
                                    <?php bp_directory_members_search_form(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="members-group-list" class="group_members dir-list">

                    <?php bp_get_template_part('groups/single/members'); ?>

                </div>
                <?php
            }

            /**
             * Output the Group members filters
             */
            public function groups_members_filter() {
                ?>
                <div id="group_members-order-select" class="filter">
                    <label for="group_members-order-by"><?php _e('Order By:', 'buddypress'); ?></label>
                    <select id="group_members-order-by">
                        <option value="last_joined"><?php _e('Newest', 'buddypress'); ?></option>
                        <option value="first_joined"><?php _e('Oldest', 'buddypress'); ?></option>

                        <?php if (bp_is_active('activity')) : ?>
                            <option value="group_activity"><?php _e('Group Activity', 'buddypress'); ?></option>
                        <?php endif; ?>

                        <option value="alphabetical"><?php _e('Alphabetical', 'buddypress'); ?></option>

                        <?php do_action('bp_groups_members_order_options'); ?>

                    </select>
                </div>
                <?php
            }

            /***
             * Modify groups/members default avatar
             */
            public function groups_default_avatar( $avatar, $params ) {
                if ( isset( $params['object'] ) && 'group' === $params['object'] ) {
                    if ( isset( $params['type'] ) && 'thumb' === $params['type'] ) {
                        $file = 'group-150';
                    } else {
                        $file = 'group';
                    }
                    $avatar = apply_filters( 'boombox/buddypress/group_default_avatar', BOOMBOX_THEME_URL . "buddypress/images/$file.jpg", $file );
                }

                return $avatar;
            }

            /***
             * Modify user default avatar
             */
            public function user_default_avatar( $avatar, $params ) {
                if ( isset( $params['object'] ) && 'user' === $params['object'] ) {
                    if ( isset( $params['type'] ) && 'thumb' === $params['type'] ) {
                        $file = 'user-150';
                    } else {
                        $file = 'user';
                    }
                    $avatar = apply_filters( 'boombox/buddypress/user_default_avatar', BOOMBOX_THEME_URL . "buddypress/images/$file.jpg", $file );
                }

                return $avatar;
            }

            /***
             * Force To Prevent Using Grav
             */
            public function force_no_grav( $no_grav, $params ) {
                $no_grav = true;

                return $no_grav;
            }

            /**
             * Change invited users list HTML
             */
            public function get_new_group_invite_friend_list( $items, $r, $args ) {
                $friends = friends_get_friends_invite_list( $r['user_id'], $r['group_id'] );

                if ( ! empty( $friends ) ) {

                    $items = array();

                    $invites = groups_get_invites_for_group( $r['user_id'], $r['group_id'] );

                    for ( $i = 0, $count = count( $friends ); $i < $count; ++$i ) {
                        $checked = in_array( (int) $friends[ $i ]['id'], (array) $invites );
                        $items[] = '<' . $r['separator'] . '><label class="bbp-checkbox" for="f-' . esc_attr( $friends[ $i ]['id'] ) . '"><input' . checked( $checked, true, false ) . ' type="checkbox" name="friends[]" id="f-' . esc_attr( $friends[ $i ]['id'] ) . '" value="' . esc_attr( $friends[ $i ]['id'] ) . '" /><span class="bbp-checkbox-check"></span>' . esc_html( $friends[ $i ]['full_name'] ) . '</label></' . $r['separator'] . '>';
                    }

                }

                return $items;
            }

            /**
             * Open wrapper for member/home templates
             */
            public function before_member_home_content() {
                $bbp_wrapper_classes = array('bbp-wrapper');

                if( ! bp_attachments_get_user_has_cover_image() ) {
                    $bbp_wrapper_classes[] = 'no-item-image';
                }

                echo sprintf( '<div class="%s">', implode(' ', $bbp_wrapper_classes) );
            }

            /**
             * Close wrapper for member/home templates
             */
            public function after_member_home_content() {
                echo sprintf( '</div>' );
            }

            /**
             * Open wrapper for group/home templates
             */
            public function before_group_home_content() {
                $bbp_wrapper_classes = array('bbp-wrapper');

                if( ! bp_attachments_get_group_has_cover_image() ) {
                    $bbp_wrapper_classes[] = 'no-item-image';
                }

                echo sprintf( '<div class="%s">', implode(' ', $bbp_wrapper_classes) );
            }

            /**
             * Close wrapper for group/home templates
             */
            public function after_group_home_content() {
                echo sprintf( '</div>' );
            }

            /**
             * Add additional data to profile extended box
             *
             * @param $extended_html
             * @param $user_id
             * @return string
             */
            public function bbp_author_extended_data( $extended_html, $user_id ) {
                $profile_socials = $this->get_xprofile_socials( $user_id );

                if( !empty( $profile_socials ) ) {
                    $website = $socials = $html = '';
                    $website_field_name = strtolower( apply_filters( 'bbp_website_field_name', 'website' ) );

                    foreach( $profile_socials as $name => $data ) {
                        if( $data[ 'bb_key' ] == $website_field_name ) {
                            $website = $data['field_data'];
                            continue;
                        }
                        $socials .= sprintf( '<li><a class="icon-%1$s" href="%2$s" title="%3$s" target="_blank" rel="nofollow"></a></li>', $data['icon'], $data['field_data'], $name );
                    }

                    if( $website ) {
                        $html .= sprintf( '<a class="website-url" href="%1$s" target="_blank" rel="nofollow">%1$s</a>', $website );
                    }

                    if( $socials ) {
                        $html .= sprintf( '<div class="social m-t"><ul>%s</ul></div>', $socials );
                    }

                    if( $html ) {
                        $extended_html .= sprintf( '<div class="clearfix">%s</div>', $html );
                    }
                }

                return $extended_html;
            }

            /**
             * Change profile description
             *
             * @param $bio
             * @param $user_id
             * @return mixed
             */
            public function bbp_author_bio( $bio, $user_id ) {
                $field_name = apply_filters( 'bbp_bio_field_name', 'biography' );
                $xprofile_field_id = xprofile_get_field_id_from_name( $field_name );
                if( $xprofile_field_id ) {
                    $xprofile_field_data = xprofile_get_field_data( $xprofile_field_id, $user_id );
                    if( $xprofile_field_data ) {
						return wpautop( $xprofile_field_data, false );
                    }
                }
                return $bio;
            }

            /**
             * Color scheme support
             *
             * @param $css
             * @return string
             * See boombox_global_style_css() for available colors
             */
            public function bbp_color_scheme_styles( $css ) {
                $css .= '

                    /* --Buddypress styles */

                    /* -link color */
                    #buddypress .visibility-toggle-link {
                      color:%21$s
                    }

                    /* Base Text Color */
                    .header .account-box .notifications-list.menu ul li a {
                        color: %8$s;
                    }

                    /* Heading Text Color */
                    #buddypress table th,
                    #buddypress .item-header a,
                    #buddypress .activity-header a,
                    #buddypress .acomment-header a,
                    #buddypress #invite-list label,
                    #buddypress .standard-form label,
                    #buddypress .standard-form legend,
                    #buddypress .standard-form span.label,
                    #buddypress .messages-notices .thread-from a,
                    #buddypress .messages-notices .thread-info a,
                    #buddypress #item-header-content .group-name,
                    #buddypress #item-header-content .member-name {
                        color: %10$s;
                    }

                    /* Secondary Text Color */
                    #buddypress .vp_dash_pagina a,
                    #buddypress .pagination-links a,
                    #buddypress .vp_dash_pagina span,
                    #buddypress .pagination-links span,
                    #buddypress .pagination .pag-count,
                    #buddypress .notification-description a,
                    #buddypress #item-header-content .group-activity,
                    #buddypress #item-header-content .member-activity,
                    #buddypress #register-page .field-visibility-settings-toggle, #buddypress #register-page .wp-social-login-connect-with, #buddypress .field-visibility-settings-close {
                        color: %9$s;
                    }

                    #buddypress #register-page ::-webkit-input-placeholder, #buddypress #activate-page ::-webkit-input-placeholder {
                        color: %9$s;
                    }
                    #buddypress #register-page ::-moz-placeholder, #buddypress #activate-page ::-moz-placeholder {
                        color: %9$s;
                    }
                    #buddypress #register-page :-ms-input-placeholder, #buddypress #activate-page :-ms-input-placeholder {
                        color: %9$s;
                    }
                    #buddypress #register-page :-moz-placeholder, #buddypress #activate-page :-moz-placeholder {
                        color: %9$s;
                    }


                    /* Global Border Color */
                    #buddypress table td,
                    #buddypress table th,
                    #buddypress .bbp-item-info,
                    #buddypress .activity-list li,
                    #buddypress .activity-meta a,
                    #buddypress .acomment-options a,
                    #buddypress .item-list .item-action a,
                    #buddypress .bbp-radio-check,
                    #buddypress .bbp-checkbox-check,
                    #buddypress .standard-form .submit,
                    #buddypress #invite-list li,
                    #buddypress #invite-list li:first-child,

                    #buddypress #blogs-list,
                    #buddypress #groups-list,
                    #buddypress #member-list,
                    #buddypress #friend-list,
                    #buddypress #admins-list,
                    #buddypress #mods-list,
                    #buddypress #members-list,
                    #buddypress #request-list,
                    #buddypress #group-list,

                    #buddypress #blogs-list li,
                    #buddypress #groups-list li,
                    #buddypress #member-list li,
                    #buddypress #friend-list li,
                    #buddypress #admins-list li,
                    #buddypress #mods-list li,
                    #buddypress #members-list li,
                    #buddypress #request-list li,
                    #buddypress #group-list li,

                    #buddypress .vp_post_entry,
                    #buddypress .vp_post_entry .col-lg-3 .entry-footer .post-edit-link,

                    #buddypress #register-page .standard-form .submit {
                        border-color: %13$s;
                    }

                    .bp-avatar-nav ul,
                    .bp-avatar-nav ul.avatar-nav-items li.current {
                        border-color: %13$s;
                    }

                    .bp-avatar-nav ul.avatar-nav-items li.current {
                        background-color: %13$s;
                    }

                    /* -secondary components bg color */
                    #buddypress .field-visibility-settings {
			            background-color: %14$s;
		            }

                    /* Primary Color */
                    #buddypress button,
                    #buddypress input[type=button],
                    #buddypress input[type=reset],
                    #buddypress input[type=submit],
                    #buddypress ul.button-nav li a,
                    #buddypress a.bp-title-button,
                    #buddypress .comment-reply-link,
                    #buddypress .activity-list .load-more a,
                    #buddypress .activity-list .load-newest a {
                        background-color: %6$s;
                    }

                    .header .account-box .notifications-list.menu ul li a:hover {
                        color: %6$s;
                    }

                    /* Primary Text */
                    #buddypress #register-page input[type=submit], #buddypress #activate-page input[type=submit] {
                        color: %7$s;
                    }

                    /* -content bg color */
                    #buddypress  #register-page .field-visibility-settings {
                      background-color: %5$s;
                    }

                    /* -border-radius */
                    #buddypress  #register-page .field-visibility-settings {
                      -webkit-border-radius: %11$s;
                      -moz-border-radius: %11$s;
                      border-radius: %11$s;
                     }

                    /* --border-radius inputs, buttons */
                    #buddypress #register-page input[type=submit], #buddypress #activate-page input[type=submit] ,
                    #buddypress .bb-form-block input, #buddypress .bb-form-block textarea, #buddypress .bb-form-block select {
                      -webkit-border-radius: %12$s;
                      -moz-border-radius: %12$s;
                      border-radius: %12$s;
                    }

                ';

                return $css;
            }

            /**
             * Social Media Icons based on the profile user info
             */
            public function member_social_xprofile_data() {

                $profile_socials = $this->get_xprofile_socials();

                $html = '';
                if( ! empty( $profile_socials ) ) {
                    $html .= '<ul class="bbp-social">';
                    foreach( $profile_socials as $name => $data ) {
                        $html .= sprintf( '<li><a href="%1$s" title="%2$s" target="_blank" rel="nofollow" ><span class="icon-%3$s"></span></a></li>', $data['field_data'], $name, $data['icon'] );
                    }
                    $html .= '</ul>';
                }

                echo $html;
            }

            /**
             * Get buildin social options from "boombox-theme-extensions" plugin
             *
             * @return array|bool
             */
            private function get_builtin_socials() {
                if( class_exists( 'Boombox_Social' ) ) {
                    return Boombox_Social::social_default_items();
                }
                return false;
            }

            /**
             * Get Extended profile social data
             * @return array
             */
            private function get_xprofile_socials( $user_id = false ) {
                if( ! $user_id ) {
                    $user_id = bp_displayed_user_id();
                }

                $profile_data = BP_XProfile_ProfileData::get_all_for_user( $user_id );
                $socials = array_merge(
                    (array)$this->get_builtin_socials(),
                    array(
                        'vkontakte' => array( 'icon' => 'vk', 'title' => 'Vkontakte', 'default' => '' ),
                        'odnoklassniki' => array( 'icon' => 'odnoklassniki', 'title' => 'Vkontakte', 'default' => '' ),
                        'stackoverflow' => array( 'icon' => 'stack-overflow', 'title' => 'Stack Overflow', 'default' => '' ),
                        'website'       => array( 'icon' => 'chain', 'title' => 'Website', 'default' => '' )
                    ),
                    apply_filters( 'bbp_additional_socials', array() )
                );

                $return = array();
                if( $socials ) {
                    foreach( $profile_data as $human_key => $data ) {

                        $key = strtr( strtolower( $human_key ), array( '-' => '', '_' => '', ' ' => '', '+' => 'plus' ) );
                        if( array_key_exists( $key, $socials ) ) {
                            $icon = ( isset( $socials[ $key ][ 'icon' ] ) && $socials[ $key ][ 'icon' ] ) ? $socials[ $key ][ 'icon' ] : false;
                            if( $icon ) {
                                $return[ $human_key ] = array_merge( $data, array('icon' => $icon, 'bb_key' => $key ) );
                            }
                        }
                    }
                }

                return $return;

            }

            /**
             * Replace some settings tables with Boombox ones
             */
            public function bp_activity_screen_notification_settings() {
                if ( bp_activity_do_mentions() ) {
                    if ( ! $mention = bp_get_user_meta( bp_displayed_user_id(), 'notification_activity_new_mention', true ) ) {
                        $mention = 'yes';
                    }
                }

                if ( ! $reply = bp_get_user_meta( bp_displayed_user_id(), 'notification_activity_new_reply', true ) ) {
                    $reply = 'yes';
                }

                ?>

                <table class="notification-settings" id="activity-notification-settings">
                    <thead>
                    <tr>
                        <th class="title"><?php _e( 'Activity', 'buddypress' ) ?></th>
                        <th class="yes"><?php _e( 'Yes', 'buddypress' ) ?></th>
                        <th class="no"><?php _e( 'No', 'buddypress' )?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if ( bp_activity_do_mentions() ) : ?>
                        <tr id="activity-notification-settings-mentions">
                            <td><?php printf( __( 'A member mentions you in an update using "@%s"', 'buddypress' ), bp_core_get_username( bp_displayed_user_id() ) ) ?></td>
                            <td class="yes">
                                <label for="notification-activity-new-mention-yes" class="bbp-radio">
                                    <input type="radio" name="notifications[notification_activity_new_mention]" id="notification-activity-new-mention-yes" value="yes" <?php checked( $mention, 'yes', true ) ?>/>
                                    <span class="bbp-radio-check"></span>
                            <span class="bp-screen-reader-text"><?php
                                /* translators: accessibility text */
                                _e( 'Yes, send email', 'buddypress' );
                                ?></span>
                                </label>
                            </td>
                            <td class="no">
                                <label for="notification-activity-new-mention-no" class="bbp-radio">
                                    <input type="radio" name="notifications[notification_activity_new_mention]" id="notification-activity-new-mention-no" value="no" <?php checked( $mention, 'no', true ) ?>/>
                                    <span class="bbp-radio-check"></span>
                            <span class="bp-screen-reader-text"><?php
                                /* translators: accessibility text */
                                _e( 'No, do not send email', 'buddypress' );
                                ?></span>
                                </label>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <tr id="activity-notification-settings-replies">
                        <td><?php _e( "A member replies to an update or comment you've posted", 'buddypress' ) ?></td>
                        <td class="yes">
                            <label for="notification-activity-new-reply-yes" class="bbp-radio">
                                <input type="radio" name="notifications[notification_activity_new_reply]" id="notification-activity-new-reply-yes" value="yes" <?php checked( $reply, 'yes', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                        <span class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'Yes, send email', 'buddypress' );
                            ?></span>
                            </label>
                        </td>
                        <td class="no">
                            <label for="notification-activity-new-reply-no" class="bbp-radio">
                                <input type="radio" name="notifications[notification_activity_new_reply]" id="notification-activity-new-reply-no" value="no" <?php checked( $reply, 'no', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                        <span class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'No, do not send email', 'buddypress' );
                            ?></span>
                            </label>
                        </td>
                    </tr>

                    <?php

                    /**
                     * Fires inside the closing </tbody> tag for activity screen notification settings.
                     *
                     * @since 1.2.0
                     */
                    do_action( 'bp_activity_screen_notification_settings' ) ?>
                    </tbody>
                </table>
            <?php }

            /**
             * Notification setting for 'friends' screen
             */
            public function friends_screen_notification_settings() {
                if ( !$send_requests = bp_get_user_meta( bp_displayed_user_id(), 'notification_friends_friendship_request', true ) )
                    $send_requests   = 'yes';

                if ( !$accept_requests = bp_get_user_meta( bp_displayed_user_id(), 'notification_friends_friendship_accepted', true ) )
                    $accept_requests = 'yes'; ?>

                <table class="notification-settings" id="friends-notification-settings">
                    <thead>
                    <tr>
                        <th class="title"><?php _ex( 'Friends', 'Friend settings on notification settings page', 'buddypress' ) ?></th>
                        <th class="yes"><?php _e( 'Yes', 'buddypress' ) ?></th>
                        <th class="no"><?php _e( 'No', 'buddypress' )?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr id="friends-notification-settings-request">
                        <td><?php _ex( 'A member sends you a friendship request', 'Friend settings on notification settings page', 'buddypress' ) ?></td>
                        <td class="yes">
                            <label for="notification-friends-friendship-request-yes" class="bbp-radio">
                                <input type="radio" name="notifications[notification_friends_friendship_request]" id="notification-friends-friendship-request-yes" value="yes" <?php checked( $send_requests, 'yes', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                        <span class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'Yes, send email', 'buddypress' );
                            ?></span>
                            </label>
                        </td>
                        <td class="no">
                            <label for="notification-friends-friendship-request-no" class="bbp-radio">
                                <input type="radio" name="notifications[notification_friends_friendship_request]" id="notification-friends-friendship-request-no" value="no" <?php checked( $send_requests, 'no', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                         <span class="bp-screen-reader-text"><?php
                             /* translators: accessibility text */
                             _e( 'No, do not send email', 'buddypress' );
                             ?></span>
                            </label>
                        </td>
                    </tr>
                    <tr id="friends-notification-settings-accepted">
                        <td><?php _ex( 'A member accepts your friendship request', 'Friend settings on notification settings page', 'buddypress' ) ?></td>
                        <td class="yes">
                            <label for="notification-friends-friendship-accepted-yes" class="bbp-radio">
                                <input type="radio" name="notifications[notification_friends_friendship_accepted]" id="notification-friends-friendship-accepted-yes" value="yes" <?php checked( $accept_requests, 'yes', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                        <span class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'Yes, send email', 'buddypress' );
                            ?></span>
                            </label>
                        </td>
                        <td class="no">
                            <label for="notification-friends-friendship-accepted-no" class="bbp-radio">
                                <input type="radio" name="notifications[notification_friends_friendship_accepted]" id="notification-friends-friendship-accepted-no" value="no" <?php checked( $accept_requests, 'no', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                        <span class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'No, do not send email', 'buddypress' );
                            ?></span>
                            </label>
                        </td>
                    </tr>

                    <?php

                    /**
                     * Fires after the last table row on the friends notification screen.
                     *
                     * @since 1.0.0
                     */
                    do_action( 'friends_screen_notification_settings' ); ?>

                    </tbody>
                </table>

                <?php
            }

            /**
             * Notification setting for 'messages' screen
             */
            public function messages_screen_notification_settings() {
                if ( bp_action_variables() ) {
                    bp_do_404();
                    return;
                }

                if ( !$new_messages = bp_get_user_meta( bp_displayed_user_id(), 'notification_messages_new_message', true ) ) {
                    $new_messages = 'yes';
                } ?>

                <table class="notification-settings" id="messages-notification-settings">
                    <thead>
                    <tr>
                        <th class="title"><?php _e( 'Messages', 'buddypress' ) ?></th>
                        <th class="yes"><?php _e( 'Yes', 'buddypress' ) ?></th>
                        <th class="no"><?php _e( 'No', 'buddypress' )?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr id="messages-notification-settings-new-message">
                        <td><?php _e( 'A member sends you a new message', 'buddypress' ) ?></td>
                        <td class="yes">
                            <label for="notification-messages-new-messages-yes" class="bbp-radio">
                                <input type="radio" name="notifications[notification_messages_new_message]" id="notification-messages-new-messages-yes" value="yes" <?php checked( $new_messages, 'yes', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                        <span class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'Yes, send email', 'buddypress' );
                            ?></span>
                            </label>
                        </td>
                        <td class="no">
                            <label for="notification-messages-new-messages-no" class="bbp-radio">
                                <input type="radio" name="notifications[notification_messages_new_message]" id="notification-messages-new-messages-no" value="no" <?php checked( $new_messages, 'no', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                        <span class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'No, do not send email', 'buddypress' );
                            ?></span>
                            </label>
                        </td>
                    </tr>

                    <?php

                    /**
                     * Fires inside the closing </tbody> tag for messages screen notification settings.
                     *
                     * @since 1.0.0
                     */
                    do_action( 'messages_screen_notification_settings' ); ?>
                    </tbody>
                </table>

                <?php
            }

            /**
             * Notification setting for 'groups' screen
             */
            public function groups_screen_notification_settings() {
                if ( !$group_invite = bp_get_user_meta( bp_displayed_user_id(), 'notification_groups_invite', true ) )
                    $group_invite  = 'yes';

                if ( !$group_update = bp_get_user_meta( bp_displayed_user_id(), 'notification_groups_group_updated', true ) )
                    $group_update  = 'yes';

                if ( !$group_promo = bp_get_user_meta( bp_displayed_user_id(), 'notification_groups_admin_promotion', true ) )
                    $group_promo   = 'yes';

                if ( !$group_request = bp_get_user_meta( bp_displayed_user_id(), 'notification_groups_membership_request', true ) )
                    $group_request = 'yes';

                if ( ! $group_request_completed = bp_get_user_meta( bp_displayed_user_id(), 'notification_membership_request_completed', true ) ) {
                    $group_request_completed = 'yes';
                }
                ?>

                <table class="notification-settings" id="groups-notification-settings">
                    <thead>
                    <tr>
                        <th class="title"><?php _ex( 'Groups', 'Group settings on notification settings page', 'buddypress' ) ?></th>
                        <th class="yes"><?php _e( 'Yes', 'buddypress' ) ?></th>
                        <th class="no"><?php _e( 'No', 'buddypress' )?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr id="groups-notification-settings-invitation">
                        <td><?php _ex( 'A member invites you to join a group', 'group settings on notification settings page','buddypress' ) ?></td>
                        <td class="yes">
                            <label for="notification-groups-invite-yes" class="bbp-radio">
                                <input type="radio" name="notifications[notification_groups_invite]" id="notification-groups-invite-yes" value="yes" <?php checked( $group_invite, 'yes', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                        <span class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'Yes, send email', 'buddypress' );
                            ?></span>
                            </label>
                        </td>
                        <td class="no">
                            <label for="notification-groups-invite-no" class="bbp-radio">
                                <input type="radio" name="notifications[notification_groups_invite]" id="notification-groups-invite-no" value="no" <?php checked( $group_invite, 'no', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                        <span class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'No, do not send email', 'buddypress' );
                            ?></span>
                            </label>
                        </td>
                    </tr>
                    <tr id="groups-notification-settings-info-updated">
                        <td><?php _ex( 'Group information is updated', 'group settings on notification settings page', 'buddypress' ) ?></td>
                        <td class="yes">
                            <label for="notification-groups-group-updated-yes" class="bbp-radio">
                                <input type="radio" name="notifications[notification_groups_group_updated]" id="notification-groups-group-updated-yes" value="yes" <?php checked( $group_update, 'yes', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                        <span class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'Yes, send email', 'buddypress' );
                            ?></span>
                            </label>
                        </td>
                        <td class="no">
                            <label for="notification-groups-group-updated-no" class="bbp-radio">
                                <input type="radio" name="notifications[notification_groups_group_updated]" id="notification-groups-group-updated-no" value="no" <?php checked( $group_update, 'no', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                        <span class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'No, do not send email', 'buddypress' );
                            ?></span>
                            </label>
                        </td>
                    </tr>
                    <tr id="groups-notification-settings-promoted">
                        <td><?php _ex( 'You are promoted to a group administrator or moderator', 'group settings on notification settings page', 'buddypress' ) ?></td>
                        <td class="yes">
                            <label for="notification-groups-admin-promotion-yes" class="bbp-radio">
                                <input type="radio" name="notifications[notification_groups_admin_promotion]" id="notification-groups-admin-promotion-yes" value="yes" <?php checked( $group_promo, 'yes', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                        <span class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'Yes, send email', 'buddypress' );
                            ?></span>
                            </label>
                        </td>
                        <td class="no">
                            <label for="notification-groups-admin-promotion-no" class="bbp-radio">
                                <input type="radio" name="notifications[notification_groups_admin_promotion]" id="notification-groups-admin-promotion-no" value="no" <?php checked( $group_promo, 'no', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                        <span class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'No, do not send email', 'buddypress' );
                            ?></span>
                            </label>
                        </td>
                    </tr>
                    <tr id="groups-notification-settings-request">
                        <td><?php _ex( 'A member requests to join a private group for which you are an admin', 'group settings on notification settings page', 'buddypress' ) ?></td>
                        <td class="yes">
                            <label for="notification-groups-membership-request-yes" class="bbp-radio">
                                <input type="radio" name="notifications[notification_groups_membership_request]" id="notification-groups-membership-request-yes" value="yes" <?php checked( $group_request, 'yes', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                        <span class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'Yes, send email', 'buddypress' );
                            ?></span>
                            </label>
                        </td>
                        <td class="no">
                            <label for="notification-groups-membership-request-no" class="bbp-radio">
                                <input type="radio" name="notifications[notification_groups_membership_request]" id="notification-groups-membership-request-no" value="no" <?php checked( $group_request, 'no', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                        <span class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'No, do not send email', 'buddypress' );
                            ?></span>
                            </label>
                        </td>
                    </tr>
                    <tr id="groups-notification-settings-request-completed">
                        <td><?php _ex( 'Your request to join a group has been approved or denied', 'group settings on notification settings page', 'buddypress' ) ?></td>
                        <td class="yes">
                            <label for="notification-groups-membership-request-completed-yes" class="bbp-radio">
                                <input type="radio" name="notifications[notification_membership_request_completed]" id="notification-groups-membership-request-completed-yes" value="yes" <?php checked( $group_request_completed, 'yes', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                        <span class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'Yes, send email', 'buddypress' );
                            ?></span>
                            </label>
                        </td>
                        <td class="no">
                            <label for="notification-groups-membership-request-completed-no" class="bbp-radio">
                                <input type="radio" name="notifications[notification_membership_request_completed]" id="notification-groups-membership-request-completed-no" value="no" <?php checked( $group_request_completed, 'no', true ) ?>/>
                                <span class="bbp-radio-check"></span>
                        <span class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'No, do not send email', 'buddypress' );
                            ?></span>
                            </label>
                        </td>
                    </tr>

                    <?php

                    /**
                     * Fires at the end of the available group settings fields on Notification Settings page.
                     *
                     * @since 1.0.0
                     */
                    do_action( 'groups_screen_notification_settings' ); ?>

                    </tbody>
                </table>

                <?php
            }

            /**
             * Render user notifications box
             */
            public function user_notifications() {
                if( boombox_disabled_site_auth() || !is_user_logged_in() || !bp_is_active( 'notifications' ) ) {
                    return;
                }

                $user_id = bp_loggedin_user_id();

                $max_show = 5;
                $count = bp_notifications_get_unread_notification_count( $user_id );
                $notifications = bp_notifications_get_notifications_for_user( $user_id, 'string' );

                $all_notifications_url = esc_url( bp_loggedin_user_domain() . bp_get_notifications_slug() );
                ?>
                <!-- Start: User Notifications -->
                <div class="user-notifications bb-icn-count">
                    <a class="icn-link icon-notification <?php if( $count ) echo 'has-count' ?>" href="<?php echo $all_notifications_url; ?>">
                        <?php if( $count ) { ?>
                            <span class="count"><?php echo bp_core_number_format( $count ); ?></span>
                        <?php } ?>
                    </a>

                    <?php if( (bool)$notifications ) { ?>
                        <div class="notifications-list menu">
                            <ul>
                                <?php foreach($notifications as $index => $notification) { ?>
                                    <?php if( $index >= $max_show ) break; ?>
                                    <li><?php echo $notification; ?></li>
                                <?php } ?>
                            </ul>
                            <?php if( $count > $max_show ) { ?>
                                <a href="<?php echo $all_notifications_url; ?>" class="notifications-more"><?php esc_html_e('View all', 'buddypress'); ?></a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <!-- End: User Notifications -->
                <?php
            }

            /**
             * Add menu item to buddypress navigation
             */
            public function nav_menu_objects( $menu_items, $args ) {

                $user_id = bp_loggedin_user_id();

                if( $user_id ) {
                    $menu = new stdClass;
                    $menu->class = array( 'menu-parent' );
                    $menu->css_id = 'logout';
                    $menu->link = wp_logout_url( bp_get_requested_url() );
                    $menu->name = esc_html__('Logout', 'boddypress');
                    $menu->parent = 0;

                    $menu_items[] = $menu;
                }

                return $menu_items;
            }

            /**
             * Add user meta description to wordpress header
             */
            public function generate_user_meta_description() {
                if( function_exists( 'bp_is_user' ) && bp_is_user() ) {
                    $meta_description = boombox_get_user_meta_description(bp_displayed_user_id());
                    printf( '<meta name="description" content="%1$s" />', $meta_description );
                }
            }

        }

    }

    Boombox_Buddypress::get_instance();

}