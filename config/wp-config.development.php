<?php
/**
 * Development environment config settings
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
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);
//define('U_THEME_DEV', true);

