<?php
/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function booth_woo_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'booth_woo_pingback_header' );


add_filter( 'excerpt_length', 'booth_woo_excerpt_length' );
function booth_woo_excerpt_length( $length ) {
	return get_theme_mod( 'excerpt_length', 55 );
}

add_filter( 'the_content', 'booth_woo_lightbox_rel', 12 );
add_filter( 'get_comment_text', 'booth_woo_lightbox_rel' );
if ( ! function_exists( 'booth_woo_lightbox_rel' ) ) :
	function booth_woo_lightbox_rel( $content ) {
		if ( get_theme_mod( 'theme_lightbox', 1 ) ) {
			global $post;

			if ( ! empty( $post ) ) {
				$pattern     = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
				$replacement = '<a$1href=$2$3.$4$5 data-lightbox="gal[' . $post->ID . ']"$6>$7</a>';
				$content     = preg_replace( $pattern, $replacement, $content );
			}
		}

		return $content;
	}
endif;

add_filter( 'wp_get_attachment_link', 'booth_woo_wp_get_attachment_link_lightbox_caption', 10, 6 );
function booth_woo_wp_get_attachment_link_lightbox_caption( $html, $id, $size, $permalink, $icon, $text ) {
	if ( get_theme_mod( 'theme_lightbox', 1 ) && false === $permalink ) {
		$found = preg_match( '#(<a.*?>)<img.*?></a>#', $html, $matches );
		if ( $found ) {
			$found_title = preg_match( '#title=([\'"])(.*?)\1#', $matches[1], $title_matches );

			// Only continue if title attribute doesn't exist.
			if ( 0 === $found_title ) {
				$caption = booth_woo_get_image_lightbox_caption( $id );

				if ( $caption ) {
					$new_a = $matches[1];
					$new_a = rtrim( $new_a, '>' );
					$new_a = $new_a . ' title="' . esc_attr( $caption ) . '">';

					$html = str_replace( $matches[1], $new_a, $html );
				}
			}
		}
	}

	return $html;
}

add_filter( 'the_title', 'booth_woo_replace_the_title', 10, 2 );
if ( ! function_exists( 'booth_woo_replace_the_title' ) ) :
	function booth_woo_replace_the_title( $title, $id ) {
		if ( is_admin() ) {
			return $title;
		}

		$alt_title = get_post_meta( $id, 'title', true );

		if ( $alt_title ) {
			$title = $alt_title;
		}

		return $title;
	}
endif;

// Wraps the core's archive widget's post counts in span.ci-count
add_filter( 'get_archives_link', 'booth_woo_wrap_archive_widget_post_counts_in_span', 10, 2 );
if ( ! function_exists( 'booth_woo_wrap_archive_widget_post_counts_in_span' ) ) :
	function booth_woo_wrap_archive_widget_post_counts_in_span( $output ) {
		$output = preg_replace_callback( '#(<li>.*?<a.*?>.*?</a>.*?)&nbsp;(\(.*?\))(.*?</li>)#', 'booth_woo_replace_archive_widget_post_counts_in_span', $output );

		return $output;
	}
endif;

if ( ! function_exists( 'booth_woo_replace_archive_widget_post_counts_in_span' ) ) :
	function booth_woo_replace_archive_widget_post_counts_in_span( $matches ) {
		return sprintf( '%s <span class="ci-count">%s</span>%s',
			$matches[1],
			$matches[2],
			$matches[3]
		);
	}
endif;

// Wraps the core's category widget's post counts in span.
add_filter( 'wp_list_categories', 'booth_woo_wrap_category_widget_post_counts_in_span', 10, 2 );
if ( ! function_exists( 'booth_woo_wrap_category_widget_post_counts_in_span' ) ) :
	function booth_woo_wrap_category_widget_post_counts_in_span( $output, $args ) {
		if ( ! isset( $args['show_count'] ) || 0 === (int) $args['show_count'] ) {
			return $output;
		}
		$output = preg_replace_callback( '#(<a.*?>)\s*?(\(.*?\))#', 'booth_woo_replace_category_widget_post_counts_in_span', $output );

		return $output;
	}
endif;

if ( ! function_exists( 'booth_woo_replace_category_widget_post_counts_in_span' ) ) :
	function booth_woo_replace_category_widget_post_counts_in_span( $matches ) {
		return sprintf( '%s <span class="ci-count">%s</span>',
			$matches[1],
			$matches[2]
		);
	}
endif;

/**
 * Include a skip to content link at the top of the page so that users can bypass the menu.
 */
add_action( 'wp_body_open', 'booth_woo_skip_link', 5 );
function booth_woo_skip_link() {
	?><div><a class="skip-link sr-only sr-only-focusable" href="#site-content"><?php esc_html_e( 'Skip to the content', 'booth-woo' ); ?></a></div><?php
}

/**
 * Add wrapper to embedded items to apply responsive styling.
 */
add_filter( 'embed_oembed_html', 'booth_woo_oembed_responsive_wrapper', 10, 4 );
function booth_woo_oembed_responsive_wrapper( $cache, $url, $attr, $post_ID ) {
	if ( empty( $cache ) ) {
		return $cache;
	}

	$url_patterns = array(
		'youtube.com',
		'youtu.be',
		'youtube-nocookie.com', // This doesn't seem to embed anything.
		'vimeo.com',
		'dailymotion.com',
		'dai.ly', // This doesn't seem to embed anything.
		'hulu.com',
		'wordpress.tv',
		'slideshare.net',
	);

	$match = false;

	foreach ( $url_patterns as $url_pattern ) {
		$pattern = 'https?://.*?' . preg_quote( $url_pattern, '#' );
		if ( preg_match( '#' . $pattern . '#', $url ) ) {
			$match = true;
			break;
		}
	}

	if ( $match ) {
		$cache = '<div class="booth-woo-responsive-embed">' . $cache . '</div>';
	}

	return $cache;
}

add_filter( 'wp_page_menu', 'booth_woo_wp_page_menu', 10, 2 );
function booth_woo_wp_page_menu( $menu, $args ) {
	$menu = preg_replace( '#^<div .*?>#', '', $menu, 1 );
	$menu = preg_replace( '#</div>$#', '', $menu, 1 );
	$menu = preg_replace( '#^<ul>#', '<ul id="' . esc_attr( $args['menu_id'] ) . '" class="' . esc_attr( $args['menu_class'] ) . '">', $menu, 1 );
	return $menu;
}

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
