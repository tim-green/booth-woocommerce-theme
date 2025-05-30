<?php
/**
 * Standard Customizer Sections and Settings
 */

add_action( 'customize_register', 'booth_woo_customize_register' );
function booth_woo_customize_register( $wp_customize ) {

	// Register custom section types.
	$wp_customize->register_section_type( 'Booth_Woo_Customize_Section_Pro' );

	// Register sections.
	$wp_customize->add_section( new Booth_Woo_Customize_Section_Pro(
		$wp_customize,
		'theme_go_pro',
		array(
			'priority' => 1,
			'title'    => esc_html__( 'Booth', 'booth-woo' ),
			'pro_text' => esc_html__( 'Go Pro one day', 'booth-woo' ),
			'pro_url'  => 'https://www.timgreen.ws',
		)
	) );


	// Partial for various settings that affect the customizer styles, but can't have a dedicated icon, e.g. 'limit_logo_size'
	$wp_customize->selective_refresh->add_partial( 'theme_style', array(
		'selector'            => '#booth-woo-style-inline-css',
		'render_callback'     => 'booth_woo_get_all_customizer_css',
		'settings'            => array(),
		'container_inclusive' => false,
	) );


	// Header
	if ( apply_filters( 'booth_woo_customizable_header', true ) ) {
		$wp_customize->add_panel( 'theme_header', array(
			'title'    => esc_html_x( 'Header', 'customizer section title', 'booth-woo' ),
			'priority' => 10, // Before site_identity, 20
		) );

		$wp_customize->add_section( 'theme_header_style', array(
			'title'    => esc_html_x( 'Header style', 'customizer section title', 'booth-woo' ),
			'panel'    => 'theme_header',
			'priority' => 10,
		) );

		require_once get_theme_file_path( 'inc/customizer/options/theme-header-style.php' );

		$wp_customize->add_section( 'theme_header_primary_menu', array(
			'title'    => esc_html_x( 'Primary menu bar', 'customizer section title', 'booth-woo' ),
			'panel'    => 'theme_header',
			'priority' => 30,
		) );

		require_once get_theme_file_path( 'inc/customizer/options/theme-header-primary-menu.php' );
	} // filter booth_woo_customizable_header

	// Blog
	$wp_customize->add_panel( 'theme_blog', array(
		'title'    => esc_html_x( 'Blog settings', 'customizer section title', 'booth-woo' ),
		'priority' => 30, // After site_identity, 20
	) );

	$wp_customize->add_section( 'theme_archive_options', array(
		'title'       => esc_html_x( 'Archive options', 'customizer section title', 'booth-woo' ),
		'panel'       => 'theme_blog',
		'description' => esc_html__( 'Customize the default archive pages, such as the blog, category, tag, date archives, etc.', 'booth-woo' ),
		'priority'    => 10,
	) );

	require_once get_theme_file_path( 'inc/customizer/options/theme-archive-options.php' );

	$wp_customize->add_section( 'theme_post_options', array(
		'title'    => esc_html_x( 'Post options', 'customizer section title', 'booth-woo' ),
		'panel'    => 'theme_blog',
		'priority' => 20,
	) );

	require_once get_theme_file_path( 'inc/customizer/options/theme-post-options.php' );

	// Colors
	$wp_customize->add_panel( 'theme_colors', array(
		'title'                    => esc_html_x( 'Colors', 'customizer section title', 'booth-woo' ),
		'priority'                 => 30,
		'auto_expand_sole_section' => true,
	) );

	$wp_customize->add_section( 'theme_colors_global', array(
		'title'    => esc_html_x( 'Global', 'customizer section title', 'booth-woo' ),
		'panel'    => 'theme_colors',
		'priority' => 40,
	) );

	require_once get_theme_file_path( 'inc/customizer/options/theme-colors-global.php' );

	// Social
	$wp_customize->add_section( 'theme_social', array(
		'title'       => esc_html_x( 'Social Networks', 'customizer section title', 'booth-woo' ),
		'description' => esc_html__( 'Enter your social network URLs. Leaving a URL empty will hide its respective icon.', 'booth-woo' ),
		'priority'    => 80,
	) );

	require_once get_theme_file_path( 'inc/customizer/options/theme-social.php' );


	// Footer
	if ( apply_filters( 'booth_woo_customizable_footer', true ) ) {
		$wp_customize->add_panel( 'theme_footer', array(
			'title'    => esc_html_x( 'Footer', 'customizer section title', 'booth-woo' ),
			'priority' => 90,
		) );

		$wp_customize->add_section( 'theme_footer_style', array(
			'title'    => esc_html_x( 'Footer style', 'customizer section title', 'booth-woo' ),
			'panel'    => 'theme_footer',
			'priority' => 10,
		) );

		require_once get_theme_file_path( 'inc/customizer/options/theme-footer-style.php' );

		$wp_customize->add_section( 'theme_footer_bottom_bar', array(
			'title'    => esc_html_x( 'Bottom bar', 'customizer section title', 'booth-woo' ),
			'panel'    => 'theme_footer',
			'priority' => 20,
		) );

		require_once get_theme_file_path( 'inc/customizer/options/theme-footer-bottom-bar.php' );
	} // filter booth_woo_customizable_footer


	// Titles
	$wp_customize->add_panel( 'theme_titles', array(
		'title'    => esc_html_x( 'Titles', 'customizer section title', 'booth-woo' ),
		'priority' => 100,
	) );

	$wp_customize->add_section( 'theme_titles_general', array(
		'title'    => esc_html_x( 'General', 'customizer section title', 'booth-woo' ),
		'panel'    => 'theme_titles',
		'priority' => 10,
	) );

	require_once get_theme_file_path( 'inc/customizer/options/theme-titles-general.php' );

	$wp_customize->add_section( 'theme_titles_post', array(
		'title'    => esc_html_x( 'Posts', 'customizer section title', 'booth-woo' ),
		'panel'    => 'theme_titles',
		'priority' => 20,
	) );
	require_once get_theme_file_path( 'inc/customizer/options/theme-titles-post.php' );


	//
	// Other
	//
	$wp_customize->add_panel( 'theme_other', array(
		'title'                    => esc_html_x( 'Other', 'customizer section title', 'booth-woo' ),
		'description'              => esc_html__( 'Other options affecting the whole site.', 'booth-woo' ),
		'auto_expand_sole_section' => true,
		'priority'                 => 110,
	) );

	$wp_customize->add_section( 'theme_other_google_fonts', array(
		'title'    => esc_html_x( 'Google Fonts', 'customizer section title', 'booth-woo' ),
		'panel'    => 'theme_other',
		'priority' => 20,
	) );
	require_once get_theme_file_path( 'inc/customizer/options/theme-other-google-fonts.php' );


	//
	// Site identity
	//
	require_once get_theme_file_path( 'inc/customizer/options/site-identity.php' );

}



add_action( 'customize_register', 'booth_woo_customize_register_custom_controls', 9 );
/**
 * Registers custom Customizer controls.
 *
 * @param WP_Customize_Manager $wp_customize Reference to the customizer's manager object.
 */
function booth_woo_customize_register_custom_controls( $wp_customize ) {
	require_once get_template_directory() . '/inc/customizer/controls/section-pro/section-pro.php';
}

add_action( 'customize_preview_init', 'booth_woo_customize_preview_js' );

/*
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function booth_woo_customize_preview_js() {
	$theme = wp_get_theme();

	wp_enqueue_script( 'booth-woo-customizer-preview', get_template_directory_uri() . '/js/admin/customizer-preview.js', array( 'customize-preview' ), $theme->get( 'Version' ), true );

	wp_enqueue_style( 'booth-woo-customizer-preview', get_template_directory_uri() . '/css/admin/customizer-preview.css', array( 'customize-preview' ), $theme->get( 'Version' ) );
}

add_action( 'customize_controls_enqueue_scripts', 'booth_woo_customize_controls_js' );
function booth_woo_customize_controls_js() {
	$theme = wp_get_theme();

	wp_enqueue_script( 'booth-woo-customizer-section-pro', get_template_directory_uri() . '/inc/customizer/controls/section-pro/customize-controls.js', array( 'customize-controls' ), $theme->get( 'Version' ), true );
	wp_enqueue_style( 'booth-woo-customizer-section-pro', get_template_directory_uri() . '/inc/customizer/controls/section-pro/customize-controls.css', array(), $theme->get( 'Version' ) );
}

/*
 * Customizer partial callbacks.
 */

require_once get_theme_file_path( '/inc/customizer/partial-callbacks.php' );

/*
 * Customizer generated styles.
 */
require_once get_theme_file_path( '/inc/customizer/generated-styles.php' );
