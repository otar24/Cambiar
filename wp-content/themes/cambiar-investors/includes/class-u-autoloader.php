<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * U_Autoloader Autoloader.
 *
 * @class 		U_Autoloader
 * @version		1.0.0
 * @package		U_Theme/Classes
 * @category	Class
 * @author 		uCAT
 */
class U_Autoloader {

	/**
	 * Path to the includes directory.
	 *
	 * @var string
	 */
	private $include_path = '';

	/**
	 * The Constructor.
	 */
	public function __construct() {
		if ( function_exists( "__autoload" ) ) {
			spl_autoload_register( "__autoload" );
		}

		spl_autoload_register( array( $this, 'autoload' ) );

		$this->include_path = untrailingslashit( get_template_directory() ) . '/includes/';
	}

	/**
	 * Take a class name and turn it into a file name.
	 *
	 * @param  string $class
	 * @return string
	 */
	private function get_file_name_from_class( $class ) {
		return 'class-' . str_replace( '_', '-', $class ) . '.php';
	}

	/**
	 * Include a class file.
	 *
	 * @param  string $path
	 * @return bool successful or not
	 */
	private function load_file( $path ) {
		if ( $path && is_readable( $path ) ) {
			include_once( $path );
			return true;
		}
		return false;
	}

	/**
	 * Auto-load utheme classes on demand to reduce memory consumption.
	 *
	 * @param string $class
	 */
	public function autoload( $class ) {
		$class = strtolower( $class );
		$file  = $this->get_file_name_from_class( $class );
		$path  = '';

		if ( strpos( $class, 'u_meta_box' ) === 0 ) {
			$path = $this->include_path . 'admin/meta-boxes/';
		} elseif ( strpos( $class, 'u_admin' ) === 0 ) {
			$path = $this->include_path . 'admin/';
        } elseif ( strpos( $class, 'u_settings' ) === 0 ) {
            $path = $this->include_path . 'admin/';
        } elseif ( strpos( $class, 'u_walker' ) === 0 ) {
            $path = $this->include_path . 'walker/';
        } elseif ( strpos( $class, 'u_elementor' ) === 0 ) {
            $path = $this->include_path . 'elementor/';
        }

		if ( empty( $path ) || ( ! $this->load_file( $path . $file ) && strpos( $class, 'u_' ) === 0 ) ) {
			$this->load_file( $this->include_path . $file );
		}
	}
}

new U_Autoloader();
