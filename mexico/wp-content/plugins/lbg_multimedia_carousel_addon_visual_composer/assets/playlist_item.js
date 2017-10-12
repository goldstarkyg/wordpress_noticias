    window.VcPlaylistItemView = window.VcColumnView.extend({
        events:{
			//////'mouseover .vc_playlist_item':'activateControls',
			/*'click .vc_playlist_item':'activateControls',
            'click > .vc_controls .column_delete,.wpb_vc_accordion_tab > .vc_controls .vc_control-btn-delete':'deleteShortcode',
            'click > .vc_controls .column_add,.wpb_vc_accordion_tab >  .vc_controls .vc_control-btn-prepend':'addElement',
            'click > .vc_controls .column_edit,.wpb_vc_accordion_tab >  .vc_controls .vc_control-btn-edit':'editElement',
            'click > .vc_controls .column_clone,.wpb_vc_accordion_tab > .vc_controls .vc_control-btn-clone':'clone',*/
			'click .vc_playlist_item':'activateControls',
            'click > .vc_controls .column_delete,.vc_playlist_item > .vc_controls .vc_control-btn-delete':'deleteShortcode',
            'click > .vc_controls .column_add,.vc_playlist_item >  .vc_controls .vc_control-btn-prepend':'addElement',
            'click > .vc_controls .column_edit,.vc_playlist_item >  .vc_controls .vc_control-btn-edit':'editElement',
            'click > .vc_controls .column_clone,.vc_playlist_item > .vc_controls .vc_control-btn-clone':'clone',
            //'click > [data-element_type] > .wpb_element_wrapper > .vc_empty-container':'addToEmpty'
        },
        setContent:function () {
            this.$content = this.$el.find('> [data-element_type] > .wpb_element_wrapper > .vc_container_for_children');
        },
        changeShortcodeParams:function (model) {
            var params = model.get('params');
            window.VcPlaylistItemView.__super__.changeShortcodeParams.call(this, model);
            if (_.isObject(params) && _.isString(params.title)) {
                this.$el.find('> h3 .tab-label').text(params.title);
            }
        },
        /*setEmpty:function () {
            $('> [data-element_type]', this.$el).addClass('vc_empty-column');
            this.$content.addClass('vc_empty-container');
        },
        unsetEmpty:function () {
            $('> [data-element_type]', this.$el).removeClass('vc_empty-column');
            this.$content.removeClass('vc_empty-container');
        }*/
        activateControls:function (e) {
            /*jQuery('.vc_control-container').css({
				'display':'block',
				'visibility':'visible'
			});*/
			//alert ( jQuery('.vc_controls-tc').css('display') );
        }
    });
	
	
	
	