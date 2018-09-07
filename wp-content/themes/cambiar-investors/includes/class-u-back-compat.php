<?php
/**
 * Theme back compat functionality
 *
 * Prevents Theme from running on WordPress versions prior to 4.9,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.9.
 *
 * @class     U_Back_Compat
 * @version   1.0.0
 * @package   U_Theme/Classes
 * @category  Class
 * @author    uCAT
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * U_Back_Compat Class.
 */
class U_Back_Compat
{
    private $minimum_version;
    private $current_version;
    private $title;

    public function __construct($args = array() ) {
        $this->minimum_version = $args['minimum_version'];
        $this->current_version = $args['current_version'];
        $this->title   = $args['title'];

        add_action( 'after_switch_theme',  array( $this, 'switch_theme') );
        add_action( 'load-customize.php', array( $this, 'customize') );
        add_action( 'template_redirect', array( $this, 'preview') );
    }

    /**
     * Prevent switching to U_Theme on old versions.
     * Switches to the default theme.
     *
     * @since 1.0
     */
    public function switch_theme() {
        switch_theme( WP_DEFAULT_THEME );
        unset( $_GET['activated'] );
        add_action( 'admin_notices', array( $this, 'upgrade_notice') );
    }

    /**
     * Adds a message for unsuccessful theme switch.
     *
     * Prints an update nag after an unsuccessful attempt to switch to
     * U_Theme on versions prior.
     *
     * @since 1.0
     */
    public function upgrade_notice() {
        $message = sprintf( __( 'U_Theem requires at least %s version %s. You are running version %s. Please upgrade and try again.', 'utheme' ),
            $this->title,
            $this->minimum_version,
            $this->current_version
        );
        printf( '<div class="error"><p>%s</p></div>', $message );
    }

    /**
     * Prevents the Customizer.
     *
     * @since 1.0
     */
    public function customize() {
        wp_die( sprintf( __( 'U_Theem requires at least %s version %s. You are running version %s. Please upgrade and try again.', 'utheme' ),
                $this->title,
                $this->minimum_version,
                $this->current_version
            ), '', array(
            'back_link' => true,
        ) );
    }

    /**
     * Prevents the Theme Preview.
     *
     * @since 1.0
     */
    public function preview() {
        if ( isset( $_GET['preview'] ) ) {
            wp_die( sprintf( __( 'U_Theem requires at least %s version %s. You are running version %s. Please upgrade and try again.', 'utheme' ),
                $this->title,
                $this->minimum_version,
                $this->current_version
            ) );
        }
    }
}