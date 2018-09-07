<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package WordPress
 * @subpackage U_Theme
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>
<div class="container">
    <div class="search-container">
        <section class="section-header">
            <h1 class="title text-uppercase">
                <?php
                if ( have_posts() ){
                    printf( __( 'Search Results for: %s', 'utheme' ), get_search_query() );
                }else{
                    _e( 'Nothing Found', 'utheme' );
                }
                ?>
            </h1>
        </section>
                <?php
                if ( have_posts() ) :
                    /* Start the Loop */
                    while ( have_posts() ) : the_post();
                    ?>
                    <?php
                        get_template_part( 'template-parts/post/content', 'excerpt' );

                    endwhile; // End of the loop.

                    the_posts_pagination( array(
                        'prev_text' => utheme_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous page', 'utheme' ) . '</span>',
                        'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'utheme' ) . '</span>' . utheme_get_svg( array( 'icon' => 'arrow-right' ) ),
                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'utheme' ) . ' </span>',
                    ) );

                else : ?>

                    <p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'utheme' ); ?></p>
                    <?php
                    get_search_form();

                endif;
                ?>
        <div class="clearfix"></div>
    </div>
</div>
<?php get_footer();