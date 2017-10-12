
/** GLOBAL Variables */

var bb = {
    isMobile : false,
    isRTL : false,
    html  : jQuery('html'),
    windowWidth : jQuery(window).width(),
    windowHeight : jQuery(window).height(),
    stickyBorder : jQuery('#sticky-border').offset().top,
    fixedHeader : 0,
    scrollTop : 0,
    floatingPagination : 0,
    adminBar : 0
};

(function ($) {
    "use strict";

    if( bb_detect_mobile() ){
        bb.isMobile = true;
        bb.html.addClass('mobile');
    } else {
        bb.isMobile = false;
        bb.html.addClass('desktop');
    }

    if($('body').hasClass('rtl')){
        bb.isRTL = true;
    }

    function bb_detect_mobile() {
        var is_mobile = ( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test( navigator.userAgent ) );

        return is_mobile;
    }

    function setSize(){
        bb.windowWidth = $(window).width();
        bb.windowHeight = $(window).height();
        $('.wh').css('height', bb.windowHeight +'px');
        $('.min-wh').css('min-height', bb.windowHeight +'px');
        $('.error404 .page-wrapper').css('min-height', bb.windowHeight);

        var topWidth = $('.page-wrapper').width(),
            bottomWidth = $('.page-wrapper').width();

        $('#video-stream .viewport').css('height',$('#video-stream .video-wrapper').height());

        $('#header .top').css('width',topWidth);
        $('#header .bottom').css('width',bottomWidth);

        if($('#wpadminbar').length){
            bb.adminBar = $('#wpadminbar').outerHeight(true);
        }
    }
    setSize();

    headerAlignment();

    /************************************************** Tabs **************************************************/
    var tabActive = $('.bb-tabs .tabs-menu>li.active');
    if( tabActive.length > 0 ){
        for (var i = 0; i < tabActive.length; i++) {
            var tab_id = $(tabActive[i]).children().attr('href');

            $(tab_id).addClass('active').show();
        }
    }

    $('.bb-tabs .tabs-menu a').on("click", function(e){
        var tab = $(this);
        var tab_id = tab.attr('href');
        var tab_wrap = tab.closest('.bb-tabs');
        var tab_content = tab_wrap.find('.tab-content');

        tab.parent().addClass("active");
        tab.parent().siblings().removeClass('active');
        tab_content.not(tab_id).removeClass('active').hide();
        $(tab_id).addClass('active').fadeIn(500);

        e.preventDefault();
    });

    /************************************************** /end Tabs **************************************************/

    /************************************************** Window Load **************************************************/
    $(window).load(function () {

        setSize();

        headerAlignment();

        fixedHeader();

        bb.html.addClass('page-loaded');
    });
    /************************************************** /end Window Load **************************************************/

    /************************************************** Window Resize **************************************************/
    $(window).resize(function () {
        setSize();

        if(this.resizeTO) clearTimeout(this.resizeTO);
        this.resizeTO = setTimeout(function() {
            $(this).trigger('resizeEnd');
        }, 100);
    });

    // Deteck Window Resize event end
    $(window).bind('resizeEnd', function() {
        headerAlignment();
    });
    /************************************************** /end Window Resize **************************************************/


    jQuery(window).scroll(function () {
        bb.scrollTop = jQuery(window).scrollTop();
        bb.stickyBorder = jQuery('#sticky-border').offset().top;
    });

})(jQuery);

function initMainNavigation(container) {

    // Add dropdown toggle that displays child menu items.
    var dropdownToggle = jQuery('<span />', {
        'class': 'dropdown-toggle'
    });

    container.find('.menu-item-has-children > a').after(dropdownToggle);

    container.find('.dropdown-toggle').on("click", function (e) {
        var _this = jQuery(this);

        e.preventDefault();
        e.stopPropagation();
        if(_this.hasClass('toggled-on')){
            _this.removeClass('toggled-on');
            _this.next('.children, .sub-menu').removeClass('toggled-on');
        } else {
            _this.parent().parent().find('.toggled-on').removeClass('toggled-on');
            _this.addClass('toggled-on');
            _this.next('.children, .sub-menu').addClass('toggled-on');
        }
    });
}

function fixedHeader(){
    if(jQuery('.fixed-header').length) {

        if (jQuery('.fixed-top').length && jQuery('.no-top').length < 1) {
            var StickyTop = new Waypoint.Sticky({
                element: jQuery('.fixed-top .top')[0]
            });
            bb.fixedHeader = jQuery('.fixed-top .top').outerHeight(true);
        } else if (jQuery('.fixed-bottom').length && jQuery('.no-bottom').length < 1) {
            var StickyBottom = new Waypoint.Sticky({
                element: jQuery('.fixed-bottom .bottom')[0]
            });
            bb.fixedHeader = jQuery('.fixed-bottom .bottom').outerHeight(true);
        } else if (jQuery('.fixed-both').length){
            var StickyHeader = new Waypoint.Sticky({
                element: jQuery('.fixed-both')[0]
            });
            bb.fixedHeader = jQuery('.fixed-both').outerHeight(true);
        }
    }
}

function floatingPagination(){

    if (jQuery('.fixed-next-page').length) {
        var target = jQuery('#header'),
            offset = target.height();
        bb.floatingPagination = jQuery('.fixed-next-page').outerHeight(true);

        jQuery(window).scroll(function () {
            if (bb.scrollTop >= offset) {
                jQuery('.fixed-next-page').addClass('stuck');
            } else {
                jQuery('.fixed-next-page').removeClass('stuck');
            }
        });
    }
}

// Header items vertical alignment
function headerAlignment() {

    var topHeight = jQuery('#header .top').height(),
        bottomHeight = jQuery('#header .bottom').height();

    jQuery('#header .top .container > *,#header .top .mobile-box > *').each(function(){
        var elementHeight = jQuery(this).innerHeight();
        jQuery(this).css({
            'top': (topHeight-elementHeight)/2 +'px',
            'opacity': 1
        });
    });
    jQuery('#header .top .navigation-box .wrapper > *').each(function(){
        var elementHeight = jQuery(this).innerHeight();
        jQuery(this).css({
            'top': (topHeight-elementHeight)/2 +'px'
        });
    });
    jQuery('#header .bottom .container > *,#header .bottom .mobile-box > *').each(function(){
        var elementHeight = jQuery(this).innerHeight();
        jQuery(this).css({
            'top': (bottomHeight-elementHeight)/2 +'px',
            'opacity': 1
        });
    });
    jQuery('#header .bottom .navigation-box .wrapper > *').each(function(){
        var elementHeight = jQuery(this).innerHeight();
        jQuery(this).css({
            'top': (bottomHeight-elementHeight)/2 +'px'
        });
    });
}

jQuery.fn.fitText = function () {

    return this.each(function () {

        var $this = jQuery(this),
            style = $this.css('font-size'),
            fontSize = parseFloat(style);

        //  resize items based on the object width
        for (var i = fontSize; i > 3; i--) {

            $this.css('font-size', i);

            if ($this.width() <= $this.parent().width()) break;
        }
    });
};

// $(".badge-text .badge .text,.badge-text-angle .badge .text,.no-svg  .badge .text").fitText();

function ShowFullPost(obj) {
    var oW = obj.attr('width'),
        oH = obj.attr('height');

    if (oH / oW >= 3) {
        obj.parents('.post-thumbnail').addClass('show-short-media');
        obj.parents('.post').addClass('full-post-show');
    }
}

/* Function for setting form placeholders
*
*/
function setFormPlaceholders(wrapperSel, rowSel){
    jQuery(wrapperSel + ' ' +rowSel).each(function(){
        if(jQuery(this).children('label').text())
            jQuery(this).find('input').attr('placeholder',jQuery(this).children('label').text());
    })
};