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

if( boombox_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {


    if ( ! class_exists( 'Boombox_Woocommerce' ) ) {

        class Boombox_Woocommerce {

            private $injected_products = null;

            /**
             * Singleton.
             */
            static function get_instance() {
                static $instance = null;
                if ( $instance == null ) {
                    $instance = new self();
                }

                return $instance;
            }

            /**
             * Constructor
             */
            public function __construct() {
                $this->hooks();
            }

            /**
             * Add requered hooks
             */
            private function hooks() {
                add_action( 'after_setup_theme', array( $this, 'bw_setup_theme' ), 12 );
                add_filter( 'woocommerce_enqueue_styles', array( $this, 'bw_enqueue_styles' ), 10, 1 );
                add_filter( 'body_class', array( $this, 'bw_body_classes' ), 20, 1 );
                add_filter( 'boombox/sidebar_id', array( $this, 'bw_sidebar_id' ), 10, 1 );
                add_filter( 'woocommerce_product_reviews_tab_title', array( $this, 'bw_product_reviews_tab_title' ), 10, 1 );
                add_filter( 'boombox/color_scheme_styles', array( $this, 'bw_color_scheme_styles' ), 10, 1 );
                add_filter( 'woocommerce_product_review_comment_form_args', array( $this, 'bw_product_review_comment_form_args' ), 10, 1 );

                add_action( 'widgets_init', array( $this, 'bw_override_woocommerce_widgets' ), 15 );

                add_filter( 'boombox/woocommerce/product_inject_choices', array( $this, 'bw_product_inject_choices' ), 10, 1 );

                add_action( 'woocommerce_single_product_summary', array( $this, 'bw_single_product_categories' ), 10 );
                add_action( 'boombox/auth_box_icons', array( $this, 'bw_auth_box_add_cart_icon' ), 20, 1 );
                add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'woocommerce_header_add_to_cart_fragment' ), 10, 1 );
                add_filter( 'boombox/customizer/layout-archive', array( $this, 'bw_customizer_layout_archive' ), 10, 3 );
                add_filter( 'boombox/customizer_default_values', array($this, 'bw_customizer_default_values' ), 10, 1 );

                /** inject */
                add_action( 'boombox/loop-helper/product', array( $this, 'bw_loop_helper_product' ), 10, 4 );
                add_action( 'boombox/loop-item/before-content', array( $this, 'bw_loop_item_before_content' ), 10, 1 );
                add_action( 'boombox/loop-item/after-content', array( $this, 'bw_loop_item_after_content' ), 10, 1 );
                add_action( 'boombox/loop-item/content-start', array( $this, 'bw_loop_item_content_start_open_wrapper' ), 10 );
                add_action( 'boombox/loop-item/content-start', array( $this, 'bw_loop_item_content_start_before_title' ), 20 );

                add_action( 'boombox/loop-item/content-end', array( $this, 'bw_loop_item_content_end_add_product_features' ), 20 );
                add_action( 'boombox/loop-item/content-end', array( $this, 'bw_loop_item_content_end_close_wrapper' ), 20 );

                $template_with_actions_overwriting = array( 'content-single-product', 'cart/cart', 'content-product' );
                foreach( $template_with_actions_overwriting as $template ) {
                    $this->change_template_actions_order( $template );
                }

            }

            /**
             * Add theme support for woocommerce
             */
            public function bw_setup_theme() {
                add_theme_support( 'woocommerce' );

                add_theme_support( 'wc-product-gallery-zoom' );
                add_theme_support( 'wc-product-gallery-lightbox' );
                add_theme_support( 'wc-product-gallery-slider' );
            }

            /**
             * Add theme styles
             *
             * @param $styles
             * @return mixed
             */
            public function bw_enqueue_styles( $styles ) {

                $styles[ 'boombox-woocommerce' ] = array(
                    'src'     => str_replace( array( 'http:', 'https:' ), '', BOOMBOX_THEME_URL ) . 'woocommerce/css/woocommerce' . (is_rtl() ? '-rtl' : '') . '.min.css',
                    'deps'    => 'woocommerce-general',
                    'version' => boombox_get_assets_version(),
                    'media'   => 'all'
                );

                return $styles;
            }

            /**
             * Add body classes for different templates
             * @param $classes
             * @return array
             */
            public function bw_body_classes( $classes ) {
                if( is_woocommerce() ) {

                    if( is_singular( 'product' ) ) {

                        $post_template = boombox_get_single_post_template();

                        if( 'no-sidebar' == $post_template ) {
                            $classes[ 'sidebar_position' ] = esc_attr( $post_template ) . ' ' . 'no-sidebar';
                            add_filter( 'boombox_is_sidebar_enabled', function(){ return false; } );
                        } else {
                            $classes[ 'sidebar_position' ] = esc_attr( $post_template );
                        }

                    } else {

                        $shop_page_id = wc_get_page_id( 'shop' );
                        $template_name = get_page_template_slug( $shop_page_id );

                        switch( $template_name ) {
                            case 'page-with-left-sidebar.php':
                                $classes[ 'sidebar_position' ] = 'left-sidebar';
                                break;
                            case 'page-no-sidebar.php':
                                add_filter( 'boombox_is_sidebar_enabled', function(){ return false; } );

                                $classes[ 'sidebar_position' ] = 'no-sidebar';
                                break;
                            case '':
                            case 'default':
                            case 'page-trending-result.php':
                                $classes[ 'sidebar_position' ] = 'right-sidebar';
                                break;
                        }

                    }

                }
                return $classes;
            }

            /**
             * Modify sidebar id
             *
             * @param $sidebar_id
             * @return mixed
             */
            public function bw_sidebar_id( $sidebar_id ) {
                if( is_woocommerce() ) {
                    $shop_page_id = wc_get_page_id( 'shop' );

                    $boombox_sidebar_id = boombox_get_post_meta( $shop_page_id, 'boombox_sidebar_template' );
                    $sidebar_id = empty( $boombox_sidebar_id ) ? $sidebar_id : $boombox_sidebar_id;
                }

                return $sidebar_id;
            }

            /**
             * Parse review tab count html
             *
             * @param $title
             * @return string
             */
            public function bw_product_reviews_tab_title( $title ) {
                preg_match_all('/\((.*?)\)/', $title, $matches);
                $title = strtr( $title, array( $matches[0][0] => sprintf( '<span class="count"> %s</span>', $matches[1][0] ) ) );

                return $title;
            }

            /**
             * Color scheme support
             *
             * @param $css
             * @return string
             * See boombox_global_style_css() for available colors
             */
            public function bw_color_scheme_styles( $css ) {
                $css .= '
                    /* * Woocommerce specific styles * */

                    /* -base text color */
                    .woocommerce, .woocommerce .variations label, .woocommerce-checkout ul.payment_methods label,
                    .woocommerce .widget .buttons a:nth-child(1), .woocommerce .widget .buttons a:nth-child(1):focus, .woocommerce .widget .buttons a:nth-child(1):hover,
                    .woocommerce .widget .price_slider_amount .button, .woocommerce .widget .price_slider_amount .button:focus, .woocommerce .widget .price_slider_amount .button:hover,
                    .woocommerce div.product .woocommerce-variation-price ins .amount, .woocommerce div.product .woocommerce-variation-price span.price>.amount,
                    .woocommerce div.product p.price {
                        color: %8$s;
                    }

                    /* -content bg color */
                    @media screen and (max-width: 768px) {
                        .woocommerce table.shop_table_responsive.cart tbody tr.cart_item:nth-child(2n) td, .woocommerce-page table.shop_table_responsive.cart tbody tr.cart_item:nth-child(2n) td, {
                            background-color: %5$s;
                        }
                    }
                    .woocommerce .cart-totals-col .cart_totals .shipping-calculator-form, .woocommerce-page .cart-totals-col .cart_totals .shipping-calculator-form,
                    .woocommerce div.product div.images .woocommerce-product-gallery__trigger {
                        background-color: %5$s;
                    }

                    /* -primary color and bg */
                    .woocommerce a.button, .woocommerce a.button:hover, .woocommerce a.button:focus,
                    .woocommerce input.button, .woocommerce input.button:hover, .woocommerce input.button:focus,
                    .woocommerce button.button, .woocommerce button.button:hover, .woocommerce button.button:focus,
                    .woocommerce .button.alt.single_add_to_cart_button, .woocommerce .button.alt.single_add_to_cart_button:hover, .woocommerce .button.alt.single_add_to_cart_button:focus,
                    .woocommerce .button.alt.single_add_to_cart_button.disabled, .woocommerce .button.alt.single_add_to_cart_button.disabled:hover, .woocommerce .button.alt.single_add_to_cart_button.disabled:focus,
                    .woocommerce .widget_price_filter .ui-slider .ui-slider-range,
                     div.pp_woocommerce a.pp_contract, div.pp_woocommerce a.pp_expand, div.pp_woocommerce .pp_close, div.pp_woocommerce a.pp_contract:hover, div.pp_woocommerce a.pp_expand:hover, div.pp_woocommerce .pp_close:hover {
                        background-color: %6$s;
                    }
                    .woocommerce div.product .woocommerce-product-rating .star-rating span {
                        color: %6$s;
                    }
                    div.pp_woocommerce .pp_next:before, div.pp_woocommerce .pp_previous:before, div.pp_woocommerce .pp_arrow_next:before, div.pp_woocommerce .pp_arrow_previous:before {
                         color: %6$s!important;
                    }

                    /* --primary text color */
                    .woocommerce a.button, .woocommerce a.button:hover, .woocommerce input.button,.woocommerce button.button, .woocommerce button.button:hover, .woocommerce input.button:hover,
                    .woocommerce .button.alt.single_add_to_cart_button, .woocommerce .button.alt.single_add_to_cart_button:hover,
                    .woocommerce .button.alt.single_add_to_cart_button.disabled, .woocommerce .button.alt.single_add_to_cart_button.disabled:hover,
                     div.pp_woocommerce a.pp_contract, div.pp_woocommerce a.pp_expand, div.pp_woocommerce .pp_close, div.pp_woocommerce a.pp_contract:hover, div.pp_woocommerce a.pp_expand:hover, div.pp_woocommerce .pp_close:hover {
                        color: %7$s;
                    }
                    div.pp_woocommerce a.pp_contract, div.pp_woocommerce a.pp_expand, div.pp_woocommerce .pp_close, div.pp_woocommerce a.pp_contract:hover, div.pp_woocommerce a.pp_expand:hover, div.pp_woocommerce .pp_close:hover {
                        color: %7$s!important;
                    }

                    /* --heading text color */
                    .woocommerce .product-name a,
                    .woocommerce .shop_table .shipping-calculator-button, .woocommerce .shop_table .shipping-calculator-button,
                    .woocommerce ul.cart_list li a, .woocommerce ul.product_list_widget li a,
                    .woocommerce .star-rating span,
                    .widget_layered_nav a, .widget_layered_nav a:focus, .widget_layered_nav a:hover,
                    .widget_product_categories a, .widget_product_categories a:focus, .widget_product_categories a:hover,
                    .woocommerce.widget ul li.chosen a, .woocommerce .widget ul li.chosen a,
                    .woocommerce #reviews #comments ol.commentlist li .meta strong,
                    .woocommerce div.product div.images .woocommerce-product-gallery__trigger {
                        color: %10$s;
                    }

                    /* --secondary text color */
                    .woocommerce .woocommerce-result-count, .woocommerce a.added_to_cart, .woocommerce table.shop_table .product-name p, .woocommerce .coupon span, .woocommerce .checkout_coupon span,
                    .woocommerce .cart_totals table.shop_table_responsive tbody tr.cart-subtotal td:before,
                    .woocommerce .create-account p, .woocommerce .woocommerce form.checkout_coupon span, .woocommerce form.login p,
                    .product_list_widget li .quantity .amount,
                    .widget_shopping_cart .total .txt, .widget_rating_filter ul li a, .widget_layered_nav .count, .product_list_widget .reviewer,
                    .woocommerce .widget ul li.chosen a:before, .woocommerce.widget ul li.chosen a:before,
                    .woocommerce div.product .woocommerce-review-link, .woocommerce .woocommerce-tabs .star-rating span, .woocommerce .reset_variations, .woocommerce div.product .stock,
                    .woocommerce #reviews #comments ol.commentlist li .meta time, .woocommerce div.product #tab-description p, .woocommerce div.product #reviews .woocommerce-noreviews,
                    .woocommerce div.product p.stars a.active, .woocommerce div.product p.stars a:hover, .woocommerce div.product p.stars a:focus, .woocommerce p.stars.selected a:not(.active):before,
                    .woocommerce p.stars.selected a.active:before, .woocommerce p.stars:hover a:before,
                    #order_review #payment .payment_box,
                    .widget_product_categories .post_count {
                        color: %9$s;
                    }
                    /* Remove button */
                    .widget_shopping_cart .cart_list li a.remove, .woocommerce a.remove {
                        color: %9$s!important;
                    }
                    .woocommerce a.remove:hover, .widget_shopping_cart .cart_list li a.remove:hover {
                        background: %9$s;
                        color: %5$s!important;
                    }


                    /* -border-color */
                    .woocommerce table.shop_table td, .woocommerce table.shop_table tbody th, .woocommerce-cart .cart-collaterals .cart_totals tr th,
                    .woocommerce table.shop_table_responsive.cart tbody tr.cart_item, .woocommerce-page table.shop_table_responsive.cart tbody tr.cart_item,
                    .woocommerce form .form-row.woocommerce-validated .select2-container,
                     #order_review .woocommerce-checkout-review-order-table, #order_review #payment ul.payment_methods, .woocommerce-page .shop_table.customer_details,
                    .woocommerce div.product form.cart .variations-wrapper, .woocommerce div.product #comments .comment_container, .woocommerce table.shop_attributes td, .woocommerce table.shop_attributes th,
                    .woocommerce div.product .woocommerce-tabs,
                    .woocommerce ul.cart_list li dl, .woocommerce ul.product_list_widget li dl {
                        border-color: %13$s;
                    }
                    hr {
                        background-color: %13$s;
                    }

                    /* -loader styling */
                    .woocommerce .blockUI.blockOverlay:before, .woocommerce .loader:before {
                        border-color: %13$s;
                    }

                    /* -secondary components bg color */
                    .woocommerce .cart-totals-col .cart_totals, .woocommerce-page .cart-totals-col .cart_totals, #order_review .woocommerce-checkout-review-order-table tfoot,
                    .woocommerce-page .shop_table.order_details tfoot,
                    #order_review #payment .payment_box,
                    .woocommerce .widget .buttons a:nth-child(1), .woocommerce .widget .buttons a:nth-child(1):focus, .woocommerce .widget .buttons a:nth-child(1):hover,
                    .woocommerce .widget .price_slider_amount .button, .woocommerce .widget .price_slider_amount .button:focus, .woocommerce .widget .price_slider_amount .button:hover,
                    .woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
                    .woocommerce div.product table.shop_attributes {
                        background-color: %14$s;
                    }
                    #order_review #payment .payment_box:before {
                        border-color: %14$s;
                    }

                    /* -secondary components bg color for woocommerce */
                    @media screen and (max-width: 768px){
                        .woocommerce table.shop_table_responsive.cart tbody tr.cart_item td.product-remove, .woocommerce-page table.shop_table_responsive.cart tbody tr.cart_item td.product-remove {
                            background-color: %14$s;
                        }
                    }

                    /* -border-radius */
                    .woocommerce .cart-totals-col .cart_totals, .woocommerce-page .cart-totals-col .cart_totals,
                    .woocommerce table.shop_table_responsive.cart tbody tr.cart_item, .woocommerce-page table.shop_table_responsive.cart tbody tr.cart_item,
                    #order_review .woocommerce-checkout-review-order-table, #order_review #payment ul.payment_methods,
                    .woocommerce .cart-totals-col .cart_totals .shipping-calculator-form, .woocommerce-page .cart-totals-col .cart_totals .shipping-calculator-form,
                    .woocommerce div.product table.shop_attributes {
                    -webkit-border-radius: %11$s;
                    -moz-border-radius: %11$s;
                        border-radius: %11$s;
                    }

                    /* --border-radius inputs, buttons */
                    .woocommerce a.button, .woocommerce input.button, .woocommerce button.button  {
                        -webkit-border-radius: %12$s;
                        -moz-border-radius: %12$s;
                        border-radius: %12$s;
                    }
                ';

                return $css;
            }

            /**
             * Edit single product comment form args
             *
             * @param $args
             * @return mixed|void
             */
            public function bw_product_review_comment_form_args( $args ) {

                $args = boombox_get_comment_form_args();

                if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
                    $args['fields']['author'] = '<div class="comment-form-rating"><div class="input-field"><label for="rating">' . __( 'Your Rating', 'woocommerce' ) .'</label><select name="rating" id="rating" aria-required="true" required>
							<option value="">' . __( 'Rate&hellip;', 'woocommerce' ) . '</option>
							<option value="5">' . __( 'Perfect', 'woocommerce' ) . '</option>
							<option value="4">' . __( 'Good', 'woocommerce' ) . '</option>
							<option value="3">' . __( 'Average', 'woocommerce' ) . '</option>
							<option value="2">' . __( 'Not that bad', 'woocommerce' ) . '</option>
							<option value="1">' . __( 'Very Poor', 'woocommerce' ) . '</option>
						</select></div></div>' . $args['fields']['author'];
                }

                return $args;
            }

            /**
             * Overwrite some widgets
             */
            public function bw_override_woocommerce_widgets() {
                if ( class_exists( 'WC_Widget_Recent_Reviews' ) ) {
                    unregister_widget( 'WC_Widget_Recent_Reviews' );

                    include_once( 'widgets/class-wc-widget-recent-reviews.php' );

                    register_widget( 'Boombox_WC_Widget_Recent_Reviews' );
                }

                if ( class_exists( 'WC_Widget_Price_Filter' ) ) {
                    unregister_widget( 'WC_Widget_Price_Filter' );

                    include_once( 'widgets/class-wc-widget-price-filter.php' );

                    register_widget( 'Boombox_WC_Widget_Price_Filter' );
                }
            }

            /**
             * Setup choices for product injection
             * @param $choices
             * @return array
             */
            public function bw_product_inject_choices( $choices ) {
                return array_merge( (array)$choices, array(
                    'inject_into_posts_list' => esc_html__( 'Inject Into Posts List', 'boombox' ),
                    'none'                   => esc_html__( 'None', 'boombox' )
                ) );
            }

            /**
             * Change register actions registration order
             *
             * @param array $actions_data
             */
            private function change_registered_actions_order( $actions_data = array() ) {
                foreach( $actions_data as $action_data ) {
                    $action = ( isset( $action_data['action'] ) && $action_data['action'] ) ? $action_data['action'] : false;
                    $function = ( isset( $action_data['function'] ) && $action_data['function'] ) ? $action_data['function'] : false;
                    $old_priority = ( isset( $action_data['old_priority'] ) && $action_data['old_priority'] ) ? $action_data['old_priority'] : 10;
                    $new_priority = ( isset( $action_data['new_priority'] ) && $action_data['new_priority'] ) ? $action_data['new_priority'] : 10;
                    $accepted_args = ( isset( $action_data['accepted_args'] ) && $action_data['accepted_args'] ) ? $action_data['accepted_args'] : 1;

                    if( ! $action || !$function ) {
                        continue;
                    }
                    $this->change_registered_action_order( $action, $function, $old_priority, $new_priority, $accepted_args );
                }
            }

            /**
             * Change single action registration order
             *
             * @param $action
             * @param $function
             * @param int $old_priority
             * @param int $new_priority
             * @param int $accepted_args
             */
            private function change_registered_action_order( $action, $function, $old_priority = 10, $new_priority = 10, $accepted_args = 1 ) {

                if( $old_priority != $new_priority ) {
                    remove_action( $action, $function, $old_priority );
                    add_action( $action, $function, $new_priority, $accepted_args );
                }

            }

            /**
             * Add/edit/remove template actions
             *
             * @param $template
             */
            private function change_template_actions_order( $template ) {

                /** Single product */
                if( $template === 'content-single-product' ) {

                    $this->change_registered_actions_order( array(
                        array(
                            'action'        => 'woocommerce_single_product_summary',
                            'function'      => 'woocommerce_template_single_rating',
                            'old_priority'  => 10,
                            'new_priority'  => 20
                        ),
                        array(
                            'action'        => 'woocommerce_single_product_summary',
                            'function'      => 'woocommerce_template_single_title',
                            'old_priority'  => 5,
                            'new_priority'  => 30
                        ),
                        array(
                            'action'        => 'woocommerce_single_product_summary',
                            'function'      => 'woocommerce_template_single_excerpt',
                            'old_priority'  => 20,
                            'new_priority'  => 40
                        ),
                        array(
                            'action'        => 'woocommerce_single_product_summary',
                            'function'      => 'woocommerce_template_single_price',
                            'old_priority'  => 10,
                            'new_priority'  => 50
                        ),
                        array(
                            'action'        => 'woocommerce_single_product_summary',
                            'function'      => 'woocommerce_template_single_add_to_cart',
                            'old_priority'  => 30,
                            'new_priority'  => 60
                        ),
                        array(
                            'action'        => 'woocommerce_single_product_summary',
                            'function'      => 'woocommerce_template_single_meta',
                            'old_priority'  => 40,
                            'new_priority'  => 70
                        ),
                        array(
                            'action'        => 'woocommerce_single_product_summary',
                            'function'      => 'woocommerce_template_single_sharing',
                            'old_priority'  => 50,
                            'new_priority'  => 80
                        )
                    ) );
                }
                // cart
                elseif( $template === 'cart/cart' ) {

                    remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );

                    add_action( 'boombox_woocommerce_cart_totals', 'woocommerce_cart_totals', 10 );
                }
                // content-product
                elseif( $template === 'content-product' ) {

                    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

                    add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 20 );
                    add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'bw_before_shop_loop_item_title_open_wrapper' ), 20 );
                    add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'bw_single_product_categories' ), 20 );
                    add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating', 20 );
                    add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'bw_before_shop_loop_item_title_close_wrapper' ), 20 );
                    add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 20 );

                }

                // add possibility for modification
                do_action( 'boombox/woocommerce/actions-order/' . $template );
            }

            /**
             * Display single product categories
             */
            public function bw_single_product_categories() {
                global $product;

                printf( '<div class="bb-cat-links">%s</div>', wc_get_product_category_list( $product->get_id(), ' ' ) );
            }

            /**
             * Render shopping cart icon
             * @param $boombox_header_settings
             */
            public function bw_auth_box_add_cart_icon( $boombox_header_settings ) {

                $count = WC()->cart->get_cart_contents_count();
                printf( '
                    <div class="user-cart bb-icn-count">
                        <a class="icn-link icon-shop%1$s" href="%2$s" title="%3$s">%4$s</a>
                    </div>',
                    $count ? ' has-count' : '',
                    wc_get_cart_url(),
                    __( 'View your shopping cart', 'boombox' ),
                    $count ? sprintf( '<span class="count">%d</span>', $count ) : ''
                );

            }

            /**
             * Partially refresh shopping cart to place actual count of products
             *
             * @param $fragments
             * @return mixed
             */
            public function woocommerce_header_add_to_cart_fragment( $fragments ) {

                $count = WC()->cart->get_cart_contents_count();

                $fragments['div.user-cart .icn-link'] = sprintf(
                    '<a class="icn-link icon-shop%1$s" href="%2$s" title="%3$s">%4$s</a>',
                    $count ? ' has-count' : '',
                    wc_get_cart_url(),
                    __( 'View your shopping cart', 'boombox' ),
                    $count ? sprintf( '<span class="count">%d</span>', $count ) : ''
                );

                return $fragments;
            }

            /**
             * Set a wrapper before shop loop item
             */
            public function bw_before_shop_loop_item_title_open_wrapper() {
                echo '<div class="cat-rat-row">';
            }

            /**
             * Close wrapper after shop loop item
             */
            public function bw_before_shop_loop_item_title_close_wrapper() {
                echo '</div>';
            }

            /**
             * Add extra fields to theme customizer
             *
             * @param $layout_archive
             * @param $boombox_option_name
             * @param $boombox_customizer_defaults
             * @return mixed
             */
            public function bw_customizer_layout_archive( $layout_archive, $boombox_option_name, $boombox_customizer_defaults ) {
                $layout_archive['fields'][] = array(
                    'setting' => array(
                        'id' 	=> $boombox_option_name . '[layout_archive_products]',
                        'args'	=> array(
                            'default'           => $boombox_customizer_defaults['layout_archive_products'],
                            'type'              => 'option',
                            'capability'        => 'edit_theme_options',
                            'sanitize_callback' => 'sanitize_text_field',
                        )
                    ),
                    'control' => array(
                        'id' 	=> 'boombox_layout_archive_products',
                        'args' 	=> array(
                            'label'    => esc_html__( 'Products', 'boombox' ),
                            'section'  => 'boombox_layout_archive_section',
                            'settings' => $boombox_option_name . '[layout_archive_products]',
                            'type'     => 'select',
                            'choices'  => apply_filters( 'boombox/woocommerce/product_inject_choices', array() )
                        )
                    )
                );

                $layout_archive['fields'][] = array(
                    'setting' => array(
                        'id' 	=> $boombox_option_name . '[layout_archive_products_count]',
                        'args'	=> array(
                            'default'           => $boombox_customizer_defaults['layout_archive_products_count'],
                            'type'              => 'option',
                            'capability'        => 'edit_theme_options',
                            'sanitize_callback' => 'sanitize_text_field',
                        )
                    ),
                    'control' => array(
                        'id' 	=> 'boombox_layout_archive_products_count',
                        'args' 	=> array(
                            'label'    => esc_html__( 'Inject # product(s)', 'boombox' ),
                            'section'  => 'boombox_layout_archive_section',
                            'settings' => $boombox_option_name . '[layout_archive_products_count]',
                            'type'     => 'number',
                            'input_attrs' => array(
                                'min'   => 1
                            )
                        )
                    )
                );

                $layout_archive['fields'][] = array(
                    'setting' => array(
                        'id' 	=> $boombox_option_name . '[layout_archive_products_position]',
                        'args'	=> array(
                            'default'           => $boombox_customizer_defaults['layout_archive_products_position'],
                            'type'              => 'option',
                            'capability'        => 'edit_theme_options',
                            'sanitize_callback' => 'sanitize_text_field',
                        )
                    ),
                    'control' => array(
                        'id' 	=> 'boombox_layout_archive_products_position',
                        'args' 	=> array(
                            'label'    => esc_html__( 'Inject product(s) after every # post', 'boombox' ),
                            'section'  => 'boombox_layout_archive_section',
                            'settings' => $boombox_option_name . '[layout_archive_products_position]',
                            'type'     => 'number',
                            'input_attrs' => array(
                                'min'   => 1
                            )
                        )
                    )
                );

                return $layout_archive;
            }

            /**
             * Setup default values for customizer extra fields
             * @param $values
             * @return mixed
             */
            public function bw_customizer_default_values( $values ) {
                $values['layout_archive_products'] = 'none';
                $values['layout_archive_products_count'] = 1;
                $values['layout_archive_products_position'] = 1;

                return $values;
            }

            /**
             * Start boombox-loop-helper
             *
             * @param $placement_data
             * @param $current_page
             * @param $loop_index
             */
            public function bw_loop_helper_product( $placement_data, $query, $current_page, $loop_index ) {
                if( is_null( $this->injected_products ) ) {

                    $posts_per_page = $query->get( 'posts_per_page' );
                    if( $posts_per_page != -1  ) {
                        $periods_count = floor( $posts_per_page / $placement_data['position'] );
                        $offset = ( $current_page - 1 ) * $placement_data['count'] * $periods_count;
                    } else {
                        $periods_count = floor( $query->found_posts / $placement_data['position'] );
                        $offset = 0;
                    }
                    $args = array(
                        'post_type'      => 'product',
                        'post_status'    => 'publish',
                        'posts_per_page' => $placement_data['count'] * $periods_count,
                        'offset'         => $offset
                    );
                    $query = new WP_Query( $args );
                    $this->injected_products = $query->get_posts();
                }
                if( ! empty( $this->injected_products ) ) {

                    if( boombox_is_plugin_active( 'quick-adsense-reloaded/quick-adsense-reloaded.php' ) ) {
                        global $quads_options;
                        $quads_options[ 'post_types' ] = array_key_exists( 'post_types', $quads_options ) ? (array)$quads_options['post_types'] : array();
                        if( !in_array( 'product', (array)$quads_options['post_types'] ) ) {
                            $quads_options['post_types'][] = 'product';
                        }
                    }

                    global $post;
                    $post = array_shift( $this->injected_products );
                }
            }

            /**
             * Manipulate loop-item content for injected product
             * @param $layout
             */
            public function bw_loop_item_before_content( $layout ) {
                if( get_post_type() != 'product' ) {
                    return;
                }

                global $product;
                $product = new WC_Product( get_the_ID() );

                add_filter('boombox/loop-item/show-badges', array($this, 'bw_injected_product_hide_block'), 10, 1);
                add_filter('boombox/loop-item/show-post-vote-count', array($this, 'bw_injected_product_hide_block'), 10, 1);
                add_filter('boombox/loop-item/show-share-count', array($this, 'bw_injected_product_hide_block'), 10, 1);
                add_filter('boombox/loop-item/show-post-type-badges', array($this, 'bw_injected_product_hide_block'), 10, 1);
                add_filter('boombox/loop-item/show-categories', array( $this, 'bw_injected_product_hide_block' ), 10, 1);
                add_filter('boombox/loop-item/show-comments-count', array($this, 'bw_injected_product_hide_block'), 10, 1);
                add_filter('boombox/loop-item/show-post-author-meta', array($this, 'bw_injected_product_hide_block'), 10, 1);
                add_filter('boombox/loop-item/show-post-excerpt', array($this, 'bw_injected_product_hide_block'), 10, 1);

                remove_action('boombox_affiliate_content', 'boombox_render_affiliate_content', 10);

                if( in_array( $layout, array( 'content-classic', 'content-classic2', 'content-stream' ) ) ) {
                    remove_action( 'boombox/loop-item/content-end', array( $this, 'bw_loop_item_content_end_add_product_features' ), 20 );
                    add_action( 'boombox_affiliate_content', array( $this, 'bw_loop_item_content_start_open_wrapper' ), 10 );
                    add_action( 'boombox_affiliate_content', array( $this, 'bw_loop_item_content_end_add_product_features' ), 10 );
                }

                if( $layout == 'content-numeric-list' ) {
                    add_action('boombox/loop-item/show-box-index', array($this, 'bw_injected_product_hide_block'), 10, 1);
                }
            }

            /**
             * Revert back all manipulation for injected products
             * @param $layout
             */
            public function bw_loop_item_after_content( $layout ) {
                if( get_post_type() != 'product' ) {
                    return;
                }

                global $product;
                unset( $product );

                remove_filter('boombox/loop-item/show-badges', array($this, 'bw_injected_product_hide_block'), 10);
                remove_filter('boombox/loop-item/show-post-vote-count', array($this, 'bw_injected_product_hide_block'), 10);
                remove_filter('boombox/loop-item/show-share-count', array($this, 'bw_injected_product_hide_block'), 10);
                remove_filter('boombox/loop-item/show-post-type-badges', array($this, 'bw_injected_product_hide_block'), 10);
                remove_filter('boombox/loop-item/show-categories', array( $this, 'bw_injected_product_hide_block' ), 10);
                remove_filter('boombox/loop-item/show-comments-count', array($this, 'bw_injected_product_hide_block'), 10);
                remove_filter('boombox/loop-item/show-post-author-meta', array($this, 'bw_injected_product_hide_block'), 10);
                remove_filter('boombox/loop-item/show-post-excerpt', array($this, 'bw_injected_product_hide_block'), 10, 1);

                add_action('boombox_affiliate_content', 'boombox_render_affiliate_content', 10);

                if( in_array( $layout, array( 'content-classic', 'content-classic2', 'content-stream' ) ) ) {
                    remove_action( 'boombox_affiliate_content', array( $this, 'bw_loop_item_content_end_add_product_features' ), 10 );
                    remove_action( 'boombox_affiliate_content', array( $this, 'bw_loop_item_content_start_open_wrapper' ), 10 );
                    add_action( 'boombox/loop-item/content-end', array( $this, 'bw_loop_item_content_end_add_product_features' ), 20 );
                }

                if( $layout == 'content-numeric-list' ) {
                    remove_action('boombox/loop-item/show-box-index', array($this, 'bw_injected_product_hide_block'), 10, 1);
                }
            }

            /**
             * Hide loop-item block
             * @param $show
             * @return bool
             */
            public function bw_injected_product_hide_block( $show ) {
                $show = false;

                return $show;
            }

            /**
             * Open woocommerce wrapper for injected product features
             */
            public function bw_loop_item_content_start_open_wrapper() {
                if( get_post_type() != 'product' ) {
                    return;
                }

                echo sprintf( '<div class="woocommerce product-affiliate">' );
            }

            /**
             * Add rating and product categories for injected product
             */
            public function bw_loop_item_content_start_before_title() {
                if( get_post_type() != 'product' ) {
                    return;
                }

                $this->bw_before_shop_loop_item_title_open_wrapper();
                $this->bw_single_product_categories();
                woocommerce_template_loop_rating();
                $this->bw_before_shop_loop_item_title_close_wrapper();
            }

            /**
             * Render injected product features
             */
            public function bw_loop_item_content_end_add_product_features() {
                if( get_post_type() != 'product' ) {
                    return;
                }

                global $product;
                woocommerce_template_loop_price();
                echo WC_Shortcodes::product_add_to_cart( array( 'id' => $product->get_id(), 'show_price' => 'false', 'style' => '' ) );
            }

            /**
             * Close woocommerce wrapper for injected product features
             */
            public function bw_loop_item_content_end_close_wrapper() {
                if( get_post_type() != 'product' ) {
                    return;
                }
                echo '</div>';
            }

        }

    }

    Boombox_Woocommerce::get_instance();

}