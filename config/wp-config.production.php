<?php
/**
 * Production environment config settings
 *
 * Enter any WordPress config settings that are specific to this environment 
 * in this file.
 */
  

// ** MySQL settings - You can get this info from your web host ** //
/** MySQL hostname */
if ( ! defined( 'DB_HOST' ) )
	define('DB_HOST', '');

/** The name of the database for WordPress */
if ( ! defined( 'DB_NAME' ) )
	define('DB_NAME', '');

/** MySQL database username */
if ( ! defined( 'DB_USER' ) )
	define('DB_USER', '');

/** MySQL database password - set in wp-config.local.php */

/**
 * For developers: WordPress debugging mode.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
