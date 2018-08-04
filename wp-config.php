<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'bimandict_DB');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         '*xu<VDJB$:3<e)m=JX5YH )+af[zB}4L!A{Z@9igSND+Q^c2f+S_C$u~~=nv{Nt3');
define('SECURE_AUTH_KEY',  'R7s?_d#cq,Y~V!X<`kJCC}mQ8,f_@{L2xq?72wBB2f:yf!0X7VU49qlvA/yC,.,V');
define('LOGGED_IN_KEY',    'mS|=IwQCQaear`VMU*CWCEEupuHfP?O$!T$HbsjpgT{AfMVHC}DiPs$jHb[<e$(K');
define('NONCE_KEY',        'k!z4lfz|cyh<~27U{C,Bva]id-N}(/>XzG[V]H!@{nv~pKC4/u7Q?QdI>bVv(=h8');
define('AUTH_SALT',        'qr+4NFv:RSR,3@LVOM;K]I6mlCE|.Zy,%*|K_[;3;7BlNrm^igr{`o.cDK|&(<e{');
define('SECURE_AUTH_SALT', '.on>yaAqzc|h4QG|-?}wUtruBr+kA}*)$F[;w3M0gU]OeQqiFtxHz{,H.%K<Ql/i');
define('LOGGED_IN_SALT',   '-%}!5QJ|x+D1z?<,|,6IRANR`zG>tHcO}=(4%belvQEp|!a~(h}m.W]~Uejz6B7U');
define('NONCE_SALT',       'ds-{Pc>M]KW01Hq,FCP(e$hx3b)GM_}3@MW|l]=HqNxcz3gQkh_bb&--BmU;*h(~');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
