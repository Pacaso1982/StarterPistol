<?php

// Enqueue Parent Styles
add_action( 'wp_enqueue_scripts', 'starterpistol_styles' );
function starterpistol_styles() {
	$parenthandle = 'starterpistol';
	$theme        = wp_get_theme();
	wp_enqueue_style( $parenthandle,
		get_template_directory_uri() . '/style.css',
		$theme->parent()->get( 'Version' )
	);
	wp_enqueue_style( 'child-style',
		get_stylesheet_uri(),
		array( $parenthandle ),
		$theme->get( 'Version' )
	);
}