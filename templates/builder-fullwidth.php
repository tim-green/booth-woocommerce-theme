<?php
/*
 * Template Name: Page builder - Fullwidth
 */
?>

<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<?php the_content(); ?>
<?php endwhile; ?>

<?php get_footer();