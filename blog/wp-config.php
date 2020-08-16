<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true);
define( 'WPCACHEHOME', '/home/globalskillshub/globalskills.io/blog/wp-content/plugins/wp-super-cache/' );
define('DB_NAME', 'globalskills_io');

/** MySQL database username */
define('DB_USER', 'globalskillsio');

/** MySQL database password */
define('DB_PASSWORD', 'rJUm2pjG');

/** MySQL hostname */
define('DB_HOST', 'mysql.globalskills.io');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'v);q+5dF;*mE3IT?QP@2M95y7"aZnb3&(v`uS|0)?7I/A4U)GhAi&/&R95hJ(EeE');
define('SECURE_AUTH_KEY',  '1d"&Ja9D^JpW1Yrc&ErTC$q3&j~yg%$iO0~J*6hrT9eY2t:ItN*?hR71DZ7s#&MV');
define('LOGGED_IN_KEY',    '`Zx7N*8T|ebFSgmdwOUtbDRyyPfdE1m:D^T3V3Y98OEKifczLYqL&3y57!5MON3U');
define('NONCE_KEY',        '!R5k0NqVkf)4;MhNNS8_yO`zcyv|B0//kM|(A5$Cz?l^vme!:JVYFv_$bhFGU52x');
define('AUTH_SALT',        '`m~:17B&pn_Dg2$G*`ao*w02V`Yf+h;;IGh"@Q+`n:PbGjoe%(rCs;gZ_LqBzi*%');
define('SECURE_AUTH_SALT', '&RBMg3It@;v6R^yM`^sR2oW;:xAB^0bsQ;67yc"e$kh8l^!jZxhtJ++eu$V0FaQ~');
define('LOGGED_IN_SALT',   ';hI1$mpct&WH^6rns4hV`JCmgOOARIJHgFqyS5zt`^rk?!20z51?Lz5Qs)`7Lr~+');
define('NONCE_SALT',       'eC`!~PUf&iNndJSweW6*_XW`7ho?JOO+(&jhYYsQ7:ImcC+p)sMx^SD^#We!wcxo');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_qxce5b_';

/**
 * Limits total Post Revisions saved per Post/Page.
 * Change or comment this line out if you would like to increase or remove the limit.
 */
define('WP_POST_REVISIONS',  10);

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
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/**
 * Removing this could cause issues with your experience in the DreamHost panel
 */

if (preg_match("/^(.*)\.dream\.website$/", $_SERVER['HTTP_HOST'])) {
        $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        define('WP_SITEURL', $proto . '://' . $_SERVER['HTTP_HOST']);
        define('WP_HOME',    $proto . '://' . $_SERVER['HTTP_HOST']);
}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

