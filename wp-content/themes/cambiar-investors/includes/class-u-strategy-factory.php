<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Strategy Factory Class
 *
 * The strategy factory creating the right strategy objects.
 *
 * @class 		U_Strategy_Factory
 * @version		1.0.0
 * @package     U_Theme/Classes
 * @category	Class
 * @author      Elena Zhyvohliad
 */
class U_Strategy_Factory {

	/**
	 * Get strategy.
	 *
	 * @param bool $the_strategy (default: false)
	 * @return U_Strategy|bool
	 */
	public function get_strategy( $the_strategy = false ) {
		global $post;

		if ( false === $the_strategy ) {
			$the_strategy = $post;
		} elseif ( is_numeric( $the_strategy ) ) {
			$the_strategy = get_post( $the_strategy );
		} elseif ( $the_strategy instanceof U_Strategy ) {
			$the_strategy = get_post( $the_strategy->id );
		}

		if ( ! $the_strategy || ! is_object( $the_strategy ) ) {
			return false;
		}

		return new U_Strategy( $the_strategy );
	}
}
