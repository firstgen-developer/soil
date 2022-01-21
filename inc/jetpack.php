<?php
/**
 * Jetpack Compatibility File
 *
 * @link https://jetpack.com/
 *
 * @package fgbase_
 */

/**
 * Jetpack setup function.
 *
 * See: https://jetpack.com/support/infinite-scroll/
 * See: https://jetpack.com/support/responsive-videos/
 * See: https://jetpack.com/support/content-options/
 */
function fgbase_jetpackfgbase_etup() {
	// Add theme support for Infinite Scroll.
	add_themefgbase_upport(
		'infinite-scroll',
		array(
			'container' => 'main',
			'render'    => 'fgbase_infinitefgbase_croll_render',
			'footer'    => 'page',
		)
	);

	// Add theme support for Responsive Videos.
	add_themefgbase_upport( 'jetpack-responsive-videos' );

	// Add theme support for Content Options.
	add_themefgbase_upport(
		'jetpack-content-options',
		array(
			'post-details' => array(
				'stylesheet' => 'fgbase_-style',
				'date'       => '.posted-on',
				'categories' => '.cat-links',
				'tags'       => '.tags-links',
				'author'     => '.byline',
				'comment'    => '.comments-link',
			),
			'featured-images' => array(
				'archive' => true,
				'post'    => true,
				'page'    => true,
			),
		)
	);
}
add_action( 'afterfgbase_etup_theme', 'fgbase_jetpackfgbase_etup' );

/**
 * Custom render function for Infinite Scroll.
 */
function fgbase_infinitefgbase_croll_render() {
	while ( have_posts() ) {
		the_post();
		if ( isfgbase_earch() ) :
			get_template_part( 'template-parts/content', 'search' );
		else :
			get_template_part( 'template-parts/content', get_post_type() );
		endif;
	}
}
