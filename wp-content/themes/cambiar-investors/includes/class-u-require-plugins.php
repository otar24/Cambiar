<?php
/**
 * Register the required plugins for theme.
 *
 * @class     U_Require_Plugins
 * @version   1.0.0
 * @package   U_Theme/Classes
 * @category  Class
 * @author    uCAT
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * U_Require_Plugins Class.
 */
class U_Require_Plugins
{

    /**
     * This method is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
     */
    public static function register_required_plugins()
    {
        /*
         * Array of plugin arrays. Required keys are name and slug.
         * If the source is NOT from the .org repo, then source is also required.
         */
        /*$plugins = array(
            array(
                'name'      => 'Elementor Page Builder',
                'slug'      => 'elementor',
                'required'  => true,
                'version'   => '2.0.9'
            )
        );*/

        $plugins = array(
            array(
                'name'      => 'Advanced Custom Fields PRO',
                'slug'      => 'advanced-custom-fields-pro',
                'required'  => true,
            ),
            array(
                'name'      => 'Advanced Custom Fields: Repeater Field',
                'slug'      => 'acf-repeater',
                'required'  => true,
                'source'    => get_template_directory() . '/includes/vendor/acf-repeater.zip'
            ),
            array(
                'name'      => 'Advanced Custom Fields: Flexible Content Field',
                'slug'      => 'acf-flexible-content',
                'required'  => true,
                'source'    => get_template_directory() . '/includes/vendor/acf-flexible-content.zip'
            ),
            array(
                'name'      => 'Advanced Custom Fields: Date and Time Picker',
                'slug'      => 'acf-field-date-time-picker',
                'required'  => true,
                'source'    => get_template_directory() . '/includes/vendor/acf-field-date-time-picker.zip'
            )

        );

        $config = array(
            'id'           => 'utheme',                // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',                      // Default absolute path to bundled plugins.
            'menu'         => 'tgmpa-install-plugins', // Menu slug.
            'parent_slug'  => 'themes.php',            // Parent menu slug.
            'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
            'has_notices'  => true,                    // Show admin notices or not.
            'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.
        );

        tgmpa( $plugins, $config );
    }

}