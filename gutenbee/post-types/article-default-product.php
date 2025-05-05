<?php
/**
 * Default template part for GutenBee's products.
 *
 * @since 1.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$classes = array( 'item', 'item-product' );
?>

<div <?php wc_product_class( $classes, $product ); ?>>
	<?php
		woocommerce_show_product_loop_sale_flash();
		woocommerce_template_loop_product_thumbnail();
	?>

	<div class="item-content">
	<?php
		booth_woo_woocommerce_show_product_loop_categories();
		woocommerce_template_loop_product_title();
		woocommerce_template_loop_price();
		woocommerce_template_loop_rating();
	?>
	</div>
</div>
