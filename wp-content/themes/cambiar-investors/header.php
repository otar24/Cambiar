<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage U_Theme
 * @since 1.0
 * @version 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class( 'loading'); ?>>
    <div class="preloader">
        <img class="circular" src="<?php echo u_get_assets_uri('images/cambir-logo-button.svg'); ?>">
    </div>
    <div id="wrapper"<?php echo ( $css_bg = get_field( 'u_css_background' ) ) ? ' style="background-image: url(\'' . $css_bg . '\')"' : ''; ?>>

        <header id="header" class="site-header" role="banner">
        <?php if(get_field('hide_contact_button') != 1):
                contactButton();
            endif; ?>
            <div class="container-fluid">
                <div class="header-holder">
                    <?php get_template_part( 'template-parts/header/header', 'image' ); ?>

                    <?php if ( has_nav_menu( 'main' ) ) :
                        get_template_part( 'template-parts/navigation/navigation', 'main' );
                    endif; ?>

                </div>
            </div>

        </header><!-- #masthead -->
        <?php
        if(is_singular('strategy') ){
            get_template_part( 'template-parts/header/header', 'strategy' );
        }
        elseif( is_blog_page() ){
            get_template_part( 'template-parts/header/header', 'post' );
        } ?>

        <main id="<?php echo u_get_container_id(); ?>" class="main <?php echo u_get_container_class()?>">