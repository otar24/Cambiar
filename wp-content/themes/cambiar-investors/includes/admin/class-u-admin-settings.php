<?php
/**
 * @author      uCAT
 * @category    Admin
 * @package     U_Theme/Admin/
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'U_Admin_Settings', false ) ) :

/**
 * U_Admin_Settings Class.
 */
class U_Admin_Settings {

    /**
     * Save the settings.
     */
    public static function save( $options = array())
    {
        if (empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'utheme-settings')) {
            die(__('Action failed. Please refresh the page and retry.', 'utheme'));
        }

        $data = $_POST;
        if ( empty( $data ) ) {
            return false;
        }

        // Options to update will be stored here and saved later.
        $update_options = array();

        // Loop options and get values to save.
        foreach ( $options as $option ) {
            if (!isset($option['id']) || !isset($option['type'])) {
                continue;
            }

            // Get posted value.
            if ( strstr( $option['id'], '[' ) ) {
                parse_str( $option['id'], $option_name_array );
                $option_name  = current( array_keys( $option_name_array ) );
                $setting_name = key( $option_name_array[ $option_name ] );
                $raw_value    = isset( $data[ $option_name ][ $setting_name ] ) ? wp_unslash( $data[ $option_name ][ $setting_name ] ) : null;
            } else {
                $option_name  = $option['id'];
                $setting_name = '';
                $raw_value    = isset( $data[ $option['id'] ] ) ? wp_unslash( $data[ $option['id'] ] ) : null;
            }

            // Format the value based on option type.
            switch ( $option['type'] ) {
                case 'checkbox' :
                    $value = '1' === $raw_value || 'yes' === $raw_value ? 'yes' : 'no';
                    break;
                case 'textarea' :
                    $value = wp_kses_post( trim( $raw_value ) );
                    break;
                case 'wysiwyg' :
                    $value = wp_kses_post( trim( $raw_value ) );
                    break;
                case 'multiselect' :
                    $value = array_filter( array_map( 'u_clean', (array) $raw_value ) );
                    break;
                case 'select':
                    $allowed_values = empty( $option['options'] ) ? array() : array_keys( $option['options'] );
                    if ( empty( $option['default'] ) && empty( $allowed_values ) ) {
                        $value = null;
                        break;
                    }
                    $default = ( empty( $option['default'] ) ? $allowed_values[0] : $option['default'] );
                    $value   = in_array( $raw_value, $allowed_values ) ? $raw_value : $default;
                    break;
                default :
                    $value = u_clean( $raw_value );
                    break;
            }

            if ( is_null( $value ) ) {
                continue;
            }

            // Check if option is an array and handle that differently to single values.
            if ( $option_name && $setting_name ) {
                if ( ! isset( $update_options[ $option_name ] ) ) {
                    $update_options[ $option_name ] = get_option( $option_name, array() );
                }
                if ( ! is_array( $update_options[ $option_name ] ) ) {
                    $update_options[ $option_name ] = array();
                }
                $update_options[ $option_name ][ $setting_name ] = $value;
            } else {
                $update_options[ $option_name ] = $value;
            }
        }

        // Save all options in our array.
        foreach ( $update_options as $name => $value ) {
            update_option( $name, $value );
        }
    }

    /**
     * Add a message.
     * @param string $text
     */
    public static function add_message( $text ) {
        self::$messages[] = $text;
    }

    /**
     * Add an error.
     * @param string $text
     */
    public static function add_error( $text ) {
        self::$errors[] = $text;
    }

    /**
     * Output messages + errors.
     */
    public static function show_messages() {
        if ( sizeof( self::$errors ) > 0 ) {
            foreach ( self::$errors as $error ) {
                echo '<div id="message" class="error inline"><p><strong>' . esc_html( $error ) . '</strong></p></div>';
            }
        } elseif ( sizeof( self::$messages ) > 0 ) {
            foreach ( self::$messages as $message ) {
                echo '<div id="message" class="updated inline"><p><strong>' . esc_html( $message ) . '</strong></p></div>';
            }
        }
    }
}

endif;

return new U_Admin_Settings();
