jQuery(function($){
    'use strict';

    var reaction_container = jQuery( "#reaction-thumb"),
        reaction_color_container = jQuery( "#term-icon-background-color-wrap" );

    $( '#reaction_icon_file_name' ).selectmenu({
        width: 200,
        change: function( event, data ) {
            reaction_container.find( 'img' ).attr( 'src', data.item.element.data( 'url' ) );
        }
    });

    $( '#term_icon_color_scheme').selectmenu({
        width: 200,
        change: function( event, data ) {
            var _color_scheme = data.item.value,
                _choosen_color = null;

            if( "default" == _color_scheme ) {
                _choosen_color = reaction_container.data( 'default-color' );
            } else {
                _choosen_color = $("#reaction-custom-color-wrap input").val();
            }
            reaction_container.css({ backgroundColor : _choosen_color });
            reaction_color_container.attr( 'color-scheme', _color_scheme );
        }
    });

    /**
     * Render color picker
     */
    $('.color-wrap input[type="text"]').wpColorPicker({
        change : function( event, ui ){
            reaction_container.css({ backgroundColor : ui.color.toCSS() });
        }
    });

});