<?php
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
		<label for="<?php echo esc_attr( $fieldname ); ?>"><?php esc_html_e( "Hide when viewing this post's page", 'booth-woo' ); ?></label>
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

	if ( isset( $_POST['_booth_woo_hide_single_featured_meta_box_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['_booth_woo_hide_single_featured_meta_box_nonce'] ), 'booth_woo_hide_single_featured_nonce' ) ) {
		update_post_meta( $post_id, 'booth_woo_hide_single_featured', isset( $_POST['booth_woo_hide_single_featured'] ) ); // Input var okay.
	}
}