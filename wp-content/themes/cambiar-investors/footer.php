<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage U_Theme
 * @since 1.0
 * @version 1.2
 */

?>
        <footer id="footer" class="fp-block fp-auto-height">
            <div class="container">
                <?php if ( has_nav_menu( 'footer' ) ) : ?>
                    <nav class="footer-top" role="navigation" aria-label="<?php esc_attr_e( 'Footer Links Menu', 'utheme' ); ?>">
                        <a href="#" class="copyright"><?php _e( '© 2018 Cambiar', 'utheme' ); ?></a>
                        <?php
                        $menuParameters = array(
                            'theme_location' => 'footer',
                            'container'       => false,
                            'echo'            => false,
                            'items_wrap'      => '%3$s',
                            'depth'           => 0,
                        );
                        echo strip_tags(wp_nav_menu( $menuParameters ), '<a>' );
                        ?>
                    </nav><!-- .social-navigation -->
                <?php endif; ?>
                <?php
                get_template_part( 'template-parts/footer/site', 'info' );
                get_template_part( 'template-parts/footer/footer', 'widgets' );
                ?>
            </div>

        </footer> <!--#footer-->

    </main> <!--#fullpage-->

    <?php get_template_part( 'template-parts/footer/contact' ); ?>
    <?php get_template_part( 'template-parts/footer/signup' ); ?>

</div><!-- #wrapper -->
<?php wp_footer(); ?>

</body>
</html>
