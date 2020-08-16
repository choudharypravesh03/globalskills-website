<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin
 * and defines a function that starts the plugin.
 *
 * @link              https://www.vedathemes.com
 * @since             1.0.0
 * @package           Podcast_Player
 *
 * @wordpress-plugin
 * Plugin Name:       podcast player
 * Plugin URI:        https://vedathemes.com/blog/vedaitems/podcast-player-pro/
 * Description:       Host your podcast episodes anywhere, display them only using podcast feed url. Use custom widget or shortcode to display podcast player anywhere on your site.
 * Version:           2.8.0
 * Author:            vedathemes
 * Author URI:        https://www.vedathemes.com
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       podcast-player
 * Domain Path:       /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Currently plugin version.
define( 'PODCAST_PLAYER_VERSION', '2.8.0' );

// Define plugin constants.
define( 'PODCAST_PLAYER_DIR', plugin_dir_path( __FILE__ ) );

// Load plugin textdomain.
add_action( 'plugins_loaded', 'podcast_player_plugins_loaded' );

// Load plugin's bridge functionality.
require_once PODCAST_PLAYER_DIR . '/bridge/functions.php';
require_once PODCAST_PLAYER_DIR . '/bridge/class-instance-counter.php';

// Load plugin's front-end functionality.
require_once PODCAST_PLAYER_DIR . '/frontend/inc/class-display.php';
require_once PODCAST_PLAYER_DIR . '/frontend/inc/class-feed.php';
require_once PODCAST_PLAYER_DIR . '/frontend/class-frontend.php';

// Load plugin's admin functionality.
require_once PODCAST_PLAYER_DIR . '/backend/class-backend.php';

// Load premium features (if exist).
if ( file_exists( PODCAST_PLAYER_DIR . '/pp-pro/pp-pro.php' ) ) {
	require_once PODCAST_PLAYER_DIR . '/pp-pro/pp-pro.php';
}

if ( ! function_exists( 'podcast_player_plugins_loaded' ) ) {
	/**
	 * Load plugin text domain.
	 *
	 * @since 1.0.0
	 */
	function podcast_player_plugins_loaded() {
		require_once PODCAST_PLAYER_DIR . '/backend/inc/class-compat.php';
		load_plugin_textdomain( 'podcast-player', false, PODCAST_PLAYER_DIR . 'lang/' );
	}
}
