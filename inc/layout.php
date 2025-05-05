<?php
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function booth_woo_content_width() {
	$content_width = $GLOBALS['content_width'];

	if ( is_page_template( 'templates/full-width-page.php' )
		|| is_page_template( 'templates/front-page.php' )
		|| is_page_template( 'templates/builder.php' )
		|| is_page_template( 'templates/builder-contained.php' )
	) {
		$content_width = 1290;
	} elseif ( is_singular() || is_home() || is_archive() ) {
		$info          = booth_woo_get_layout_info();
		$content_width = $info['content_width'];
	}

	$GLOBALS['content_width'] = apply_filters( 'booth_woo_content_width', $content_width );
}
add_action( 'template_redirect', 'booth_woo_content_width', 0 );

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

if ( ! function_exists( 'booth_woo_get_layout_info' ) ) :
	/**
	 * Return appropriate layout information.
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
		} elseif ( is_singular() ) {
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
