<?php
/**
 * The template for displaying search results pages
 *
 * @package BoomBox_Theme
 */
?>

<form role="search" method="get" class="search-form bb-input-addon" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo _x( 'Buscar:', 'label' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	</label>
	<button type="submit" class="search-submit" ></button>
</form>
