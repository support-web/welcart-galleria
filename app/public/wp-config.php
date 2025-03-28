<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '{Ie2IJK9?uo=^fUXz F9&s{xrU~5?eRwKaet&%`t6)Cxf[Z;(EPc(C?=gg{CQ#(^' );
define( 'SECURE_AUTH_KEY',   '&`7:>J[Vc}nXT#5h{#1:~U&JSIPwbF]G6<C]^Qc]X,N/j,2vXY6Rkd&Gm:2vI2PE' );
define( 'LOGGED_IN_KEY',     '@P{J^>/vN&Wx0u&QN+8-f#:73Z]UpQK1MfS9FD_^4L2)]i|T^(T3({eJ1W+O }iC' );
define( 'NONCE_KEY',         'V(/}2;H/<q`p<;t&*=Q_QRlYf#Inm>6.y(uida&=s=CXME~M}!|7rU>p2mdqgtFY' );
define( 'AUTH_SALT',         'C3ePci$VWgQSjXcviQU0knu%aL<PW5>d@wUPZ~XdxiCZrSvG(Q0[1OM[X^IutYwf' );
define( 'SECURE_AUTH_SALT',  'Xy_E7EeS|D>(cZPK$Ihm)1qU}Nb&j3TR)4yN!e?H-:DI56Gf&><0]bsh#k0PauDb' );
define( 'LOGGED_IN_SALT',    '*u_sqj?{O.W3B 61O8u+/M[sV@Hc]cbTVl^^;qi@8=x})qD[}M5q&Cch9#`U)~SA' );
define( 'NONCE_SALT',        'BW3GzrY:a_I:I^f:=4kUX(Ys2{e)P}MGgH *(k}[K]/0r+8w3B#%q| MBQ%$K*yB' );
define( 'WP_CACHE_KEY_SALT', 'ax]T@HjN$ErlD:FTWo5I`_,.5$U]1Ir&R4`B`3K9]J0 q^l?r(?sMQlHVm#4[B*6' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
