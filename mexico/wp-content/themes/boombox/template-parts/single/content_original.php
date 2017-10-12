<?php
$boombox_featured_image_size = 'boombox_image768';
$boombox_single_options = boombox_get_single_page_settings($boombox_featured_image_size);
$boombox_featured_video = $boombox_single_options['featured_video'];
$boombox_template_options = $boombox_single_options['template_options'];
$boombox_post_template = $boombox_single_options['post_template'];
$boombox_is_nsfw_post = $boombox_single_options['is_nsfw'];
$boombox_article_classes = apply_filters( 'boombox_single_article_classes', $boombox_single_options['classes'] );
$boombox_disable_strip = $boombox_single_options['disable_strip'];
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($boombox_article_classes); ?> <?php boombox_single_article_structured_data(); ?> >
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
                <!--<hr/>-->
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($boombox_template_options['top_sharebar']) { ?>
            <?php $top_sharebar_id = $boombox_template_options['sticky_top_sharebar'] ? 'id="sticky-share-box"' : ''; ?>
            <div <?php echo $top_sharebar_id; ?> class="post-share-box top">
                <?php get_template_part('template-parts/single/share', 'box'); ?>
            </div>
        <?php } ?>
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
                    echo '<!-- Start Content -->';
                    the_content();
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

        <!--<hr/>-->
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