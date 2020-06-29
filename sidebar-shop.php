<?php
	$info = booth_woo_get_layout_info();
	if ( ! $info['has_sidebar'] ) {
		return;
	}
?>
<div class="<?php booth_woo_the_sidebar_classes(); ?>">
	<div class="sidebar">
		<?php dynamic_sidebar('shop'); ?>
	</div>
</div>