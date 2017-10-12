<?php
/**
 * The template part for displaying the single post header section
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$boombox_template_options = boombox_get_template_single_elements_options();

if ( $boombox_template_options['badges'] ) {
	// badges list
	$badges_list = boombox_get_post_badge_list( array( 'post_id' => get_the_ID(), 'badges_count' => 4 ) );
	echo $badges_list['badges'];
}

if ( $boombox_template_options['categories'] ) :
	// categories list
	boombox_categories_list();
endif;
?>
<a href="<?php echo get_permalink(); ?>"><?php the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?></a>
<?php
if ( $boombox_template_options['subtitle'] ) :
	boombox_the_post_subtitle();
endif; ?>

<?php do_action( 'boombox_affiliate_content' ); ?>
