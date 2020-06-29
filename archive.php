<?php get_header(); ?>

<main class="main">
	<div class="container">

		<div class="row <?php booth_woo_the_row_classes(); ?>">

			<?php get_template_part( 'template-parts/breadcrumbs' ); ?>

			<div class="col-12">
				<div class="page-title">
					<?php the_archive_title(); ?>
				</div>
			</div>

			<div id="site-content" class="<?php booth_woo_the_container_classes(); ?>">
				<?php
					if ( have_posts() ) :

						while ( have_posts() ) : the_post();

							get_template_part( 'template-parts/item-media', get_post_type() );

						endwhile;

						booth_woo_posts_pagination();

					else :

						get_template_part( 'template-parts/article', 'none' );

					endif;
				?>
			</div>

			<?php get_sidebar(); ?>
		</div>

	</div>
</main>

<?php get_footer();
