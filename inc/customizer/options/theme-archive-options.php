<?php
	// This is better left without a partial, as excerpt may appear anywhere (and with any wrapper).
	$wp_customize->add_setting( 'excerpt_length', array(
		'default'           => 55,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'excerpt_length', array(
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 10,
			'step' => 1,
		),
		'section'     => 'theme_archive_options',
		'priority'    => 40,
		'label'       => esc_html__( 'Automatically generated excerpt length (in words)', 'booth-woo' ),
	) );


	$wp_customize->add_setting( 'pagination_method', array(
		'transport'         => 'postMessage',
		'default'           => booth_woo_pagination_method_default(),
		'sanitize_callback' => 'booth_woo_sanitize_pagination_method',
	) );
	$wp_customize->add_control( 'pagination_method', array(
		'type'     => 'select',
		'section'  => 'theme_archive_options',
		'priority' => 50,
		'label'    => esc_html__( 'Pagination method', 'booth-woo' ),
		'choices'  => array(
			'numbers' => esc_html_x( 'Numbered links', 'pagination method', 'booth-woo' ),
			'text'    => esc_html_x( '"Previous - Next" links', 'pagination method', 'booth-woo' ),
		),
	) );

	$wp_customize->selective_refresh->add_partial( 'pagination_method', array(
		'selector'            => 'nav.navigation',
		'render_callback'     => 'booth_woo_customize_preview_pagination',
		'container_inclusive' => true,
	) );
