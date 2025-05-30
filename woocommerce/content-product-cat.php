<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product-cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$classes = array( 'item', 'item-product' );

global $woocommerce_loop;

$col_class = booth_woo_get_columns_classes( ! empty( $woocommerce_loop['columns'] ) ? $woocommerce_loop['columns'] : apply_filters( 'loop_shop_columns', 4 ) );
?>
<div class="<?php echo esc_attr( $col_class ); ?>">
	<div <?php wc_product_cat_class( $classes, $category ); ?>>
		<?php
		/**
		 * The woocommerce_before_subcategory hook.
		 *
		 * @hooked woocommerce_template_loop_category_link_open - 10 // Removed by the theme.
		 */
		do_action( 'woocommerce_before_subcategory', $category );

		/**
		 * The woocommerce_before_subcategory_title hook.
		 *
		 * @hooked woocommerce_subcategory_thumbnail - 10 // Removed by the theme.
		 * @hooked booth_woo_woocommerce_subcategory_thumbnail - 10 // Added by the theme.
		 */
		do_action( 'woocommerce_before_subcategory_title', $category );
		?>

		<div class="item-content">

			<?php
			/**
			 * The woocommerce_shop_loop_subcategory_title hook.
			 *
			 * @hooked woocommerce_template_loop_category_title - 10
			 */
			do_action( 'woocommerce_shop_loop_subcategory_title', $category );
			?>

		</div>

		<?php
		/**
		 * The woocommerce_after_subcategory_title hook.
		 */
		do_action( 'woocommerce_after_subcategory_title', $category );

		/**
		 * The woocommerce_after_subcategory hook.
		 *
		 * @hooked woocommerce_template_loop_category_link_close - 10 // Removed by the theme.
		 */
		do_action( 'woocommerce_after_subcategory', $category ); ?>
	</div>
</div>
