<?php
$categories = get_categories( array(
    'orderby' => 'name',
    'parent'  => 0
) );

if( !$categories || !count($categories) || is_post_type_archive( 'strategy' )) return;
?>
<div class="nav-panel sticky-panel">
    <div class="container">
        <div class="holder">
            <div class="anchor-box full-width">
                <ul id="in-focus-tabs" class="slick-carousel-base text-uppercase">
                    <li><a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"<?php echo is_home() ? ' class="active"' : ''; ?>><?php _e('All', 'utheme'); ?></a></li>
                    <?php foreach( $categories as $category ) {
                        printf(
                            '<li><a href="%1$s" alt="%2$s" class="%4$s">%3$s</a></li>',
                            esc_url( get_category_link( $category->term_id ) ),
                            esc_attr( sprintf( __( 'View all posts in %s', 'utheme' ), $category->name ) ),
                            esc_html( $category->name ),
                            ( $category->term_id == get_queried_object_id() || ( is_singular( 'post' ) && in_array( $category->term_id, wp_get_post_categories( get_queried_object_id() ) ) ) ? 'active' : '' )
                        );
                    } ?>
                </ul>
            </div>
        </div>
    </div>
</div>