<?php
function booth_woo_get_social_networks() {
	return apply_filters(
		'booth_woo_social_networks',
		array(
			array(
				'name'  => 'facebook',
				'label' => esc_html__( 'Facebook', 'booth-woo' ),
				'icon'  => 'fab fa-facebook',
			),
			array(
				'name'  => 'twitter',
				'label' => esc_html__( 'Twitter', 'booth-woo' ),
				'icon'  => 'fab fa-twitter',
			),
			array(
				'name'  => 'instagram',
				'label' => esc_html__( 'Instagram', 'booth-woo' ),
				'icon'  => 'fab fa-instagram',
			),
			array(
				'name'  => 'snapchat',
				'label' => esc_html__( 'Snapchat', 'booth-woo' ),
				'icon'  => 'fab fa-snapchat',
			),
			array(
				'name'  => 'bloglovin',
				'label' => esc_html__( 'Bloglovin', 'booth-woo' ),
				'icon'  => 'fas fa-heart',
			),
			array(
				'name'  => 'pinterest',
				'label' => esc_html__( 'Pinterest', 'booth-woo' ),
				'icon'  => 'fab fa-pinterest',
			),
			array(
				'name'  => 'youtube',
				'label' => esc_html__( 'YouTube', 'booth-woo' ),
				'icon'  => 'fab fa-youtube',
			),
			array(
				'name'  => 'vimeo',
				'label' => esc_html__( 'Vimeo', 'booth-woo' ),
				'icon'  => 'fab fa-vimeo',
			),
			array(
				'name'  => 'linkedin',
				'label' => esc_html__( 'LinkedIn', 'booth-woo' ),
				'icon'  => 'fab fa-linkedin',
			),
			array(
				'name'  => 'tumblr',
				'label' => esc_html__( 'Tumblr', 'booth-woo' ),
				'icon'  => 'fab fa-tumblr',
			),
			array(
				'name'  => 'flickr',
				'label' => esc_html__( 'Flickr', 'booth-woo' ),
				'icon'  => 'fab fa-flickr',
			),
			array(
				'name'  => 'dribbble',
				'label' => esc_html__( 'Dribbble', 'booth-woo' ),
				'icon'  => 'fab fa-dribbble',
			),
			array(
				'name'  => 'wordpress',
				'label' => esc_html__( 'WordPress', 'booth-woo' ),
				'icon'  => 'fab fa-wordpress',
			),
			array(
				'name'  => '500px',
				'label' => esc_html__( '500px', 'booth-woo' ),
				'icon'  => 'fab fa-500px',
			),
			array(
				'name'  => 'soundcloud',
				'label' => esc_html__( 'Soundcloud', 'booth-woo' ),
				'icon'  => 'fab fa-soundcloud',
			),
			array(
				'name'  => 'spotify',
				'label' => esc_html__( 'Spotify', 'booth-woo' ),
				'icon'  => 'fab fa-spotify',
			),
			array(
				'name'  => 'vine',
				'label' => esc_html__( 'Vine', 'booth-woo' ),
				'icon'  => 'fab fa-vine',
			),
			array(
				'name'  => 'tiktok',
				'label' => esc_html__( 'TikTok', 'booth-woo' ),
				'icon'  => 'fab fa-tiktok',
			),
			array(
				'name'  => 'telegram',
				'label' => esc_html__( 'Telegram', 'booth-woo' ),
				'icon'  => 'fab fa-telegram',
			),
		)
	);
}

function booth_woo_wp_link_pages_default_args() {
	return apply_filters(
		'booth_woo_wp_link_pages_default_args',
		array(
			'before'      => '<div class="page-links">' . esc_html__( 'Pages:', 'booth-woo' ),
			'after'       => '</div>',
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>',
		)
	);
}

/**
 * Returns a set of related posts, or the arguments needed for such a query.
 *
 * @uses wp_parse_args()
 * @uses get_post_type()
 * @uses get_post()
 * @uses get_object_taxonomies()
 * @uses get_the_terms()
 * @uses wp_list_pluck()
 *
 * @param int   $post_id A post ID to get related posts for.
 * @param int   $related_count The number of related posts to return.
 * @param array $args Array of arguments to change the default behavior.
 * @return object|array A WP_Query object with the results, or an array with the query arguments.
 */
function booth_woo_get_related_posts( $post_id, $related_count, $args = array() ) {
	$args = wp_parse_args(
		(array) $args,
		array(
			'orderby' => 'rand',
			'return'  => 'query', // Valid values are: 'query' (WP_Query object), 'array' (the arguments array)
		)
	);

	$post_type = get_post_type( $post_id );
	$post      = get_post( $post_id );

	$tax_query  = array();
	$taxonomies = get_object_taxonomies( $post, 'names' );

	foreach ( $taxonomies as $taxonomy ) {
		$terms = get_the_terms( $post_id, $taxonomy );
		if ( is_array( $terms ) && count( $terms ) > 0 ) {
			$term_list = wp_list_pluck( $terms, 'slug' );
			$term_list = array_values( $term_list );
			if ( ! empty( $term_list ) ) {
				$tax_query['tax_query'][] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => $term_list,
				);
			}
		}
	}

	if ( count( $taxonomies ) > 1 ) {
		$tax_query['tax_query']['relation'] = 'OR';
	}

	$query_args = array(
		'post_type'      => $post_type,
		'posts_per_page' => $related_count,
		'post_status'    => 'publish',
		'post__not_in'   => array( $post_id ),
		'orderby'        => $args['orderby'],
	);

	if ( 'query' === $args['return'] ) {
		return new WP_Query( array_merge( $query_args, $tax_query ) );
	} else {
		return array_merge( $query_args, $tax_query );
	}
}


/**
 * Returns the appropriate page(d) query variable to use in custom loops (needed for pagination).
 *
 * @uses get_query_var()
 *
 * @param int $default_return The default page number to return, if no query vars are set.
 * @return int The appropriate paged value if found, else 0.
 */
function booth_woo_get_page_var( $default_return = 0 ) {
	$paged = get_query_var( 'paged', false );
	$page  = get_query_var( 'page', false );

	if ( false === $paged && false === $page ) {
		return $default_return;
	}

	return max( $paged, $page );
}


/**
 * Retrieve or display list of posts as a dropdown (select list).
 *
 * @since 2.1.0
 *
 * @param array|string $args Optional. Override default arguments.
 * @param string       $name Optional. Name of the select box.
 * @return string HTML content, if not displaying.
 */
function booth_woo_dropdown_posts( $args = '', $name = 'post_id' ) {
	$defaults = array(
		'depth'                 => 0,
		'post_parent'           => 0,
		'selected'              => 0,
		'echo'                  => 1,
		// 'name'                  => 'page_id', // With this line, get_posts() doesn't work properly.
		'id'                    => '',
		'class'                 => '',
		'show_option_none'      => '',
		'show_option_no_change' => '',
		'option_none_value'     => '',
		'post_type'             => 'post',
		'post_status'           => 'publish',
		'suppress_filters'      => false,
		'numberposts'           => -1,
		'select_even_if_empty'  => false, // If no posts are found, an empty <select> will be returned/echoed.
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	$hierarchical_post_types = get_post_types( array( 'hierarchical' => true ) );
	if ( in_array( $r['post_type'], $hierarchical_post_types ) ) {
		$pages = get_pages( $r );
	} else {
		$pages = get_posts( $r );
	}

	$output = '';
	// Back-compat with old system where both id and name were based on $name argument
	if ( empty( $id ) ) {
		$id = $name;
	}

	if ( ! empty( $pages ) || $select_even_if_empty == true ) {
		$output = "<select name='" . esc_attr( $name ) . "' id='" . esc_attr( $id ) . "' class='" . esc_attr( $class ) . "'>\n";
		if ( $show_option_no_change ) {
			$output .= "\t<option value=\"-1\">$show_option_no_change</option>";
		}
		if ( $show_option_none ) {
			$output .= "\t<option value=\"" . esc_attr( $option_none_value ) . "\">$show_option_none</option>\n";
		}
		if ( ! empty( $pages ) ) {
			$output .= walk_page_dropdown_tree( $pages, $depth, $r );
		}
		$output .= "</select>\n";
	}

	$output = apply_filters( 'booth_woo_dropdown_posts', $output, $name, $r );

	if ( $echo ) {
		echo wp_kses(
			$output,
			array(
				'select'   => array(
					'id'    => true,
					'class' => true,
					'name'  => true,
				),
				'option'   => array(
					'value'    => true,
					'class'    => true,
					'selected' => true,
				),
				'optgroup' => array(
					'label'    => true,
					'disabled' => true,
				),
			)
		);
	}

	return $output;
}

/**
 * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.
 *
 * @see https://gist.github.com/stephenharris/5532899
 *
 * @param string $color Hexadecimal color value. May be 3 or 6 digits, with an optional leading # sign.
 * @param float  $percent Decimal (0.2 = lighten by 20%, -0.4 = darken by 40%)
 *
 * @return string Lightened/Darkened colour as hexadecimal (with hash)
 */
function booth_woo_color_luminance( $color, $percent ) {
	// Remove # if provided
	if ( '#' === $color[0] ) {
		$color = substr( $color, 1 );
	}

	// Validate hex string.
	$hex     = preg_replace( '/[^0-9a-f]/i', '', $color );
	$new_hex = '#';

	$percent = floatval( $percent );

	if ( strlen( $hex ) < 6 ) {
		$hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
	}

	// Convert to decimal and change luminosity.
	for ( $i = 0; $i < 3; $i ++ ) {
		$dec      = hexdec( substr( $hex, $i * 2, 2 ) );
		$dec      = min( max( 0, $dec + $dec * $percent ), 255 );
		$new_hex .= str_pad( dechex( (int) $dec ), 2, 0, STR_PAD_LEFT );
	}

	return $new_hex;
}

/**
 * Converts hexadecimal color value to rgb(a) format.
 *
 * @param string     $color Hexadecimal color value. May be 3 or 6 digits, with an optional leading # sign.
 * @param float|bool $opacity Opacity level 0-1 (decimal) or false to disable.
 *
 * @return string
 */
function booth_woo_hex2rgba( $color, $opacity = false ) {

	$default = 'rgb(0,0,0)';

	// Return default if no color provided
	if ( empty( $color ) ) {
		return $default;
	}

	// Remove # if provided
	$color = ltrim( $color, '#' );

	// Check if color has 6 or 3 characters and get values
	if ( strlen( $color ) === 6 ) {
		$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	} elseif ( strlen( $color ) === 3 ) {
		$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	} else {
		return $default;
	}

	$rgb = array_map( 'hexdec', $hex );

	if ( false !== $opacity ) {
		$opacity = abs( floatval( $opacity ) );
		if ( $opacity > 1 ) {
			$opacity = 1.0;
		}
		$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
	} else {
		$output = 'rgb(' . implode( ',', $rgb ) . ')';
	}

	return $output;
}

function booth_woo_empty_content( $content = false ) {
	if ( false === $content ) {
		$post    = get_post();
		$content = $post->post_content;
	}

	return trim( str_replace( '&nbsp;', '', strip_tags( $content ) ) ) === '';
}

/**
 * Returns the caption of an image, to be used in a lightbox.
 *
 * @uses get_post_thumbnail_id()
 * @uses wp_prepare_attachment_for_js()
 *
 * @param int|false $image_id The image's attachment ID.
 *
 * @return string
 */
function booth_woo_get_image_lightbox_caption( $image_id = false ) {
	if ( false === $image_id ) {
		$image_id = get_post_thumbnail_id();
	}

	$lightbox_caption = '';

	$attachment = wp_prepare_attachment_for_js( $image_id );

	if ( is_array( $attachment ) ) {
		$field = apply_filters( 'booth_woo_image_lightbox_caption_field', 'caption', $image_id, $attachment );

		if ( array_key_exists( $field, $attachment ) ) {
			$lightbox_caption = $attachment[ $field ];
		}
	}

	return $lightbox_caption;
}

// Make sure this function is defined, even if the plugin is disabled.
if ( ! function_exists( 'booth_woo_documentation_url' ) ) {
	/**
	 * Returns the URL to the theme's documentation page.
	 *
	 * @return string
	 */
	function booth_woo_documentation_url() {
		$url = 'https://docs.timgreen.ws/wp/booth-woo/';

		return apply_filters( 'booth_woo_documentation_url', $url );
	}
}

add_filter( 'stylesheet_uri', 'booth_woo_stylesheet_uri', 10, 2 );
if ( ! function_exists( 'booth_woo_stylesheet_uri' ) ) {
	function booth_woo_stylesheet_uri( $stylesheet_uri, $stylesheet_dir_uri ) {
		if ( ! is_child_theme() ) {
			$suffix         = booth_woo_scripts_styles_suffix();
			$stylesheet_uri = preg_replace( '/\.css$/', "{$suffix}.css", $stylesheet_uri );
		}

		return $stylesheet_uri;
	}
}

if ( ! function_exists( 'booth_woo_scripts_styles_suffix' ) ) {
	function booth_woo_scripts_styles_suffix() {
		$suffix = '.min';

		if ( ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ||
			( defined( 'WP_DEBUG' ) && WP_DEBUG ) ) {
			$suffix = '';
		}

		return apply_filters( 'booth_woo_scripts_styles_suffix', $suffix );
	}
}

if ( ! function_exists( 'booth_woo_has_sidebar' ) ) :
	/**
	 * Determine if a sidebar is being displayed.
	 */
	function booth_woo_has_sidebar() {
		$has_sidebar = false;

		if ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
			if ( is_active_sidebar( 'shop' ) ) {
				$has_sidebar = true;
			}

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

if ( ! function_exists( 'booth_woo_get_template_part' ) ) :
	/**
	 * Load a template part into a template, optionally passing an associative array
	 * that will be available as variables.
	 *
	 * Makes it easy for a theme to reuse sections of code in a easy to overload way
	 * for child themes.
	 *
	 * Includes the named template part for a theme or if a name is specified then a
	 * specialised part will be included. If the theme contains no {slug}.php file
	 * then no template will be included.
	 *
	 * The template is included using require, not require_once, so you may include the
	 * same template part multiple times.
	 *
	 * For the $name parameter, if the file is called "{slug}-special.php" then specify
	 * "special".
	 *
	 * When $data is an array, the key of each value becomes the name of the variable,
	 * and the value becomes the variable's value.
	 *
	 * $data_overwrite should be one of the extract() flags, as described in http://www.php.net/extract
	 *
	 * @uses locate_template()
	 * @uses do_action() Calls 'get_template_part_{$slug}' action.
	 * @uses do_action() Calls 'ci_get_template_part_{$slug}' action.
	 *
	 * @param string $slug The slug name for the generic template.
	 * @param string $name The name of the specialised template.
	 * @param array  $data A key-value array of data to be available as variables.
	 * @param int    $data_overwrite The EXTR_* constant to pass to extract( $data ).
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

		require $_template_file;
	}
endif;
