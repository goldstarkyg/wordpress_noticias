/*
	Admin Settings Page Scripts
*/
(function($) { 
    jQuery('.mobiwp-colorpicker').wpColorPicker();
    mobiwp_draggable();
    jQuery('.mobiwp-select2').select2();
    jQuery("#mobiwp-main").select2().on('change', function(e){
        $('.mobiwp-menu-wrap').fadeOut('fast');
        var selected = e.val;
        if(typeof selected != 'undefined' && selected.length > 0){
            jQuery.ajax({
                type : "POST",
                url : mobiwpAjax.ajaxurl,
                data : {action: "mobiwp_get_menu_items", term_id : e.val},
                success: function(response) {
                    $('.mobiwp-draggable-left').html( response );
                    $('.mobiwp-menu-wrap').fadeIn('fast');
                    mobiwp_draggable();
                 }
            });

        }else{
            $('.mobiwp-menu-wrap').fadeIn('fast');
            alert('Please Select Main Menu. Thanks!');
        }
        
    });

    mobiwp_font_select();

    jQuery('#mobiwp-popup-menu').select2().on('change', function(e){
        var selected = e.val;
        if(typeof selected != 'undefined' && selected.length > 0){
            jQuery.ajax({
                type : "POST",
                url : mobiwpAjax.ajaxurl,
                data : {action: "mobiwp_get_menu_items", term_id : e.val, usage : 'popup'},
                success: function(response) {
                    jQuery('.mobiwp-menu-customizer').html(response);
                    mobiwp_font_select();
                 }
            });

        }else{
            $('.mobiwp-menu-wrap').fadeIn('fast');
            alert('Please Select Navigation Menu. Thanks!');
        }
    });

    jQuery(document).on('click', '.handlediv-mobiwp, .mobiwp-menu-customizer .mobiwp-widget-top .hndle',function(){
        jQuery(this).parent('.mobiwp-widget-top').parent('div').find('.mobiwp-widget-inside').slideToggle(400);
    });

    jQuery(document).on('click', '.mobi-remove-this',function(e){
        jQuery(this).closest('.mobiwp-widget-sortable').slideToggle(350, function(){ 
            jQuery(this).remove();
        });
        e.preventDefault();
    });

    jQuery(document).on('click', '.mobiwp-visibility-check',function(){
        if (this.checked) {
            visibility = 1;
        }else{
            visibility = 0;
        }
        jQuery(this).parent('td').find('.mobiwp-visibility-input').val( visibility );
    });
    jQuery(document).on('click', '#mobiwp-social-custom',function(){
        if (this.checked) {
            jQuery('.for-social-custom').fadeIn();
        }else{
            jQuery('.for-social-custom').fadeOut('fast');
        }
    });

    //version 2 image uploader
    if( jQuery('.mobiwp_media_upload').length > 0 ){

        var file_frame;

        jQuery('body').on('click','.mobiwp_media_upload', function( event ){
            event.preventDefault();
            var widget_id = jQuery(this).closest('.widget').attr('id');

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
              title: jQuery( this ).data( 'uploader_title' ),
              button: {
                text: jQuery( this ).data( 'uploader_button_text' ),
              },
              multiple: false  // Set to true to allow multiple files to be selected
            });

            // When an image is selected, run a callback.
            file_frame.on( 'select', function() {
              // We set multiple to false so only get one image from the uploader
              attachment = file_frame.state().get('selection').first().toJSON();
              jQuery('input#mobiwp_image_assigned').val(attachment.url);
              // jQuery('div.wpgldpnl_media_image').html('<img src="'+ attachment.url +'" />');
              // jQuery('#wpautbox_user_image_url').html('<img src="'+ attachment.url +'" width="120"/><br />');
              // Do something with attachment.id and/or attachment.url here
            });

            // Finally, open the modal
            file_frame.open();
        });

        jQuery('.mobiwp_remove_image').on('click',function(){
            jQuery('input#mobiwp_image_assigned').val('');
              jQuery('div.wpgldpnl_media_image').html('');
        });
    
    }


})(jQuery);

function mobiwp_draggable(){
    jQuery('.mobiwp-sortable').sortable({
        handle: '.hndle',
        placeholder: 'ui-state-highlight',
        stop: function(event, ui) {
            jQuery(ui.item).removeClass("mobiwp-widget");
        }
    });
    // jQuery(".mobiwp-sortable").disableSelection();

    jQuery("#mobiwp-draggable-right .inside").droppable({
        activeClass: "ui-state-default",
        hoverClass: "ui-state-hover",
        accept: '.mobiwp-widget',
        drop: function(event, ui) {
            var item = jQuery(ui.draggable);

            item.removeClass("mobiwp-widget");
            jQuery('.mobiwp-placeholder').hide();
            //add name on the fields
            item.find('select,input,textarea').each(function(){
                getName = jQuery(this).data('name');
                if(typeof getName != 'undefined'){
                    jQuery(this).attr('name', getName);
                }
            });

            item.find('.mobiwp-icon-group').select2({
                placeholder: "Select Icon Group",
            }).on('change', function(e){
                if(e.val == 'fontawesome'){
                    jQuery(this).closest('table').find('.mobiwp-icon-ionicons').hide();
                    jQuery(this).closest('table').find('.mobiwp-icon-fontawesome').show();
                }else if(e.val == 'ionicons'){
                    jQuery(this).closest('table').find('.mobiwp-icon-fontawesome').hide();
                    jQuery(this).closest('table').find('.mobiwp-icon-ionicons').show();
                }
                jQuery(this).closest('table').find('.mobiwp-icon-selection').css({ 'visibility' : 'visible' });
            });
            item.find('.mobiwp-icon-fontawesome').select2({
                placeholder: "Select Icon",
                allowClear: true,
                formatResult: mobiwp_icon_fontawesome,
                formatSelection: mobiwp_icon_fontawesome,
                escapeMarkup: function(m) {
                  return m;
                }
            });
            item.find('.mobiwp-icon-ionicons').select2({
                placeholder: "Select Icon",
                allowClear: true,
                formatResult: mobiwp_icon_ionicons,
                formatSelection: mobiwp_icon_ionicons,
                escapeMarkup: function(m) {
                  return m;
                }
            });
            // item.find('.mobiwp-widget-inside').css({ 'display' : 'block' });
            // item.parent().wrap('<li></li>').parent();

            jQuery(this).find('.mobiwp-sortable').append( item ); 

        },
        over: function( event, ui ) {
            // $(this).find('p.mobi-description').hide();
        }
    });

    jQuery('.mobiwp-draggable-left .mobiwp-widget').draggable({
        connectToSortable: ".mobiwp-sortable",
        helper: 'clone',
        revert: 'invalid',
        // containment: '#mobi-droppable',
    });
}

function mobiwp_font_select(){
    jQuery('.mobiwp-draggable-right .mobiwp-icon-group, .mobiwp-menu-customizer .mobiwp-icon-group').select2({
        placeholder: "Select Icon Group",
    }).on('change', function(e){
        if(e.val == 'fontawesome'){
            jQuery(this).closest('table').find('.mobiwp-icon-ionicons').hide();
            jQuery(this).closest('table').find('.mobiwp-icon-fontawesome').show();
        }else if(e.val == 'ionicons'){
            jQuery(this).closest('table').find('.mobiwp-icon-fontawesome').hide();
            jQuery(this).closest('table').find('.mobiwp-icon-ionicons').show();
        }
        jQuery(this).closest('table').find('.mobiwp-icon-selection').css({ 'visibility' : 'visible', 'position' : 'relative' });
    });

    jQuery('.mobiwp-draggable-right .mobiwp-icon-fontawesome, .mobiwp-menu-customizer .mobiwp-icon-fontawesome').select2({
        placeholder: "Select Icon",
        allowClear: true,
        formatResult: mobiwp_icon_fontawesome,
        formatSelection: mobiwp_icon_fontawesome,
        escapeMarkup: function(m) {
          return m;
        }
    });
    jQuery('.mobiwp-draggable-right .mobiwp-icon-ionicons, .mobiwp-menu-customizer .mobiwp-icon-ionicons').select2({
        placeholder: "Select Icon",
        allowClear: true,
        formatResult: mobiwp_icon_ionicons,
        formatSelection: mobiwp_icon_ionicons,
        escapeMarkup: function(m) {
          return m;
        }
    });
}

function mobiwp_icon_fontawesome(o) {
    if (!o.id)
      return o.text;
    else
        return "<i class='fa "+ o.id +"'></i> | " + o.text;
}
function mobiwp_icon_ionicons(o) {
    if (!o.id)
      return o.text;
    else
        return "<i class='mobi-ion "+ o.id +"'></i> | " + o.text;
}