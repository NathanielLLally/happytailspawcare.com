<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp' );

/** Database username */
define( 'DB_USER', 'wp' );

/** Database password */
define( 'DB_PASSWORD', 'q1w2E3r4' );

/** Database hostname */
define( 'DB_HOST', 'happytailspawcare.com' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'Ztx#1,ptuKFT`1nn_=rc$y><ns?90@>R:Fp){6O`Mu5)Bi*,JX_n&jb5d/Qu&l;M' );
define( 'SECURE_AUTH_KEY',  'H[J;_rEQ/-Aa?:z#L` 4$w/uFb((3B`0%@xP@qf~6&?rk.yPO[K%V7dCLV#^*$8j' );
define( 'LOGGED_IN_KEY',    'c*e[u@,,%m|V/Ad*I$^I4fcVrHuSvi.mSTXdvE^2]<xXi<,%89Pi]pdrfm(P4wFo' );
define( 'NONCE_KEY',        'UdO?]$uM]4]@`y@;X%Yn.Y%({E*M*i8*j>sL^,||ohmUmmf@?Vg;: y*=YF(QVC]' );
define( 'AUTH_SALT',        '5d?X>ihJ|CBb]]~u?3)`=_:n3vs&a@Da6+>gYH/`bY0=2G<D5~j$)?J33.on,?c^' );
define( 'SECURE_AUTH_SALT', '^h%d-oTa:+>n%0O!I+T+cA7<xI~!k<a;ysmsEJGPmje)oA2:u${K2##LG RGlp.p' );
define( 'LOGGED_IN_SALT',   'JXB1i+MPl#&VM;=R7ZZc>eSt/F7SnJL24?@C_SCQH;5AC;#L[w?#i3%EIB{OA[Ah' );
define( 'NONCE_SALT',       'LWP&Z}I0*r%Qp6~$xigv28dSeoI&)0XFq0ZH c5s>h;H]9rfhLgyiP0mr<eLR1U&' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
