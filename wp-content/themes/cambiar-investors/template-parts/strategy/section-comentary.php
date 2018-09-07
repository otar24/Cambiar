<?php
/**
 * @global WP_Post $post
 */
global $post;

$related_insights_title = get_field( 'related_insights_title' );
$related_insights       = get_field( 'related_insights' );

if ( ! $post->_commentary_id ) return;

if ( $post = get_post( $post->_commentary_id ) ) :
    setup_postdata( $post );
?>
<div class="fp-block fp-auto-height" data-anchor="commentary">
    <section class="single-post section-commentary">
        <article class="post type-post status-publish format-standard has-post-thumbnail hentry">
        <div class="container">
            <header class="section-header">
                <h1 class="title text-uppercase"><?php _e('Commentary', 'utheme'); ?></h1>
            </header>
            <div class="clearfix"></div>
            <section class="content">
                <figure class="main-media">
                    <div class="video-box mod">
                        <?php
                            $thumbnail         = get_the_post_thumbnail_url( $post, 'post-thumbnail' );
                            $format            = get_post_format();
                            $video_url         = get_field( 'video_url' );
                            $video_title       = get_field( 'video_title' );
                            $video_description = get_field( 'video_description' );
                            
                            if( $video_url && $format == 'video') :
                        ?>
                            <div class="u-yapi" data-url="<?php echo $video_url; ?>" style="width: 100%; position: relative;">
                                <div class="holder" style="background-image: url(<?php echo $thumbnail; ?>); background-position:center center; background-size: cover;">
                                    <a href="#" class="play-btn">
                                        <img src="<?php echo u_get_assets_uri('images/play-btn.svg'); ?>" width="96" height="96" alt="image description">
                                    </a>
                                </div>
                                <?php if ( $video_title || $video_description ) : ?>
                                    <div class="video-text">
		                                <?php if ( $video_title ) : ?>
                                            <div class="title text-uppercase">
                                                <?php echo apply_filters( 'the_content', $video_title ); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ( $video_description ) : ?>
                                            <div class="text">
	                                            <?php echo apply_filters( 'the_content', $video_description ); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php
                            elseif ( has_post_thumbnail() ) :
                                the_post_thumbnail('post-thumbnail');
                            endif;
                        ?>
                    </div>
                </figure>
                <div class="collapse-holder mod">
                    <h2 class="text-uppercase collapse-btn mod">
                        <?php the_title(); ?>
                        <i class="icon-arrow-down"></i>
                    </h2>
                    <div class="collapse">
                        <?php
                        the_content( sprintf(
                            __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'utheme' ),
                            get_the_title()
                        ) );
                        ?>
                    </div>
                </div>
                <?php get_template_part( 'template-parts/post/content', 'disclosures' ); ?>
            </section>
            <?php if ( $related_insights ) : ?>
                <aside class="single-sidebar not-fixed">
                    <div class="widget collapse-holder mod">
                        <?php if ( $related_insights_title ) : ?>
                            <h2 class="collapse-btn mod text-uppercase">
                                <?php echo esc_attr( $related_insights_title ); ?>
                                <i class="icon-arrow-down"></i>
                            </h2>
                        <?php endif; ?>
                        <div class="collapse">
                            <ul class="sidebar-post-list">
                                <?php foreach ( $related_insights as $related_post ) : ?>
	                                <?php
                                        $post_title = get_the_title( $related_post->ID );
                                        $title      = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)' );
                                        $category   = get_the_category_list( ', ', '',  $related_post->ID );
	                                ?>
                                    <li>
                                        <div class="text">
                                            <div class="meta">
				                                <?php printf( '%s - %s', $category, get_the_time( 'j M Y ', $related_post ) ); ?>
                                            </div>
                                            <div class="title"><a href="<?php the_permalink( $related_post->ID ); ?>"><?php echo $title; ?></a></div>
                                        </div>
                                        <div class="image">
			                                <?php echo get_the_post_thumbnail( $related_post->ID, 'post-thumbnail-small'); ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </aside>
            <?php endif; ?>
            <div class="clearfix"></div>
        </div>
        </article>
    </section>
</div>
<?php
    wp_reset_postdata();
endif;
?>