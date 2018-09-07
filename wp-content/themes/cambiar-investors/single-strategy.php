<?php
/**
 * The template for displaying all single strategy
 *
 *
 * @package WordPress
 * @subpackage U_Theme
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

    <?php
    /* Start the Loop */
    while ( have_posts() ) : the_post();

        get_template_part( 'template-parts/strategy/section', 'overview' );
        get_template_part( 'template-parts/strategy/section', 'perfomance' );
        get_template_part( 'template-parts/strategy/section', 'composition' );
        get_template_part( 'template-parts/strategy/section', 'comentary' );
        get_template_part( 'template-parts/strategy/section', 'cap-gains' );
//        get_template_part( 'template-parts/strategy/section', 'disclosures' );

    endwhile; // End of the loop.
    ?>

<?php get_footer();
