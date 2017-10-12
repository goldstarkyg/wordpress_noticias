jQuery(function ($) {
    'use strict';

    $(document).ready( function() {

        ////////////////////////////// Category //////////////////////////////

        /**
         * Category Thumbnail
         */
        // Only show the "remove image" button when needed
        if ( !$( '#cat_thumbnail_id' ).val() ) {
            $( '.remove_image_button' ).hide();
        }
        // Uploading files
        var file_frame;
        $( document ).on( 'click', '.upload_image_button', function (event) {

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if (file_frame) {
                file_frame.open();
                return;
            }

            // Create the media frame.
            file_frame = wp.media.frames.downloadable_file = wp.media({
                title: 'Choose an image',
                button: {
                    text: 'Use image'
                },
                multiple: false
            });

            // When an image is selected, run a callback.
            file_frame.on( 'select', function () {
                var attachment = file_frame.state().get( 'selection' ).first().toJSON();

                $( '#cat_thumbnail_id' ).val( attachment.id );
                $( '#cat_thumbnail' ).find( 'img' ).attr( 'src', attachment.sizes.thumbnail.url );
                $( '.remove_image_button' ).show();
            });

            // Finally, open the modal.
            file_frame.open();
        });

        $( document ).on( 'click', '.remove_image_button', function () {
            $( '#cat_thumbnail' ).find( 'img' ).attr( 'src', $( '#cat_placeholder_img_src' ).val() );
            $( '#cat_thumbnail_id' ).val('');
            $( '.remove_image_button' ).hide();
            return false;
        });

        /**
         *  Category Icon
         */
        var boombox_category_icon_container = $( "#cat_icon_name");
        if( boombox_category_icon_container.length > 0){
            $.widget( 'custom.iconselectmenu', $.ui.selectmenu, {
                _renderItem: function( ul, item ) {
                    var li = $( "<li>", { text: item.label } );

                    $( "<span>", {
                        "class": "icon " + item.element.attr( "data-class" )
                    }).prependTo( li );

                    return li.appendTo( ul );
                }
            });
            boombox_category_icon_container.iconselectmenu().iconselectmenu( 'menuWidget' ).addClass( 'ui-menu-icons customicons' );
        }


        ////////////////////////////// Page //////////////////////////////

        /**
         * Show / hide listing settings
         */
        var boombox_listing_type = $( '#boombox_listing_type' );
        boombox_run_show_hide_element_functionality( boombox_listing_type, '.boombox-listing-settings' );

        /**
         * Show / hide pagination elements
         */
        var boombox_pagination_type = $( '#boombox_pagination_type' );
        boombox_run_show_hide_element_functionality( boombox_pagination_type, '.boombox-page-form-posts-per-page' );

        /**
         * Show / hide adv elements
         */
        var boombox_adv = $( '#boombox_page_ad' );
        boombox_run_show_hide_element_functionality( boombox_adv, '.boombox-page-form-adv-instead' );

        /**
         * Show / hide newsletter elements
         */
        var boombox_newsletter = $( '#boombox_page_newsletter' );
        boombox_run_show_hide_element_functionality( boombox_newsletter, '.boombox-page-form-newsletter-instead' );

        /**
         * Show / hide product elements
         */
        var boombox_products = $( '#boombox_page_products_inject' );
        boombox_run_show_hide_element_functionality( boombox_products, '.boombox-page-form-products-sentence' );

        var posts_per_page = $( '#boombox_posts_per_page' );
        $( posts_per_page).on( 'change', function( e ){
            var _this = $(this),
                _page_adv_position_input = $( '#boombox_inject_ad_instead_post'),
                _page_newsletter_position_input = $( '#boombox_inject_newsletter_instead_post'),
                _page_products_position_input = $( '#boombox_page_injected_products_position' );

            _page_adv_position_input.attr( 'max', _this.val() );
            _page_newsletter_position_input.attr( 'max', _this.val() );
            _page_products_position_input.attr( 'max', _this.val() );
        } );

        /**
         * Running show/hide elements functionality
         *
         * @param selected_element
         * @param elements_selector
         */
        function boombox_run_show_hide_element_functionality( selected_element, elements_selector ){
            if( selected_element.length > 0 ){
                var selected_val = selected_element.val();
                var elements = selected_element.closest('.boombox-page-advanced-fields').find( elements_selector );
                boombox_show_hide_elements( selected_val, elements );

                selected_element.change( function() {
                    var cur_selected_val = $( this ).val();
                    boombox_show_hide_elements( cur_selected_val, elements );
                } );
            }
        }

        /**
         * Show/hide elements
         *
         * @param selected_val
         * @param elements
         */
        function boombox_show_hide_elements( selected_val, elements ){
            if( 'none' == selected_val ){
                elements.fadeOut();
            }else{
                elements.fadeIn();
            }
        }


        ////////////////////////////// Nav Menus //////////////////////////////

        var boombox_menu_item_icon = $( ".edit-menu-item-icon");
        if( boombox_menu_item_icon.length > 0 ){
            $.widget( 'custom.iconselectmenu', $.ui.selectmenu, {
                _renderItem: function( ul, item ) {
                    var li = $( "<li>", { text: item.label } );

                    $( "<span>", {
                        "class": "icon " + item.element.attr( "data-class" )
                    }).prependTo( li );

                    return li.appendTo( ul );
                }
            });
            $.each( boombox_menu_item_icon, function(){
                $( this ).addClass('menu-item-custom-select');
                $( this ).iconselectmenu().iconselectmenu( 'menuWidget' ).addClass( 'ui-menu-icons customicons' );
            });

            jQuery("#post-body-content .menu").bind("DOMSubtreeModified", function() {
                $(".pending .edit-menu-item-icon").each( function(){
                    if( !$( this).hasClass('menu-item-custom-select') ) {
                        $( this ).iconselectmenu().iconselectmenu('menuWidget').addClass('ui-menu-icons customicons');
                        $( this ).addClass('menu-item-custom-select');
                    }
                });
            });

        }


        /**
         * Render color picker
         */
        if( $('.term-icon-background-color-wrap').length ) {
            $('.term-icon-background-color-wrap input[type="text"]').wpColorPicker();
        }

        ////////////////////////////// Tabs //////////////////////////////
        var tabActive = $('.boombox-admin-tabs-menu>li.active');
        if( tabActive.length > 0 ){
            for (var i = 0; i < tabActive.length; i++) {
                var tab_id = $(tabActive[i]).children().attr('href');

                $(tab_id).addClass('active').show();
            }
        }

        $('.boombox-admin-tabs-menu a').on('click', function(e){
            var tab = $(this);
            var tab_id = tab.attr('href');
            var tab_wrap = tab.closest('.boombox-admin-tabs');
            var tab_content = tab_wrap.find('.boombox-admin-tab-content');

            tab.parent().addClass("active");
            tab.parent().siblings().removeClass('active');
            tab_content.not(tab_id).removeClass('active').hide();
            $(tab_id).addClass('active').fadeIn(500);

            e.preventDefault();
        });

    });

    /** depended fields functionality */

    // dropdown
    $( '.boombox-post-advanced-fields select' ).on( 'change', function(){
        var _node = $(this),
            _id = _node.attr( 'id' ),
            _value = _node.val(),
            _depended_nodes = $( '.boombox-post-advanced-fields .depended-of-' + _id );

        if( _depended_nodes.length <= 0 ) return false;

        _depended_nodes.each( function(){
            var _this = $(this),
                _compare = $(this).data('compare'),
                _compare_value = $(this).data('compare-value');

            if( ( _compare == '=' ) ) {
                if( _value == _compare_value ) {
                    _this.removeClass( 'hidden' );
                } else {
                    _this.addClass( 'hidden' );
                }
            } else if( ( _compare == '!=' ) ) {
                if( _value != _compare_value ) {
                    _this.removeClass( 'hidden' );
                } else {
                    _this.addClass( 'hidden' );
                }
            } else {
                _this.addClass( 'hidden' );
            }

        } );

    } );

    // checkbox
    $( '.boombox-post-advanced-fields input[type="checkbox"]' ).on( 'change', function(){

        var _node = $(this),
            _id = _node.attr( 'id' ),
            _value = _node.is(':checked') ? 1 : 0,
            _depended_nodes = $( '.boombox-post-advanced-fields .depended-of-' + _id );

        if( _depended_nodes.length <= 0 ) return false;

        _depended_nodes.each( function(){
            var _this = $(this),
                _compare = $(this).data('compare'),
                _compare_value = $(this).data('compare-value');

            if( ( _compare == '=' ) ) {
                if( _value == _compare_value ) {
                    _this.removeClass( 'hidden' );
                } else {
                    _this.addClass( 'hidden' );
                }
            } else if( ( _compare == '!=' ) ) {
                if( _value != _compare_value ) {
                    _this.removeClass( 'hidden' );
                } else {
                    _this.addClass( 'hidden' );
                }
            } else {
                _this.addClass( 'hidden' );
            }

        } );

    } );

    // radio
    $( '.boombox-post-advanced-fields input[type="radio"]' ).on( 'change', function(){

        var _node = $(this),
            _id = _node.attr( 'id' ),
            _value = _node.val(),
            _depended_nodes = $( '.boombox-post-advanced-fields .depended-of-' + _id );

        if( _depended_nodes.length <= 0 ) return false;

        _depended_nodes.each( function(){
            var _this = $(this),
                _compare = $(this).data('compare'),
                _compare_value = $(this).data('compare-value');

            if( ( _compare == '=' ) ) {
                if( _value == _compare_value ) {
                    _this.removeClass( 'hidden' );
                } else {
                    _this.addClass( 'hidden' );
                }
            } else if( ( _compare == '!=' ) ) {
                if( _value != _compare_value ) {
                    _this.removeClass( 'hidden' );
                } else {
                    _this.addClass( 'hidden' );
                }
            } else {
                _this.addClass( 'hidden' );
            }

        } );

    } );
});