<?php
/**
 * Displays footer widgets if assigned
 *
 * @package WordPress
 * @subpackage U_Theme
 * @since 1.0
 * @version 1.0
 */

?>

<?php
if ( is_active_sidebar( 'footer-1' ) ||
	 is_active_sidebar( 'footer-2' ) ) :
?>

	<aside class="widget-area" role="complementary" aria-label="<?php esc_attr_e( 'Footer', 'utheme' ); ?>">
		<?php
		if ( is_active_sidebar( 'footer-1' ) ) { ?>
			<div class="widget-column footer-widget-1">
				<?php dynamic_sidebar( 'footer-1' ); ?>
			</div>
		<?php }
		if ( is_active_sidebar( 'footer-2' ) ) { ?>
			<div class="widget-column footer-widget-2">
				<?php dynamic_sidebar( 'footer-2' ); ?>
			</div>
		<?php } ?>
	</aside><!-- .widget-area -->

<?php endif; ?>
