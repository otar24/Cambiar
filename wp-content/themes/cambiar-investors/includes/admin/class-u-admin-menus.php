<?php
/**
 * Setup menus in WP admin.
 *
 * @author      uCAT
 * @category    Admin
 * @package     U_Theme/Admin/
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'U_Admin_Menus', false ) ) :

/**
 * U_Admin_Menus Class.
 */
class U_Admin_Menus {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		//theme options tab in appearance
		if( function_exists( 'acf_add_options_sub_page' ) ) {
			acf_add_options_sub_page( array(
				'title'  => 'Theme Options',
				'parent' => 'themes.php',
			) );
		}
		
		// Add menus
		add_action( 'admin_menu', array( $this, 'disclosures_menu' ), 9 );
		add_action( 'admin_menu', array( $this, 'import_menu' ), 10 );

        add_action( 'admin_head', array( $this, 'menu_highlight' ) );
	}

	/**
	 * Add menu item.
	 */
	public function disclosures_menu() {
        $page = add_submenu_page('edit.php?post_type=strategy', __('Contents/Disclosures', 'utheme'), __('Contents/Disclosures', 'utheme'), 'manage_options', 'u-disclosures', array($this, 'disclosures_page'));
        add_action( 'load-' . $page, array( $this, 'disclosures_page_init' ) );
	}

    /**
     * Add menu item.
     */
    public function import_menu() {
        add_submenu_page('edit.php?post_type=strategy', __('Import', 'utheme'), __('Import', 'utheme'), 'manage_options', 'admin.php?import=u-strategies' );
        register_importer( 'u-strategies', 'Strategies', __('Import Strategies', 'utheme'), array( $this, 'import_page' ) );
    }

    /**
     * Highlights the correct top level admin menu item for post type add screens.
     */
    public function menu_highlight() {
        global $parent_file, $submenu_file;
        if( isset($_GET['import']) &&  $_GET['import'] === 'u-strategies'){
            $parent_file = 'edit.php?post_type=strategy';
            $submenu_file = 'admin.php?import=u-strategies';
        }
    }

	/**
	 * Init the disclosures page.
	 */
	public function disclosures_page() {
        U_Settings_Disclosures::output();
	}

	public function disclosures_page_init(){
        if ( ! empty( $_POST ) ) {
            U_Settings_Disclosures::save();
        }
    }

    /**
     * Init the import page.
     */
    public function import_page() {
        $import = new U_Admin_Importer_Controller();
        $import->dispatch();
    }


}

endif;

return new U_Admin_Menus();
