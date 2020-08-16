<?php
/**
 * The admin-specific functionality for displaying podcast player.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/admin
 */

namespace Podcast_Player;

/**
 * The admin-specific functionality for displaying podcast player.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/admin
 * @author     vedathemes <contact@vedathemes.com>
 */
class Player {

	/**
	 * Holds the instance of this class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    object
	 */
	protected static $instance = null;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {}

	/**
	 * Register hooked functions.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_action( 'widgets_init', [ self::get_instance(), 'register_custom_widget' ] );
		add_shortcode( 'podcastplayer', [ self::get_instance(), 'player_shortcode' ] );
		add_action( 'init', [ self::get_instance(), 'register_block' ] );
	}

	/**
	 * Register the custom Widget.
	 *
	 * @since 1.0.0
	 */
	public function register_custom_widget() {

		/**
		 * The class responsible for defining the podcast player widget.
		 */
		require_once PODCAST_PLAYER_DIR . 'backend/inc/class-widget.php';

		register_widget( 'Podcast_Player\Widget' );
	}

	/**
	 * Podcast player shortcode function.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts User defined attributes in shortcode tag.
	 * @param str   $pp_content Shortcode text content.
	 */
	public function player_shortcode( $atts, $pp_content = null ) {

		$defaults = [
			'feed_url'         => '',
			'skin'             => 'light',
			'sortby'           => 'sort_date_desc',
			'filterby'         => '',
			'number'           => 10,
			'podcast_menu'     => '',
			'cover_image_url'  => '',
			'excerpt_length'   => 25,
			'grid_columns'     => 3,
			'aspect_ratio'     => 'squr',
			'crop_method'      => 'centercrop',
			'no_excerpt'       => '',
			'header_default'   => '',
			'hide_header'      => '',
			'hide_title'       => '',
			'hide_cover'       => '',
			'hide_description' => '',
			'hide_subscribe'   => '',
			'hide_search'      => '',
			'hide_author'      => '',
			'hide_content'     => '',
			'hide_loadmore'    => '',
			'hide_download'    => '',
			'hide_social'      => '',
			'accent_color'     => '',
			'display_style'    => '',
			'fetch_method'     => 'feed',
			'post_type'        => 'post',
			'taxonomy'         => '',
			'terms'            => '',
			'podtitle'         => '',
			'mediasrc'         => '',
			'episodetitle'     => '',
			'episodelink'      => '',
			'audio_msg'        => '',
			'play_freq'        => 0,
			'msg_start'        => 'start',
			'msg_time'         => '',
			'msg_text'         => esc_html__( 'Episode will play after this message.', 'podcast-player' ),
			'font_family'      => '',
		];

		$atts = shortcode_atts( $defaults, $atts, 'podcastplayer' );

		$url = '';
		if ( 'feed' === $atts['fetch_method'] ) {
			// Return if there is no feed url.
			$url = ! empty( $atts['feed_url'] ) ? $atts['feed_url'] : '';

			while ( stristr( $url, 'http' ) !== $url ) {
				$url = substr( $url, 1 );
			}

			if ( empty( $url ) ) {
				return;
			}
		}

		$img_url  = '';
		$image_id = '';
		if ( $atts['cover_image_url'] ) {
			$dir = wp_upload_dir();
			if ( false !== strpos( $atts['cover_image_url'], $dir['baseurl'] . '/' ) ) {
				$image_id = attachment_url_to_postid( esc_url( $atts['cover_image_url'] ) );
			} else {
				$img_url = $atts['cover_image_url'];
			}
		}

		$display_args = apply_filters(
			'podcast_player_shcode_display',
			[
				'url'              => $url,
				'skin'             => $atts['skin'],
				'sortby'           => $atts['sortby'],
				'filterby'         => $atts['filterby'],
				'number'           => $atts['number'],
				'menu'             => $atts['podcast_menu'],
				'image'            => $image_id,
				'description'      => $pp_content,
				'img_url'          => $img_url,
				'no-excerpt'       => 'true' === $atts['no_excerpt'] ? 1 : 0,
				'header-default'   => 'true' === $atts['header_default'] ? 1 : 0,
				'hide-header'      => 'true' === $atts['hide_header'] ? 1 : 0,
				'hide-title'       => 'true' === $atts['hide_title'] ? 1 : 0,
				'hide-cover-img'   => 'true' === $atts['hide_cover'] ? 1 : 0,
				'hide-description' => 'true' === $atts['hide_description'] ? 1 : 0,
				'hide-subscribe'   => 'true' === $atts['hide_subscribe'] ? 1 : 0,
				'hide-search'      => 'true' === $atts['hide_search'] ? 1 : 0,
				'hide-author'      => 'true' === $atts['hide_author'] ? 1 : 0,
				'hide-content'     => 'true' === $atts['hide_content'] ? 1 : 0,
				'hide-loadmore'    => 'true' === $atts['hide_loadmore'] ? 1 : 0,
				'hide-download'    => 'true' === $atts['hide_download'] ? 1 : 0,
				'hide-social'      => 'true' === $atts['hide_social'] ? 1 : 0,
				'accent-color'     => $atts['accent_color'],
				'display-style'    => $atts['display_style'],
				'from'             => 'shortcode',
			],
			$atts
		);

		$episodes = podcast_player_display( $display_args, true );
		return $episodes;
	}

	/**
	 * Register editor block for featured content.
	 *
	 * @since 1.0.0
	 */
	public function register_block() {
		// Check if the register function exists.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		// No block if legacy player is activated.
		if ( 'on' === get_option( 'pp-legacy-player' ) ) {
			return;
		}

		register_block_type(
			'podcast-player/podcast-player',
			array(
				'render_callback' => array( $this, 'render_block' ),
				'attributes'      => array(
					'feedURL'       => array(
						'type'    => 'string',
						'default' => '',
					),
					'sortBy'        => array(
						'type'    => 'string',
						'default' => 'sort_date_desc',
					),
					'filterBy'      => array(
						'type'    => 'string',
						'default' => '',
					),
					'number'        => array(
						'type'    => 'number',
						'default' => 10,
					),
					'excerptLength' => array(
						'type'    => 'number',
						'default' => 25,
					),
					'gridColumns'   => array(
						'type'    => 'number',
						'default' => 3,
					),
					'podcastMenu'   => array(
						'type'    => 'string',
						'default' => '',
					),
					'aspectRatio'   => array(
						'type'    => 'string',
						'default' => 'squr',
					),
					'cropMethod'    => array(
						'type'    => 'string',
						'default' => 'centercrop',
					),
					'coverImage'    => array(
						'type'    => 'string',
						'default' => '',
					),
					'description'   => array(
						'type'    => 'string',
						'default' => '',
					),
					'accentColor'   => array(
						'type'    => 'string',
						'default' => '',
					),
					'displayStyle'  => array(
						'type'    => 'string',
						'default' => '',
					),
					'fetchMethod'   => array(
						'type'    => 'string',
						'default' => 'feed',
					),
					'postType'      => array(
						'type'    => 'string',
						'default' => 'post',
					),
					'taxonomy'      => array(
						'type'    => 'string',
						'default' => '',
					),
					'terms'         => array(
						'type'    => 'array',
						'items'   => array(
							'type' => 'string',
						),
						'default' => [],
					),
					'podtitle'      => array(
						'type'    => 'string',
						'default' => '',
					),
					'audioSrc'      => array(
						'type'    => 'string',
						'default' => '',
					),
					'audioTitle'    => array(
						'type'    => 'string',
						'default' => '',
					),
					'audioLink'     => array(
						'type'    => 'string',
						'default' => '',
					),
					'headerDefault' => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'hideHeader'    => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'hideTitle'     => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'hideCover'     => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'hideDesc'      => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'hideSubscribe' => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'hideSearch'    => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'hideAuthor'    => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'hideContent'   => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'hideLoadmore'  => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'hideDownload'  => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'ahideDownload' => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'hideSocial'    => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'ahideSocial'   => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'audioMsg'      => array(
						'type'    => 'string',
						'default' => '',
					),
					'playFreq'      => array(
						'type'    => 'number',
						'default' => 0,
					),
					'msgStart'      => array(
						'type'    => 'string',
						'default' => 'start',
					),
					'msgTime'       => array(
						'type'    => 'array',
						'items'   => array(
							'type' => 'number',
						),
						'default' => [ 0, 0, 0 ],
					),
					'msgText'       => array(
						'type'    => 'string',
						'default' => esc_html__( 'Episode will play after this message.', 'podcast-player' ),
					),
					'fontFamily'    => array(
						'type'    => 'string',
						'default' => '',
					),
				),
			)
		);
	}

	/**
	 * Render editor block for podcast player.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Display attributes.
	 */
	public function render_block( $atts ) {

		$url = '';
		if ( 'feed' === $atts['fetchMethod'] ) {
			// Return if there is no feed url.
			$url = ! empty( $atts['feedURL'] ) ? $atts['feedURL'] : '';

			while ( stristr( $url, 'http' ) !== $url ) {
				$url = substr( $url, 1 );
			}

			if ( empty( $url ) ) {
				return esc_html__( 'Incorrect Feed URL entered', 'podcast-player' );
			}
		}

		$img_url  = '';
		$image_id = '';
		if ( $atts['coverImage'] ) {
			$dir = wp_upload_dir();
			if ( false !== strpos( $atts['coverImage'], $dir['baseurl'] . '/' ) ) {
				$image_id = attachment_url_to_postid( esc_url( $atts['coverImage'] ) );
			} else {
				$img_url = $atts['coverImage'];
			}
		}

		$display_args = apply_filters(
			'podcast_player_block_display',
			[
				'url'              => $url,
				'sortby'           => $atts['sortBy'],
				'filterby'         => $atts['filterBy'],
				'number'           => $atts['number'],
				'menu'             => $atts['podcastMenu'],
				'image'            => $image_id,
				'description'      => $atts['description'],
				'img_url'          => $img_url,
				'header-default'   => true === $atts['headerDefault'] ? 1 : 0,
				'hide-header'      => true === $atts['hideHeader'] ? 1 : 0,
				'hide-title'       => true === $atts['hideTitle'] ? 1 : 0,
				'hide-cover-img'   => true === $atts['hideCover'] ? 1 : 0,
				'hide-description' => true === $atts['hideDesc'] ? 1 : 0,
				'hide-subscribe'   => true === $atts['hideSubscribe'] ? 1 : 0,
				'hide-search'      => true === $atts['hideSearch'] ? 1 : 0,
				'hide-author'      => true === $atts['hideAuthor'] ? 1 : 0,
				'hide-content'     => true === $atts['hideContent'] ? 1 : 0,
				'hide-loadmore'    => true === $atts['hideLoadmore'] ? 1 : 0,
				'hide-download'    => true === $atts['hideDownload'] ? 1 : 0,
				'hide-social'      => true === $atts['hideSocial'] ? 1 : 0,
				'accent-color'     => $atts['accentColor'],
				'display-style'    => $atts['displayStyle'],
				'random'           => true,
				'from'             => 'block',
			],
			$atts
		);

		$episodes = podcast_player_display( $display_args, true );
		return $episodes;
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

Player::init();
