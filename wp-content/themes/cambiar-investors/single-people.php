<?php
/**
 * The template for displaying all single posts
 *
 */

get_header(); ?>
    <?php
    /* Start the Loop */
    while ( have_posts() ) : the_post();
        
        get_template_part( 'template-parts/people/section', 'overview' );
    
    endwhile; // End of the loop.
    ?>
<?php get_footer();
