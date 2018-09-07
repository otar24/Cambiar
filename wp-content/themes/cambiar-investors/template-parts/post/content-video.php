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
    $video  = get_field('video_url');
    $video_w  = get_field('video_width');
    $video_h  = get_field('video_height');
    if( has_post_thumbnail() && !$video ){
        $caption = get_the_post_thumbnail_caption();
        ?>
        <figure class="main-media">
            <?php
            the_post_thumbnail('post-thumbnail');
            if( $caption ) {
                echo '<figcaption>' . $caption . '</figcaption>';
            }
            ?>
        </figure>
    <?php }elseif ($video){
        $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'post-thumbnail');
        ?>
    <figure class="main-media">
        <div class="video-box mod">
            <div class="u-yapi" data-url="<?php echo $video; ?>" data-width="<?php echo $video_w ? $video_w : '100%'; ?>" data-height="<?php echo $video_h ? $video_h : '100%'; ?>" style="width: 100%;<?php echo $video_h ? ' height: ' . $video_h . 'px;' : ''; ?> position: relative;">
                <div class="holder" style="background-image: url(<?php echo $thumbnail; ?>); background-position:center center; background-size: cover;">
                    <a href="#" class="play-btn">
                        <img src="<?php echo u_get_assets_uri('images/play-btn.svg'); ?>" width="96" height="96" alt="">
                    </a>
                </div>
                <div class="video-text">
                    <div class="title text-uppercase">
                        <p><?php the_field('video_title'); ?></p>
                    </div>
                    <div class="text">
                        <p><?php the_field('video_description'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </figure>
    <?php } ?>

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