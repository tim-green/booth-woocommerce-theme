	<?php if ( is_active_sidebar( 'prefooter' ) ) : ?>
		<div class="widget-sections-footer">
			<?php dynamic_sidebar( 'prefooter' ); ?>
		</div>
	<?php endif; ?>

	<?php booth_woo_footer(); ?>

</div>

<?php wp_footer(); ?>

</body>
</html>
