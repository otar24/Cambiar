<?php
/**
 * Displays top navigation
 *
 * @package WordPress
 * @subpackage U_Theme
 * @since 1.0
 */

?>
<nav class="menu" role="navigation" aria-label="<?php esc_attr_e( 'Main Menu', 'utheme' ); ?>">
    <a href="#" class="nav-opener"><span></span></a>
    <div class="drop">
        <div class="top-panel">
            <a href="#" class="close-btn nav-opener"><i class="icon-close"></i></a>
        </div>
        <div class="menu-panel-holder">
            <div class="main-nav-holder">
                <div class="breadcrumb-nav">
                    <span property="itemListElement">
                        <span><?php echo get_bloginfo('name'); ?></span>
                    </span>
                </div>
                <?php wp_nav_menu( array(
                    'theme_location' => 'main',
                    'menu_id'        => 'main-menu',
                    'menu_class'     => 'main-nav',
                    'walker'         => new U_Walker_Nav_Menu()
                ) ); ?>

            </div>
            <div class="form-holder">
                <?php get_search_form(); ?>
            </div>
        </div>

    </div>
</nav>