<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage U_Theme
 * @since 1.0
 * @version 1.0
 */

if ( ! is_active_sidebar( 'sidebar-blog' ) ) {
	return;
}
?>
<aside id="secondary" class="single-sidebar" role="complementary" aria-label="<?php esc_attr_e( 'Blog Sidebar', 'utheme' ); ?>">
	<?php dynamic_sidebar( 'sidebar-blog' ); ?>
</aside><!-- #secondary -->
