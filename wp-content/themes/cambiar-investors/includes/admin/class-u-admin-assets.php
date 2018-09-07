<?php
/**
 * Load assets
 *
 * @author      uCAT
 * @category    Admin
 * @package     U_Theme/Classes/Admin/Assets
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'U_Admin_Assets' ) ) :

/**
 * U_Admin_Assets Class.
 */
class U_Admin_Assets {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Enqueue styles.
	 */
	public function admin_styles() {
		global $wp_scripts;

		$screen         = get_current_screen();
		$screen_id      = $screen ? $screen->id : '';
		$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.11.4';

		// Register admin styles
		wp_register_style( 'utheme_admin_styles', u_get_assets_uri('css/admin.css'), array(), U_THEME_VERSION );
		wp_register_style( 'utheme_admin_dragtable', u_get_assets_uri('css/dragtable.css'), array(), '2.0.15' );
		wp_register_style( 'utheme_admin_import_styles', u_get_assets_uri('css/admin-import.css'), array(), U_THEME_VERSION );
		wp_register_style( 'jquery-ui-style', '//code.jquery.com/ui/' . $jquery_version . '/themes/smoothness/jquery-ui.min.css', array(), $jquery_version );
		wp_register_style( 'utheme_datatables_styles', 'https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css', array(), U_THEME_VERSION );
		
		// Admin styles for utheme pages only
		if ( in_array( $screen_id, u_get_screen_ids() ) ) {
			wp_enqueue_style( 'utheme_admin_styles' );
			wp_enqueue_style( 'utheme_admin_dragtable' );
			wp_enqueue_style( 'jquery-ui-style' );
		}

        if( isset($_GET['import']) &&  $_GET['import'] === 'u-strategies'){
            wp_enqueue_style( 'utheme_admin_import_styles' );
        }
	}


	/**
	 * Enqueue scripts.
	 */
	public function admin_scripts() {
		global $wp_query, $post;

		$screen       = get_current_screen();
		$screen_id    = $screen ? $screen->id : '';
		$suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$suffix       = '';

		// Register scripts
		#$depth = array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable', 'utheme-enhanced-select', 'utheme_datatables', 'utheme_admin');
		$depth = array( 'jquery', 'jquery-ui-datepicker');

		//wp_register_script( 'jquery-tiptip', u_get_assets_uri('js/jquery-tiptip/jquery.tipTip' . $suffix . '.js'), array( 'jquery' ), U_THEME_VERSION, true );
		wp_register_script( 'utheme_datatables', 'https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js', array( 'jquery' ), U_THEME_VERSION );

		wp_localize_script( 'utheme_admin', 'utheme_admin_params', array(
			'i18n_ajax_error'           => _x( 'Loading failed', 'enhanced select', 'utheme' ),
			'i18n_load_more'            => _x( 'Loading more results&hellip;', 'enhanced select', 'utheme' ),
			'i18n_searching'            => _x( 'Searching&hellip;', 'enhanced select', 'utheme' ),
			'ajax_url'                  => admin_url( 'admin-ajax.php' ),
			'default_nonce'             => wp_create_nonce( 'default_nonce' ),
		) );

		wp_register_script( 'utheme-admin-meta-boxes', u_get_assets_uri('js/meta-boxes' . $suffix . '.js'), $depth, U_THEME_VERSION );
		wp_register_script( 'utheme-admin-dragtable', u_get_assets_uri('js/jquery.dragtable.js'), $depth, '2.0.15' );
		wp_register_script( 'utheme_admin', u_get_assets_uri('js/utheme_admin' . $suffix . '.js'), array('jquery' ), U_THEME_VERSION, true );
		wp_register_script( 'utheme_admin_import', u_get_assets_uri('js/utheme_admin_import' . $suffix . '.js'), array('jquery' ), U_THEME_VERSION );

		// Admin styles for utheme pages only
		if ( $screen_id == 'strategy' ) {
            wp_enqueue_media();
			wp_enqueue_script( 'utheme-admin-meta-boxes' );
			wp_enqueue_script( 'utheme-admin-dragtable' );
		}
		if ( $screen_id == 'edit-strategy' ) {
			wp_enqueue_script( 'utheme_admin' );
		}
		
	}

}

endif;

return new U_Admin_Assets();
