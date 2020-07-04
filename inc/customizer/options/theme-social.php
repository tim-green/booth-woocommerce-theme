<?php
	$wp_customize->add_setting( 'theme_social_target', array(
		'transport'         => 'postMessage',
		'default'           => 1,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'theme_social_target', array(
		'type'    => 'checkbox',
		'section' => 'theme_social',
		'label'   => esc_html__( 'Open social links in a new tab.', 'booth-woo' ),
	) );

	$networks    = booth_woo_get_social_networks();
	$social_mods = array();

	foreach ( $networks as $network ) {
		$social_mod    = 'theme_social_' . $network['name'];
		$social_mods[] = $social_mod;

		$wp_customize->add_setting( $social_mod, array(
			'transport'         => 'postMessage',
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( $social_mod, array(
			'type'    => 'url',
			'section' => 'theme_social',
			/* translators: %s is a social network's name, e.g.: Facebook URL */
			'label'   => esc_html( sprintf( _x( '%s URL', 'social network url', 'booth-woo' ), $network['label'] ) ),
		) );
	}

	$wp_customize->add_setting( 'theme_rss_feed', array(
		'transport'         => 'postMessage',
		'default'           => get_bloginfo( 'rss2_url' ),
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'theme_rss_feed', array(
		'type'    => 'url',
		'section' => 'theme_social',
		'label'   => esc_html__( 'RSS Feed', 'booth-woo' ),
	) );

	$wp_customize->selective_refresh->add_partial( 'theme_socials', array(
		'selector'            => '.list-social-icons',
		'render_callback'     => 'booth_woo_the_social_icons',
		'settings'            => array_merge( array( 'theme_social_target', 'theme_rss_feed' ), $social_mods ),
		'container_inclusive' => true,
	) );
