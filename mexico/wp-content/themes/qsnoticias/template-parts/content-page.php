<?php
/**
 * The template part for displaying page content
 *
 * @package BoomBox_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
add_filter( 'post_class', 'boombox_remove_editor_article_classes', 10, 3 );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<div class="section-box">
			<?php the_content(); ?>

			<?php wp_link_pages(); ?>
		</div>
	</div>
</article>