<?php
/**
 * The template for displaying all single posts
 *
 */

get_header(); ?>
    <?php
    /* Start the Loop */
    while ( have_posts() ) : the_post();
    ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="container">
            <?php
            $format = get_post_format();
            $format = $format === 'video' ? $format : '';
            get_template_part( 'template-parts/post/content', $format );
            ?>
            </div>
        </article>
        <div class="show-on-mobile">
            <?php echo do_shortcode( '[addthis tool="addthis_inline_share_toolbox_t9hj"]' ); ?>
        </div>
    <?php endwhile; // End of the loop.?>
    <?php get_sidebar(); ?>
<?php get_footer();
