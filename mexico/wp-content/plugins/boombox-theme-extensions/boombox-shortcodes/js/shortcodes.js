jQuery(function ($) {
    'use strict';

    var boombox_contact_form_selector = '.boombox-contact-form';
    if( $( boombox_contact_form_selector).length ) {
        var contact_form_captcha    = null;
        var contact_captcha_container = $(boombox_contact_form_selector).find( '#boombox-contact-captcha' );

        if (params.captcha_type === 'image') {
            refresh_captcha( $(boombox_contact_form_selector), $(boombox_contact_form_selector).attr('action'));
        } else if( params.captcha_type === 'google' ) {
            $('body').on( 'boombox/grecaptcha_loaded', function(){
                if( contact_form_captcha === null ) {
                    contact_form_captcha = grecaptcha.render( contact_captcha_container.attr('id'), {
                        sitekey : contact_captcha_container.data('boombox-sitekey'),
                        theme   : 'light'
                    });
                } else {
                    grecaptcha.reset( contact_form_captcha );
                }
            } );
        }
    }

    /**
     * Contact Form Submit Event
     */
    $('body').on('submit', boombox_contact_form_selector, function(e){
        e.preventDefault();

        var _this                   = $( this );
        var name                    = _this.find('[name=boombox_name]');
        var email                   = _this.find('[name=boombox_email]');
        var comment                 = _this.find('[name=boombox_comment]');
        var captcha_code            = null;
        var message_container       = _this.parent().find('.boombox-contact-form-message');
        var submit_btn              = _this.find('[name=submit]');

        if( params.captcha_type === 'image' ) {
            captcha_code = _this.find('[name="boombox_captcha_code"]');
        } else if( params.captcha_type === 'google' ) {
            captcha_code = _this.find( '[name="g-recaptcha-response"]' );
        }
        var check_captcha           = ( captcha_code && captcha_code.length ) ? 1 : 0;

        _this.parent().find('.boombox-contact-form-message').html('');
        _this.find('.error').removeClass('error');
        submit_btn.attr( 'disabled', 'disabled' );

        var data = {
            action          : 'contact_form_submit',
            name            : name.val(),
            email           : email.val(),
            comment         : comment.val(),
            check_captcha   : check_captcha
        }
        if( check_captcha ) {
            data.captcha = captcha_code.val();
        }

        $.post(
            params.ajax_url,
            data,
            function( response ) {
                var data = $.parseJSON(response);

                if( data.valid.length ){
                    var error_message = data.message ? data.message : params.error_message;
                    message_container.html( error_message );
                    if( check_captcha ) {
                        reset_captcha();
                    }
                    show_form_errors( data.valid, name, email, comment, captcha_code );
                }else if( data.sent ){
                    message_container.html( params.success_message );
                    _this[0].reset();

                    if( check_captcha ) {
                        reset_captcha();
                    }
                }else{
                    message_container.html( params.wrong_message );
                }
                submit_btn.removeAttr( 'disabled' );
            }
        );

    });

    /**
     * Refresh Captcha
     */
    $( 'body' ).on( 'click', '.boombox-refresh-captcha', function( e ){
        e.preventDefault();
        var form = $( this ).closest( 'form' );
        var type = form.attr('action');
        refresh_captcha( form, type );
    } );

    /**
     * Add to Contact Forms Fields 'error' class
     *
     * @param is_valid
     * @param name
     * @param email
     * @param comment
     * @param captcha_code
     */
    function show_form_errors( is_valid, name, email, comment, captcha_code ){
        if( is_valid.hasOwnProperty('name') && ! is_valid.name ){
            name.addClass('error');
        }

        if( is_valid.hasOwnProperty('email') && ! is_valid.email ){
            email.addClass('error');
        }

        if( is_valid.hasOwnProperty('comment') && ! is_valid.comment ){
            comment.addClass('error');
        }

        if( is_valid.hasOwnProperty('captcha') && ! is_valid.captcha_code ){
            captcha_code.addClass('error');
        }
    }

    function reset_captcha() {
        if( params.captcha_type === 'image' ) {
            refresh_captcha($(boombox_contact_form_selector), $(boombox_contact_form_selector).attr('action'));
        } else {
            if( contact_form_captcha === null ) {
                contact_form_captcha = grecaptcha.render( contact_captcha_container.attr('id'), {
                    sitekey : contact_captcha_container.data('sitekey'),
                    theme   : 'light'
                });
            } else {
                grecaptcha.reset( contact_form_captcha );
            }
        }
    }

    /**
     * Refresh Captcha Function
     *
     * @param selector
     */
    function refresh_captcha( selector, type ){
        selector.find( '.captcha' ).attr( 'src', params.captcha_file_url + '?' + Math.random() + '&type=' + type ).closest( '.captcha-container').removeClass('loading');
    }
});