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
define( 'DB_NAME', 'bitnami_wordpress' );

/** MySQL database username */
define( 'DB_USER', 'bn_wordpress' );

/** MySQL database password */
define( 'DB_PASSWORD', '419c53b3d9' );

/** MySQL hostname */
define( 'DB_HOST', '34.83.140.72' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define('AUTH_KEY',         ';&=p+}io,!gym=M-4b:LHY_}?Fu[FXsqOGt+iMjw7A9u@Xx!p.XKoSA}!xcM*aO.');
define('SECURE_AUTH_KEY',  '8 QQ%m 5O-k|#ewEFk:-yE<-hAW#)#/Tab<[bjL|dU]!5kCHmY|{,{b3X-/zGH>>');
define('LOGGED_IN_KEY',    'DaXw9GF%8t5! =J~O^&@zV]LZO22D9t{9AryPH3UfT|]hKFh|;tx3<=qQ%Okt<cM');
define('NONCE_KEY',        '{K|+d$+~ _+|I~ D+5h&U,0Z^|-pq7^)&6+VfK=3wGi(aOF?2WvR$M/}HRpk-W$x');
define('AUTH_SALT',        '~=,- q,-(ie9YaW{oz}+[o|HSN4V~tty>j+B@H7Lk:/}8cFP%!d/)J?X)$1xfKnj');
define('SECURE_AUTH_SALT', '|&}2EQY7DA[!!A=LI+`R~I=7}?H655>lYC,Q&7-LGlvFN9oB+f=gR-iR-+$Oy[>`');
define('LOGGED_IN_SALT',   'sDFF{]]Y}0{>IK9WXkrg/K OgH;d8W@XHi5|1LQR(e{`[AzS7+[n|9 ]Yux8-8}y');
define('NONCE_SALT',       '|A{W8V5TL-_wV/]Rwh;,`o#g84#.E7oI85AW{pQ%xe@C;Tb4#lH&Nk&L:yp|n!$u');

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
