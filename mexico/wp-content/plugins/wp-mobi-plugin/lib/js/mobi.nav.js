/**
*
* Mobi Nav
* URL: http://www.codecanyon.net/user/phpbits
* Version: 1.0
* Author: phpbits
* Author URL: http://www.codecanyon.net/user/phpbits
*
*/

// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {
	var Mobinav = {
		init: function( options, elem ) {
			var self = this;

			self.elem = elem;
			self.$elem = $( elem );
			self.options = $.extend( { onHover:function(){} }, $.fn.Mobinav.options, options );

			self.buildFrag();
			self.buildControls();
			self.buildClick();
			self.onScrolling();
		},
		buildFrag: function() {
			var self = this;
			var nav_height = null;
			var body_height = $(window).height();
			var body_width = $(window).width();
			self.$elem.hide();
			// $( self.options.target ).hide();
			$( 'body' ).addClass( 'mobi-container mobi-container-effect-'+ self.options.effect);
			// if( self.options.wrapper == null ){
			// 	$("body").find("script").remove().end().wrapInner( '<div class="mobi-pusher"></div>' );
			// }

			//add classes
			self.$elem.addClass( 'mobi-nav' ).wrap( '<div class="mobi-nav-wrapper"></div>' );
			self.$elem.addClass( 'mobi-nav-' + self.options.position );
			self.$elem.addClass( 'mobi-nav-desktop-' + self.options.desktop );
			
			self.$elem.find( 'ul:first' ).addClass( 'mobi-main-nav' );
			$( self.options.target ).appendTo( 'body' );
			$( self.options.target ).addClass( 'mobi-nav-target mobi-effect-'+ self.options.effect);

			nav_height = self.$elem.height();
			$( self.options.target ).css({ 'padding-bottom' : nav_height + 'px' });
			// $( self.options.target ).css({ 'height' : (body_height - nav_height) + 'px' });
			if( self.options.position == 'bottom' ){
				$( self.options.target ).find( '.mobi-socials' ).css({ 'padding-bottom' : (nav_height + 15) + 'px' });
			}

			if( self.options.position == 'top' && body_width < 769 ){
				$( self.options.target ).css({ 'top' : nav_height + 'px' });
				$( 'html' ).css({ 'margin-top' : nav_height + 'px' });
			}

			self.$elem.fadeIn( 'slow' );
			$( self.options.target ).fadeIn( 'fast' );
			// console.log( self.$elem.find( 'ul:first' ) );
		},
		buildControls: function() {
			var self = this;

			if( self.options.opener.length > 0 ){
				$( document ).on( 'click', self.options.opener, function(e){
					if( $( 'body' ).hasClass( 'mobi-open' ) ){
						$( 'body' ).removeClass( 'mobi-open' );
						$( '.mobiwp-closer' ).hide();
						$( '.mobiwp-opener' ).show();
					}else{
						if( !$(this).hasClass( 'mobi-close-popup' ) ){
							$( 'body' ).addClass( 'mobi-open' );
							$( '.mobiwp-closer' ).show();
							$( '.mobiwp-opener' ).hide();
							self.onScrolling();
						}
					}

					if( !$(this).hasClass( 'mobi-close-popup' ) ){
						e.preventDefault();
					}
				});
			}
			if( self.options.target.length > 0 ){
				$( document ).on( 'click', self.options.target, function(e){
					if( e.target.id == 'mobi-nav-wrap-target' ){
						alert(self.options.opener);
						$( self.options.opener ).click();
					}
				});
			}
			
		},
		buildClick: function(){
			var self = this;
			$( self.options.target ).find( '.mobinav-parent a.mobiwp-has-children' ).on( 'click',function(e){
				target = $(this).parent( '.mobinav-parent' ).data( 'id' );
				if( $(this).parent( '.mobinav-parent' ).hasClass( 'mobinav-parent-open' ) ){
					$(this).parent( '.mobinav-parent' ).removeClass( 'mobinav-parent-open' );
					$(this).removeClass( 'mobinav-current-menu' );
				}else{
					$( self.options.target ).find( '.mobinav-current-menu' ).removeClass( 'mobinav-current-menu' );
					$(this).addClass( 'mobinav-current-menu' );
					$(this).parent( '.mobinav-parent' ).addClass( 'mobinav-parent-open' );
				}
				$( self.options.target ).find( '.'+ target).slideToggle( 'fast' );
				$(".nano").nanoScroller();
				// self.onScrolling();
				e.preventDefault();
			});
		},
		onScrolling: function(){
			var self = this;
			$(".nano").nanoScroller({
	          preventPageScrolling: true,
	          // tabIndex: -1
	        });
		}
	};
	$.fn.Mobinav = function( options ) {
		return this.each(function() {
			var mobi = Object.create( Mobinav );
			
			mobi.init( options, this );

			$.data( this, 'Mobinav', mobi );
		});
	};

	$.fn.Mobinav.options = {
		effect : 'slideTop',
		target : null,
		opener : null,
		wrapper : null
	};

	//scrolling animation
	var ost = 0;

	$(window).scroll(function() {
		var cOst = $(this).scrollTop();
		// console.log(cOst + '--' + ost)
		if(cOst > ost && (cOst > 0) && !$( 'body' ).hasClass( 'mobi-open' )) {
		   $( '.mobiwp-navigation' ).addClass( 'mobiwp-scrollup' ).removeClass( 'mobiwp-scrolldown' );
		}
		else {
			if(!$( 'body' ).hasClass( 'mobi-open' )){
				$( '.mobiwp-navigation' ).addClass( 'mobiwp-scrolldown' ).removeClass( 'mobiwp-scrollup' );
			}
		}

		ost = cOst;
	});

})( jQuery, window, document );