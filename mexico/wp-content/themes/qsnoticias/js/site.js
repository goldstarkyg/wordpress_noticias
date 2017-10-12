(function ($) {
    "use strict";

    // Sidebar first widget
    $('#secondary .widget:first,#secondary-container .widget:first').addClass('first');


    /************************************************** Plugins **************************************************/

    //Featured Carousel
    if ($(".featured-carousel").length) {

        $(".featured-carousel.big-item").slick({
            infinite: false,
            slidesToShow: 6,
            slidesToScroll: 2,
            rtl: bb.isRTL,
            responsive: [
                {
                    breakpoint: 1000,
                    settings: {
                        arrows: false,
                        slidesToShow: 4,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        arrows: true,
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        arrows: true,
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 0,
                    settings: {
                        arrows: true,
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
        $(".featured-carousel.small-item").slick({
            infinite: false,
            slidesToShow: 8,
            slidesToScroll: 2,
            rtl: bb.isRTL,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 6,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 1000,
                    settings: {
                        arrows: false,
                        slidesToShow: 4,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        arrows: true,
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        arrows: true,
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 0,
                    settings: {
                        arrows: true,
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }

    // Popups
    $('.js-inline-popup').fancybox({
        padding: 0
    });

    /************************************************** /end Plugins **************************************************/

    if (bb.isMobile && boombox_gif_event == 'hover') {
        boombox_gif_event = 'click';
    }

    // Post GIF play on scroll & click
    function GIFimage() {

        $('.single .single.post img[src*=".gif"],.post-list.standard img[src*=".gif"]').not('.gallery-item img[src*=".gif"], .regif_row_parent img[src*=".gif"],.next-prev-pagination img[src*=".gif"]').Hyena({
            "style": 1,
            "controls": false,
            "on_hover": (boombox_gif_event == 'hover'),
            "on_scroll": (boombox_gif_event == 'scroll')
        });
    }

    GIFimage();

    // Post Featured Video autoplay
    if (bb.html.hasClass('video')) {
        $('.post-thumbnail video').not('.gif-video').each(function () {
            var video = $(this)[0];
            featuredVideo(video);
        });

        $(' video.gif-video').each(function () {
            var video = $(this)[0];
            GIFvideo(video);
        });

        $(' img.gif-image').each(function () {
            var img = $(this)[0];
            GIFtoVideo(img);
        });
    }

    function featuredVideo(video) {

        var $videoWrapper = $(video).parent(),
            autoPause = true,
            canPlay = true;

        if (!bb.isMobile) {
            var videoView = new Waypoint.Inview({
                element: video,
                entered: function () {
                    if (canPlay) {
                        $videoWrapper.addClass('play');
                        video.play();
                    }

                },
                exited: function () {
                    if (canPlay) {
                        setTimeout(function () {
                            video.pause();
                        }, 150);
                    }
                }
            });

            $videoWrapper.find('.icon-volume').on("click", function () {
                $(video).prop('controls', true);
                $(video).prop('muted', false);
                $(this).hide();
                canPlay = false;
                return false;
            });

        } else {
            $videoWrapper.on("click", function () {
                $videoWrapper.addClass('play');
                video.play();
                $(video).prop('controls', true);
                $(video).prop('muted', false);
                $videoWrapper.find('.icon-volume').hide();
                return false;
            });
        }
    }

    function GIFvideo(video) {

        video.pause();

        $(video).attr('width', '100%').attr('height', 'auto');

        var $videoWrapper = $(video).parent(),
            canPlay = true;


        if (bb.isMobile) {
            $(video).attr('webkit-playsinline', 'webkit-playsinline');
        }
        if (boombox_gif_event == 'hover') {

            $videoWrapper.on('mouseenter touchstart', function () {
                $videoWrapper.addClass('play');
                video.play();

            }).on('mouseleave touchend', function () {
                $videoWrapper.removeClass('play');
                video.pause();
            });

        } else if (boombox_gif_event == 'scroll') {

            var videoView = new Waypoint.Inview({
                element: video,
                entered: function () {
                    if (canPlay) {
                        $videoWrapper.addClass('play');
                        video.play();
                    }

                },
                exited: function () {
                    if (canPlay) {
                        setTimeout(function () {
                            $videoWrapper.removeClass('play');
                            video.pause();
                        }, 150);

                    }
                }
            });
        }
        $videoWrapper.on('click', function (e) {
            e.stopPropagation();
            if (!$videoWrapper.hasClass('play')) {
                video.play();
                $videoWrapper.addClass('play');
            } else {
                video.pause();
                $videoWrapper.removeClass('play');
            }
            return false;
        });
    }

    function GIFtoVideo(img) {

        var $videoWrapper = $(img).parent();
        var imgUrl = $(img).attr('src');
        var video;

        $videoWrapper[0].addEventListener('click', function () {

            if (!$(this).hasClass('video-done')) {

                var videoUrl = $(img).data('video');

                video = document.createElement("video");

                video.setAttribute("loop", true);
                video.setAttribute("poster", imgUrl);
                video.setAttribute("webkit-playsinline", "webkit-playsinline");

                var videoSrc = document.createElement("source");

                videoSrc.setAttribute("src", videoUrl);
                videoSrc.setAttribute("type", "video/mp4");

                video.appendChild(videoSrc);
                $(this)[0].appendChild(video);

                toggleVideoPlaying(video);

                $(this).find('img').remove();
                $(this).addClass('video-done');

            } else {

                toggleVideoPlaying(video);
            }
        });

        var videoView = new Waypoint.Inview({
            element: $videoWrapper,
            exited: function () {
                if ($videoWrapper.hasClass('video-done')) {
                    var img = '<img  src=' + imgUrl + ' alt="">';
                    $(img).appendTo($videoWrapper);
                    $videoWrapper.find('video').remove();
                    $videoWrapper.removeClass('play');
                    $videoWrapper.removeClass('video-done');
                }
            }
        });
    }

    function toggleVideoPlaying(video) {

        if (video.paused) {

            var promise = video.play();

            // promise won’t be defined in browsers that don't support promisified play()
            if (promise === undefined) {

                //Promisified video play() not supported

                video.setAttribute("controls", true);

            } else {
                promise.then(function () {
                    // Video playback successfully initiated, returning a promise
                }).catch(function (error) {
                    // Error initiating video playback

                    video.setAttribute("controls", true);
                });
            }

            $(video).parent().addClass('play');

        } else {
            video.pause();
            $(video).parent().removeClass('play');

        }
    }


    /* Youtube Stream */

    if ($('#stream-player').length) {


        var player, container = $('#video-stream'), videoID = $('#stream-player').data('id');

        function onYouTubeIframeAPIReady() {
            player = new YT.Player('stream-player', {
                height: '420',
                width: '760',
                videoId: videoID,
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });
        }

        onYouTubeIframeAPIReady();

        function onPlayerReady(event) {

            container.find('.video-play').on("click", function (e) {
                e.preventDefault();
                event.target.playVideo();
                container.addClass('isPlaying');
            });
        }

        function onPlayerStateChange(event) {
        }

        function stopVideo() {
            player.stopVideo();
        }

        var activeVideo = container.find('li.active'),
            pos = activeVideo.position(),
            scrollContainner = document.getElementById("stream-scroll");
        scrollContainner.scrollTop = pos.top;
    }


    /************************************************** Click Events **************************************************/

        // Top search toggle
    $(document).on("click", '.top-search .js-toggle', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var $this = $(this);
        if ($this.parent().hasClass('open')) {
            $this.parent().removeClass('open');
        } else {
            $this.parent().addClass('open');
            setTimeout(function () {
                $this.parent().find('input')[0].focus()
            }, 3000);
        }
    });
    $(document).on("click", '.top-search,#more-menu,#mobile-navigation', function (e) {
        e.stopPropagation();
    });


    // Body click events
    $(document).on("click", 'body', function (e) {
        $('.top-search').removeClass('open');
        $('#more-menu-toggle').removeClass('active');
        $('.more-menu-item .more-menu').removeClass('active');
        $('.toggled-on').removeClass('toggled-on');
        if (bb.html.hasClass('main-menu-open')) {
            //e.preventDefault(); See if this code will be needed for future
            bb.html.removeClass('main-menu-open');
        }
    });

    // Main menu open event
    $(document).on("click", '#menu-button', function (e) {
        e.stopPropagation();
        e.preventDefault();
        bb.html.addClass('main-menu-open');
    });

    // Main menu  close event
    $(document).on("click", '#menu-close', function (e) {
        e.stopPropagation();
        e.preventDefault();
        bb.html.removeClass('main-menu-open');
    });

    // More menu toggle
    $(document).on("click", '#more-menu-toggle', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).toggleClass('active');
        $('.more-menu-item .more-menu').toggleClass('active');
    });

    // Animation to page top
    $(document).on("click", '#go-top', function () {
        $('body,html').animate({
            scrollTop: 0
        }, 800);
        return false;
    });

    // User Profile menu open on click in mobile
    if (bb.isMobile && $('.account-box .user-box .menu').length) {
        $('.account-box .user-box > a').on('click', function (e) {
            e.preventDefault();
            $(this).parent().toggleClass('active');
        });
    }
    /************************************************** /end Click Events **************************************************/

    /************************************************** Sticky Social Box **************************************************/
    function StickySocialBox(enebled) {

        var
            sticky = $('#sticky-share-box'),
            offset = 0,
            adminBarOffset = 0;

        if (bb.adminBar) adminBarOffset = bb.adminBar;

        if (bb.fixedHeader) {
            offset = bb.fixedHeader + adminBarOffset;
        } else if (bb.floatingPagination) {
            offset = bb.floatingPagination + adminBarOffset;
        }

        if (!enebled) {
            var StickyActive = new Waypoint.Sticky({
                element: sticky[0],
                offset: offset
            });
        }

        var stickyParent = sticky.parent('.sticky-wrapper'),
            stickyWidth = stickyParent.outerWidth();
        sticky.removeAttr('style');
        sticky.css({
            'width': stickyWidth,
            'top': offset + 'px',
        });

        $(window).scroll(function () {
            if (bb.scrollTop >= bb.stickyBorder - 500) {
                sticky.addClass('hidden');
            } else {
                sticky.removeClass('hidden');
            }
        });
    }

    if ($('#sticky-share-box').length) {
        StickySocialBox(false);
    }
    /************************************************** /end Sticky Social Box **************************************************/

    /************************************************** Sticky Sidebar **************************************************/
    var
        STstickyHeight = 1,
        SToffset = 25,
        STenebled = false;

    function StickySidebar(STenebled, obj) {

        if ($('#secondary').outerHeight(true) < $('#main').outerHeight(true)) {

            var STsticky = $(obj), adminBarOffset = 0;

            STstickyHeight = STsticky.innerHeight();
            SToffset = 25;

            if (bb.adminBar) adminBarOffset = bb.adminBar;

            if (bb.fixedHeader) {
                SToffset = bb.fixedHeader + adminBarOffset + 25;
            } else if (bb.floatingPagination) {
                SToffset = bb.floatingPagination + adminBarOffset + 25;
            }

            if (!STenebled) {
                var StickyActive = new Waypoint.Sticky({
                    element: STsticky[0],
                    offset: SToffset
                });
                STenebled = true;
            }

            var stickyParent = STsticky.parent('.sticky-wrapper'),
                stickyWidth = stickyParent.outerWidth();

            STsticky.removeAttr('style');

            STsticky.css({
                'width': stickyWidth,
                'top': SToffset + 'px'
            });

            $(window).scroll(function () {

                if (bb.scrollTop >= (bb.stickyBorder - STstickyHeight - SToffset)) {

                    STsticky.css({
                        'position': 'absolute',
                        'bottom': 0,
                        'top': 'inherit',
                        'width': stickyWidth
                    });
                } else {
                    STsticky.removeAttr('style');
                    STsticky.css({
                        'width': stickyWidth,
                        'top': SToffset + 'px'
                    });
                }
            })
        }
    }

    function StickyContent(obj) {
        var sticky = $(obj),
            next = sticky.nextAll('.widget');
        $(next).appendTo(sticky);
    }

    /************************************************** /end Sticky Sidebar **************************************************/

    /************************************************** Load More Content **************************************************/
    if ($('#load-more-button').length) {

        var
            load_more_btn = $('#load-more-button'),
            loading = false,
            firstClick = false,
            loadType = load_more_btn.data('scroll');


        $('#load-more-button').on("click", function () {
            if (loading) return;

            loading = true;

            var next_page_url = load_more_btn.attr('data-next_url');

            load_more_btn.parent().addClass('loading');
            jQuery.post(next_page_url, {},
                function (response) {
                    var html = $(response),
                        container = html.find('#post-items'),
                        articles = container.find('article').addClass('item-added'),
                        more_btn = html.find('#load-more-button');

                    $('#post-items').append(articles);

                    // Post GIF play on scroll & click

                    $('.post-list.standard .item-added  img[src*=".gif"]').Hyena({
                        "style": 1,
                        "controls": false,
                        "on_hover": (boombox_gif_event == 'hover'),
                        "on_scroll": (boombox_gif_event == 'scroll')
                    });

                    if ($('#secondary .sticky-sidebar,#secondary-container .sticky-sidebar').length && !bb.isMobile) {
                        StickySidebar(true, '.sticky-sidebar');
                    }
                    // Post Featured Video autoplay
                    if ($("html").hasClass('video')) {
                        $('#post-items  .item-added video').not('.gif-video').each(function () {
                            var video = $(this)[0];
                            featuredVideo(video);
                        });
                        $('#post-items  .item-added video.gif-video').each(function () {
                            var video = $(this)[0];
                            GIFvideo(video);
                        });

                        $('#post-items  .item-added img.gif-image').each(function () {
                            var img = $(this)[0];
                            GIFtoVideo(img);
                        });
                    }

                    $('.has-full-post-button .post-list.standard .item-added .post-thumbnail img').each(function(){
                        ShowFullPost($(this));
                    });

                    $('#post-items  .item-added').removeClass('item-added');

                    load_more_btn.parent().removeClass('loading');

                    if (more_btn.length > 0) {
                        var next_url = more_btn.data('next_url');
                        load_more_btn.attr('data-next_url', next_url);
                    } else {
                        load_more_btn.parent().remove();
                    }

                    loading = false;
                    firstClick = true;
                    if (loadType === 'on_demand' || loadType === 'infinite_scroll') {
                        infiniteScroll();
                    }
                }
            );

        });


        var infiniteScroll = function () {

            if (loadType === 'on_demand' && !firstClick) {
                return false;
            }

            load_more_btn.waypoint(function (direction) {
                if (direction === 'down') {
                    load_more_btn.trigger("click");
                }
            }, {
                offset: '150%'
            });
        }

        if (loadType === 'infinite_scroll') {
            infiniteScroll();
        }

    }

    $("body").on( "alnp-post-loaded", function(){
        $("div#balnp_content_container  .item-added video").not(".gif-video").each(function () {
            var video = $(this)[0];
            featuredVideo(video);
        });

        $("div#balnp_content_container  .item-added video.gif-video").each(function () {
            var video = $(this)[0];
            GIFvideo(video);
        });

        $("div#balnp_content_container  .item-added img.gif-image").each(function () {
            var img = $(this)[0];
            GIFtoVideo(img);
        });

        $("div#balnp_content_container .item-added").removeClass("item-added");
    } );

    /************************************************** Functions **************************************************/
    // add main navigation to init
    initMainNavigation($('.main-navigation'));

    floatingPagination();

    $('.has-full-post-button .post-list.standard .post-thumbnail img').each(function(){
        ShowFullPost($(this));
    });

    /************************************************** /end Functions **************************************************/

    /************************************************** /end Load More Content **************************************************/

    /** Auto load next post trigger load (Load Next post plugin) */
    $('body').on('alnp-post-loaded', function () {
        if (typeof ZombifyOnAjax !== 'undefined' && ZombifyOnAjax) {
            ZombifyOnAjax();
        }
    });

    /************************************************** Window Load **************************************************/
    $(window).load(function () {
        if ($('#sticky-share-box').length) {
            StickySocialBox(true);
        }

        if ($('.sticky-sidebar').length && !bb.isMobile) {
            $('.sticky-sidebar').each(function () {
                StickyContent($(this));
                StickySidebar(false, $(this));
            });

        }

    });
    /************************************************** /end Window Load **************************************************/

    /************************************************** Window Resize **************************************************/
    $(window).resize(function () {
        if ($('#secondary .sticky-sidebar,#secondary-container .sticky-sidebar').length && !bb.isMobile) {
            StickySidebar(true, '.sticky-sidebar');
            $(window).scroll();
        }
        if ($('#sticky-share-box').length) {
            StickySocialBox(true);
        }
    });
    /************************************************** /end Window Resize **************************************************/


    /************************************************** Window Scroll **************************************************/

    $(window).scroll(function () {

        if (bb.scrollTop >= 500) {
            $('#go-top').addClass('show');
        } else {
            $('#go-top').removeClass('show');
        }


        if (bb.scrollTop >= bb.stickyBorder - 500) {
            $('.fixed-pagination').addClass('hide');
        } else {
            $('.fixed-pagination').removeClass('hide');
        }
    });
    /************************************************** /end Window Scroll **************************************************/


    /************************************************** Woocommerce Form ****************************************************/
    $(function(){
        setFormPlaceholders('.woocommerce','.form-row');
    })
    $(window).load(function(){
        setFormPlaceholders('.woocommerce','.form-row');
    })
    /************************************************** /end Woocommerce Form ***********************************************/

})(jQuery);
