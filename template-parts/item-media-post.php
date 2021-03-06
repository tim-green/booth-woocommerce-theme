<article id="item-<?php the_ID(); ?>" <?php post_class( 'item item-media' ); ?>>
	<?php booth_woo_the_item_thumbnail( 'booth_woo_item_media' ); ?>

	<div class="item-content">
		<?php booth_woo_the_post_item_date(); ?>

		<h3 class="item-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<div class="entry-meta">
			<?php booth_woo_the_post_entry_sticky_label(); ?>

			<?php booth_woo_the_post_entry_categories(); ?>

			<?php booth_woo_the_post_entry_comments_link(); ?>
		</div>

		<div class="item-excerpt">
			<?php the_excerpt(); ?>
		</div>

		<a href="<?php the_permalink(); ?>" class="btn item-read-more"><?php esc_html_e( 'Read More', 'booth-woo' ); ?></a>
	</div>
</article>
