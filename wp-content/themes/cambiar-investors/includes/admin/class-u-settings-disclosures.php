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

if ( ! class_exists( 'U_Settings_Disclosures', false ) ) :

/**
 * U_Settings_Disclosures Class.
 */
class U_Settings_Disclosures {

    public static function output(){
        $settings = self::get_settings();
        include 'views/html-admin-page-disclosures.php';
    }

    /**
     * Get settings array.
     *
     * @return array
     */
    public static function get_settings() {
        $settings = array(
	        array(
		        'label' => __('Top Content for Mutual Fund', 'utheme'),
		        'id'   => 'u_mutual_funds_top_content',
		        'type' => 'wysiwyg'
	        ),
	        array(
                'label' => __('Home Disclosure for Mutual Fund', 'utheme'),
                'id'   => 'u_home_mutual_funds_disclosures',
                'type' => 'wysiwyg'
            ),
	        array(
		        'label' => __('Home Disclosure for Separate Accounts', 'utheme'),
		        'id'   => 'u_home_managed_accounts_disclosures',
		        'type' => 'wysiwyg'
	        ),
	        array(
                'label' => __('Disclosure for Mutual Fund', 'utheme'),
                'id'   => 'u_mutual_funds_disclosures',
                'type' => 'wysiwyg'
            ),
	        array(
		        'label' => __('Disclosure for Separate Accounts', 'utheme'),
		        'id'   => 'u_managed_accounts_disclosures',
		        'type' => 'wysiwyg'
	        ),
        );
        return $settings;
    }

    /**
     * Save settings.
     */
    public static function save() {
        $settings = self::get_settings();

        U_Admin_Settings::save( $settings );
    }
}

endif;

return new U_Settings_Disclosures();
