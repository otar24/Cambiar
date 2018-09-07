<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Manager
 *
 * These are regular managers, which extend the abstract post class.
 *
 * @class     U_Manager
 * @version   1.0.0
 * @package   U_Theme/Classes
 * @category  Class
 * @author    Elena Zhyvohliad
 */
class U_Manager extends U_Abstract_Post{

	/**
	 * Init/load the manager object. Called from the constructor.
	 *
	 * @param  int|object|U_Manager $manager Manager to init.
	 */
	protected function init( $manager ) {
        if ( $manager instanceof U_Manager ) {
            $this->id   = absint( $manager->id );
            $this->post = $manager->post;
            $this->get_post( $this->id );
        }else{
            parent::init( $manager );
        }
	}

}
