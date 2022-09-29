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
define( 'DB_NAME', 'lvgenjww_wp_yzrxg' );

/** MySQL database username */
define( 'DB_USER', 'lvgenjww_wp_ra8gs' );

/** MySQL database password */
define( 'DB_PASSWORD', 'gTX@%1b^S~7gEA2s' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost:3306' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'Gpt2N;-_;5*+*4W4r59x:i;*0HgU8Rj|+][sX867r41cO-Tw[uP99/U(Gr0ss#k-');
define('SECURE_AUTH_KEY', '2IYP+2Qx&B;dA~]S2C|g4l&X@8nCv2CBF%4S|(+K22~aH+FU#Tg5~2Yy044R/1-M');
define('LOGGED_IN_KEY', 'U_8cO]G1BdZDS_qNDdE999k;Peg5GC7sCw0&72/NMz6F)2cm0gw0990KO:14KFi~');
define('NONCE_KEY', '@|V0V0)O#L@t8%85XEQbZmPJ*5dPoPhu4G98xTV-mvT851I8b62!e7(Ju%G1y7g*');
define('AUTH_SALT', 'S0]64WTDM3ZCP09W7#!6SZq|2kzaC2u~0;n0)q@Z0Q76L07U:kWsA+(6ciGkF~0S');
define('SECURE_AUTH_SALT', 'Dq9!9QA4a9a#:c858K#LIj|]Mwi52g%e||Ztde&1fvVRZVNv|P9|;OvWDRLR7L#z');
define('LOGGED_IN_SALT', '%fs2k2~l3J#JTs3!#8)|94C9S059C|P&6CB3/+WD9#huT:01O296s72|#n23hdS4');
define('NONCE_SALT', ')Q-o08+F3s+S#x~:*(EbAufg%KhHVGkY;c60n5%][ccV75M7@|3-nH-1[%*h!~o2');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'iIriXTyV_';


define('WP_ALLOW_MULTISITE', true);
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
