<?php
    $blog_page_id = is_home() ? get_queried_object_id() : get_option( 'page_for_posts' );
    $title   = get_field( 'stay_connected_title', $blog_page_id );
    $content = get_field( 'stay_connected_content', $blog_page_id );
    $code    = get_field( 'embed_code', $blog_page_id );
    
    if ( $title || $content || $code ) :
?>
<section class="stay-connected">
    <div class="container">
        <?php if ( $title ) : ?>
            <header class="section-header">
                <h1 class="title text-uppercase"><?php echo esc_attr( $title ); ?></h1>
            </header>
        <?php
            endif;
            
            echo apply_filters( 'the_content', $content );
            
            if ( $code ) :
        ?>
            <div role="form" class="wpcf7" id="" lang="en-US" dir="ltr">
                <div class="screen-reader-response"></div>
                <?php echo do_shortcode( $code ); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>