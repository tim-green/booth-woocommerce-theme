<?php
/*
 * Booth_Woo scripts and styles related functions.
 */

/*
 * Register Google Fonts
 */
function booth_woo_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese';

	/* translators: If there are characters in your language that are not supported by Source Sans Pro, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Source Sans Pro font: on or off', 'booth-woo' ) ) {
		$fonts[] = 'Source Sans Pro:400,400i,600,700';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	if ( get_theme_mod( 'theme_local_google_fonts' ) ) {
		$fonts_url = get_template_directory_uri() . '/css/google-fonts.css';
	}
	return $fonts_url;
}

/*
 * Register scripts and styles unconditionally.
 */
function booth_woo_register_scripts() {
	$theme = wp_get_theme();

	if ( ! wp_script_is( 'alpha-color-picker', 'enqueued' ) && ! wp_script_is( 'alpha-color-picker', 'registered' ) ) {
		wp_register_script( 'alpha-color-picker', get_template_directory_uri() . '/assets/js/plugins/alpha-color-picker/alpha-color-picker.js', array('jquery','wp-color-picker',), '1.0.0', true );
	}


	if ( ! wp_script_is( 'booth-woo-plugin-post-meta', 'enqueued' ) && ! wp_script_is( 'booth-woo-plugin-post-meta', 'registered' ) ) {
		wp_register_style( 'booth-woo-plugin-post-meta', get_template_directory_uri() . '/admin/css/post-meta.css', array('alpha-color-picker',), $theme->get( 'Version' ) );
		wp_register_script( 'booth-woo-plugin-post-meta', get_template_directory_uri() . '/admin/js/post-meta.js', array('media-editor','jquery','jquery-ui-sortable','alpha-color-picker',), $theme->get( 'Version' ), true );

		$settings = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'tSelectFile' => esc_html__( 'Select file', 'booth-woo' ),
			'tSelectFiles' => esc_html__( 'Select files', 'booth-woo' ),
			'tUseThisFile' => esc_html__( 'Use this file', 'booth-woo' ),
			'tUseTheseFiles' => esc_html__( 'Use these files', 'booth-woo' ),
			'tUpdateGallery' => esc_html__( 'Update gallery', 'booth-woo' ),
			'tLoading' => esc_html__( 'Loading...', 'booth-woo' ),
			'tPreviewUnavailable' => esc_html__( 'Gallery preview not available.', 'booth-woo' ),
			'tRemoveImage' => esc_html__( 'Remove image', 'booth-woo' ),
			'tRemoveFromGallery'  => esc_html__( 'Remove from gallery', 'booth-woo' ),
		);
		wp_localize_script('booth-woo-plugin-post-meta', 'booth_woo_plugin_PostMeta', $settings );
	}

	wp_register_style('booth-woo-repeating-fields', get_template_directory_uri() . '/css/admin/repeating-fields.min.css', array(), $theme->get( 'Version' ) );
	wp_register_script('booth-woo-repeating-fields', get_template_directory_uri() . '/js/admin/repeating-fields.js', array('jquery','jquery-ui-sortable',), $theme->get( 'Version' ), true );
	wp_register_style('booth-woo-google-font', booth_woo_fonts_url(), array(), null );
	wp_register_style('booth-woo-base', get_template_directory_uri() . '/css/base.css', array(), $theme->get( 'Version' ) );

	wp_register_style('booth-woo-dependencies', false, array('booth-woo-google-font','booth-woo-base','booth-woo-common','mmenu','slick','font-awesome-5',), $theme->get( 'Version' ) );

	if ( is_child_theme() ) {
		wp_register_style( 'booth-woo-style-parent', get_template_directory_uri() . '/style.css', array(
			'booth-woo-dependencies',
		), $theme->get( 'Version' ) );
	}

	wp_register_style( 'booth-woo-style', get_stylesheet_uri(), array(
		'booth-woo-dependencies',
	), $theme->get( 'Version' ) );


	wp_enqueue_script( 'fontawesome-kit', 'https://kit.fontawesome.com/c20cab8581.js', array(), '1.0.0', true );

	wp_register_script( 'booth-woo-front-scripts', get_template_directory_uri() . '/assets/build/app.min.js', array('booth-woo-dependencies',), $theme->get( 'Version' ), true );

	$vars = array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
	);
	wp_localize_script( 'booth-woo-front-scripts', 'booth_woo_vars', $vars );

}
add_action( 'init', 'booth_woo_register_scripts' );

/*
 * Enqueue scripts and styles.
 */
function booth_woo_enqueue_scripts() {

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( get_theme_mod( 'theme_lightbox', 1 ) ) {
		wp_enqueue_style( 'jquery-magnific-popup' );
		wp_enqueue_script( 'jquery-magnific-popup' );
		wp_enqueue_script( 'booth-woo-magnific-init' );
	}

	if ( is_child_theme() ) {
		wp_enqueue_style( 'booth-woo-style-parent' );
	}
	
	wp_enqueue_style( 'main', get_template_directory_uri() . '/assets/build/app.min.css'); 
	wp_enqueue_style( 'fa', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css'); 
	wp_enqueue_style( 'booth-woo-style' );
	wp_add_inline_style( 'booth-woo-style', booth_woo_get_all_customizer_css() );
	wp_enqueue_script( 'booth-woo-front-scripts' );

}
add_action( 'wp_enqueue_scripts','booth_woo_enqueue_scripts' );

/*
 * Enqueue admin scripts and styles.
 */
function booth_woo_admin_scripts( $hook ) {
	$theme = wp_get_theme();

	wp_register_style( 'booth-woo-widgets', get_template_directory_uri() . '/css/admin/widgets.min.css', array('booth-woo-repeating-fields','booth-woo-plugin-post-meta','alpha-color-picker',
	), $theme->get( 'Version' ) );

	wp_register_script( 'booth-woo-widgets', get_template_directory_uri() . '/js/admin/widgets.js', array('jquery','booth-woo-repeating-fields','booth-woo-plugin-post-meta','alpha-color-picker',
	), $theme->get( 'Version' ), true );
	$params = array(
		'ajaxurl'  => admin_url( 'admin-ajax.php' ),
		'widget_post_type_items_nonce' => wp_create_nonce( 'booth-woo-post-type-items' ),
	);
	wp_localize_script( 'booth-woo-widgets', 'ThemeWidget', $params );

	// Enqueue
	if ( in_array( $hook, array( 'widgets.php', 'customize.php' ), true ) ) {
		wp_enqueue_style( 'booth-woo-repeating-fields' );
		wp_enqueue_script( 'booth-woo-repeating-fields' );

		wp_enqueue_media();
		wp_enqueue_style( 'booth-woo-widgets' );
		wp_enqueue_script( 'booth-woo-widgets' );
	}

	if ( in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		wp_enqueue_media();
		wp_enqueue_style( 'booth-woo-plugin-post-meta' );
		wp_enqueue_script( 'booth-woo-plugin-post-meta' );
	}
}
add_action( 'admin_enqueue_scripts','booth_woo_admin_scripts' );
