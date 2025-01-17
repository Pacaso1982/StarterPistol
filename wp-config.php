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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'dbr46heyb79qk9' );

/** MySQL database username */
define( 'DB_USER', 'uv3hxnq76sek7' );

/** MySQL database password */
define( 'DB_PASSWORD', 'j3re2bbtwb57' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'tG6]H,Rcvfw9#?VR*x<Cgg!<LQa3o3R=wY(UxGikMKoUBMSw,1Fcxw]6gX5y3Xc>' );
define( 'SECURE_AUTH_KEY',  'dG yf#Orq0c-)wlreG=vbdxC5)5-/xV0&?lK;iDs9+6&$s|ENhu2A>H:t>,(fGl3' );
define( 'LOGGED_IN_KEY',    '+w4?9}$F99^G<9QuBwOA8Tn,yJvI(<f,N$z##PC76:{D-s4L|9DybIzDlhXaOzfV' );
define( 'NONCE_KEY',        '7qNap^Dp8)W.0n)<00o(GTETK2 g_F$CV%Fz%`m}!47Wv~r4@O.4-]YRqp0oXb-X' );
define( 'AUTH_SALT',        '-yCJ,`[z)V4Crb370r8wvqGB`7a:eIutjD$CjOK[KplPY]g@EO!wPtM<$!7S+MNC' );
define( 'SECURE_AUTH_SALT', 'YCTmR!IRpgnD/6gtypb$3Wy;y.q8q;8R]h!RwS,0u06/Q=dA^Ug^XJLs:uw@3+;8' );
define( 'LOGGED_IN_SALT',   'Z4BnRkj%.1>C{I_r,z`KaE.~sxIx9uft6dlW#=^WWn2w{Jn;vyKhYYa-%rRwbbPU' );
define( 'NONCE_SALT',       'U97$6d1k_?@Z=vs=.~[/XaxL1ooOyKDm%(Esk#e4#Z3!BV]^_nA,83tW)h:PQ|8h' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

define('WP_POST_REVISIONS', 5);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
@include_once('/var/lib/sec/wp-settings-pre.php'); // Added by SiteGround WordPress management system
require_once ABSPATH . 'wp-settings.php';
@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system
