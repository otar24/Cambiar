<?php
/**
 * Template Name: Contact Page
 */

get_header(); ?>
			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/page/contact/content' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

<?php get_footer();
