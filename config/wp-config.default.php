<?php
/**
 * Default config settings
 *
 * Enter any WordPress config settings that are default to all environments
 * in this file.
 * 
 * Please note if you add constants in this file (i.e. define statements) 
 * these cannot be overridden in environment config files so make sure these are only set once.
 */
  

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'u# `uR^$UPeK:~eRvznjjqG,I)q >[1g.YKw3/h=&hR6Y#|7m/Y&1KYwFkX{a<D%');
define('SECURE_AUTH_KEY',  'V2b>ay(PNVLZ{^dv7Q.KWr.X_6itTH+:pe@!D+LbK0KVdL{~S%LPN#?5aX@U%y8N');
define('LOGGED_IN_KEY',    'z:VGY+S}`4/DxoYF|+h!rgnvJoNV3#d&5uyoUl07,PKz +.q7*j2)@Q61aj`E&8e');
define('NONCE_KEY',        'rqb%%JLtJf}CN_JJ; >ua|+m<(va+Gk>L+jO<81W00YrI!MrcU,SvA@n0)v5;S@[');
define('AUTH_SALT',        '-2-?E$ Nw*^,Qy{]E(42#w3fW-f?NTb+_7m^|`iGBjA&*Nqw@r7[;!hJbfe_3W)3');
define('SECURE_AUTH_SALT', '[#}-y)*K!ld,9|fM,z[jPjnA{eP`$*Ad_F[.o[x(lvL?s6I= 2gw0$TJ[tRk05 ]');
define('LOGGED_IN_SALT',   '|D%C):L}pGPw2M6_*y(=xr`{D<lTI.-g,z!@Y45s/o[t>]9it5eO,<3+jl0e%[N ');
define('NONCE_SALT',       '$-`3?R#Yk<1Xk61Ik!^|A-5HAT50lLZM>9[L&Rx4`Vda^XnC[@y}2NQd$-~8#M`c');

define( 'WP_CONTENT_DIR', WPCONFIGPATH . '/wp-content' );

/**#@-*/


/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'ci_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * Increase memory limit. 
 */
define('WP_MEMORY_LIMIT', '64M');
