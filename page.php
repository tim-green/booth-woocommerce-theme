<?php get_header(); ?>
<main class="main">
	<div class="container">
		<div class="row <?php booth_woo_the_row_classes(); ?>">
			<?php get_template_part( 'template-parts/breadcrumbs' ); ?>
			<div id="site-content" class="<?php booth_woo_the_container_classes(); ?>">
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="entry-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
						<?php booth_woo_the_post_thumbnail(); ?>

						<?php booth_woo_the_post_header(); ?>

						<div class="entry-content">
							<?php the_content(); ?>

							<?php wp_link_pages( booth_woo_wp_link_pages_default_args() ); ?>
						</div>
					</article>
					<?php comments_template(); ?>
				<?php endwhile; ?>

			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</main>
<?php get_footer();