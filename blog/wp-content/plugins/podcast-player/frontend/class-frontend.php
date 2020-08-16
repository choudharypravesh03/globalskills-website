<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/public
 */

namespace Podcast_Player;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/public
 * @author     vedathemes <contact@vedathemes.com>
 */
class Frontend {

	/**
	 * Holds the instance of this class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    object
	 */
	protected static $instance = null;

	/**
	 * Are we using modern player.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    int
	 */
	protected $is_modern = true;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$legacy = get_option( 'pp-legacy-player' );
		if ( 'on' === $legacy ) {
			$this->is_modern = false;
		}
	}

	/**
	 * Register hooked functions.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', [ self::get_instance(), 'enqueue_styles' ] );

		// The script must be loaded before mediaelement-migrate script.
		add_action( 'wp_enqueue_scripts', [ self::get_instance(), 'mediaelement_migrate_error_fix' ], 0 );

		add_filter( 'podcast_player_mediaelement_settings', [ self::get_instance(), 'mejs_settings' ] );
		add_action( 'wp_footer', [ self::get_instance(), 'enqueue_scripts' ] );
		add_action( 'wp_footer', [ self::get_instance(), 'svg_icons' ], 9999 );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		if ( $this->is_modern ) {

			/**
			 * Register public facing stylesheets.
			 */
			wp_enqueue_style(
				'pppublic',
				plugin_dir_url( __FILE__ ) . 'css/podcast-player-public.css',
				[],
				PODCAST_PLAYER_VERSION,
				'all'
			);
			wp_style_add_data( 'pppublic', 'rtl', 'replace' );
		} else {

			/**
			 * Register public facing stylesheets.
			 */
			wp_enqueue_style(
				'pppublic',
				plugin_dir_url( __FILE__ ) . 'legacy/podcast-player-public.css',
				[],
				PODCAST_PLAYER_VERSION,
				'all'
			);
		}

	}


	/**
	 * Register the script to fix mediaelement migrate error.
	 *
	 * Mediaelement migrate WP script forces to use 'mejs-' class prefix for all
	 * mediaelements. Podcast player only work with 'ppjs__' class prefix. Hence,
	 * fixing this issue.
	 *
	 * @since    1.0.0
	 */
	public function mediaelement_migrate_error_fix() {
		/*
		 * This file must be loaded before mediaelement-migrate script.
		 * Many times plugins load mediaelement files in header, which can
		 * break podcast player. Therefore, loading in header.
		 */
		$in_footer = false;

		/**
		 * Register public facing stylesheets.
		 */
		wp_enqueue_script(
			'podcast-player-mmerrorfix',
			plugin_dir_url( __FILE__ ) . 'js/mmerrorfix.js',
			[ 'jquery', 'mediaelement-core' ],
			PODCAST_PLAYER_VERSION,
			$in_footer
		);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// Scripts data.
		$cdata         = apply_filters( 'podcast_player_script_data', [] );
		$ppjs_settings = apply_filters(
			'podcast_player_mediaelement_settings',
			[
				'pluginPath'  => includes_url( 'js/mediaelement/', 'relative' ),
				'classPrefix' => 'ppjs__',
				'stretching'  => 'responsive',
				'features'    => [ 'current', 'progress', 'duration', 'fullscreen' ],
			]
		);

		if ( $this->is_modern ) {

			/**
			 * Register public facing javascripts.
			 */
			wp_enqueue_script(
				'pppublic',
				plugin_dir_url( __FILE__ ) . 'js/public.build.js',
				[ 'jquery', 'mediaelement-core' ],
				PODCAST_PLAYER_VERSION,
				true
			);
		} else {

			/**
			 * Register public facing javascripts.
			 */
			wp_enqueue_script(
				'podcast-player-simplebar',
				plugin_dir_url( __FILE__ ) . 'legacy/simplebar.min.js',
				[],
				PODCAST_PLAYER_VERSION,
				true
			);

			wp_enqueue_script(
				'pppublic',
				plugin_dir_url( __FILE__ ) . 'legacy/public.build.js',
				[ 'jquery', 'mediaelement-core', 'podcast-player-simplebar' ],
				PODCAST_PLAYER_VERSION,
				true
			);
		}

		wp_localize_script( 'pppublic', 'podcastPlayerData', $cdata );
		wp_localize_script( 'pppublic', 'ppmejsSettings', $ppjs_settings );

	}

	/**
	 * Add SVG definitions to the site footer.
	 *
	 * @since 1.0.0
	 */
	public function svg_icons() {

		if ( $this->is_modern ) {

			/**
			 * This files defines all svg icons used by the plugin.
			 */
			require_once PODCAST_PLAYER_DIR . 'frontend/images/icons.svg';
		} else {

			/**
			 * This files defines all svg icons used by the plugin.
			 */
			require_once PODCAST_PLAYER_DIR . 'frontend/legacy/icons.svg';
		}
	}

	/**
	 * Media Element player settings.
	 *
	 * @param array $settings Array of mejs settings.
	 * @since 1.0.0
	 */
	public function mejs_settings( $settings ) {

		if ( $this->is_modern ) {
			return $this->mejs_icons_modern( $settings );
		} else {
			return $this->mejs_icons_legacy( $settings );
		}
	}

	/**
	 * Media Element player modern icons.
	 *
	 * @param array $settings Array of mejs settings.
	 * @since 1.0.0
	 */
	public function mejs_icons_modern( $settings ) {

		// Add Play Pause button markup.
		$playpausebtn = sprintf(
			'<div class="ppjs__button ppjs__playpause-button"><div class="ppjs__img-wrapper"><img class="ppjs__img-btn" src=""></div><button type="button"><span class="ppjs__offscreen">%s</span>%s%s</button></div>',
			esc_html__( 'Play Pause', 'podcast-player' ),
			podcast_player_get_icon( [ 'icon' => 'pp-play' ] ),
			podcast_player_get_icon( [ 'icon' => 'pp-pause' ] )
		); // WPCS xss ok.

		// Add skip backward button markup.
		$skipbackwardbtn = sprintf(
			'<div class="ppjs__button ppjs__skip-backward-button"><button type="button"><span class="ppjs__offscreen">%s</span>%s<span class="skip-duration">%s</span></button></div>',
			esc_html__( 'Skip Backward', 'podcast-player' ),
			podcast_player_get_icon( [ 'icon' => 'pp-rotate-ccw' ] ),
			esc_html__( '15', 'podcast-player' )
		); // WPCS xss ok.

		// Add fast forward button markup.
		$jumpforwardbtn = sprintf(
			'<div class="ppjs__button ppjs__jump-forward-button"><button type="button"><span class="ppjs__offscreen">%s</span>%s<span class="skip-duration">%s</span></button></div>',
			esc_html__( 'Jump Forward', 'podcast-player' ),
			podcast_player_get_icon( [ 'icon' => 'pp-rotate-cw' ] ),
			esc_html__( '15', 'podcast-player' )
		); // WPCS xss ok.

		// Add playBackRate button markup.
		$playbackrate = sprintf(
			'<div class="ppjs__button ppjs__play-rate-button"><button type="button"><span class="ppjs__offscreen">%s</span>%s<div class="play-rate-text"><span class="pp-rate withx current">1</span><span class="pp-rate">1.25</span><span class="pp-rate">1.5</span><span class="pp-rate">1.75</span><span class="pp-rate withx">2</span><span class="pp-rate">0.5</span><span class="pp-rate">0.75</span><span class="pp-times">x</span></div></button></div>',
			esc_html__( 'Change Playback Rate', 'podcast-player' ),
			podcast_player_get_icon( [ 'icon' => 'pp-circle' ] )
		); // WPCS xss ok.

		// Add download button markup.
		$scriptbtn = sprintf(
			'<div class="ppjs__button ppjs__script-button"><button type="button"><span class="ppjs__offscreen">%s</span>%s</button></div>',
			esc_html__( 'Show Episode Text', 'podcast-player' ),
			podcast_player_get_icon( [ 'icon' => 'pp-text' ] )
		); // WPCS xss ok.

		// Add share button markup.
		$sharebtn = sprintf(
			'<div class="ppjs__button ppjs__share-button"><button type="button"><span class="ppjs__offscreen">%s</span>%s</button></div>',
			esc_html__( 'Share This Episode', 'podcast-player' ),
			podcast_player_get_icon( [ 'icon' => 'pp-share' ] )
		); // WPCS xss ok.

		// Secondary control btns.
		$secondary_btns = $playbackrate . $skipbackwardbtn . $jumpforwardbtn . $scriptbtn . $sharebtn;

		// Add Current episode title placeholder.
		$episodetitle = '<div class="ppjs__episode-title"></div>';

		// Create overall markup.
		$overallmarkup = sprintf( '<div class="ppjs__secondary-controls">%s<div class="ppjs__control_btns">%s</div></div>', $episodetitle, $secondary_btns );

		// Add audio control button's markup.
		$settings['ppAudioControlBtns'] = $playpausebtn . $overallmarkup;

		// Add play pause button for video.
		$settings['ppPlayPauseBtn'] = sprintf(
			'<div class="ppjs__button ppjs__playpause-button"><button type="button">%s%s</button></div>',
			podcast_player_get_icon( [ 'icon' => 'pp-play' ] ),
			podcast_player_get_icon( [ 'icon' => 'pp-pause' ] )
		); // WPCS xss ok.

		// Add play icon markup.
		$settings['ppPauseBtn'] = podcast_player_get_icon( [ 'icon' => 'pp-pause' ] );

		// Add close icon markup.
		$settings['ppClose'] = podcast_player_get_icon( [ 'icon' => 'pp-x' ] );

		// Add fullscreen maximize icon markup.
		$settings['ppMaxiScrnBtn'] = podcast_player_get_icon( [ 'icon' => 'pp-maximize' ] );

		// Add fullscreen minimize icon markup.
		$settings['ppMiniScrnBtn'] = podcast_player_get_icon( [ 'icon' => 'pp-minimize' ] );

		// Add play circle icon markup.
		$settings['ppPlayCircle'] = podcast_player_get_icon( [ 'icon' => 'pp-play' ] );

		// Add video loading icon markup.
		$settings['ppVidLoading'] = podcast_player_get_icon( [ 'icon' => 'pp-refresh' ] );

		$settings['isPremium'] = false;

		// Screen reader text to close/Minimize the player.
		$settings['ppCloseBtnText'] = sprintf( '<span class="ppjs__offscreen">%s</span>', esc_html__( 'Minimize or Close the player', 'podcast-player' ) );

		return $settings;
	}

	/**
	 * Media Element player legacy icons.
	 *
	 * @param array $settings Array of mejs settings.
	 * @since 1.0.0
	 */
	public function mejs_icons_legacy( $settings ) {

		// Add skip backward button markup.
		$settings['ppSkipBackwardBtn'] = sprintf(
			'<div class="ppjs__button ppjs__skip-backward-button"><button type="button">%s<span class="skip-duration">%s</span></button></div>',
			podcast_player_get_icon( [ 'icon' => 'skipback' ] ),
			esc_html__( '15', 'podcast-player' )
		); // WPCS xss ok.

		// Add Play Pause button markup.
		$settings['ppPlayPauseBtn'] = sprintf(
			'<div class="ppjs__button ppjs__playpause-button"><button type="button">%s%s%s</button></div>',
			podcast_player_get_icon( [ 'icon' => 'play' ] ),
			podcast_player_get_icon( [ 'icon' => 'play-circle' ] ),
			podcast_player_get_icon( [ 'icon' => 'pause' ] )
		); // WPCS xss ok.

		// Add fast forward button markup.
		$settings['ppJumpForwardBtn'] = sprintf(
			'<div class="ppjs__button ppjs__jump-forward-button"><button type="button">%s<span class="skip-duration">%s</span></button></div>',
			podcast_player_get_icon( [ 'icon' => 'jumpforward' ] ),
			esc_html__( '15', 'podcast-player' )
		); // WPCS xss ok.

		// Add fullscreen maximize icon markup.
		$settings['ppMaxiScrnBtn'] = podcast_player_get_icon( [ 'icon' => 'maximize' ] );

		// Add fullscreen minimize icon markup.
		$settings['ppMiniScrnBtn'] = podcast_player_get_icon( [ 'icon' => 'minimize' ] );

		// Add play circle icon markup.
		$settings['ppPlayCircle'] = podcast_player_get_icon( [ 'icon' => 'play-circle' ] );

		// Add video loading icon markup.
		$settings['ppVidLoading'] = podcast_player_get_icon( [ 'icon' => 'loading' ] );

		return $settings;
	}

	/**
	 * Returns the instance of this class.
	 *
	 * @since  1.0.0
	 *
	 * @return object Instance of this class.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

Frontend::init();
