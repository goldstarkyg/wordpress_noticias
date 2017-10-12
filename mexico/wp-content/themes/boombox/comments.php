<?php
/**
 * The template for displaying comments
 *
 * @package BoomBox_Theme
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */


/*Boombox comment tabs */
?>

<?php
if ( post_password_required() ) {
	return;
}
$boombox_single_options     = boombox_get_single_page_settings();
$boombox_template_options   = $boombox_single_options[ 'template_options' ];
if( $boombox_template_options['comments'] ): ?>

	<div id="comments" class="comments">

		<h2 class="comments-title">
			<?php
			$comments_number = get_comments_number();
			printf( '%1$s <span>%2$d</span>', _x( 'Comments', 'comments title', 'boombox' ), number_format_i18n( $comments_number ) );
			?>
		</h2>

		<?php comment_form( boombox_get_comment_form_args() ); ?>

		<?php if ( have_comments() ) : ?>

			<?php the_comments_navigation(); ?>

			<ol class="comment-list">
				<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 42,
				) );
				?>
			</ol><!-- .comment-list -->

			<?php the_comments_navigation(); ?>

		<?php endif; // Check for have_comments(). ?>

		<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'boombox' ); ?></p>
		<?php endif; ?>

	</div><!-- .comments-area -->

	<?php
	if( is_single() ){
		boombox_the_advertisement( 'boombox-single-after-comments-section', 'large' );
	} ?>

<?php endif; ?>