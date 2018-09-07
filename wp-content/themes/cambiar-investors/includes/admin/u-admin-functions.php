<?php
/**
 * Theme Admin Functions
 *
 * General admin functions available on admin side.
 *
 * @author 		uCAT
 * @category 	Admin
 * @package 	U_Theme/Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function u_get_screen_ids(){
    return array('strategy', 'edit-strategy', 'people', 'edit-people');
}