<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage U_Theme
 * @since 1.0
 * @version 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('contact-page'); ?>>
    <div class="container">
        <section class="section-header">
            <?php the_title( '<h1 class="title text-uppercase">', '</h1>' ); ?>
        </section>
        <div class="columns">
            <div class="column-item">
                <?php
                the_content();

                wp_link_pages( array(
                    'before' => '<div class="page-links">' . __( 'Pages:', 'utheme' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div>
            <?php
            if( have_rows('u_columns') ):
                while ( have_rows('u_columns') ) : the_row();
                ?>
                <div class="column-item">
                    <?php the_sub_field('column_content'); ?>
                </div>
                <?php
                endwhile;
            endif; ?>
        </div>
        <div class="clearfix"></div>
    </div>
</article><!-- #post-## -->
