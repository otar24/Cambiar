<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage U_Theme
 * @since 1.0
 * @version 1.2
 */

?>
<section class="section-header">
    <h1 class="title text-uppercase">
        <?php the_title(); ?>
    </h1>
    <div class="excerpt">
        <?php the_excerpt(); ?>
    </div>
</section>
<section class="content">
    <?php
    the_content( sprintf(
        __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'utheme' ),
        get_the_title()
    ) );
    ?>

	<?php get_template_part( 'template-parts/post/content', 'disclosures' ) ?>
    <div class="clearfix"></div>
    <?php
    if ( is_single() ) {
        utheme_entry_footer();
    }
    ?>
</section>
<?php get_sidebar(); ?>
<div class="clearfix"></div>
