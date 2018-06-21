<?php
/**
 * Functions to register client-side assets (scripts and stylesheets) for the
 * Gutenberg block.
 *
 * @package apod-revised
 */

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * @see https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type/#enqueuing-block-scripts
 */
function apod_extended_block_init() {
	$dir = dirname( __FILE__ );

	$block_js = 'apod-extended/block.js';
	wp_register_script(
		'apod-extended-block-editor',
		plugins_url( $block_js, __FILE__ ),
		array(
			'wp-blocks',
			'wp-i18n',
			'wp-element',
		),
		filemtime( "$dir/$block_js" )
	);

	$editor_css = 'apod-extended/editor.css';
	wp_register_style(
		'apod-extended-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(
			'wp-blocks',
		),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'apod-extended/style.css';
	wp_register_style(
		'apod-extended-block',
		plugins_url( $style_css, __FILE__ ),
		array(
			'wp-blocks',
		),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'apod-revised/apod-extended', array(
		'editor_script' => 'apod-extended-block-editor',
		'editor_style'  => 'apod-extended-block-editor',
		'style'         => 'apod-extended-block',
		'render_callback' => 'apod_revised_display',
	) );
}
add_action( 'init', 'apod_extended_block_init' );
