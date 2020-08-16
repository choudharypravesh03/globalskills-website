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
define( 'WPCACHEHOME', '/home/globalskillshub/globalskillscanada.com/blog/wp-content/plugins/wp-super-cache/' );
define('DB_NAME', 'globalskillscanada_com');

/** MySQL database username */
define('DB_USER', 'globalskillscana');

/** MySQL database password */
define('DB_PASSWORD', 'dDr4zRHC');

/** MySQL hostname */
define('DB_HOST', 'mysql.globalskillscanada.com');

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
define('AUTH_KEY',         '_%A&WRX)9hoVQ;:7T2KB6:kEjl:Ev3cykJkmtbxvR+se!@qYk6k:nD6;0t_o;M85');
define('SECURE_AUTH_KEY',  'Z5TDcOtS2V2;60D/I9mD?HaxHn%|8Cx8"h?!7+SMS3B&OTlho6(x`9F8R)alo~~W');
define('LOGGED_IN_KEY',    'W@*k&99O@m+dfLvA2@ST/5B3/yRMvl`@&h68^+anJna9?Jatzo*G`zex|WtCZivM');
define('NONCE_KEY',        '6X#QQdk%?ck7Dq(hT!Q5vv|$sBd3lgLpR99T@`N~_kriFQqI#H&bBtgXF)_~/FIV');
define('AUTH_SALT',        'nxu(dKT?/e5/(qzgp2L(GUrl+!SNVNVnBw8o~!WG%K%5L`"$d4"S5mF!6Mt"@W0Z');
define('SECURE_AUTH_SALT', 'I/UQH%pL~BNOLNLfi_kxo&V3wUiZj)Mph7d:ce96k%N!#bp?rgpPiGM*j+IT)66d');
define('LOGGED_IN_SALT',   'Le4if?quW&^6tJ7qpoz0Fqb`A8hkMuaN~k2X5CZGY21fxnD9OfxD*WxXz/BoF8/G');
define('NONCE_SALT',       'VMDUK2YbgZYfkrMwBDp(NH2:A8*@q3vtW%73LuT%uX1nv9qVYSOr(g&"4zHy4_%|');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_8cdcux_';

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

