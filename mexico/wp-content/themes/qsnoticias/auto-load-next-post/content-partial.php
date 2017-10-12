<?php
/**
	 * This file loads the content partially.
	 *
	 * @version 1.4.4
	 */

// Fetch plugin settings.
$remove_comments = get_option('auto_load_next_post_remove_comments');

// Load content before the loop.
do_action('alnp_load_before_loop');

// Check that there are more posts to load.
while (have_posts()) : the_post();

	$post_format = get_post_format(); // Post Format e.g. video
	if (false === $post_format) {
		$post_format = 'standard';
	}

	// Load content before the post content.
	do_action('balnp_load_before_content');

	// Load content before the post content for a specific post format.
	do_action('balnp_load_before_content_type_'.$post_format);

	get_template_part('template-parts/single/content');

	// If comments are open or we have at least one comment, load up the comment template.
	if (comments_open() || get_comments_number()) :
		if ($remove_comments != 'yes') { comments_template(); }
	endif;

	// Load content after the post content for a specific post format.
	do_action('balnp_load_after_content_type_'.$post_format);

	// Load content after the post content.
	do_action('balnp_load_after_content');

// End the loop.
endwhile;

// Load content after the loop.
do_action('alnp_load_after_loop');
