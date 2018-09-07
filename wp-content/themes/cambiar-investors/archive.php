<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage U_Theme
 * @since 1.0
 * @version 1.0
 */

if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] == 1){
	get_template_part( 'template-parts/archive/posts', 'list' );
	return;
}

get_header(); ?>

<section class="section-posts-list">
    <div class="container">
        <header class="section-header">
            <h1 class="title text-uppercase"><?php _e('In Focus', 'utheme'); ?></h1>
        </header>
    </div>

    <div class="scroll-holder">
        <div id="scroll-wrap-posts-list" class="scroll-wrap">
            <?php get_template_part( 'template-parts/archive/posts', 'list' ); ?>
        </div>
    </div>


</section><!-- .wrap -->
<?php get_template_part( 'template-parts/archive/stay-connected' ); ?>

<?php get_footer();
