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

    $address_info    = get_field( 'address_info' );
    $map             = get_field( 'map' );
    $map_description = get_field( 'description' );
    $marker          = get_field( 'marker' );
    $gmap_api_key    = get_field( 'gmap_api_key', 'options' );
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
            <?php if ( $address_info || ( $map && $gmap_api_key ) ) : ?>
                <div class="column-item">
                    <?php if ( $address_info ) : ?>
                        <div class="info">
	                        <?php  echo apply_filters( 'the_content', $address_info ); ?>
                        </div>
                    <?php endif; ?>
	
	                <?php if ( $map && $gmap_api_key ) : ?>
                        <div class="gmap-holder map-responsive" data-lat="<?php echo $map['lat']; ?>" data-lng="<?php echo $map['lng']; ?>" data-marker="<?php echo $marker ? $marker : '/wp-content/themes/cambiar-investors/assets/images/map-marker.png'; ?>"><?php echo $map_description ? $map_description : $map['address']; ?></div>
	                <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
    </div>
</article><!-- #post-## -->
