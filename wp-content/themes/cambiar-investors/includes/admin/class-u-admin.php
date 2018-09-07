<?php
/**
 * Additional admin features
 *
 * @package U_Theme/Classes/Admin
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * U_Admin class.
 */
class U_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		add_action( 'admin_init', array( $this, 'buffer' ), 1 );
		add_action( 'admin_footer', 'u_print_js', 25 );
	}

	/**
	 * Output buffering allows admin screens to make redirects later on.
	 */
	public function buffer() {
		ob_start();
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
        include_once( 'u-admin-functions.php' );
        include_once( 'class-u-admin-assets.php' );
        include_once( 'class-u-admin-settings.php' );
        include_once( 'class-u-admin-menus.php' );
        include_once( 'class-u-admin-meta-boxes.php' );
        include_once( 'class-u-admin-post-types.php' );
	}

	

	/**
	 * Prevent any user who cannot 'edit_posts' (subscribers etc) from accessing admin.
	 */
	public function prevent_admin_access() {
		$prevent_access = false;

		if ( ! is_ajax() && basename( $_SERVER["SCRIPT_FILENAME"] ) !== 'admin-post.php' ) {
			$has_cap     = false;
			$access_caps = array( 'edit_posts', 'view_admin_dashboard' );

			foreach ( $access_caps as $access_cap ) {
				if ( current_user_can( $access_cap ) ) {
					$has_cap = true;
					break;
				}
			}

			if ( ! $has_cap ) {
				$prevent_access = true;
			}
		}

		if ( apply_filters( 'utheme_prevent_admin_access', $prevent_access ) ) {
			wp_safe_redirect( get_home_url( ) );
			exit;
		}
	}

	
}

return new U_Admin();