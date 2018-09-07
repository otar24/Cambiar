<?php
/**
 * Template for displaying search forms in Cambiar Investors
 *
 * @package WordPress
 * @subpackage U_Theme
 * @since 1.0
 * @version 1.0
 */

?>

<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-form" id="searchform" method="get" role="search">
    <input id="<?php echo $unique_id; ?>" name="s" type="search" placeholder="Search" value="<?php echo get_search_query(); ?>">
    <button class="btn btn-search" id="searchsubmit">
        <i class="icon-search"></i>
    </button>
</form>