<?php
if ( ! function_exists( 'booth_woo_customize_preview_blogname' ) ) {
	function booth_woo_customize_preview_blogname() {
		bloginfo( 'name' );
	}
}

if ( ! function_exists( 'booth_woo_customize_preview_blogdescription' ) ) {
	function booth_woo_customize_preview_blogdescription() {
		bloginfo( 'description' );
	}
}

/**
 * Renders pagination preview for archive pages.
 *
 * Its results may not be accurate as the actual call may include arguments, however it should be good enough for preview purposes. booth_woo_posts_pagination() cannot be used directly as the render callback passes $this and $container_context
 * as the first two arguments.
 */
if ( ! function_exists( 'booth_woo_customize_preview_pagination' ) ) {
	function booth_woo_customize_preview_pagination( $_this, $container_context ) {
		booth_woo_posts_pagination();
	}
}
