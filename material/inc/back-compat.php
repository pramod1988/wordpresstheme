<?php

function material_switch_theme() {
	switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'material_upgrade_notice' );
}
add_action( 'after_switch_theme', 'material_switch_theme' );


function material_upgrade_notice() {
	$message = sprintf( __( 'Material requires at least WordPress version 4.1. You are running version %s. Please upgrade and try again.', 'material-blog-story' ), $GLOBALS['wp_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevent the Customizer from being loaded on WordPress versions prior to 4.1.
 
 */
function material_customize() {
	wp_die( sprintf( __( 'Material requires at least WordPress version 4.1. You are running version %s. Please upgrade and try again.', 'material-blog-story' ), $GLOBALS['wp_version'] ), '', array(
		'back_link' => true,
	) );
}
add_action( 'load-customize.php', 'material_customize' );

/**
 * Prevent the Theme Preview from being loaded on WordPress versions prior to 4.1.
 
 */
function material_preview() {
	if ( isset( $_GET['preview'] ) ) {
		wp_die( sprintf( __( 'Material requires at least WordPress version 4.1. You are running version %s. Please upgrade and try again.', 'material-blog-story' ), $GLOBALS['wp_version'] ) );
	}
}
add_action( 'template_redirect', 'material_preview' );
