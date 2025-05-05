<?php

// Booth functions and definitions
if (!defined ('Booth_Woo')){
	define('Booth_Woo', 'booth-woo');
}

if (! defined('Booth_Whitelabel')){
	define('Booth_Whitelabel', false);
}

$menus = array(
	'menu-1' => esc_html__( 'Main Menu', 'booth-woo' ),
	'menu-2' => esc_html__( 'Main Menu - Right side', 'booth-woo' ),
);
register_nav_menus( $menus );


if (! function_exists('booth_setup')):

	/*
		Setup theme defaults and register support for various WordPress features

		Note that this function is hooked into the after_setup_theme hook, which runs before the init hook.

		The init hook is too late for some features such as indicating supoort for post thumbnails.
	*/

	function booth_setup(){

	// Default content width
	$GLOBALS['content_width'] = 960;

	// make theme available for translation.
	// Make theme available for translation.
	load_theme_textdomain( 'booth-woo', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	$menus = array(
		'menu-1' => esc_html__( 'Main Menu', 'booth-woo' ),
		'menu-2' => esc_html__( 'Main Menu - Right', 'booth-woo' ),
	);
	register_nav_menus( $menus );

	// Switch default core markup for search form, comment form, and comments to output valid HTML5.
	add_theme_support( 'html5', apply_filters( 'booth_woo_add_theme_support_html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) ) );

	// Add theme support for custom logos.
	add_theme_support( 'custom-logo', apply_filters( 'booth_woo_add_theme_support_custom_logo', array() ) );

	// Set up the WordPress core custom background feature.
	$scss = new Booth_Woo_SCSS_Colors( get_theme_file_path( '/assets/sass/modules/variables.scss' ) );
	add_theme_support( 'custom-background', apply_filters( 'booth_woo_custom_background_args', array(
		'default-color' => $scss->get( 'body-bg' ),
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets' );

	// Image sizes
	set_post_thumbnail_size( 960, 540, true );
	add_image_size( 'booth_woo_item', 630, 355, true );
	add_image_size( 'booth_woo_item_media', 520, 520, true );
	add_image_size( 'booth_woo_item_media_sm', 90, 90, true );
	add_image_size( 'booth_woo_fullwidth', 1290, 750, true );
	add_image_size( 'booth_woo_block_item_lg', 910, 510, true );
	add_image_size( 'booth_woo_block_item_long', 1290, 215, true );
	add_image_size( 'booth_woo_block_item_md', 630, 345, true );
	add_image_size( 'booth_woo_block_item_xl', 1290, 725, true );

	add_theme_support( 'booth-woo-hide-single-featured', apply_filters( 'booth_woo_theme_support_hide_single_featured_post_types', array(
		'post',
		'page',
	) ) );

	// This provides back-compat for author descriptions on WP < 4.9. Remove by WP 5.1.
	if ( ! has_filter( 'get_the_author_description', 'wpautop' ) ) {
		add_filter( 'get_the_author_description', 'wpautop' );
	}
}
endif;
add_action( 'after_setup_theme', 'booth_woo_setup' );

/**
 * Template tags.
 */
require_once get_theme_file_path( '/inc/template-tags.php' );

/**
 * Sanitization functions.
 */
require_once get_theme_file_path( '/inc/sanitization.php' );

/**
 * Hooks.
 */
require_once get_theme_file_path( '/inc/default-hooks.php' );

/**
 * Scripts and styles.
 */
require_once get_theme_file_path( '/inc/scripts-styles.php' );

/**
 * Sidebars and widgets.
 */
require_once get_theme_file_path( '/inc/sidebars-widgets.php' );

/**
 * Customizer controls.
 */
require_once get_theme_file_path( '/inc/customizer.php' );

/**
 * Various helper functions, so that this functions.php is cleaner.
 */
require_once get_theme_file_path( '/inc/helpers.php' );

/**
 * Theme layout functions.
 */
require_once get_theme_file_path( '/inc/layout.php' );

/**
 * Single featured image toggling functionality.
 */
require_once get_theme_file_path( '/inc/single-featured.php' );

/**
 * WooCommerce related code.
 */
require_once get_theme_file_path( '/inc/woocommerce.php' );

/**
 * MaxSlider related code.
 */
require_once get_theme_file_path( '/inc/maxslider.php' );

/**
 * User onboarding.
 */
require_once get_theme_file_path( '/inc/onboarding.php' );

/**
 * SCSS Colors reader.
 */
require_once get_theme_file_path( '/inc/class-scss-colors.php' );

/**
 * Presentational custom fields for pages.
 */
require_once get_theme_file_path( '/inc/custom-fields-page.php' );
