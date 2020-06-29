<?php

$theme_version = '2.2.0';

	/**
	 * Include Theme Customizer
	 *
	 * @since v1.0
	 */
	$theme_customizer = get_template_directory() . '/inc/customizer.php';
	if ( is_readable( $theme_customizer ) ) {
		require_once $theme_customizer;
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
		'menu-2' => esc_html__( 'Main Menu - Right side', 'booth-woo' ),
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

/*
	Set the content width in pixels, based on the theme's design and stylesheet.
	
	Priority 0 to make it available to lower priority callbacks.

	* @global int $content_width
 */
function booth_woo_content_width(){
	$content_width = $GLOBALS['content_width'];

	if (is_page_template ('template/full-width-page.php' )
	|| is_page_template( 'templates/front-page.php' )
	|| is_page_template( 'templates/builder.php' )
	|| is_page_template( 'templates/builder-contained.php' )
	) {
		$content_width = 1290;
	} 
	elseif ( is_singular() || is_home() || is_archive() ) {
		$info          = booth_woo_get_layout_info();
		$content_width = $info['content_width'];
	}

	$GLOBALS['content_width'] = apply_filters( 'booth_woo_content_width', $content_width );
}
add_action( 'template_redirect', 'booth_woo_content_width', 0 );

add_filter( 'wp_page_menu', 'booth_woo_wp_page_menu', 10, 2 );

function booth_woo_wp_page_menu( $menu, $args ) {
	$menu = preg_replace( '#^<div .*?>#', '', $menu, 1 );
	$menu = preg_replace( '#</div>$#', '', $menu, 1 );
	$menu = preg_replace( '#^<ul>#', '<ul id="' . esc_attr( $args['menu_id'] ) . '" class="' . esc_attr( $args['menu_class'] ) . '">', $menu, 1 );
	return $menu;
}

if ( ! function_exists( 'booth_woo_get_columns_classes' ) ) :
	function booth_woo_get_columns_classes( $columns ) {
		switch ( intval( $columns ) ) {
			case 1:
				$classes = 'col-12';
				break;
			case 2:
				$classes = 'col-sm-6 col-12';
				break;
			case 3:
				$classes = 'col-lg-4 col-sm-6 col-12';
				break;
			case 4:
			default:
				$classes = 'col-xl-3 col-lg-4 col-sm-6 col-12';
				break;
		}

		return apply_filters( 'booth_woo_get_columns_classes', $classes, $columns );
	}
endif;

if ( ! function_exists( 'booth_woo_has_sidebar' ) ) :
/*
  Determine if a sidebar is being displayed.
 */
function booth_woo_has_sidebar() {
	$has_sidebar = false;

	if ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
		if ( is_active_sidebar( 'shop' ) ) {
			$has_sidebar = true;
		}


	/**
	 * Set the content width based on the theme's design and stylesheet
	 *
	 * @since v1.0
	 */
	if ( ! isset( $content_width ) ) {
		$content_width = 800;
		if ( is_product() ) {
			$has_sidebar = false;
		}
	} elseif ( is_home() || is_archive() ) {
		if ( is_active_sidebar( 'sidebar-1' ) ) {
			$has_sidebar = true;
		}
	} elseif ( ! is_page() && is_active_sidebar( 'sidebar-1' ) ) {
		$has_sidebar = true;
	} elseif ( is_page() && is_active_sidebar( 'sidebar-2' ) ) {
		$has_sidebar = true;
	}

	return apply_filters( 'booth_woo_has_sidebar', $has_sidebar );
}
endif;

if ( ! function_exists( 'booth_woo_get_layout_info' ) ) :
/*
 Return appropriate layout information.
 */
function booth_woo_get_layout_info() {
	$has_sidebar = booth_woo_has_sidebar();

	$classes = array(
		'container_classes' => $has_sidebar ? 'col-lg-9 col-12' : 'col-xl-8 col-lg-10 col-12',
		'sidebar_classes'   => $has_sidebar ? 'col-lg-3 col-12' : '',
		'content_width'     => 960,
		'has_sidebar'       => $has_sidebar,
	);

	$sidebar_option = '';
	if ( is_singular() ) {
		$sidebar_option = get_post_meta( get_queried_object_id(), 'booth_woo_sidebar', true );
	}

	if ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {

		if ( is_product() ) {
			$classes = array(
				'container_classes' => 'col-12',
				'sidebar_classes'   => '',
				'content_width'     => 740,
				'has_sidebar'       => false,
			);
		} else {
			$classes = array(
				'container_classes' => 'col-lg-9 col-12',
				'sidebar_classes'   => 'col-lg-3 col-12',
				'content_width'     => 960,
				'has_sidebar'       => $has_sidebar,
			);
		}
	} 
	
	elseif ( is_singular() ) {
		if ( 'none' === get_post_meta( get_the_ID(), 'booth_woo_sidebar', true ) ) {
			$classes = array(
				'container_classes' => 'col-xl-8 col-lg-10 col-12',
				'sidebar_classes'   => '',
				'content_width'     => 960,
				'has_sidebar'       => false,
			);
		}
	}

	$classes['row_classes'] = '';
	if ( is_singular() ) {
		if ( ! $has_sidebar || 'none' === $sidebar_option ) {
			$classes['row_classes'] = 'justify-content-center';
		} elseif ( 'left' === $sidebar_option ) {
			$classes['row_classes'] = 'flex-row-reverse';
		}
	} elseif ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_taxonomy() ) ) {
		$classes['row_classes'] = 'flex-row-reverse';
	} elseif ( ! $has_sidebar ) {
		$classes['row_classes'] = 'justify-content-center';
	}
	return apply_filters( 'booth_woo_layout_info', $classes, $has_sidebar );
}
endif;

add_filter( 'tiny_mce_before_init', 'booth_woo_insert_wp_editor_formats' );
function booth_woo_insert_wp_editor_formats( $init_array ) {
	$style_formats = array(
		array(
			'title'   => esc_html__( 'Intro text (big text)', 'booth-woo' ),
			'block'   => 'div',
			'classes' => 'entry-content-intro',
			'wrapper' => true,
		),
		array(
			'title'   => esc_html__( '2 Column Text', 'booth-woo' ),
			'block'   => 'div',
			'classes' => 'entry-content-column-split',
			'wrapper' => true,
		),
	);

	$init_array['style_formats'] = wp_json_encode( $style_formats );

	return $init_array;
}

add_filter( 'mce_buttons_2', 'booth_woo_mce_buttons_2' );
function booth_woo_mce_buttons_2( $buttons ) {
	array_unshift( $buttons, 'styleselect' );

	return $buttons;
}

add_action( 'admin_init', 'booth_woo_admin_setup_hide_single_featured' );
function booth_woo_admin_setup_hide_single_featured() {
	if ( current_theme_supports( 'booth-woo-hide-single-featured' ) ) {
		$hide_featured_support = get_theme_support( 'booth-woo-hide-single-featured' );
		$hide_featured_support = $hide_featured_support[0];

		foreach ( $hide_featured_support as $supported_post_type ) {
			add_meta_box( 'booth-woo-single-featured-visibility', esc_html__( 'Featured Image Visibility', 'booth-woo' ), 'booth_woo_single_featured_visibility_metabox', $supported_post_type, 'side', 'default' );
		}
	}

	add_action( 'save_post', 'booth_woo_hide_single_featured_save_post' );
}

add_action( 'init', 'booth_woo_setup_hide_single_featured' );
function booth_woo_setup_hide_single_featured() {
	if ( current_theme_supports( 'booth-woo-hide-single-featured' ) ) {
		add_filter( 'get_post_metadata', 'booth_woo_hide_single_featured_get_post_metadata', 10, 4 );
	}
}

function booth_woo_single_featured_visibility_metabox( $object, $box ) {
	$fieldname = 'booth_woo_hide_single_featured';
	$checked   = get_post_meta( $object->ID, $fieldname, true );

	?>
		<input type="checkbox" id="<?php echo esc_attr( $fieldname ); ?>" class="check" name="<?php echo esc_attr( $fieldname ); ?>" value="1" <?php checked( $checked, 1 ); ?> />
		<label for="<?php echo esc_attr( $fieldname ); ?>"><?php esc_html_e( "Hide when viewing this post page", 'booth-woo' ); ?></label>
	<?php
	wp_nonce_field( 'booth_woo_hide_single_featured_nonce', '_booth_woo_hide_single_featured_meta_box_nonce' );
}

function booth_woo_hide_single_featured_get_post_metadata( $value, $post_id, $meta_key, $single ) {
	$hide_featured_support = get_theme_support( 'booth-woo-hide-single-featured' );
	$hide_featured_support = $hide_featured_support[0];

	if ( ! in_array( get_post_type( $post_id ), $hide_featured_support, true ) ) {
		return $value;
	}

	if ( '_thumbnail_id' === $meta_key && ( is_single( $post_id ) || is_page( $post_id ) ) && get_post_meta( $post_id, 'booth_woo_hide_single_featured', true ) ) {
		return false;
	}
	return $value;
}

function booth_woo_hide_single_featured_save_post( $post_id ) {
	$hide_featured_support = get_theme_support( 'booth-woo-hide-single-featured' );
	$hide_featured_support = $hide_featured_support[0];

	if ( ! in_array( get_post_type( $post_id ), $hide_featured_support, true ) ) {
		return;
	}
	add_filter( 'the_password_form', 'themes_starter_password_form' );


	if ( ! function_exists( 'themes_starter_comment' ) ) :
		/**
		 * Style Reply link
		 *
		 * @since v1.0
		 */
		function themes_starter_replace_reply_link_class( $class ) {
			$output = str_replace( "class='comment-reply-link", "class='comment-reply-link btn btn-outline-secondary", $class );
			return $output;
		}
		add_filter( 'comment_reply_link', 'themes_starter_replace_reply_link_class' );

		/**
		 * Template for comments and pingbacks:
		 * add function to comments.php ... wp_list_comments( array( 'callback' => 'themes_starter_comment' ) );
		 *
		 * @since v1.0
		 */
		function themes_starter_comment( $comment, $args, $depth ) {
			global $theme_dateformat, $theme_timeformat;

			$GLOBALS['comment'] = $comment;
			switch ( $comment->comment_type ) :
				case 'pingback' :
				case 'trackback' :
			?>
			<li class="post pingback">
				<p><?php _e( 'Pingback:', 'my-theme' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'my-theme' ), '<span class="edit-link">', '</span>' ); ?></p>
			<?php
					break;
				default :
			?>
			<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
				<article id="comment-<?php comment_ID(); ?>" class="comment">
					<footer class="comment-meta">
						<div class="comment-author vcard">
							<?php
								$avatar_size = 136;
								if ( '0' !== $comment->comment_parent ) {
									$avatar_size = 68;
								}
								echo get_avatar( $comment, $avatar_size );

								/* translators: 1: comment author, 2: date and time */
								printf( __( '%1$s, %2$s', 'my-theme' ),
									sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
									sprintf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
										esc_url( get_comment_link( $comment->comment_ID ) ),
										get_comment_time( 'c' ),
										/* translators: 1: date, 2: time */
										//sprintf( __( '%1$s - %2$s', 'my-theme' ), get_comment_time( $theme_dateformat ), get_comment_time( $theme_timeformat ) )
										sprintf( __( '%1$s ago', 'my-theme' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) )
									)
								);
							?>

							<?php edit_comment_link( __( 'Edit', 'my-theme' ), '<span class="edit-link">', '</span>' ); ?>
						</div><!-- .comment-author .vcard -->

						<?php if ( '0' === $comment->comment_approved ) : ?>
							<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'my-theme' ); ?></em>
							<br />
						<?php endif; ?>

					</footer>

					<div class="comment-content"><?php comment_text(); ?></div>

					<div class="reply">
						<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'my-theme' ) . ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</div><!-- .reply -->
				</article><!-- #comment-## -->

			<?php
					break;
			endswitch;

		}
	if ( isset( $_POST['_booth_woo_hide_single_featured_meta_box_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['_booth_woo_hide_single_featured_meta_box_nonce'] ), 'booth_woo_hide_single_featured_nonce' ) ) {
		update_post_meta( $post_id, 'booth_woo_hide_single_featured', isset( $_POST['booth_woo_hide_single_featured'] ) ); // Input var okay.
	}
}

		/**
		 * Custom Comment form
		 *
		 * @since v1.0
		 * @since v1.1: Added 'submit_button' and 'submit_field'
		 * @since v2.0.2: Added '$consent' and 'cookies'
		 */
		function themes_starter_custom_commentform( $args = array(), $post_id = null ) {
			if ( null === $post_id ) {
				$post_id = get_the_ID();
			}

			$commenter = wp_get_current_commenter();
			$user = wp_get_current_user();
			$user_identity = $user->exists() ? $user->display_name : '';

			$args = wp_parse_args( $args );

			$req = get_option( 'require_name_email' );
			$aria_req = ( $req ? " aria-required='true' required" : '' );
			$consent  = ( empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"' );
			$fields = array(
				'author'  => '<div class="form-group"><label for="author">' . __( 'Name', 'my-theme' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label>' . 
							'<input type="text" id="author" name="author" class="form-control" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $aria_req . ' /></div>',
				'email'   => '<div class="form-group"><label for="email">' . __( 'Email', 'my-theme' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label>' . 
							'<input type="email" id="email" name="email" class="form-control" value="' . esc_attr( $commenter['comment_author_email'] ) . '"' . $aria_req . ' /></div>',
				'url'     => '',
				'cookies' => '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' /> ' .
							 '<label for="wp-comment-cookies-consent">' . __( 'Save my name, email, and website in this browser for the next time I comment.', 'my-theme' ) . '</label></p>',
			);
if ( ! function_exists( 'booth_woo_get_template_part' ) ) :
/*
 * Load a template part into a template, optionally passing an associative array that will be available as variables.
 
 Makes it easy for a theme to reuse sections of code in a easy to overload way for child themes.
 
 Includes the named template part for a theme or if a name is specified then a specialised part will be included. If the theme contains no {slug}.php file then no template will be included.
 
 The template is included using require, not require_once, so you may include the same template part multiple times.
 
 For the $name parameter, if the file is called "{slug}-special.php" then specify "special".
 
 When $data is an array, the key of each value becomes the name of the variable, and the value becomes the variable's value.
 
 $data_overwrite should be one of the extract() flags, as described in http://www.php.net/extract
 
 * @uses locate_template()
 * @uses do_action() Calls 'get_template_part_{$slug}' action.
 * @uses do_action() Calls 'ci_get_template_part_{$slug}' action.
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template.
 * @param array $data A key-value array of data to be available as variables.
 * @param int $data_overwrite The EXTR_* constant to pass to extract( $data ).
 */

 function booth_woo_get_template_part( $slug, $name = null, $data = array(), $data_overwrite = EXTR_PREFIX_SAME ) {
	// Code similar to get_template_part() as of WP v4.9.8

	// Retain the same action hook, so that calls to our function respond to the same hooked functions.
	do_action( "get_template_part_{$slug}", $slug, $name );

	// Add our own action hook, so that we can hook using $data also.
	do_action( "ci_get_template_part_{$slug}", $slug, $name, $data );

	$templates = array();
	$name      = (string) $name;

	if ( '' !== $name ) {
		$templates[] = "{$slug}-{$name}.php";
	}

	$templates[] = "{$slug}.php";

	// Don't load the template ( it would normally call load_template() )
	$_template_file = locate_template( $templates, false, false );

	// Code similar to load_template()
	global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

	if ( is_array( $wp_query->query_vars ) ) {
		extract( $wp_query->query_vars, EXTR_SKIP );
	}

	if ( is_array( $data ) and ( count( $data ) > 0 ) ) {
		extract( $data, $data_overwrite, 'imp' );
	}

	require( $_template_file );
}
endif;


add_filter( 'get_the_archive_title', 'booth_woo_get_the_archive_title' );
if ( ! function_exists( 'booth_woo_get_the_archive_title' ) ) :
function booth_woo_get_the_archive_title( $title ) {
	if ( is_category() ) {
		$title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
	} elseif ( is_tax() ) {
		$title = single_term_title( '', false );
	} elseif ( is_author() ) {
		/* translators: %s is an author's name. */
		$title = sprintf( __( 'All posts by %s', 'booth-woo' ), '<span class="vcard">' . get_the_author() . '</span>' );
	}

	return $title;
}
endif;

function booth_woo_post_type_listing_get_valid_columns_options( $post_type = false ) {
	$array = array(
		'min'   => 2,
		'max'   => 4,
		'range' => range( 2, 4 ),
	);

	return apply_filters( 'booth_woo_post_type_listing_valid_columns_options', $array, $post_type );
}

/**
 * Common theme features.
 */
require_once get_theme_file_path( '/common/common.php' );

/**
 * Scripts and styles.
 */
require_once get_theme_file_path( '/inc/scripts-styles.php' );

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
 * WooCommerce related code.
 */
require_once get_theme_file_path( '/inc/woocommerce.php' );

/**
 * MaxSlider related code.
 */
require_once get_theme_file_path( '/inc/maxslider.php' );

/**
 * SCSS Colors reader.
 */
require_once get_theme_file_path( '/inc/class-scss-colors.php' );

/**
 * Presentational custom fields for pages.
 */
require_once get_theme_file_path( '/inc/custom-fields-page.php' );
?>