<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Manager Factory Class
 *
 * The manager factory creating the right manager objects.
 *
 * @class 		U_Manager_Factory
 * @version		1.0.0
 * @package     U_Theme/Classes
 * @category	Class
 * @author      Elena Zhyvohliad
 */
class U_Manager_Factory {

	/**
	 * Get manager.
	 *
	 * @param bool $the_manager (default: false)
	 * @return U_Manager|bool
	 */
	public function get_manager( $the_manager = false ) {
		global $post;

		if ( false === $the_manager ) {
			$the_manager = $post;
		} elseif ( is_numeric( $the_manager ) ) {
			$the_manager = get_post( $the_manager );
		} elseif ( $the_manager instanceof U_Manager ) {
			$the_manager = get_post( $the_manager->id );
		}

		if ( ! $the_manager || ! is_object( $the_manager ) ) {
			return false;
		}

		return new U_Manager( $the_manager );
	}
}
