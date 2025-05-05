<?php
add_filter( 'maxslider_enqueue_slider_css', 'booth_woo_front_page_replace_maxslider_enqueue_slider_css', 10, 2 );
function booth_woo_front_page_replace_maxslider_enqueue_slider_css( $css, $slider ) {
	if ( 'home' !== $slider['template'] ) {
		return $css;
	}

	$css = str_replace( '.maxslider-btn', '.btn', $css );
	$css = str_replace( '.maxslider-slide-', '.page-hero-', $css );

	return $css;
}

add_filter( 'maxslider_slider_classes', 'booth_woo_front_page_replace_maxslider_slider_classes', 10, 2 );
function booth_woo_front_page_replace_maxslider_slider_classes( $classes, $slider ) {
	if ( 'home' !== $slider['template'] ) {
		return $classes;
	}

	$maxslider = array_search( 'maxslider', $classes, true );
	if ( false !== $maxslider ) {
		unset( $classes[ $maxslider ] );
		$classes[] = 'page-hero-slideshow';
		$classes[] = 'booth-woo-slick-slider';
	}

	$new_classes = array();
	foreach ( $classes as $class ) {
		$new_classes[] = str_replace( 'maxslider-', 'page-hero-', $class );
	}

	return $new_classes;
}


add_filter( 'maxslider_default_slide_values', 'booth_woo_change_maxslider_default_slide_values' );
function booth_woo_change_maxslider_default_slide_values( $defaults ) {
	$defaults['content_align']  = 'maxslider-align-left';
	$defaults['content_valign'] = 'maxslider-align-bottom';

	return $defaults;
}

add_filter( 'maxslider_additional_templates', 'booth_woo_maxslider_additional_templates' );
/**
 * Appends the theme's MaxSlider templates to the list of available templates.
 *
 * @since 1.6.0
 *
 * @param array $templates
 *
 * @return array
 */
function booth_woo_maxslider_additional_templates( $templates ) {
	return array_merge( $templates, array(
		'home' => array(
			'label' => _x( 'Home', 'maxslider template', 'booth-woo' ),
		),
	) );
}

