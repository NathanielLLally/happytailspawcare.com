<?php
/**
 * Server-side rendering of the `core/iEmbed` block.
 *
 * @package WordPress
 */

/**
 * Performs wpautop() on the iEmbed block content.
 *
 * @since 5.0.0
 *
 * @param array  $attributes The block attributes.
 * @param string $content    The block content.
 *
 * @return string Returns the block content.
 */
function render_block_core_iEmbed( $attributes, $content ) {
	return wpautop( $content );
}

/**
 * Registers the `core/iEmbed` block on server.
 *
 * @since 5.0.0
 */
function register_block_core_iEmbed() {
	register_block_type_from_metadata(
		__DIR__ . '/iEmbed',
		array(
			'render_callback' => 'render_block_core_iEmbed',
		)
	);
}
add_action( 'init', 'register_block_core_iEmbed' );
