<?php
/**
 * Displays footer site info
 *
 * @package WordPress
 * @subpackage U_Theme
 * @since 1.0
 * @version 1.0
 */


if ( is_active_sidebar( 'footer-text' ) ) :
?>
<div class="text-box">
    <?php dynamic_sidebar( 'footer-text' ); ?>
</div>
<?php endif; ?>
