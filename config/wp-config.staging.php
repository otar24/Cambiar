<?php
/**
 * Staging environment config settings
 *
 * Enter any WordPress config settings that are specific to this environment 
 * in this file.
 */
  

// ** MySQL settings - You can get this info from your web host ** //
/** MySQL hostname */
if ( ! defined( 'DB_HOST' ) )
	define('DB_HOST', 'localhost');

/** The name of the database for WordPress */
if ( ! defined( 'DB_NAME' ) )
	define('DB_NAME', 'dbn_a37c18e3cd4a');

/** MySQL database username */
if ( ! defined( 'DB_USER' ) )
	define('DB_USER', 'usn_a37c18e3cd4a');

/** MySQL database password - set in wp-config.local.php */

/**
 * For developers: WordPress debugging mode.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
