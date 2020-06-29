<?php get_header(); ?>

<main class="main">

	<div class="container">

		<div class="row">

			<?php get_template_part( 'template-parts/breadcrumbs' ); ?>

			<div id="site-content" class="<?php booth_woo_the_container_classes(); ?>">

				<?php $the_title = get_theme_mod( 'title_404', __( 'Page not found', 'booth-woo' ) ); ?>

				<article class="entry error-404 not-found">
					<?php if ( $the_title ) : ?>
						<header class="entry-header">

							<h1 class="entry-title">
								<?php echo esc_html( $the_title ); ?>
							</h1>

						</header>
					<?php endif; ?>

					<div class="entry-content">
						<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'booth-woo' ); ?></p>

						<?php get_search_form(); ?>
					</div>
				</article>

			</div>

			<?php get_sidebar(); ?>

		</div>

	</div>

</main>

<?php get_footer();
