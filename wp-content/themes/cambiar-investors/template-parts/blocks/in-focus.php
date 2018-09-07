<?php
global $post;
$args = array(
    'posts_per_page' => 8,
    'post__in'  => get_option( 'sticky_posts' ),
    'ignore_sticky_posts' => 1
);
$q = new WP_Query();
$sticky_posts = $q->query( $args );$bg_type = get_sub_field( 'type' );

$bg_type = get_sub_field( 'type' );
?>
<div class="fp-block fp-auto-height" data-anchor="in-focus">
    <section<?php echo $id ? ' id="' . esc_attr( $id ) . '"' : ''; ?> class="section-hero section-in-focus<?php echo $bg_type === 'overlay' ? ' bg-stretch-mod bg-overlay-mod' : ''; ?> <?php echo esc_attr( $class ); ?>">
        <div class="fp-bg" style="background-image: url('<?php echo $bg; ?>')"></div>
	    <?php if ( $bg_type === 'overlay' ) : ?>
            <div class="fade-bg"></div>
	    <?php endif; ?>
        <div class="fp-container">
            <div class="container">
            <div class="text-box text-white">
                <header class="section-header mod">
                    <h1 class="title text-uppercase">
                        <?php the_sub_field('title'); ?>
                    </h1>
                </header>
                <div class="text-holder display-xs-visible">
                    <?php the_sub_field('description'); ?>
                </div>
                <div class="latest-news">
                    <div class="post-holder">
                        <div class="post-box">
                            <?php
                            if( $sticky_posts ) {
                                foreach ( $sticky_posts as $i => $post) {
                                    setup_postdata($post);
                                    $a_class = $i == 0 ? 'new-post' : '';
                                    ?>
                                    <article class="post <?php echo $a_class; ?>">
                                        <div class="post-img-holder">
                                            <a href="<?php the_permalink(); ?>">
                                                <div class="holder">
                                                    <?php echo get_the_post_thumbnail($post->ID, 'utheme-thumbnail'); ?>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="post-text-wrap">
                                            <div class="post-time-holder text-uppercase">
                                                <span><?php echo get_the_category_list( ', ' ); ?></span>
                                                <time datetime="<?php the_time('Y-m-d '); ?>"> - <?php the_time('j M Y '); ?></time>
                                            </div>
                                            <h2 class="post-title">
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </h2>
	                                        <?php if ( $excerpt = get_the_excerpt() ) : ?>
                                                <div class="text-box">
                                                    <div class="box">
	                                                    <?php echo apply_filters( 'the_content', wp_trim_words( $excerpt, 25 ) ); ?>
                                                    </div>
                                                </div>
	                                        <?php endif; ?>
                                        </div>
                                    </article>
                                    <?php
                                }
                                wp_reset_postdata();
                            }

                            if ( get_sub_field( 'show_sign_up_block' ) ) :
                            ?>
                            <div class="call-to-action">
                                <?php if ( $text = get_sub_field( 'sign_up_block_text' ) ) : ?>
                                    <div class="title text-uppercase">
                                        <?php echo wpautop( esc_attr( $text ) ); ?>
                                    </div>
                                <?php
                                    endif;

                                    if ( get_sub_field( 'sign_up_show_button' ) ) :
	                                    $url = get_sub_field( 'sign_up_button_url' );
	                                    $target_id = null;
	                                    if ( $is_popup_opener = strpos( $url, '#' ) === 0 ) {
		                                    $target_id = substr( $url, 1 );
	                                    }
                                ?>
                                    <div class="bnt-holder">
                                        <a href="<?php echo ! $is_popup_opener ? esc_url( $url ) : '#'; ?>" class="btn btn-secondary text-uppercase<?php echo $is_popup_opener ? ' cd-popup-trigger' : ''; ?>"<?php echo $is_popup_opener ? ' data-popup-id="' . $target_id . '"' : ''; ?>><?php echo esc_attr( get_sub_field( 'sign_up_button_text' ) ); ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="btn-box display-xs-hidden">
                        <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" class="btn btn-secondary text-uppercase">View All</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
</div>