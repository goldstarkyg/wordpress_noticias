jQuery( function($) {
    'use strict';

    /**
     * Checkbox Multiple Control
     */
    $( '.customize-control-multiple-checkbox input[type="checkbox"]' ).on(
        'change',
        function() {

            var checkbox_values = $( this ).parents( '.customize-control' ).find( 'input[type="checkbox"]:checked' ).map(
                function() {
                    return this.value;
                }
            ).get().join( ',' );

            $( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
        }
    );

    wp.customize( 'settings_reactions_maximal_count_per_vote', function( setting ) {
        setting.bind( function( value ) {
            var code = 'positive_number';
            if ( value <= 0 ) {
                setting.notifications.add( code, new wp.customize.Notification(
                    code,
                    {
                        type: 'error'
                    }
                ) );
            } else {
                setting.notifications.remove( code );
            }
        } );
    } );

} );