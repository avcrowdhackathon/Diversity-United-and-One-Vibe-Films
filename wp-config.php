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
define( 'DB_NAME', 'diversity' );

/** MySQL database username */
define( 'DB_USER', 'diversity' );

/** MySQL database password */
define( 'DB_PASSWORD', 'united' );

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
define( 'AUTH_KEY',         'fPuJ^pbN(i=$1L)xWb0.d<vZppbp/l9hBU76kT]K|lxbfo8&L~:4F}22+uyPUDJR' );
define( 'SECURE_AUTH_KEY',  'Rv~FF<P02{QB>(=k`ECbL=B D/g W`b,c#lhj)X{#o|#nOxIY?Hgtd/(Tm=*8b9r' );
define( 'LOGGED_IN_KEY',    '~e6p7 L$10&R^z?E9KwKX9R}hy_$gAXBH]^qi1inXIEv)U$CX%!_>:vW_aZ5>M`t' );
define( 'NONCE_KEY',        'K`OJ};Kg}G{:[x{R8+skvV5mf93E)Y!]4x <~1*bCgJ+.LZEanAh#mK2WI7c4Ho(' );
define( 'AUTH_SALT',        '$oj7YsD8eE4<&{Q%HkK<)]NWOC{v_Cf~_+gzXSbGtQI!dB Ee*6#o86}MuLSVW ?' );
define( 'SECURE_AUTH_SALT', 'acvuzmY[$3/}=R~]FN!rpAA1(i.YWTWiUW?9fz-s^J%7j4&Cl[6g?Z#>i*QCG7CS' );
define( 'LOGGED_IN_SALT',   '7fD;HM=0bY9AxDB)QtD-$J4z|*?.}moh: f%Wf~zoC-hyX2oq}7jge(}ky$`Zo|U' );
define( 'NONCE_SALT',       '%6BcII_D!*7I-*p-UVER=}1_]P bqYsgdz{(b.L1(9p$AlA[Huny8$rFfXY4WGgB' );

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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
