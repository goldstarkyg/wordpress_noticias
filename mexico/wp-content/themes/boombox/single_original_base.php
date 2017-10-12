<?php
/**
 * The template for displaying the single post
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if (!defined('ABSPATH')) {
    die('No direct script access allowed');
}

get_header();

$boombox_featured_image_size = 'boombox_image768';
$boombox_single_options = boombox_get_single_page_settings($boombox_featured_image_size);
$boombox_featured_video = $boombox_single_options['featured_video'];
$boombox_template_options = $boombox_single_options['template_options'];
$boombox_post_template = $boombox_single_options['post_template'];
$boombox_is_nsfw_post = $boombox_single_options['is_nsfw'];
$boombox_article_classes = $boombox_single_options['classes'];
$boombox_disable_strip = $boombox_single_options['disable_strip'];
$boombox_enable_sidebar = $boombox_single_options['enable_sidebar'];

if (!$boombox_disable_strip):
    get_template_part('template-parts/featured', 'strip');
endif;

boombox_the_advertisement('boombox-single-before-content', 'large');

if ('full-width' == $boombox_post_template && have_posts()): the_post();
    $boombox_fimage_style = '';
    if ( apply_filters( 'boombox/single/show_media', ( $boombox_template_options['media'] && boombox_has_post_thumbnail() && boombox_show_thumbail() ) ) ) :
        $thumbnail_size = 'full';
        $boombox_thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id(), $thumbnail_size );
        $boombox_thumbnail_url = isset( $boombox_thumbnail_url[0] ) ? $boombox_thumbnail_url[0] : apply_filters( 'boombox/post-default-thumbnail', '', $thumbnail_size );
        $boombox_fimage_style = $boombox_thumbnail_url ? 'style="background-image: url(\'' . esc_url( $boombox_thumbnail_url ) . '\')"' : '';
    endif; ?>
    <div class="post-featured-image" <?php echo $boombox_fimage_style; ?>>
        <div class="content">
            <!-- entry-header -->
            <header class="entry-header">
                <?php get_template_part('template-parts/single/single', 'header'); ?>
            </header>
        </div>
    </div>
    <?php
    rewind_posts();
endif; ?>
<script>
	jQuery( document ).ready(function() {
		/*Add Active class in sidebar*/
		jQuery('body').on('mousewheel DOMMouseScroll', '.sidebar.sticky', function(e) {
			var scrollTo = null;

			if (e.type == 'mousewheel') {
				scrollTo = (e.originalEvent.wheelDelta * -1);
			}
			else if (e.type == 'DOMMouseScroll') {
				scrollTo = 40 * e.originalEvent.detail;
			}

			if (scrollTo) {
				e.preventDefault();
				jQuery(this).scrollTop(scrollTo + jQuery(this).scrollTop());
			}
		});
		if(jQuery(".fixed-next-page").hasClass("stuck"))
		{
			jQuery("div.sidebar.sticky").css({top:jQuery(".fixed-next-page").position().top+jQuery(".fixed-next-page").outerHeight()});
		} else {
			jQuery("div.sidebar.sticky").css({top:jQuery(".main-container").offset().top-jQuery(window).scrollTop()});
		}
		footertop = jQuery(".footer").offset().top;
		windowtop = jQuery(window).height()+jQuery(window).scrollTop();
		if(footertop < windowtop)
		{
			sidebarbottom = windowtop-footertop+40;
		} else {
			sidebarbottom = 0;
		}
		jQuery("div.sidebar.sticky").css({bottom:sidebarbottom});
		// jQuery(".smart_scroll_container article.smart_scroll").on( "hover", function() {
			// url = jQuery(this).data("link");
			// history.pushState(null, null, url);
			// jQuery('.playlist-item.item').removeClass('active');
            // ahref = jQuery("a[href='"+url+"']");
            // ahref.closest('.playlist-item.item').addClass('active');
			// if(ahref.closest('li').length>0)
			// {
				// atop = ahref.closest('li').position().top;
				// div_scroll = atop + jQuery("div.sidebar.sticky").scrollTop();
				// jQuery("div.sidebar.sticky").animate({scrollTop: div_scroll},2000);
			// }
		// });
		url = oldurl = window.location.href;
		jQuery(window).scroll(function(){
			jQuery.each(jQuery(".smart_scroll_container article.smart_scroll"), function(skey, sval) {
				windowscroll = jQuery(window).scrollTop();
				svaltop = jQuery(sval).offset().top;
				windowbottom = jQuery(window).height()+windowscroll;
				if(windowscroll<svaltop && svaltop<windowbottom)
				{
					// console.log(sval);
					url = jQuery(sval).data("link");
					if(oldurl != url)
					{
						history.pushState(null, null, url);
						jQuery('.playlist-item.item').removeClass('active');
						ahref = jQuery("a[href='"+url+"']");
						ahref.closest('.playlist-item.item').addClass('active');
						if(ahref.closest('li').length>0)
						{
							// console.log(jQuery("div.sidebar.sticky").is(':hover'));
							// if(jQuery("div.sidebar.sticky").is(':hover')==false)
							// {
								atop = ahref.closest('li').position().top;
								div_scroll = atop + jQuery("div.sidebar.sticky").scrollTop();
								jQuery("div.sidebar.sticky").animate({scrollTop: div_scroll},1000);
							// }
						}
						oldurl = url;
					}
				}
			});
			if(jQuery(".fixed-next-page").hasClass("stuck"))
			{
				jQuery("div.sidebar.sticky").css({top:jQuery(".fixed-next-page").position().top+jQuery(".fixed-next-page").outerHeight()});
			} else {
				jQuery("div.sidebar.sticky").css({top:jQuery(".main-container").offset().top-jQuery(window).scrollTop()});
			}
			footertop = jQuery(".footer").offset().top;
			windowtop = jQuery(window).height()+jQuery(window).scrollTop();
			if(footertop < windowtop)
            {
				sidebarbottom = windowtop-footertop+40;
			} else {
				sidebarbottom = 0;
			}
			jQuery("div.sidebar.sticky").css({bottom:sidebarbottom});
			// ahref = jQuery("a[href='"+url+"']");
						// if(ahref.closest('li').length>0)
						// {
							// console.log(jQuery("div.sidebar.sticky").is(':hover'));
							// if(jQuery("div.sidebar.sticky").is(':hover')==false)
							// {
								// atop = ahref.closest('li').position().top;
								// div_scroll = atop + jQuery("div.sidebar.sticky").scrollTop();
								// jQuery("div.sidebar.sticky").animate({scrollTop: div_scroll},1000);
							// }
						// }
        });
		/* jQuery(window).scroll(function(){
            var window_top = jQuery(window).scrollTop() + 12; // the "12" should equal the margin-top value for nav.stick
            var div_top = jQuery('#nav-anchor').offset().top;
			if (window_top > div_top) {
				jQuery('nav').addClass('active');
			} else {
				jQuery('nav').removeClass('active');
			}
        }); */
		/* var $sticky = jQuery('.is-sticky');
		  var $stickyrStopper = jQuery('.footer ');
		  if (!!$sticky.offset()) { // make sure ".sticky" element exists
			var generalSidebarHeight = $sticky.innerHeight();
			var stickyTop = $sticky.offset().top;
			var stickOffset = 0;
			var stickyStopperPosition = $stickyrStopper.offset().top;
			var stopPoint = stickyStopperPosition - generalSidebarHeight - stickOffset;
			var diff = stopPoint + stickOffset;
			jQuery(window).scroll(function(){ // scroll event
			  var windowTop = jQuery(window).scrollTop(); // returns number
			  if (stopPoint < windowTop) { 
				  $sticky.css({ position: 'absolute', top: 0});
			  } else if (stickyTop < windowTop+stickOffset) {
				  $sticky.css({ position: 'fixed', top: stickOffset });
			  } else {
				  $sticky.css({position: 'absolute', top: 0});
			  }
			});
		} */
	});
</script>

<?php 
global $post;
$cat = get_the_category();

 $catid = $cat[0]->term_taxonomy_id;
	$args = array(
		'post_type' => 'post',
		'orderby'   => 'ID',
		'cat' => $catid, 
		'order'     => 'DESC',
		'posts_per_page' => 12,
	); 
	$q = new WP_Query($args);


?>
<div class="container main-container ">
<div class="smart_scroll_container">
     <div id="main " class="site-main " role="main" style="padding: 10px; border-radius: 5px;">
        <?php if (have_posts()): the_post(); 
		$cpost = get_the_ID();
		?>
            <?php
                do_action( 'boombox/single/before-main-content' );
                get_template_part('template-parts/single/content');
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                do_action( 'boombox_single_before_navigation' );
                boombox_the_advertisement('boombox-single-before-navigation', 'large');
                if ($boombox_template_options['navigation']) :
                    get_template_part('template-parts/single/navigation');
                endif;
                if ($boombox_template_options['subscribe_form']) :
                    boombox_mailchimp_form();
                endif;
                if ($boombox_template_options['floating_navbar']) :
                    get_template_part( 'template-parts/single/fixed', 'header' );
                endif;
                if( $boombox_template_options['side_navigation'] ) :
                    get_template_part( 'template-parts/single/fixed', 'navigation' );
                endif;
                if ('post' == get_post_type()):
					get_template_part('template-parts/single/posts', 'related');
					//get_template_part('template-parts/single/posts', 'more-from');
					//get_template_part('template-parts/single/posts', 'dont-miss');
                endif;
                do_action( 'boombox/single/after-main-content' );
			
			
           
                do_action( 'boombox/single/before-main-content' );
				$boombox_featured_image_size = 'boombox_image768';
$boombox_single_options = boombox_get_single_page_settings($boombox_featured_image_size);
$boombox_featured_video = $boombox_single_options['featured_video'];
$boombox_template_options = $boombox_single_options['template_options'];
$boombox_post_template = $boombox_single_options['post_template'];
$boombox_is_nsfw_post = $boombox_single_options['is_nsfw'];
$boombox_article_classes = "smart_scroll ".apply_filters( 'boombox_single_article_classes', $boombox_single_options['classes'] );
$boombox_disable_strip = $boombox_single_options['disable_strip'];
while ( $q->have_posts() ) : $q->the_post();
/* echo'<pre>';
print_r($q);
echo'</pre>'; */
$pid = get_the_ID();
?>
<article id="post-<?php the_ID(); ?>" style="<?php if($cpost == $pid){echo 'display:none;'; }?>; data-link="<?php echo get_permalink(); ?>" <?php post_class($boombox_article_classes); ?> <?php boombox_single_article_structured_data(); ?>>

    <?php if ('full-width' != $boombox_post_template): ?>
        <!-- entry-header -->
        <header class="entry-header">
            <?php get_template_part('template-parts/single/single', 'header'); ?>
            <hr/>
        </header>
    <?php endif; ?>

    <div class="post-meta-info">
        <?php if ($boombox_template_options['author'] || $boombox_template_options['date'] ||
            $boombox_template_options['views'] || $boombox_template_options['comments_count']
        ): ?>
            <div class="post-meta row">
                <div class="col-md-6 col-sm-6">
                    <div class="author-meta">
                        <?php if ($boombox_template_options['author']) :
                            boombox_post_author( array('with_avatar' => true) );
                        endif;
                        boombox_post_date( array( 'display' => $boombox_template_options['date'] ) );
                        ?>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="view-meta">
                        <?php if ($boombox_template_options['comments_count']) :
                            boombox_post_comments();
                        endif;
                        if ($boombox_template_options['views']) :
                            boombox_show_post_views();
                        endif; ?>
                    </div>
                </div>
            </div>
            <?php if ('full-width' == $boombox_post_template): ?>
                <hr/>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($boombox_template_options['top_sharebar']) { ?>
            <?php $top_sharebar_id = $boombox_template_options['sticky_top_sharebar'] ? 'id="sticky-share-box"' : ''; ?>
            <div <?php echo $top_sharebar_id; ?> class="post-share-box top">
                <?php get_template_part('template-parts/single/share', 'box'); ?>
            </div>
        <?php  } ?>
    </div>

    <?php
    /* Show NSFW message, if is NSFW post */
    $boombox_auth_is_disabled = boombox_disabled_site_auth();
    if ($boombox_is_nsfw_post && !is_user_logged_in() && !$boombox_auth_is_disabled):
        printf('<a href="%1$s" class="entry-nsfw js-authentication" >%2$s</a>',
            esc_url('#sign-in'),
            boombox_get_nsfw_message()
        );
    endif;


    $post_thumbnail_html = '';
    if (!$boombox_is_nsfw_post || ($boombox_is_nsfw_post && is_user_logged_in())):

        if ( apply_filters( 'boombox/single/show_media', ( $boombox_template_options['media'] && 'full-width' != $boombox_post_template && boombox_show_thumbail() && ( boombox_has_post_thumbnail() || $boombox_featured_video ) ) ) ) : ?>
            <!-- thumbnail -->
            <div class="post-thumbnail">
                <?php
                if ($boombox_featured_video) :
                    echo $boombox_featured_video;
                elseif ( boombox_has_post_thumbnail() ) :
                    $post_thumbnail_html = get_the_post_thumbnail( null, $boombox_featured_image_size, array( 'play' => true ));
                    echo $post_thumbnail_html;
                    boombox_post_thumbnail_caption();
                endif; ?>
            </div>
            <!-- thumbnail -->
        <?php endif; ?>

        <!-- entry-content -->
        <div itemprop="articleBody" class="entry-content">

            <?php
                if( get_post_type() == 'attachment' ) {
                    echo wp_get_attachment_image( get_the_ID(), 'full' );
                } else {
                    echo '<!-- Start Content -->'; ?>
                  <a href="<?php echo get_permalink(); ?>"><?php the_content(); ?></a>
				  <?php 
                    echo '<!-- End Content -->';
                }
            ?>

            <?php get_template_part('template-parts/single/next-prev-buttons'); ?>

            <?php boombox_the_advertisement('boombox-single-after-next-prev-buttons', 'large'); ?>

        </div>
        <?php
    endif; ?>


    <!-- entry-footer -->
    <footer class="entry-footer">

        <?php do_action( 'boombox/single/microdata', array( 'post_thumbnail_html' => $post_thumbnail_html ) ); ?>

        <!-- <hr/>-->
        <?php if ($boombox_template_options['tags']) :
            boombox_tags_list();
        endif; ?>
        <?php if ($boombox_template_options['bottom_sharebar']) { ?>
            <div class="post-share-box bottom">
                <?php do_action('boombox_single_post_text_before_share'); ?>
                <?php get_template_part('template-parts/single/share', 'box'); ?>
            </div>
        <?php } ?>
    </footer>

    <?php
    if ( $boombox_template_options['reactions'] && ( get_post_type() != 'attachment' ) ) {
        get_template_part('template-parts/single/reaction', 'vote');
    }

    if ( $boombox_template_options['author_info'] && ( get_post_type() != 'attachment' )) {
        boombox_post_author_expanded_info();
    }
    ?>
</article>
				
		<?php		
endwhile;
				
				
                //get_template_part('template-parts/single/content');
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                do_action( 'boombox_single_before_navigation' );
                boombox_the_advertisement('boombox-single-before-navigation', 'large');
                if ($boombox_template_options['navigation']) :
                    get_template_part('template-parts/single/navigation');
                endif;
                if ($boombox_template_options['subscribe_form']) :
                    boombox_mailchimp_form();
                endif;
                if ($boombox_template_options['floating_navbar']) :
                    get_template_part( 'template-parts/single/fixed', 'header' );
                endif;
                if( $boombox_template_options['side_navigation'] ) :
                    get_template_part( 'template-parts/single/fixed', 'navigation' );
                endif;
                if ('post' == get_post_type()):
					get_template_part('template-parts/single/posts', 'related');
					//get_template_part('template-parts/single/posts', 'more-from');
					//get_template_part('template-parts/single/posts', 'dont-miss');
                endif;
                do_action( 'boombox/single/after-main-content' );
        
				
				
        endif; ?>
		
		
	</div>
</div>	



    <?php if ('no-sidebar' != $boombox_post_template && $boombox_enable_sidebar):
       // get_sidebar(); ?>
		
		<div class="fix-postside">
			<div class="sidebar is-sticky fit stuck sticky" style="width: 26%; bottom: 0px; top: 80px; position: fixed; overflow-y: scroll; -webkit-transform: translate(0); ">
			<div class="list is-playlist noprint" id="fix-postside">
			<div id="nav-anchor"></div>
				<nav>
				<ul>
					<!-- <li>
						
							<h3>Espectáculos</h3>
						
					</li>--!>
					<?php
						
						$cpid = $post->ID;	
						$postdata = get_post($cpid);
						/* echo'<pre>';
						print_r($postdata);
						echo'</pre>'; */
					?>
					<li>
					<a class="scroll_post" href="<?php echo $cpid; ?>"></a>
					<div class="post<?php echo $cpid; ?>" id="<?php echo get_permalink();?>"></div>
					  <div data-type="Article" data-icon-class="icon loading" class="playlist-item item active" id="h<?php echo $cpid;  ?>" data-loaded="true" style="">
						<a href="<?php echo get_permalink();?>" data-id="" class="getPage" data-tags="" data-special="none" data-cms-ai="0">
							<div class="photo">
							
								<img src="<?php echo get_the_post_thumbnail_url(null,'thumbnai'); ?>" alt="" width="100" height="75">
							</div>
							<div class="text">
								<div class="title"><?php echo get_the_title(); ?></div>
							</div>
						</a>
					  </div>
					</li>
					<?php 
								
						$args = array(
							'post_type' => 'post',
							'cat' => $catid, 
							'orderby'   => 'ID',
							'order'     => 'DESC',
							'posts_per_page' => 20,
						); 
						$my_query = new WP_Query($args);
						while ($my_query->have_posts()) : $my_query->the_post();
						$pid = get_the_ID();
					?>
					<li style="<?php if($pid == $cpid){echo "display:none";} ?>">
					<a  class="scroll_post" href="<?php echo $pid; ?>"></a>
					<div class="post<?php echo $pid; ?>" id="<?php echo get_permalink();?>"></div>
					  <div data-type="Article" data-icon-class="icon loading" id="h<?php echo $pid;?>"  class="playlist-item item" data-loaded="true" style="">
						<a href="<?php echo get_permalink(); ?>" data-id="" class="getPage" data-tags="" data-special="none" data-cms-ai="0">
							<div class="photo">
							
								<img src="<?php echo get_the_post_thumbnail_url(null,'thumbnai'); ?>" alt="" width="100" height="75">
							</div>
							<div class="text">
								<div class="title"><?php echo get_the_title(); ?></div>
							</div>
						</a>
					  </div>
					</li>
					<?php endwhile; ?>
				</ul>
				</nav>
			</div>
			</div>
		</div>
 <?php endif; ?>
</div>

<?php get_footer(); ?>

